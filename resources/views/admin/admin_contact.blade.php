<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>【管理画面】お問い合わせ一覧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .table-container { background: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .msg-text { white-space: pre-wrap; word-break: break-all; font-size: 0.9rem; background: #f1f3f5; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="container-fluid my-5 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark"><i class="fa-solid fa-inbox me-2"></i>お問い合わせ管理一覧</h1>
        <a href="/products" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i>ショップに戻る</a>
    </div>

    <div class="table-container p-4">
        @if($contacts->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="fa-regular fa-folder-open fs-1 mb-3 d-block"></i>
                現在、届いているお問い合わせはありません。
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 8%;">ID</th>
                            <th style="width: 15%;">受信日時</th>
                            <th style="width: 15%;">お客様情報</th>
                            <th style="width: 20%;">件名</th>
                            <th style="width: 42%;">お問い合わせ内容</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                            <tr>
                                <td class="fw-bold text-secondary">#{{ $contact->id }}</td>
                                <td class="text-muted" style="font-size: 0.85rem;">
                                    {{ $contact->created_at->format('Y/m/d H:i') }}
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $contact->name }}</div>
                                    <div class="text-muted" style="font-size: 0.8rem;">{{ $contact->email }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark mb-1">件名</span>
                                    <div class="fw-bold">{{ $contact->subject }}</div>
                                </td>
                                <td>
                                    <div class="msg-text text-secondary">{{ $contact->message }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

</body>
</html>