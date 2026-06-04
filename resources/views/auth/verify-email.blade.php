<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Certly</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root{
            --primary:#003b73;
            --accent:#f9c62b;
            --bg:#f5f7fa;
            --input:#f8fafc;
            --border:#d9d9d9;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:var(--bg);
            min-height:100vh;
            font-family: Arial, Helvetica, sans-serif;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:40px 20px;
        }

        .page-container{
            width:100%;
            max-width:700px;
        }

        .logo-wrapper{
            text-align:center;
            margin-bottom:25px;
        }

        .logo-link{
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            gap:14px;
        }

        .logo-text{
            font-size:42px;
            font-weight:600;
            color:var(--primary);
        }

        .verify-card{
            background:white;
            border:3px solid var(--primary);
            border-radius:25px;
            padding:50px;
            box-shadow:0 10px 20px rgba(0,0,0,.08);
        }

        .verify-title{
            text-align:center;
            color:var(--primary);
            font-size:52px;
            font-weight:700;
            margin-bottom:20px;
        }

        .verify-subtitle{
            text-align:center;
            font-size:18px;
            color:#555;
            line-height:1.6;
            margin-bottom:40px;
        }

        .form-label{
            font-size:20px;
            font-weight:500;
            margin-bottom:10px;
        }

        .form-control{
            height:72px;
            border-radius:18px;
            border:1px solid #d7d7d7;
            background:#f8fafc;
            font-size:24px;
            letter-spacing: 8px;
            text-indent: 8px;
        }

        .form-control:focus{
            box-shadow:none;
            border-color:var(--primary);
            background:#white;
        }

        .verify-btn{
            width:100%;
            height:72px;
            border:none;
            border-radius:18px;
            background:var(--accent);
            color:var(--primary);
            font-size:22px;
            font-weight:700;
            margin-top:10px;
            transition:.2s;
        }

        .verify-btn:hover{
            opacity:.9;
        }

        .action-links-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }

        .utility-link-btn{
            background: none;
            border: none;
            color: #f0ad00;
            text-decoration: none;
            font-weight: 600;
            font-size: 18px;
            padding: 0;
            transition: .2s;
        }

        .utility-link-btn:hover{
            opacity: 0.8;
        }

        .utility-link-btn.text-muted{
            color: #777 !important;
        }

        .alert{
            border-radius:14px;
            font-size: 16px;
        }

        @media(max-width:768px){
            .verify-card{
                padding:30px;
            }

            .verify-title{
                font-size:36px;
            }

            .verify-subtitle{
                font-size:16px;
            }

            .logo-text{
                font-size:32px;
            }
        }
    </style>
</head>
<body>

<div class="page-container">

    <div class="logo-wrapper">
        <a href="{{ url('/') }}" class="logo-link">
            <img src="{{ asset('images/certly-logo.png') }}" alt="Certly Logo" class="logo-img" style="width: 68px; height: 68px; object-fit: contain;">
            <span class="logo-text">Certly</span>
        </a>
    </div>

    @if (session('message'))
        <div class="alert alert-success mb-4 text-center">
            {{ session('message') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4 text-center">
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif

    <div class="verify-card">

        <h1 class="verify-title">Verify Your Account</h1>
        <p class="verify-subtitle">
            We have sent a <strong>6-digit Verification Code</strong> to your registered email address. Please check your inbox and enter the security token below.
        </p>

        <form method="POST" action="{{ route('verification.verify_code') }}">
            @csrf

            <div class="mb-4 text-center">
                <label class="form-label d-block text-start">Verification Code</label>
                <input 
                    type="text" 
                    name="code" 
                    class="form-control text-center fw-bold @error('code') is-invalid @enderror" 
                    placeholder="000000" 
                    maxlength="6" 
                    required 
                    autocomplete="off"
                >
            </div>

            <button type="submit" class="verify-btn">
                Verify Account
            </button>
        </form>

        <div class="action-links-container">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="utility-link-btn">Resend Code</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="utility-link-btn text-muted">Log Out</button>
            </form>
        </div>

    </div>

</div>

</body>
</html>