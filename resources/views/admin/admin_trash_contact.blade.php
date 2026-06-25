<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>【管理画面】ゴミ箱</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-size: 1rem; }
        .table-container { background: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        
        .table th { padding: 18px 12px; font-size: 1.05rem; }
        .table td { padding: 20px 12px; font-size: 1rem; }
        
        .msg-text-box { 
            white-space: pre-wrap; 
            word-break: break-all; 
            font-size: 1rem; 
            background: #fafafa; 
            padding: 15px; 
            border-radius: 6px;
            border: 1px solid #e9ecef;
            line-height: 1.6;
        }
        
        .status-badge { font-size: 0.85rem; padding: 5px 10px; border-radius: 50px; font-weight: bold; }

        /* レスポンシブ対応 */
        @media (max-width: 991.98px) {
            .table thead { display: none; }
            .table tbody tr {
                display: block;
                margin-bottom: 20px;
                padding: 20px;
                background: #ffffff;
                border: 1px solid #e3e6f0;
                border-radius: 10px;
            }
            .table td {
                display: block;
                width: 100% !important;
                padding: 10px 0 !important;
                border: none !important;
                text-align: left !important;
            }
            .table td:nth-child(2)::before {
                content: "【受信日時】 ";
                font-weight: bold;
                color: #e74a3b;
                display: inline-block;
                margin-right: 5px;
            }
            .msg-text-box { margin-top: 5px; }
            .table td:last-child {
                background: #fff5f5;
                padding: 15px !important;
                margin-top: 15px;
                border-radius: 6px;
            }
        }
    </style>
</head>
<body>

@include('admin.admin_header')

@include('admin.admin_sidebar')

<div class="container-fluid my-5 px-5">
    @if(session('success_message'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error_message'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h1 class="h2 fw-bold text-dark mb-4"><i class="fa-solid fa-trash-can me-3 text-danger"></i>ゴミ箱一覧</h1>

    <form action="/admin/contact/bulk-delete" method="POST" id="bulk-delete-form" onsubmit="return confirm('選択されたすべてのお問い合わせを完全に削除しますか？この操作は取り消せません。')">
        @csrf
        @method('DELETE')

        <div class="table-container p-4">
            @if($contacts->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="fa-solid fa-trash-arrow-up fs-1 mb-3 d-block"></i>
                    ゴミ箱は空っぽです。
                </div>
            @else
                <div class="mb-3 d-flex align-items-center gap-3">
                    <div class="form-check fs-5">
                        <input class="form-check-input" type="checkbox" id="select-all">
                        <label class="form-check-input-label text-secondary" for="select-all" style="font-size: 0.95rem;">すべて選択</label>
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm fw-bold px-3 py-2">
                        <i class="fa-solid fa-dumpster-fire me-1"></i> 選択した項目を一括完全削除
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">選択</th>
                                <th style="width: 15%;">受信日時</th>
                                <th style="width: 15%;">お客様情報</th>
                                <th style="width: 15%;">件名</th>
                                <th style="width: 30%;">お問い合わせ内容</th>
                                <th style="width: 20%; text-align: center;">元の状態 / アクション</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr>
                                    <td>
                                        <div class="form-check fs-5">
                                            <input class="form-check-input contact-checkbox" type="checkbox" name="contact_ids[]" value="{{ $contact->id }}">
                                        </div>
                                    </td>
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
                                        <div class="msg-text-box text-muted">{{ $contact->message }}</div>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="mb-3">
                                            <span class="text-muted small d-block mb-1">元のステータス:</span>
                                            @if($contact->previous_status === '対応済')
                                                <span class="badge bg-success status-badge text-white"><i class="fa-solid fa-check me-1"></i>対応済</span>
                                            @else
                                                <span class="badge bg-warning status-badge text-dark"><i class="fa-solid fa-clock me-1"></i>未対応</span>
                                            @endif
                                        </div>
                                        
                                        <div class="d-flex flex-column gap-2 align-items-center">
                                            <button type="button" class="btn btn-outline-success btn-sm fw-bold w-100 py-1.5" onclick="event.stopPropagation(); document.getElementById('restore-form-{{ $contact->id }}').submit();">
                                                <i class="fa-solid fa-trash-arrow-up me-1"></i> 一覧に復元
                                            </button>
                                            
                                            <button type="button" class="btn btn-link text-danger btn-sm fw-bold w-100 text-decoration-none" onclick="event.stopPropagation(); if(confirm('このデータを完全に削除しますか？')){ document.getElementById('delete-form-{{ $contact->id }}').submit(); }">
                                                <i class="fa-solid fa-circle-xmark me-1"></i> 完全に削除
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </form>
    @foreach($contacts as $contact)
        <form id="restore-form-{{ $contact->id }}" action="/admin/contact/{{ $contact->id }}/restore" method="POST" style="display: none;">
            @csrf
        </form>
        <form id="delete-form-{{ $contact->id }}" action="/admin/contact/{{ $contact->id }}/force-delete" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
</div>

<script>
    document.getElementById('select-all')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.contact-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>

</body>
</html>