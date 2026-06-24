<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>【管理画面】お問い合わせ一覧 - MyShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    body { background-color: #f4f6f9; font-size: 1rem; }
    .table-container { background: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    
    .table th { padding: 18px 12px; font-size: 1.05rem; }
    .table td { padding: 20px 12px; font-size: 1rem; }
    
    .msg-text-box { 
        white-space: pre-wrap; 
        word-break: break-all; 
        font-size: 1rem; 
        background: #f8f9fa; 
        padding: 15px; 
        border-radius: 6px;
        border: 1px solid #e9ecef;
        line-height: 1.6;
    }

    .clickable-row { cursor: pointer; transition: background-color 0.2s; }
    .clickable-row:hover { background-color: #f8fafd !important; }
    
    .status-badge { font-size: 0.9rem; padding: 6px 12px; border-radius: 50px; font-weight: bold; }

    /* ==========================================================================
       ★ ここから追記：画面が半分くらいに縮小されたときのレスポンシブ対応
       ========================================================================== */
    @media (max-width: 991.98px) {
        /* テーブルのヘッダー（見出し行）を非表示にする */
        .table thead {
            display: none;
        }
        
        /* 行（tr）を独立した一つのカード型ブロックにする */
        .table tbody tr {
            display: block;
            margin-bottom: 20px;
            padding: 20px;
            background: #ffffff;
            border: 1px solid #e3e6f0;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        
        /* 各セル（td）を横並びから縦並び（ブロック）に変更し、幅を100%に */
        .table td {
            display: block;
            width: 100% !important;
            padding: 10px 0 !important;
            border: none !important; /* 内側の境界線を消す */
            text-align: left !important;
        }

        /* 各項目の前に何の情報か分かるようにラベル的なデザインを入れる（任意） */
        .table td:nth-child(1)::before {
            content: "【受信日時】 ";
            font-weight: bold;
            color: #4e73df;
            display: inline-block;
            margin-right: 5px;
        }

        /* お問い合わせ内容とステータスエリアの余白微調整 */
        .msg-text-box {
            margin-top: 5px;
        }
        
        /* ステータスとボタンを中央寄せから左寄せにしてスマホでも押しやすく */
        .table td:last-child {
            display: flex;
            align-items: center;
            justify-content: space-between; /* ステータスとボタンを左右に綺麗に配置 */
            gap: 10px;
            background: #f8f9fa;
            padding: 12px !important;
            margin-top: 15px;
            border-radius: 6px;
        }
        .table td:last-child .mb-3 {
            margin-bottom: 0 !important; /* 下マージンをリセット */
        }
    }
</style>
</head>
<body>

<div class="container-fluid my-5 px-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold text-dark m-0"><i class="fa-solid fa-inbox me-3 text-primary"></i>お問い合わせ管理一覧</h1>
    </div>

    <div class="table-container p-4">
        @if($contacts->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="fa-regular fa-folder-open fs-1 mb-3 d-block"></i>
                現在、届いているお問い合わせはありません。
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 15%;">受信日時</th>
                            <th style="width: 15%;">お客様情報</th>
                            <th style="width: 20%;">件名</th>
                            <th style="width: 35%;">お問い合わせ内容</th>
                            <th style="width: 15%; text-align: center;">ステータス / アクション</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                            <tr class="clickable-row" onclick="window.location='/admin/contact/{{ $contact->id }}/reply'">
                                <td class="text-secondary fw-medium">
                                    {{ $contact->created_at->format('Y/m/d H:i') }}
                                </td>
                                <td>
                                    <div class="fw-bold text-dark fs-5 mb-1">{{ $contact->name }}</div>
                                    <div class="text-muted" style="font-size: 0.9rem;">{{ $contact->email }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary mb-2" style="font-size: 0.75rem;">SUBJECT</span>
                                    <div class="fw-bold text-dark fs-6">{{ $contact->subject }}</div>
                                </td>
                                <td>
                                    <div class="msg-text-box text-dark">{{ $contact->message }}</div>
                                </td>
                                <td style="text-align: center;" onclick="event.stopPropagation();">
                                    <div class="mb-3">
                                        @if($contact->status === '対応済')
                                            <span class="badge bg-success status-badge text-white"><i class="fa-solid fa-check me-1"></i>対応済</span>
                                        @else
                                            <span class="badge bg-warning status-badge text-dark"><i class="fa-solid fa-clock me-1"></i>未対応</span>
                                        @endif
                                    </div>
                                    
                                    <a href="/admin/contact/{{ $contact->id }}/reply" class="btn btn-primary btn-sm fw-bold px-3 py-2">
                                        <i class="fa-solid fa-reply me-1"></i> 返信画面へ
                                    </a>
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