<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

@include('header')

<div class="container my-5" style="max-width: 600px;">
    <h1 class="text-center mb-4 fw-bold">お問い合わせ</h1>

    @if (session('success_message'))
        <div class="alert alert-success shadow-sm mb-4">
            <div class="d-flex align-items-start">
                <i class="fa-solid fa-circle-check me-2 mt-1"></i>
                <div>{!! nl2br(e(session('success_message'))) !!}</div>
            </div>
        </div>
    @endif

    @if (session('error_message'))
        <div class="alert alert-danger shadow-sm mb-4">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error_message') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm p-4">
        <form action="/contact" method="POST">
            @csrf

            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <label class="form-label fw-bold mb-0 me-2">お名前</label>
                    
                </div>
                <input type="text" class="form-control bg-light" value="{{ $user->name }}" readonly>
            </div>

            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <label class="form-label fw-bold mb-0 me-2">メールアドレス</label>
                </div>
                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
            </div>

            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <label for="subject" class="form-label fw-bold mb-0 me-2">件名</label>
                    <span class="badge bg-danger">必須</span>
                </div>
                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" placeholder="商品の配送について、など">
            </div>

            <div class="mb-4">
                <div class="d-flex align-items-center mb-2">
                    <label for="message" class="form-label fw-bold mb-0 me-2">お問い合わせ内容</label>
                    <span class="badge bg-danger">必須</span>
                </div>
                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" placeholder="こちらにお問い合わせ内容をご記入ください。">{{ old('message') }}</textarea>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg fw-bold fs-6">
                    <i class="fa-solid fa-paper-plane me-2"></i>送信する
                </button>
            </div>
        </form>
    </div>
</div>

@include('footer')

</body>
</html>