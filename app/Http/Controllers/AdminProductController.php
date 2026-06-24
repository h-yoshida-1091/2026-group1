<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_image;
use App\Models\Category;

class AdminProductController extends Controller
{
    // 商品一覧
    public function index()
    {
        $products = Product::all();
        $categories = Category::all(); // 追加
        foreach ($products as $product) {
            $image = Product_image::find($product->image_id);
            $product->image_url = $image ? $image->image_url : null;
        }
        return view('admin.admin_lineup', compact('products', 'categories')); // categoriesを追加
    }

    // 商品削除
    public function destroy(Request $request)
    {
        Product::findOrFail($request->input('id'))->delete();
        return redirect('/admin/products');
    }

    // 商品編集画面
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $image = Product_image::find($product->image_id);
        $product->image_url = $image ? $image->image_url : null;
        return view('admin.admin_edit', compact('product', 'categories'));
    }

    // 商品編集処理
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // 1. 基本バリデーション（URL形式とファイル形式のチェック）
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            // nullableにしておくことで、未入力時はエラーにせずスルーさせます
            'image_url'   => 'nullable|url',
            'image_file'  => 'nullable|image',
        ], [
            'image_url.url'    => '有効なURLの形式（http:// または https://）で入力してください',
            'image_file.image' => '有効な画像ファイルを選択してください',
        ]);

        // 2. 【実在・存在チェック】URLが入力されている場合のみ検証
        if ($request->input('image_type') === 'url' && $request->input('image_url')) {
            $url = $request->input('image_url');

            // 外部サーバーへ接続してヘッダー情報を取得
            $headers = @get_headers($url, 1);

            if (!$headers) {
                // ドメインが存在しない、または接続できない場合
                return back()->withInput()->withErrors(['image_url' => '指定された画像URLにアクセスできませんでした。']);
            }

            // HTTPステータスコードの確認 (200 OK かどうか)
            $status = $headers[0] ?? '';
            if (strpos($status, '200') === false) {
                return back()->withInput()->withErrors(['image_url' => '指定された画像URL（Webページ）が存在しません。']);
            }

            // Content-Type が画像（image/jpeg, image/png など）であるかチェック
            $contentType = $headers['Content-Type'] ?? $headers['content-type'] ?? '';
            if (is_array($contentType)) {
                $contentType = end($contentType);
            }

            if (strpos($contentType, 'image/') === false) {
                return back()->withInput()->withErrors(['image_url' => '指定されたURLは画像ファイルではありません。']);
            }
        }

        // 3. 画像の処理（チェックを通過した場合のみ実行）
        if ($request->input('image_type') === 'file' && $request->hasFile('image_file')) {
            // ファイルの場合：public/images に保存してURLを生成
            $path = $request->file('image_file')->store('images', 'public');
            $imageUrl = asset('storage/' . $path);

            // product_imagesテーブルに保存
            $image = Product_image::create(['image_url' => $imageUrl]);
            $product->image_id = $image->id;
        } elseif ($request->input('image_type') === 'url' && $request->input('image_url')) {
            // URLの場合：そのままproduct_imagesテーブルに保存
            $image = Product_image::create(['image_url' => $request->input('image_url')]);
            $product->image_id = $image->id;
        }

        // 4. その他の商品情報を更新（画像以外の処理はそのまま）
        $product->update([
            'category_id' => $request->input('category_id') ?? $product->category_id,
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'price'       => $request->input('price'),
            'stock'       => $request->input('stock'),
        ]);

        return redirect('/admin/products');
    }

    // 商品追加画面
    public function create()
    {
        $categories = Category::all();
        return view('admin.admin_create', compact('categories'));
    }

    // 商品追加処理
    public function store(Request $request)
    {
        // 他の必須フィールドのバリデーションも元のまま残して統合
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            // 画像のカスタムバリデーション
            'image_url'   => 'required_if:image_type,url',
            'image_file'  => 'required_if:image_type,file|image',
        ], [
            'image_url.required_if'  => '画像URLを入力してください',
            'image_url.url'          => '有効なURLの形式（http:// または https://）で入力してください',
            'image_file.required_if' => '画像ファイルを選択してください',
            'image_file.image'       => '有効な画像ファイルを選択してください',
        ]);

        // 【追加機能】URLの画像実在・存在チェック
        if ($request->input('image_type') === 'url' && $request->input('image_url')) {
            $url = $request->input('image_url');

            // 外部サーバーへリクエストを送り、ヘッダー情報を取得
            $headers = @get_headers($url, 1);

            if (!$headers) {
                // そもそもドメインが存在しない、または接続できない場合
                return back()->withInput()->withErrors(['image_url' => '指定された画像URLにアクセスできませんでした。']);
            }

            // HTTPステータスコードの確認 (200 OK かどうか)
            $status = $headers[0] ?? '';
            if (strpos($status, '200') === false) {
                return back()->withInput()->withErrors(['image_url' => '指定された画像URL（Webページ）が存在しません。']);
            }

            // Content-Type が画像(image/jpeg, image/png など)であるかチェック
            $contentType = $headers['Content-Type'] ?? $headers['content-type'] ?? '';
            if (is_array($contentType)) {
                $contentType = end($contentType);
            }

            if (strpos($contentType, 'image/') === false) {
                return back()->withInput()->withErrors(['image_url' => '指定されたURLは画像ファイルではありません。']);
            }
        }

        // 画像の処理
        if ($request->input('image_type') === 'file' && $request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('images', 'public');
            $imageUrl = asset('storage/' . $path);
            $image = Product_image::create(['image_url' => $imageUrl]);
        } elseif ($request->input('image_type') === 'url' && $request->input('image_url')) {
            $image = Product_image::create(['image_url' => $request->input('image_url')]);
        } else {
            $image = Product_image::create(['image_url' => null]);
        }

        Product::create([
            'category_id' => $request->input('category_id'),
            'image_id'    => $image->id,
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'price'       => $request->input('price'),
            'stock'       => $request->input('stock'),
        ]);

        return redirect('/admin/products');
    }
}
