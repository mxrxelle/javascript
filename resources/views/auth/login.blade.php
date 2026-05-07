<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LMS Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-card { margin-top: 100px; border: none; border-radius: 15px; }
        .card-header { border-radius: 15px 15px 0 0 !class; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            
            <!-- Success Message from Registration -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger mt-5">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card login-card shadow">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">LMS Portal Login</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ url('/login') }}" method="POST">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                        </div>

                        <!-- Remember Me (Optional but good to have) -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <!-- Login Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Login</button>
                        </div>

                        <hr class="my-4">

                        <!-- Registration Link -->
                        <div class="text-center">
                            <p class="mb-0">Don't have an account?</p>
                            <a href="{{ route('register') }}" class="text-decoration-none font-weight-bold">Register as a Student</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-3 text-muted">
                <small>&copy; 2026 Your LMS Platform</small>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (for alert dismissal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>