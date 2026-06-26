<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>【管理画面】お問い合わせ一覧 - MyShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-size: 1rem;
        }

        .table-container {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .table th {
            padding: 18px 12px;
            font-size: 1.05rem;
        }

        .table td {
            padding: 20px 12px;
            font-size: 1rem;
        }

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

        .table tbody tr {
            transition: background-color 0.2s;
        }

        .table tbody tr:hover {
            background-color: #fafbfc;
        }

        .status-badge {
            font-size: 0.9rem;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: bold;
        }

        .priority-badge {
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        @media (max-width: 991.98px) {
            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 20px;
                padding: 20px;
                background: #ffffff;
                border: 1px solid #e3e6f0;
                border-radius: 10px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            }

            .table td {
                display: block;
                width: 100% !important;
                padding: 10px 0 !important;
                border: none !important;
                text-align: left !important;
            }

            .table td:nth-child(1)::before {
                content: "【受信日時】 ";
                font-weight: bold;
                color: #4e73df;
                display: inline-block;
                margin-right: 5px;
            }

            .msg-text-box {
                margin-top: 5px;
            }

            .table td:last-child {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                background: #f8f9fa;
                padding: 12px !important;
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
        @if (session('success_message'))
        <div class="alert alert-success shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success_message') }}
        </div>
        @endif

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
                            <th style="width: 15%; text-align: center;">状態・優先度 / 操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                        <tr>
                            <td class="text-secondary fw-medium">
                                {{ $contact->created_at->format('Y/m/d H:i') }}
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-5 mb-1">{{ $contact->name }}</div>
                                <div class="text-muted" style="font-size: 0.9rem;">{{ $contact->email }}</div>
                            </td>
                            <td>
                                <div class="mb-2">
                                    @if($contact->priority == 3)
                                    <span class="badge bg-danger priority-badge text-white">
                                        <i class="fa-solid fa-triangle-exclamation me-1"></i>優先度：高
                                    </span>
                                    @elif($contact->priority == 2)
                                    <span class="badge bg-light priority-badge text-secondary border">
                                        <i class="fa-solid fa-minus me-1"></i>優先度：中
                                    </span>
                                    @else
                                    <span class="badge bg-info priority-badge text-dark">
                                        <i class="fa-solid fa-comment-dots me-1"></i>優先度：低
                                    </span>
                                    @endif
                                </div>
                                <div class="fw-bold text-dark fs-6">{{ $contact->subject }}</div>
                            </td>
                            <td>
                                <div class="msg-text-box text-dark">{{ $contact->message }}</div>
                            </td>
                            <td style="text-align: center;">
                                <div class="mb-3">
                                    @if($contact->status === '対応済')
                                    <span class="badge bg-success status-badge text-white"><i class="fa-solid fa-check me-1"></i>対応済</span>
                                    @else
                                    <span class="badge bg-warning status-badge text-dark"><i class="fa-solid fa-clock me-1"></i>未対応</span>
                                    @endif
                                </div>

                                <div class="d-flex flex-column gap-2 align-items-center">
                                    <a href="/admin/contact/{{ $contact->id }}/reply" class="btn btn-primary btn-sm fw-bold px-3 py-2 w-100">
                                        <i class="fa-solid fa-reply me-1"></i> 返信画面へ
                                    </a>

                                    <form action="/admin/contact/{{ $contact->id }}/trash" method="POST" class="w-100" onsubmit="return confirm('このお問い合わせをゴミ箱に移動しますか？')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm fw-bold px-3 py-2 w-100">
                                            <i class="fa-solid fa-trash-can me-1"></i> ゴミ箱へ
                                        </button>
                                    </form>
                                </div>
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