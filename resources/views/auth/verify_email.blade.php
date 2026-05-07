<!DOCTYPE html>
<html>
<head>
    <title>Verify Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow mx-auto" style="max-width: 500px;">
            <div class="card-body text-center">
                <h4 class="card-title">Verify Your Account</h4>
                <p class="card-text">Bago ka magpatuloy, paki-verify muna ang iyong account sa pamamagitan ng link na sinend namin sa iyong email.</p>

                @if (session('message'))
                    <div class="alert alert-success">
                        Isang bagong verification link ang naipadala sa iyong email.
                    </div>
                @endif

                <form action="{{ route('verification.send') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">Resend Verification Email</button>
                </form>

                <form action="{{ route('logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-link">Logout</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>