<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certly - Approvals Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght=400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            font-size: 16px;
        }
        /* Sidebar layout pattern mula sa Dashboard mo */
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #002855; /* Certly Dark Navy Blue */
            color: white;
            padding-top: 35px;
            z-index: 100;
        }
        .sidebar .brand {
            padding: 10px 30px;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar .brand span.logo-box {
            background-color: #ffca28; /* Certly Yellow Accent */
            color: #002855;
            padding: 4px 16px;
            border-radius: 10px;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin-top: 40px;
        }
        .sidebar-menu li a {
            display: block;
            padding: 16px 30px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 17px;
            transition: all 0.3s;
        }
        .sidebar-menu li a.active {
            background-color: #ffca28;
            color: #002855;
            font-weight: 600;
            border-radius: 0 50px 50px 0;
            margin-right: 20px;
        }
        .logout-btn {
            position: absolute;
            bottom: 40px;
            left: 30px;
            background: none;
            border: none;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            font-size: 17px;
        }
        .logout-btn:hover {
            color: #ff4d4d;
        }
        /* Main Workspace Content Area mula sa Dashboard mo */
        .main-content {
            margin-left: 280px;
            padding: 50px;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
        .card-header {
            border-bottom: none;
            font-weight: 700;
            font-size: 22px;
            color: #002855;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <span class="logo-box">C</span> Certly
        </div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('admin.dashboard') }}">⊞ Dashboard</a></li>
            <li><a href="{{ route('admin.approvals') }}" class="active">✓ Approvals Hub</a></li>
            <li><a href="{{ route('admin.users') }}">🗎 User Management</a></li>
            <li><a href="{{ route('admin.facilitators') }}">⚙ Facilitator Management</a></li>
            <li><a href="#">🛠 Settings</a></li>
        </ul>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <span>↳</span> Log Out
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="mb-5">
            <h1 class="fw-bold m-0" style="color: #002855; font-size: 36px;">Approvals Hub</h1>
        </div>

        <div class="card mb-5 p-2">
            <div class="card-header bg-white pt-4 px-4 pb-2">
                Pending Course Approvals
            </div>
            <div class="card-body px-4 pb-4">
                <div class="table-responsive">
                    <table class="table align-middle fs-6">
                        <thead class="text-muted small text-uppercase">
                            <tr class="border-bottom">
                                <th class="pb-3">Course Title</th>
                                <th class="pb-3">Facilitator</th>
                                <th class="pb-3">Submission Date</th>
                                <th class="pb-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingCourses as $course)
                                <tr>
                                    <td class="py-3 text-dark fw-medium">{{ $course->title }}</td>
                                    <td class="text-muted">{{ $course->user->name }}</td>
                                    <td class="text-muted">{{ $course->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm fw-bold px-3 text-dark" style="background-color: #ffca28; border: none;">Review</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5 fs-5">
                                        No pending course approvals at the moment.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>