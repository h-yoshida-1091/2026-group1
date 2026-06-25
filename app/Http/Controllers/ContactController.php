<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;
use App\Services\GroqContactAnalysisService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReplyMail;

class ContactController extends Controller
{
    //サービスを保持するプロパティ
    protected $groqService;

    // コンストラクタでサービスを注入
    public function __construct(GroqContactAnalysisService $groqService)
    {
        $this->groqService = $groqService;
    }

    // お問い合わせ画面の表示
    public function index()
    {
        // 未ログインならログイン画面へ遷移させ、メッセージを表示
        if (!Auth::check()) {
            return redirect('/login')->with('error_message', 'お問い合わせ機能を利用するにはログインが必要です。');
        }

        $categories = Category::all();

        // ログイン中のユーザー情報を取得
        $user = Auth::user();

        return view('contact.index', compact('categories', 'user'));
    }

    // DBへの保存処理
    public function store(Request $request)
    {
        //  念のためPOST時も未ログインチェック
        if (!Auth::check()) {
            return redirect('/login')->with('error_message', 'お問い合わせ機能を利用するにはログインが必要です。');
        }

        $userId = Auth::id();
        $userEmail = Auth::user()->email;

        // 連投スパム対策：過去24時間以内に同じユーザー（または同じメールアドレス）からの送信があるかチェック
        $lastContact = Contact::where('email', $userEmail)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->first();

        if ($lastContact) {
            return redirect('/contact')->with('error_message', '連続でのお問い合わせは制限されています。次のお問い合わせは、前回送信から24時間後にお願いいたします。');
        }

        // 入力制限しているため、名前とメールアドレスはセッションから強制的に取得してバリデーションにかける
        $request->merge([
            'name'  => Auth::user()->name,
            'email' => $userEmail,
        ]);

        // バリデーション
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'subject.required' => '件名を入力してください。',
            'message.required' => 'お問い合わせ内容を入力してください。',
        ]);

        // AI解析サービスを呼び出す前に、件名が短すぎる場合や、英数字のみの件名の場合はスパム判定とする
        if (mb_strlen($validated['subject']) < 3 || preg_match('/^[a-zA-Z0-9\s]+$/', $validated['subject'])) {
            $aiAnalysis = ['is_spam' => true, 'priority' => 2];
        } else {
            // サービスを呼び出し、AI解析を実行
            $aiAnalysis = $this->groqService->analyzeContact($validated['subject'], $validated['message']);
        }

        // 解析結果に基づいてステータスと優先度を決定
        if ($aiAnalysis['is_spam']) {
            $validated['status'] = 'スパム';
            $validated['previous_status'] = '未対応';
        }
        $validated['priority'] = $aiAnalysis['priority'];

        // DBへ保存
        Contact::create($validated);

        //  完了メッセージに注記を追加してリダイレクト
        return redirect('/contact')->with('success_message', "お問い合わせを受け付けました。ありがとうございました。\nお問い合わせに対する回答は、後日ご登録のメールアドレスに送信いたします。");
    }

    // 管理者用お問い合わせ一覧画面の表示
    public function adminIndex()
    {
        // ステータスが「ゴミ箱」以外のものを最新順に取得
        $contacts = Contact::whereNotIn('status', ['ゴミ箱', 'スパム'])
                    ->orderBy('priority', 'desc')
                    ->latest()
                    ->get();
        
        // 管理者用の一覧画面にデータを渡して表示
        return view('admin.admin_contact', compact('contacts'));
    }

    // 返信画面の表示
    public function adminReply($id)
    {
        // 対象のお問い合わせを1件取得（なければ404エラー）
        $contact = Contact::findOrFail($id);

        return view('admin.admin_reply', compact('contact'));
    }

    // 管理者用：返信処理（メール送信 ＆ ステータス更新）
    public function adminSendReply(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        // バリデーション
        $request->validate([
            'reply_message' => 'required|string|max:2000',
        ], [
            'reply_message.required' => '返信内容を入力してください。',
        ]);

        //1. 入力された返信本文を取得
        $replyMessage = $request->input('reply_message');

        //2. 実際にお客様のメールアドレス宛にメールを送信！
        Mail::to($contact->email)->send(new ContactReplyMail($replyMessage));

        // 3. ステータスを「対応済」に更新して保存
        $contact->status = '対応済';
        $contact->save();

        // 一覧画面にリダイレクト
        return redirect('/admin/contact')->with('success_message', "お問い合わせ #{$id} の返信メールを送信し、「対応済」に更新しました。");
    }

    // お問い合わせをゴミ箱に移動する処理
    public function adminTrash($id)
    {
        $contact = Contact::findOrFail($id);

        //ゴミ箱に移動する前のステータスを退避させておく
        $contact->previous_status = $contact->status;
        $contact->status = 'ゴミ箱';
        $contact->save();

        return redirect('/admin/contact')->with('success_message', "お問い合わせをゴミ箱に移動しました。");
    }

    // ゴミ箱に入ったお問い合わせ一覧の表示
    public function adminTrashIndex()
    {
        // ステータスが「ゴミ箱」のものだけを最新順に取得
        $contacts = Contact::whereIn('status', ['ゴミ箱', 'スパム'])
                    ->latest()
                    ->get();

        // ゴミ箱専用のビューを開く
        return view('admin.admin_trash_contact', compact('contacts'));
    }

    // ゴミ箱から元の状態に復元する
    public function adminRestore($id)
    {
        $contact = Contact::findOrFail($id);

        // 退避しておいた元のステータスに戻す（万が一なければ未対応にする）
        $contact->status = $contact->previous_status ?? '未対応';
        $contact->previous_status = null; // 復元したのでクリア
        $contact->save();

        return redirect('/admin/trash')->with('success_message', "お問い合わせを一覧に復元しました。");
    }

    // 選択データの一括完全削除
    public function adminBulkDelete(Request $request)
    {
        // 画面のチェックボックスから送信されたIDの配列を取得
        $contactIds = $request->input('contact_ids');

        if (empty($contactIds)) {
            return redirect('/admin/trash')->with('error_message', "削除するデータが選択されていません。");
        }

        // 選択されたIDのデータを一括で物理削除
        Contact::whereIn('id', $contactIds)
            ->whereIn('status', ['ゴミ箱', 'スパム'])
            ->delete();

        return redirect('/admin/trash')->with('success_message', "選択されたお問い合わせを一括で完全に削除しました。");
    }

    // お問い合わせをゴミ箱・スパムから1件単体で完全に物理削除する処理
    public function adminForceDelete($id)
    {
        // 対象のお問い合わせを取得（なければ404エラー）
        $contact = Contact::findOrFail($id);

        // 安全対策：ステータスが「ゴミ箱」または「スパム」のものだけ削除を許可する
        if (in_array($contact->status, ['ゴミ箱', 'スパム'])) {
            $contact->delete(); // データベースから完全に削除
            return redirect('/admin/trash')->with('success_message', "お問い合わせ #{$id} を完全に削除しました。");
        }

        return redirect('/admin/trash')->with('error_message', "このステータスのお問い合わせは直接削除できません。");
    }
}
