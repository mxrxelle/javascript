<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Certly</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #003b73;
            --accent: #ffc42e;
            --muted: #f5f7fa;
            --input-bg: #f8fafc;
            --border: #dcdfe3;
            --text-muted: #555;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--muted);
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 32px 20px;
        }

        .register-wrapper {
            width: 100%;
            max-width: 650px;
        }

        .logo-area {
            text-align: center;
            margin-bottom: 45px;
        }

        .logo-link {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .logo-box {
            width: 68px;
            height: 68px;
            background: var(--accent);
            border-radius: 14px;
            color: var(--primary);
            font-size: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-text {
            color: var(--primary);
            font-size: 42px;
            font-weight: 700;
        }

        .register-card {
            background: #fff;
            border: 3px solid var(--primary);
            border-radius: 22px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
            padding: 52px 48px 45px;
        }

        .register-title {
            color: var(--primary);
            font-size: 42px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 10px;
        }

        .register-subtitle {
            color: var(--text-muted);
            font-size: 23px;
            text-align: center;
            margin-bottom: 42px;
        }

        .form-label {
            color: #111;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .form-control {
            height: 72px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: var(--input-bg);
            font-size: 23px;
            padding: 0 24px;
        }

        .form-control::placeholder {
            color: #8f98a1;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: none;
            background: var(--input-bg);
        }

        .form-group {
            margin-bottom: 28px;
        }

        .create-btn {
            width: 100%;
            height: 70px;
            border: none;
            border-radius: 14px;
            background: var(--accent);
            color: var(--primary);
            font-size: 23px;
            font-weight: 800;
            margin-top: 4px;
        }

        .create-btn:hover {
            opacity: 0.9;
        }

        .signin-text {
            text-align: center;
            margin-top: 38px;
            color: #555;
            font-size: 20px;
        }

        .signin-text a {
            color: #f0a800;
            font-weight: 600;
            text-decoration: none;
        }

        .signin-text a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 14px;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .register-card {
                padding: 35px 28px;
            }

            .register-title {
                font-size: 35px;
            }

            .register-subtitle {
                font-size: 18px;
            }

            .logo-box {
                width: 58px;
                height: 58px;
                font-size: 34px;
            }

            .logo-text {
                font-size: 34px;
            }

            .form-control {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

<div class="register-wrapper">

    <div class="logo-area">
        <a href="{{ url('/') }}" class="logo-link">
            <div class="logo-box">C</div>
            <span class="logo-text">Certly</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="register-card">
        <h1 class="register-title">Create Account</h1>
        <p class="register-subtitle">Join thousands of learners today</p>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    placeholder="John Doe"
                    value="{{ old('name') }}"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="your.email@example.com"
                    value="{{ old('email') }}"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Create a strong password"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Re-enter your password"
                    required
                >
            </div>

            {{-- Hidden fields para hindi masira kung required pa rin sa RegisterController --}}
            <input type="hidden" name="birthday" value="{{ old('birthday', '2000-01-01') }}">
            <input type="hidden" name="contact_number" value="{{ old('contact_number', 'N/A') }}">
            <input type="hidden" name="affiliation" value="{{ old('affiliation', 'Student') }}">

            <button type="submit" class="create-btn">
                Create Account
            </button>

            <div class="signin-text">
                Already have an account?
                <a href="{{ route('login') }}">Sign in instead</a>
            </div>
        </form>
    </div>

</div>

</body>
</html>