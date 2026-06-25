<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ご購入手続き完了のお知らせ</title>
</head>
<body style="font-family: 'Noto Serif JP', serif; color: #2c2a29; line-height: 1.6;">

    <p>{{ $user->name }} 様</p>

    <p>この度は「真理文庫」にて商品をご購入いただき、誠にありがとうございます。<br>
    以下の内容でご注文手続きが完了いたしましたので、ご確認ください。</p>

    <hr style="border: none; border-top: 1px solid #e5e0da; margin: 20px 0;">

    <h3>■ ご注文明細</h3>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr style="border-bottom: 1px solid #e5e0da; text-align: left;">
                <th style="padding: 8px 0;">商品名</th>
                <th style="padding: 8px 0; text-align: center;">個数</th>
                <th style="padding: 8px 0; text-align: right;">金額</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cartItems as $item)
                @php
                    $product = $products->firstWhere('id', $item->product_id);
                @endphp
                @if ($product)
                    <tr style="border-bottom: 1px solid #f0ede9;">
                        <td style="padding: 8px 0;">{{ $product->name }}</td>
                        <td style="padding: 8px 0; text-align: center;">{{ $item->quantity }}</td>
                        <td style="padding: 8px 0; text-align: right;">¥{{ number_format($product->price * $item->quantity) }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <p style="font-size: 18px; font-weight: bold; text-align: right;">
        合計金額：¥{{ number_format($total) }}
    </p>

    <hr style="border: none; border-top: 1px solid #e5e0da; margin: 20px 0;">

    <h3>■ お届け先情報</h3>
    <p>
        お名前：{{ $user->name }} 様<br>
        ご住所：{{ $user->address }}<br>
    </p>

    <hr style="border: none; border-top: 1px solid #e5e0da; margin: 20px 0;">

    <p>商品は準備が整い次第、発送させていただきます。<br>
    発送が完了しましたら、改めてご連絡いたします。</p>

    <p>※本メールは自動配信です。ご不明な点がございましたら、お問い合わせフォームよりご連絡ください。</p>

    <p>━━━━━━━━━━━━━━━━━━━━━━━━<br>
    真理文庫<br>
    〒000-0000 東京都〇〇区〇〇町1-2-3<br>
    TEL: 03-1234-5678<br>
    E-mail: fgroup.shoping@gmail.com<br>
    ━━━━━━━━━━━━━━━━━━━━━━━━</p>

</body>
</html>