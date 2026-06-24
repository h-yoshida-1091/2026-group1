<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_image;
use App\Models\Category;
use Illuminate\Support\Facades\Auth; // Authを使うために必須
use Illuminate\Support\Facades\DB;   // DBファサードを使うために必須

class ProductController extends Controller
{

    // 商品一覧
    public function index(Request $request)
    {

        //ヘッダー用にカテゴリーを全件取得
        $categories = Category::all();
        //リクエストパラメータの取得
        $query = Product::query();
        $keyword = $request->input('keyword');
        $categoryId = $request->input('category_id');
        $minPriceParam = $request->input('min_price');
        $maxPriceParam = $request->input('max_price');

        //商品名検索処理
        if (!empty($keyword)) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }
        //カテゴリ検索処理
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }
        // お気に入り絞り込み
        if ($request->has('favorite') && Auth::check()) {
            // ログインユーザーのお気に入りテーブル（favorites）に存在する商品IDだけに絞り込む
            $productIds = DB::table('favorites')
                ->where('user_id', Auth::id())
                ->pluck('product_id'); // [1, 5, 12] のような配列を取得

            $query->whereIn('products.id', $productIds);
        }
        $dbMinPrice = Product::min('price') ?? 0;
        $dbMaxPrice = Product::max('price') ?? 0;
        $floorMin = floor($dbMinPrice / 100) * 100;
        $ceilMax = ceil($dbMaxPrice / 100) * 100;

        $priceRanges = [];
        if ($ceilMax > $floorMin) {
            // 全体の価格幅を4分割するステップサイズを計算
            $step = ceil((($ceilMax - $floorMin) / 4) / 100) * 100;

            for ($i = 0; $i < 4; $i++) {
                $rangeMin = $floorMin + ($step * $i);
                $rangeMax = ($i === 3) ? $ceilMax : ($rangeMin + $step); // 最後だけ上限を最大値に固定
                $priceRanges[] = [
                    'min' => $rangeMin,
                    'max' => $rangeMax,
                    'label' => '¥' . number_format($rangeMin) . ' 〜 ' . ($i === 3 ? '¥' . number_format($rangeMax) . '+' : '¥' . number_format($rangeMax))
                ];
            }
        }
        if (!empty($minPriceParam)) {
            $query->where('price', '>=', $minPriceParam);
        }
        if (!empty($maxPriceParam)) {
            $query->where('price', '<=', $maxPriceParam);
        }

        $userId = Auth::id() ?? 0;

        // ログイン状態によってデフォルトのソート順を切り替える
        $defaultSort = $userId ? 'recommend' : 'bestseller';
        $sort = $request->input('sort', $defaultSort);

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('products.price', 'asc'); // 価格の安い順
                break;

            case 'price_desc':
                $query->orderBy('products.price', 'desc'); // 価格の高い順
                break;

            case 'newest':
                $query->orderBy('products.id', 'desc'); // 新着順（IDの逆順）
                break;

            case 'bestseller':
                //order_itemsから商品の合計購入数を計算して多い順に並べる
                $subQuery = DB::table('order_items')
                    ->select('product_id', DB::raw('SUM(quantity) as total_sales'))
                    ->groupBy('product_id');

                $query->leftJoinSub($subQuery, 'sales', function ($join) {
                    $join->on('products.id', '=', 'sales.product_id');
                })
                    ->select('products.*')
                    ->selectRaw('COALESCE(sales.total_sales, 0) as total_sales')
                    ->orderBy('total_sales', 'desc')
                    ->orderBy('products.id', 'asc');
                break;

            case 'recommend':
            default:
                //ログインしている時のみ、Groqのスコアテーブルを結合する（安全対策）
                if ($userId > 0) {
                    $query->leftJoin('recommend_scores', function ($join) use ($userId) {
                        $join->on('products.id', '=', 'recommend_scores.product_id')
                            ->where('recommend_scores.user_id', '=', $userId);
                    })
                        // スコアが高い順 ➔ スコアが同じ（または未計算）なら商品ID順
                        ->orderBy('recommend_scores.score', 'desc');
                }

                // 未ログイン時のdefault、またはログイン時の第2ソートとしてID順を適用
                $query->orderBy('products.id', 'asc');
                break;
        }

        $products = $query->get();
        foreach ($products as $product) {
            $image = Product_image::find($product->image_id);
            $product->image_url = $image ? $image->image_url : null;

            $product->is_favorited = DB::table('favorites')
                ->where('user_id', $userId)
                ->where('product_id', $product->id)
                ->exists();
        }
        $is_logged_in = Auth::check();

        $categoryName = null;
        if ($categoryId) {
            // 全カテゴリの中から、現在選択されているIDのカテゴリを1件探す
            $currentCategory = $categories->firstWhere('id', $categoryId);
            if ($currentCategory) {
                $categoryName = $currentCategory->name;
            }
        }

        // lineupに渡す
        return view('products.lineup', compact(
            'products',
            'categories',
            'keyword',
            'categoryId',
            'categoryName',
            'is_logged_in',
            'priceRanges',
            'floorMin',
            'ceilMax',
            'minPriceParam',
            'maxPriceParam'
        ));
    }

    // 商品詳細
    public function show(Request $request)
    {

        //ヘッダー用にカテゴリーを全取得
        $categories = Category::all();
        // 指定IDの商品を取得
        $id = $request->query('id');
        $product = Product::findOrFail($id);

        $image = Product_image::find($product->image_id);
        $product->image_url = $image ? $image->image_url : null;

        // お気に入り登録の有無を判定
        $userId = Auth::id() ?? 0;
        $product->is_favorited = DB::table('favorites')
            ->where('user_id', $userId)
            ->where('product_id', $product->id)
            ->exists();

        // 詳細画面へ
        return view('products.item_detail', compact('product', 'categories'));
    }

    //お気に入り登録処理
    public function favorite(Request $request)
    {
        //セッションからログインユーザーのIDを取得

        $userId = Auth::id();
        if (!$userId) {
            session()->flash('error_message', 'お気に入り機能を利用するにはログインが必要です。');
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        //商品IDを取得
        $productId = $request->input('product_id');

        // 二重登録を防ぎつつ、お気に入りテーブルに挿入
        DB::table('favorites')->updateOrInsert([
            'user_id' => $userId,
            'product_id' => $productId,
        ], [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['status' => 'success']);
    }

    //お気に入り削除処理
    public function unfavorite(Request $request)
    {
        //セッションからログインユーザーのIDを取得 
        $userId = Auth::id();

        if (!$userId) {
            session()->flash('error_message', 'お気に入り機能を利用するにはログインが必要です。');
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        //商品IDを取得
        $productId = $request->input('product_id');

        // user_id と product_id の組み合わせで削除
        DB::table('favorites')
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['status' => 'success']);
    }
}
