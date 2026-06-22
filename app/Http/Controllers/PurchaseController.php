<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_image;
use App\Models\Cart_item;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // 購入確認画面
    public function confirm(Request $request)
    {
        $products = [];

        foreach ($request->products as $item) {

            $product = Product::findOrFail($item['id']);

            $image = Product_image::find($product->image_id);

            $product->image = $image ? $image->image_url : null;

            $product->quantity = $item['quantity'];

            $products[] = $product;
        }

        return view('purchase.confirm', compact('products'));
    }

    // 購入完了
    public function complete(Request $request)
    {
        Cart_item::where('user_id', 1)->delete();

        return view('purchase.complete');
    }
}
