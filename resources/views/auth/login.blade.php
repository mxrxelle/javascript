<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certly Login</title>

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

        .logo-box{
            width:68px;
            height:68px;
            background:var(--accent);
            border-radius:14px;
            display:flex;
            justify-content:center;
            align-items:center;
            font-size:42px;
            color:var(--primary);
        }

        .logo-text{
            font-size:42px;
            font-weight:600;
            color:var(--primary);
        }

        .login-card{
            background:white;
            border:3px solid var(--primary);
            border-radius:25px;
            padding:50px;
            box-shadow:0 10px 20px rgba(0,0,0,.08);
        }

        .login-title{
            text-align:center;
            color:var(--primary);
            font-size:60px;
            font-weight:700;
            margin-bottom:40px;
        }

        .form-label{
            font-size:20px;
            font-weight:500;
            margin-bottom:10px;
        }

        .form-control,
        .form-select{
            height:72px;
            border-radius:18px;
            border:1px solid #d7d7d7;
            background:#f8fafc;
            font-size:18px;
            padding-left:22px;
        }

        .form-control:focus,
        .form-select:focus{
            box-shadow:none;
            border-color:var(--primary);
        }

        .login-btn{
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

        .login-btn:hover{
            opacity:.9;
        }

        .forgot-link{
            display:block;
            text-align:center;
            margin-top:25px;
            text-decoration:none;
            color:#f0ad00;
            font-size:18px;
        }

        .signup-text{
            text-align:center;
            margin-top:35px;
            font-size:18px;
            color:#555;
        }

        .signup-link{
            color:#f0ad00;
            text-decoration:none;
            font-weight:600;
        }

        .alert{
            border-radius:14px;
        }

        @media(max-width:768px){

            .login-card{
                padding:30px;
            }

            .login-title{
                font-size:42px;
            }

            .logo-text{
                font-size:32px;
            }

            .logo-box{
                width:58px;
                height:58px;
                font-size:34px;
            }
        }
    </style>
</head>
<body>

<div class="page-container">

    <!-- Logo -->
    <div class="logo-wrapper">
        <a href="{{ url('/') }}" class="logo-link">
            <div class="logo-box">C</div>
            <span class="logo-text">Certly</span>
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Errors -->
    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Login Card -->
    <div class="login-card">

        <h1 class="login-title">Welcome Back</h1>

        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="mb-4">
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

            <!-- Password -->
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                    required
                >
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label class="form-label">Login As</label>

                <select name="role" class="form-select">
                    <option value="student">Student</option>
                    <option value="teacher">Facilitator</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <button type="submit" class="login-btn">
                Log In
            </button>

            <a href="#" class="forgot-link">
                Forgot Password?
            </a>

            <div class="signup-text">
                Don't have an account?
                <a href="{{ route('register') }}" class="signup-link">
                    Sign Up
                </a>
            </div>

        </form>

    </div>

</div>

</body>
</html>