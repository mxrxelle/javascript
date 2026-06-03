<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certly Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f4f6f8;
            color: #00336b;
        }

        .navbar {
            height: 110px;
            background: #00336b;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 38px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.25);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-right: 70px;
        }

        .logo-box {
            width: 58px;
            height: 58px;
            background: #ffc32b;
            border-radius: 15px;
            color: #00336b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 31px;
            font-weight: 500;
        }

        .logo span {
            font-size: 34px;
            font-weight: 700;
        }

        .search-box {
            width: 365px;
            height: 60px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 16px;
            display: flex;
            align-items: center;
            padding: 0 18px;
            margin-right: 45px;
        }

        .search-box i {
            color: rgba(255,255,255,0.65);
            font-size: 20px;
            margin-right: 15px;
        }

        .search-box input {
            background: transparent;
            border: none;
            outline: none;
            color: white;
            width: 100%;
            font-size: 22px;
        }

        .search-box input::placeholder {
            color: rgba(255,255,255,0.65);
        }

        .nav-links {
            display: flex;
            gap: 45px;
            font-size: 24px;
            font-weight: 500;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
        }

        .nav-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .profile-btn {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .logout {
            color: white;
            text-decoration: none;
            font-size: 25px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .logout:hover {
            opacity: 0.85;
        }

        .container {
            padding: 48px 36px 70px;
        }

        .layout {
            display: grid;
            grid-template-columns: 1fr 455px;
            gap: 45px;
        }

        h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 40px;
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 14px rgba(0,0,0,0.16);
        }

        .activate-card {
            padding: 40px 34px 34px;
            margin-bottom: 52px;
        }

        .activate-card h2 {
            font-size: 34px;
            margin-bottom: 28px;
        }

        .activate-card p {
            font-size: 23px;
            color: #555;
            margin-bottom: 25px;
        }

        .activate-form {
            display: flex;
            gap: 24px;
        }

        .activate-form input {
            flex: 1;
            height: 70px;
            border: 1px solid #d5d9de;
            border-radius: 14px;
            background: #f8fafc;
            padding: 0 25px;
            font-size: 23px;
            outline: none;
        }

        .activate-form button {
            width: 175px;
            height: 70px;
            border: none;
            border-radius: 14px;
            background: #ffc32b;
            color: #00336b;
            font-size: 22px;
            font-weight: 800;
            cursor: pointer;
        }

        .section-title {
            font-size: 34px;
            font-weight: 800;
            margin-bottom: 34px;
        }

        .courses {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 34px;
        }

        .course-card {
            overflow: hidden;
        }

        .course-img {
            height: 182px;
            background: linear-gradient(135deg, #00336b, #55779c);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
        }

        .course-body {
            padding: 36px 32px;
        }

        .course-body h3 {
            font-size: 29px;
            margin-bottom: 16px;
        }

        .course-body p {
            font-size: 20px;
            color: #555;
            line-height: 1.45;
            margin-bottom: 28px;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .progress-info span:last-child {
            color: #00336b;
            font-weight: 700;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: #f1f4f7;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .progress-fill {
            height: 100%;
            background: #ffc32b;
        }

        .resume-btn {
            display: block;
            text-align: center;
            background: #00336b;
            color: white;
            text-decoration: none;
            padding: 17px;
            border-radius: 12px;
            font-size: 22px;
            font-weight: 800;
        }

        .sidebar {
            padding-top: 48px;
        }

        .side-card {
            padding: 34px;
            margin-bottom: 34px;
        }

        .side-card h3 {
            font-size: 27px;
            margin-bottom: 28px;
        }

        .achievement {
            display: flex;
            align-items: center;
            gap: 24px;
            background: #f7f9fb;
            border-radius: 15px;
            padding: 22px;
        }

        .achievement-icon {
            width: 68px;
            height: 68px;
            background: #ffc32b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 31px;
        }

        .achievement h4 {
            font-size: 22px;
            margin-bottom: 7px;
        }

        .achievement p,
        .announcement p,
        .announcement small {
            color: #555;
        }

        .announcement-title {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .announcement {
            padding-bottom: 22px;
            margin-bottom: 24px;
            border-bottom: 1px solid #ddd;
        }

        .announcement:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .announcement h4 {
            font-size: 20px;
            margin-bottom: 9px;
        }

        .announcement p {
            font-size: 18px;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .announcement small {
            font-size: 15px;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 24, 51, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
            z-index: 1000;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .logout-modal {
            width: min(100%, 520px);
            background: white;
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(0,0,0,0.2);
            padding: 36px 34px 30px;
            text-align: center;
            border-top: 8px solid #ffc32b;
        }

        .logout-modal-icon {
            width: 84px;
            height: 84px;
            margin: 0 auto 22px;
            border-radius: 50%;
            background: #00336b;
            color: #ffc32b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
        }

        .logout-modal h2 {
            font-size: 34px;
            margin-bottom: 14px;
        }

        .logout-modal p {
            font-size: 20px;
            color: #555;
            line-height: 1.5;
            margin-bottom: 28px;
        }

        .modal-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
        }

        .modal-btn {
            min-width: 150px;
            height: 58px;
            border-radius: 14px;
            font-size: 20px;
            font-weight: 800;
            cursor: pointer;
            border: none;
        }

        .modal-btn.cancel {
            background: #eef2f6;
            color: #00336b;
        }

        .modal-btn.confirm {
            background: #ffc32b;
            color: #00336b;
        }

        @media (max-width: 1100px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                padding-top: 0;
            }

            .courses {
                grid-template-columns: 1fr;
            }

            .navbar {
                height: auto;
                flex-wrap: wrap;
                gap: 20px;
                padding: 25px;
            }

            .search-box {
                width: 100%;
                order: 3;
            }
        }

        @media (max-width: 640px) {
            .logout-modal {
                padding: 28px 22px 24px;
            }

            .logout-modal h2 {
                font-size: 28px;
            }

            .logout-modal p {
                font-size: 18px;
            }

            .modal-actions {
                flex-direction: column;
            }

            .modal-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

@php
    $user = Auth::user();
    $userName = $user->name ?? 'Student';
@endphp

<nav class="navbar">
    <div class="logo">
        <div class="logo-box">C</div>
        <span>Certly</span>
    </div>

    <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Search courses...">
    </div>

    <div class="nav-links">
        <a href="#">My Courses</a>
        <a href="#">Catalog</a>
    </div>

    <div class="nav-right">
        <div class="profile-btn">
            <i class="fa-regular fa-user"></i>
        </div>

        <button type="button" class="logout" id="openLogoutModal">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            Log Out
        </button>
    </div>
</nav>

<main class="container">
    <div class="layout">
        <div>
            <h1>Welcome back, {{ $userName }}!</h1>

            @if (session('success'))
                <div style="background:#d1e7dd;color:#0f5132;padding:18px 22px;border-radius:14px;margin-bottom:25px;font-size:20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div style="background:#f8d7da;color:#842029;padding:18px 22px;border-radius:14px;margin-bottom:25px;font-size:20px;">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background:#f8d7da;color:#842029;padding:18px 22px;border-radius:14px;margin-bottom:25px;font-size:20px;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <section class="card activate-card">
                <h2>Activate a Course</h2>
                <p>Enter your voucher code to unlock a new course</p>

                <form class="activate-form" action="{{ route('student.activateCourse') }}" method="POST">
                    @csrf
                    <input type="text" name="code" placeholder="Enter Voucher Code" required>
                    <button type="submit">Activate</button>
                </form>
            </section>

            <h2 class="section-title">My Learning</h2>

            <section class="courses">
                @if ($studentCourses->isEmpty())
                    <div class="card course-card" style="grid-column: 1 / -1; padding: 40px; text-align:center;">
                        <h3>No activated courses yet</h3>
                        <p style="font-size:20px;color:#555;margin-top:12px;">
                            Enter a valid voucher code above to unlock your first course.
                        </p>
                    </div>
                @else
                    @foreach ($studentCourses as $studentCourse)
                        <div class="card course-card">
                            <div class="course-img">
                                {{ $studentCourse->course->thumbnail ?? '📘' }}
                            </div>

                            <div class="course-body">
                                <h3>{{ $studentCourse->course->title }}</h3>
                                <p>{{ $studentCourse->course->description ?? 'No description available.' }}</p>

                                <div class="progress-info">
                                    <span>Progress</span>
                                    <span>{{ $studentCourse->progress }}%</span>
                                </div>

                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $studentCourse->progress }}%;"></div>
                                </div>

                                <a href="{{ route('student.courseviewer') }}" class="resume-btn">
                                    Resume Learning
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </section>
        </div>

        <aside class="sidebar">
            <div class="card side-card">
                <h3>Latest Achievements</h3>

                <div class="achievement">
                    <div class="achievement-icon">
                        <i class="fa-solid fa-award"></i>
                    </div>

                    <div>
                        <h4>Get Certified</h4>
                        <p>Complete a course</p>
                    </div>
                </div>
            </div>

            <div class="card side-card">
                <h3 class="announcement-title">
                    <i class="fa-regular fa-bell"></i>
                    Announcements
                </h3>

                <div class="announcement">
                    <h4>New Courses Available</h4>
                    <p>Check out our latest cybersecurity courses</p>
                    <small>2 days ago</small>
                </div>

                <div class="announcement">
                    <h4>Platform Update</h4>
                    <p>New features and improvements are live</p>
                    <small>1 week ago</small>
                </div>
            </div>
        </aside>
    </div>
</main>

<div class="modal-overlay" id="logoutModal" aria-hidden="true">
    <div class="logout-modal card" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle">
        <div class="logout-modal-icon">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </div>

        <h2 id="logoutModalTitle">Log out of Certly?</h2>
        <p>Your current session will end and you will be returned to the login page.</p>

        <div class="modal-actions">
            <button type="button" class="modal-btn cancel" id="closeLogoutModal">Stay Logged In</button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="modal-btn confirm">Yes, Log Out</button>
            </form>
        </div>
    </div>
</div>

<script>
    const openLogoutModalButton = document.getElementById('openLogoutModal');
    const closeLogoutModalButton = document.getElementById('closeLogoutModal');
    const logoutModal = document.getElementById('logoutModal');

    const openLogoutModal = () => {
        logoutModal.classList.add('active');
        logoutModal.setAttribute('aria-hidden', 'false');
    };

    const closeLogoutModal = () => {
        logoutModal.classList.remove('active');
        logoutModal.setAttribute('aria-hidden', 'true');
    };

    openLogoutModalButton.addEventListener('click', openLogoutModal);
    closeLogoutModalButton.addEventListener('click', closeLogoutModal);

    logoutModal.addEventListener('click', (event) => {
        if (event.target === logoutModal) {
            closeLogoutModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && logoutModal.classList.contains('active')) {
            closeLogoutModal();
        }
    });
</script>

</body>
</html>