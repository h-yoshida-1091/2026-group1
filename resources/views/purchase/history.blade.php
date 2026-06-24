<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注文履歴 - ECサイト</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .history-container { max-width: 900px; margin: 40px auto; padding: 0 15px; }
        .order-card { background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 25px; border: 1px solid #e3e6f0; }
        .order-header { background-color: #f1f3f9; padding: 15px 20px; border-bottom: 1px solid #e3e6f0; border-top-left-radius: 7px; border-top-right-radius: 7px; }
        .order-body { padding: 20px; }
        .product-item { padding: 12px 0; border-bottom: 1px dashed #e3e6f0; }
        .product-item:last-child { border-bottom: none; }
    </style>
</head>
<body>

    @include('header')

    <div class="history-container">
        <h2 class="mb-4 text-dark fw-bold">注文履歴</h2>

        {{-- ⭕️ 注文が1件も無い場合の処理 --}}
        @if($orders->isEmpty())
            <div class="alert alert-info text-center py-4">
                <p class="mb-3">まだ購入された商品はありません。</p>
                <a href="/products" class="btn btn-primary btn-sm">商品を見に行く</a>
            </div>
        @else
            {{-- ⭕️ コントローラから渡された $orderDetails をループで回す --}}
            @foreach ($orderDetails as $detail)
                <div class="order-card">
                    
                    <div class="order-header">
                        <div class="row align-items-center text-secondary small">
                            <div class="col-6 col-md-3">
                                <div class="text-uppercase fw-bold text-muted" style="font-size: 0.75rem;">注文日</div>
                                <div class="text-dark font-weight-bold">{{ \Carbon\Carbon::parse($detail['order']->order_date)->format('Y年m月d日') }}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-uppercase fw-bold text-muted" style="font-size: 0.75rem;">合計金額</div>
                                <div class="text-dark font-weight-bold">¥{{number_format($detail['order']->sumprice ?? 0) }}</div>
                            </div>
                            <div class="col-12 col-md-6 text-md-end mt-2 mt-md-0">
                                <span class="badge bg-secondary">注文番号： {{ $detail['order']->id }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="order-body">
                        @foreach ($detail['items'] as $item)
                            <div class="product-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 text-dark" style="font-size: 1rem;">{{ $item->name }}</h5>
                                    <span class="text-muted small">数量: {{ $item->quantity }} 点 / 単価: ¥{{ number_format($item->price) }}</span>
                                </div>
                                <div class="text-end fw-bold text-dark">
                                    ¥{{ number_format($item->price * $item->quantity) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach
        @endif
    </div>

    @include('footer')

</body>
</html>