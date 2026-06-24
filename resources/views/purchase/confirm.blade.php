@include('header')

<link rel="stylesheet" href="{{ asset('css/confirm.css') }}">

<h1 class="title">購入確認</h1>

<table class="confirm-table">
    <thead>
        <tr>
            <th>商品名</th>
            <th>個数</th>
            <th>値段</th>
            <th>小計</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($cartItems as $index => $cartItem)

        @php
        $product = $products->firstWhere('id', $cartItem->product_id);
        @endphp

        <tr>
            <td class="product-name">
                {{ $product->name }}
            </td>

            <td class="product-quantity">
                {{ $cartItem->quantity }}
            </td>

            <td class="product-price">
                ¥{{ number_format($product->price) }}
            </td>

            <td class="product-subtotal">
                ¥{{ number_format($subtotals[$index]) }}
            </td>
        </tr>

        @empty

        <tr>
            <td colspan="4" class="empty-message">
                購入対象の商品がありません。
            </td>
        </tr>

        @endforelse
    </tbody>
</table>

<form action="/purchase/complete" method="post" class="purchase-form">
    @csrf

    <input type="hidden" name="purchase_type" value="{{ $purchaseType }}">

    @if($purchaseType === 'now')
    <input type="hidden" name="product_id" value="{{ $products[0]->id }}">

    <input type="hidden" name="quantity" value="{{ $cartItems[0]->quantity }}">
    @endif

    <div class="user-info-section">

            <h3>お届け先・お客様情報</h3>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" required value="{{ $user->email }}">
            </div>

            <div class="form-group">
                <label for="name">お名前</label>
                <input type="text" id="name" name="name" required value="{{ $user->name }}">
            </div>

            <div class="form-group">
                <label for="address">ご住所</label>
                <input type="text" id="address" name="address" required value="{{ $user->address }}">
            </div>

        </div>

    </div>

    <!-- ここから追加 -->
    <div class="right-area">

        <div class="total-section">
            <h3>合計金額</h3>

            <p class="total-price">
                ¥{{ number_format($total) }}
            </p>
        </div>

        <div class="submit-section">

            <a href="/cart" class="btn-back-cart">
                カートに戻る
            </a>

            <button type="submit" class="btn-complete" @if(empty($products) || count($products)===0) disabled @endif>
                購入確定
            </button>

        </div>

    </div>
</form>

@include('footer')