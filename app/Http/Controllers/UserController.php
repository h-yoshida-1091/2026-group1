<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //=====================================
    //　　ログイン機能
    //=====================================

    //ログイン画面の表示
    public function login_Get()
    {
        return view('accounts.login');
    }

    //ログイン処理
    public function login_Post(Request $request)
    {
        //入力チェック
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //データベースの情報と照合
        if(Auth::attempt($credentials)){
            //セッションの再生成（セキュリティ対策）
            $request->session()->regenerate();

            //「追加」スコアの未計算チェック ＆ バックグラウンド実行
            $userId = Auth::id();
            $allProductIds = \App\Models\Product::pluck('id')->toArray();
            $scoredProductIds = DB::table('recommend_scores')
                ->where('user_id', $userId)
                ->pluck('product_id')
                ->toArray();

            // スコアテーブルに存在しない商品ID（新商品など）があるか比較
            $missingIds = array_diff($allProductIds, $scoredProductIds);

            // 未計算の商品があれば、先に画面を返した後の裏の隙間時間で命名を叩く
            if (!empty($missingIds)) {
                dispatch(function () use ($userId) {
                    $groqService = app(\App\Services\GroqRecommendationService::class);
                    $groqService->calculateAndSaveScores($userId);
                })->afterResponse(); // 魔法の非同期命令
            }
            //本来行こうとしていたページへリダイレクト
            return redirect()->intended('/products');
        }

        //失敗したらメッセージを持ってログイン画面にリダイレクト
        return redirect('/login')->with('error_message', 'メールアドレスまたはパスワードが違います');
    }


    //====================================
    //　　会員登録機能
    //====================================

    //会員登録画面の表示
    public function account_Get()
    {
        return view('accounts.user_signup');
    }

    //会員登録処理
    public function account_Post(Request $request)
    {
        //入力チェック
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:255|confirmed',
            'postal_code' => 'nullable|string|max:8',
            'address' => 'required|string|max:255',
        ],[
            //エラー表示
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'password.confirmed' => 'パスワードが一致していません。',
            'password.min' => 'パスワードは８文字以上で入力してください。',
        ]);

        //データベースへ格納
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), //暗号化
            'postal_code' => $request->postal_code,
            'address' => $request->address
        ]);

        //会員登録後、ログイン画面に移動しメッセージを表示
        return redirect('/login')->with('success_message', '会員登録が完了しました。ログインしてください。');
    }


    //===========================================
    //      ログアウト機能
    //==========================================
    public function logout(Request $request)
    {
        //ログインを解除
        Auth::logout();

        //現在のセッションを無効化
        $request->session()->invalidate();

        //CSRFトークンを再生成（二重送信やセッション固定攻撃の対策）
        $request->session()->regenerateToken();

        //ログアウト後はログイン画面にメッセージを表示
        return redirect('/login')->with('success_message', 'ログアウトしました。');
    }


    //===========================================
    //      アカウント編集機能
    //===========================================
    public function edit_Get()
    {
        $user = Auth::user();

        return view('accounts.account_edit', compact('user'));
    }

    public function edit_Post(Request $request)
    {
        //ログイン中のユーザーを取得
        $user = User::findOrFail(Auth::id());

        //バリデーションチェック
        $validated = $request->validateWithBag('updatePassword', [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['required', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'max:255', 'confirmed'],
            'address' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:8'],
        ], [
            'name.required' => 'お名前は必須項目です',
            'email.required' => 'メールアドレスは必須項目です',
            'email.email' => '正しいメールアドレスの形式で入力してください',
            'email.unique' => 'このメールアドレスは既に登録されています',
            'address.required' => '住所は必須項目です',
            'password.min' => '新しいパスワードは８文字以上で入力してください',
            'password.confirmed' => '新しいパスワード（確認用）と一致していません',
            'current_password.required' => 'アカウント情報を変更する場合は、現在のパスワードを入力してください',
            'current_password.current_password' => '現在のパスワードが正しくありません',
        ]);

        //更新
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->postal_code = $validated['postal_code'];
        $user->address = $validated['address'];

        //パスワードが入力されていた場合のみ更新
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        //データベースに保存
        $user->save();

        //リダイレクト
        return redirect()->back()->with('status', 'アカウント情報を更新しました。');
    }


    //===========================================
    //      アカウント削除機能
    //===========================================
    public function destroy(Request $request)
    {
        //パスワードのバリデーション（現在のパスワードと一致するか）
        $request->validateWithBag('userDeletion', [
            'delete_password' => ['required', 'current_password'],
        ], [
            'delete_password.required' => 'パスワードの入力は必須です',
            'delete_password.current_password' => 'パスワードが正しくありません',
        ]);

        //ログイン中のユーザーをデータベースから取得
        $user = User::findOrFail(Auth::id());

        //データベースからユーザーのレコードを削除
        $user->delete();

        //ログアウト処理
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        //削除完了メッセージとリダイレクト
        return redirect('/login')->with('success_message', 'アカウントを削除しました。');
    }


    //==============================================
    //      注文履歴機能
    //==============================================
    public function showOrderHistory()
    {
        $user = Auth::user();
 
        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインが必要です。');
        }
 
        // ユーザーの注文履歴を取得
        $orders = DB::table('orders')
            ->where('user_id', $user->id)
            ->orderBy('order_date', 'desc')
            ->get();
 
        // 各注文に関連する商品情報を取得
        $orderDetails = [];
        foreach ($orders as $order) {
            $items = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('order_items.order_id', $order->id)
                ->select('products.name', 'products.price', 'order_items.quantity')
                ->get();
 
            $orderDetails[] = [
                'order' => $order,
                'items' => $items,
            ];
        }
 
        return view('purchase.history', [
            'orders' => $orders,
            'orderDetails' => $orderDetails,
        ]);
    }
}
