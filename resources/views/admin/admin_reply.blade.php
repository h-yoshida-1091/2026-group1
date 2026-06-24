<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>【管理画面】お問い合わせ返信 - MyShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .original-box {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            white-space: pre-wrap;
            word-break: break-all;
        }
    </style>
</head>

<body>

    <div class="container my-5" style="max-width: 1000px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 fw-bold text-dark m-0"><i class="fa-solid fa-reply text-primary me-2"></i> お問い合わせへの返信</h1>
            <a href="/admin/contact" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> 一覧に戻る</a>
        </div>

        <div class="row g-4">
            <div class="col-md-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light fw-bold text-secondary">
                        <i class="fa-solid fa-user me-2"></i>受信内容
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small d-block">お客様名</label>
                            <span class="fs-5 fw-bold text-dark">{{ $contact->name }} 様</span>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">メールアドレス</label>
                            <span class="text-dark">{{ $contact->email }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">受信日時</label>
                            <span class="text-secondary">{{ $contact->created_at->format('Y/m/d H:i') }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">件名</label>
                            <span class="fw-bold text-dark">{{ $contact->subject }}</span>
                        </div>
                        <hr>
                        <div class="mb-2">
                            <label class="text-muted small d-block mb-1">お問い合わせ内容</label>
                            <div class="original-box text-secondary">{{ $contact->message }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="fa-solid fa-pen-to-square me-2"></i>返信メールの作成
                    </div>
                    <div class="card-body p-4">
                        <form action="/admin/contact/{{ $contact->id }}/reply" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">To (送信先アドレス)</label>
                                <input type="text" class="form-control bg-light" value="{{ $contact->email }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">件名</label>
                                <input type="text" class="form-control bg-light" value="Re: {{ $contact->subject }}" readonly>
                            </div>

                            <div class="mb-4">
                                <label for="reply_message" class="form-label fw-bold text-dark">返信本文</label>
                                <textarea class="form-control @error('reply_message') is-invalid @enderror" id="reply_message" name="reply_message" rows="10">{{ old('reply_message', $contact->name . " 様\n\nいつも 真理文庫 をご利用いただき、誠にありがとうございます。\nカスタマーサポートの" . auth()->user()->name . "でございます。\n") }}</textarea>
                                @error('reply_message')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold fs-6">
                                    <i class="fa-solid fa-paper-plane me-2"></i> 対応完了（メール送信）としてマークする
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>