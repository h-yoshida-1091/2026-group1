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

        // 画像の処理
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