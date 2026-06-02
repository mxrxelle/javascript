<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certly Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #003b73;
            --accent: #ffbd2e;
            --muted: #f4f7fb;
            --text-muted: #5f6368;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: white;
            color: #222;
        }

        .navbar-custom {
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
            padding: 18px 33px;
        }

        .logo-box {
            width: 57px;
            height: 57px;
            background: var(--accent);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 33px;
        }

        .brand-text {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary);
        }

        .nav-link-custom {
            font-size: 24px;
            color: #2f2f2f;
            text-decoration: none;
            margin-left: 35px;
        }

        .search-box {
            position: relative;
            margin-left: 40px;
        }

        .search-box i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 20px;
        }

        .search-box input {
            width: 370px;
            height: 60px;
            border: 1px solid #ddd;
            border-radius: 14px;
            padding-left: 58px;
            font-size: 23px;
            outline: none;
        }

        .login-btn {
            border: 3px solid var(--accent);
            color: #f0a800;
            background: white;
            border-radius: 14px;
            padding: 14px 36px;
            font-size: 22px;
            text-decoration: none;
            transition: 0.2s;
        }

        .login-btn:hover {
            background: var(--accent);
            color: var(--primary);
        }

        .hero {
            max-width: 1750px;
            margin: 0 auto;
            padding: 115px 33px 72px;
        }

        .hero-line {
            width: 115px;
            height: 6px;
            background: var(--accent);
            margin-bottom: 40px;
        }

        .hero h1 {
            color: var(--primary);
            font-size: 67px;
            line-height: 1.05;
            font-weight: 800;
            margin-bottom: 34px;
        }

        .hero p {
            color: var(--text-muted);
            font-size: 29px;
            line-height: 1.35;
            max-width: 780px;
            margin-bottom: 45px;
        }

        .btn-accent {
            background: var(--accent);
            color: var(--primary);
            border-radius: 14px;
            padding: 18px 45px;
            font-size: 23px;
            font-weight: 700;
            text-decoration: none;
            border: none;
        }

        .btn-primary-custom {
            background: var(--primary);
            color: white;
            border-radius: 14px;
            padding: 18px 45px;
            font-size: 23px;
            font-weight: 700;
            text-decoration: none;
            border: none;
        }

        .hero-card {
            background: var(--muted);
            border-radius: 28px;
            height: 550px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .circle-outer {
            width: 138px;
            height: 138px;
            background: #dde5ef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .circle-inner {
            width: 92px;
            height: 92px;
            background: var(--primary);
            border-radius: 50%;
        }

        .stats {
            background: var(--muted);
            padding: 45px 0 90px;
        }

        .stat-number {
            font-size: 70px;
            color: var(--primary);
            font-weight: 800;
        }

        .stat-label {
            font-size: 27px;
            color: var(--text-muted);
        }

        .footer {
            background: var(--primary);
            color: white;
            padding: 70px 33px 40px;
        }

        .footer h4 {
            font-weight: 700;
            margin-bottom: 30px;
        }

        .footer a,
        .footer p {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-size: 23px;
        }

        .footer li {
            margin-bottom: 20px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.25);
            margin-top: 60px;
            padding-top: 30px;
            color: rgba(255,255,255,0.6);
            text-align: center;
            font-size: 23px;
        }

        @media (max-width: 992px) {
            .navbar-custom {
                flex-wrap: wrap;
                gap: 20px;
            }

            .search-box {
                margin-left: 0;
            }

            .search-box input {
                width: 100%;
            }

            .hero h1 {
                font-size: 45px;
            }

            .hero p {
                font-size: 22px;
            }

            .hero-card {
                height: 350px;
            }
        }
    </style>
</head>

<body>

@php
    $user = Auth::user();
    $userName = $user->name ?? 'Guest';
@endphp

<nav class="navbar-custom d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center flex-wrap">
        <div class="d-flex align-items-center gap-3">
            <div class="logo-box">C</div>
            <div class="brand-text">Certly</div>
        </div>

        <a href="#" class="nav-link-custom">
            Explore Courses <i class="bi bi-chevron-down"></i>
        </a>

        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search courses...">
        </div>

        <a href="#" class="nav-link-custom">About</a>
        <a href="#" class="nav-link-custom">Partners</a>
    </div>

    <div>
        @auth
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="login-btn">Login</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="login-btn">Login</a>
        @endauth
    </div>
</nav>

<section class="hero">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <div class="hero-line"></div>

            <h1>Build Your Skills. Define Your Future.</h1>

            <p>
                Start your journey today with industry-recognized certification
                courses in Tech, Business, and more. Free to join.
            </p>

            <div class="d-flex gap-4 flex-wrap">
                <a href="{{ route('register') }}" class="btn-accent">Get Started</a>
                <a href="#" class="btn-primary-custom">Explore Subjects</a>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="hero-card">
                <div class="d-flex gap-4">
                    <div class="circle-outer"><div class="circle-inner"></div></div>
                    <div class="circle-outer"><div class="circle-inner"></div></div>
                    <div class="circle-outer"><div class="circle-inner"></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats">
    <div class="container-fluid px-5">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="stat-number">5,000+</div>
                <div class="stat-label">Courses Offered</div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="stat-number">100+</div>
                <div class="stat-label">Partnerships</div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="stat-number">20,000+</div>
                <div class="stat-label">Certified Graduates</div>
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 mb-5">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="logo-box">C</div>
                    <div class="brand-text text-white">Certly</div>
                </div>
                <p>Building skills for the future</p>
            </div>

            <div class="col-md-3 mb-5">
                <h4>Company</h4>
                <ul class="list-unstyled">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Partners</a></li>
                </ul>
            </div>

            <div class="col-md-3 mb-5">
                <h4>Support</h4>
                <ul class="list-unstyled">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>

            <div class="col-md-3 mb-5">
                <h4>Legal</h4>
                <ul class="list-unstyled">
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Cookie Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            © 2026 Certly. All rights reserved.
        </div>
    </div>
</footer>

</body>
</html>