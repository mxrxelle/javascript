<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certly - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            font-size: 16px;
        }
        /* Sidebar layout pattern mula sa Figma */
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
        .sidebar-menu li a:hover:not(.active):not(.disabled-link) {
            background-color: rgba(255,255,255,0.08);
            color: white;
        }
        .sidebar-menu li a.active {
            background-color: #ffca28;
            color: #002855;
            font-weight: 600;
            border-radius: 0 50px 50px 0;
            margin-right: 20px;
        }
        .sidebar-menu li a.disabled-link {
            opacity: 0.45;
            cursor: not-allowed;
            pointer-events: none;
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
        /* Main Workspace Content Area */
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
        /* Custom UI Metric Cards */
        .bg-navy-card {
            background-color: #1a365d;
            color: white;
        }
        .bg-yellow-card {
            background-color: #ffca28;
            color: #002855;
        }
        .metric-label {
            font-size: 16px;
            opacity: 0.85;
        }
        .metric-value {
            font-size: 3.5rem;
            font-weight: 700;
        }
        /* Platform Status List Styling */
        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 24px;
            background-color: #fff;
            font-size: 16px;
        }
        /* Input fields scale size adjustment */
        .form-control-lg-custom {
            padding: 14px 20px;
            font-size: 16px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/certly-logo.png') }}" alt="Certly Logo" style="width: 42px; height: 42px; object-fit: contain; border-radius: 8px;"> Certly
        </div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">⊞ Dashboard</a></li>
            <li><a href="{{ route('admin.approvals') }}" class="{{ request()->routeIs('admin.approvals') ? 'active' : '' }}">✓ Approvals Hub</a></li>
            <li><a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">🗎 User Management</a></li>
            <li><a href="{{ route('admin.facilitators') }}" class="{{ request()->routeIs('admin.facilitators') ? 'active' : '' }}">⚙ Facilitator Management</a></li>
            <li><a href="#" class="disabled-link">🛠 Settings</a></li>
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
            <h1 class="fw-bold m-0" style="color: #002855; font-size: 36px;">Admin Dashboard</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show p-3 fs-6 mb-4 shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                                    <td class="text-muted">{{ $course->user->name ?? 'Unknown Facilitator' }}</td>
                                    <td class="text-muted">{{ $course->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.approvals') }}" class="btn btn-warning btn-sm fw-bold px-3 text-dark" style="background-color: #ffca28; border: none; border-radius: 6px;">
                                            Review
                                        </a>
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

        <h4 class="fw-bold mb-3" style="color: #002855;">Certly Activity</h4>
        <div class="row mb-5">
            <div class="col-md-6 mb-4">
                <div class="card bg-navy-card p-4 shadow-sm">
                    <div class="metric-label">Total Users</div>
                    <div class="metric-value my-2">{{ $teachers->count() + $students->count() }}</div>
                    <div class="small opacity-50 fs-6">Registered on platform</div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card bg-yellow-card p-4 shadow-sm">
                    <div class="metric-label">Active Facilitators</div>
                    <div class="metric-value my-2">{{ $teachers->count() }}</div>
                    <div class="small opacity-50 fs-6">Creating and managing courses</div>
                </div>
            </div>
        </div>

        <div class="card p-4 mb-5 shadow-sm bg-white">
            <h4 class="fw-bold mb-4 px-3" style="color: #002855;">Platform Status</h4>
            <div class="status-item border-bottom">
                <span class="text-secondary fs-5">System Status</span>
                <span class="badge bg-success-subtle text-success px-4 py-2 rounded-pill fw-semibold fs-6">Online</span>
            </div>
            <div class="status-item border-bottom">
                <span class="text-secondary fs-5">Active Accounts (Teachers)</span>
                <span class="fw-bold text-dark fs-5">{{ $teachers->count() }}</span>
            </div>
            <div class="status-item">
                <span class="text-secondary fs-5">Registered Students</span>
                <span class="fw-bold text-dark fs-5">{{ $students->count() }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm p-4">
                    <div class="card-header bg-white pt-2 px-0">
                        Facilitator Management
                    </div>
                    <div class="card-body px-0">
                        <p class="text-muted small mb-4 fs-6">Invite new facilitators to create courses on the platform</p>
                        
                        <form action="{{ route('admin.storeTeacher') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <input type="email" name="email" class="form-control bg-light border-0 form-control-lg-custom @error('email') is-invalid @enderror" placeholder="Enter facilitator email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback small fs-6 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-warning w-100 fw-bold text-dark py-3 fs-5 mb-4" style="background-color: #ffca28; border: none; border-radius: 10px;">
                                Send Invitation
                            </button>
                        </form>

                        <div class="pt-3 border-top">
                            <span class="text-muted d-block mb-3 fw-medium fs-5">Recent Invitations</span>
                            <div class="d-flex flex-column gap-3">
                                
                                @forelse($recentInvitations as $invitation)
                                    <div class="d-flex justify-content-between align-items-center fs-6">
                                        <span class="text-secondary fw-medium">{{ $invitation->email }}</span>
                                        <span class="text-success fw-bold">Active</span>
                                    </div>
                                @empty
                                    <div class="text-muted text-center py-2 fs-6">No invitations sent yet.</div>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm p-4">
                    <div class="card-header bg-white pt-2 px-0">
                        Platform News
                    </div>
                    <div class="card-body px-0">
                        
                        <div class="text-center py-4 text-muted border rounded bg-light mb-4" style="border-radius: 12px;">
                            <span class="fs-5 d-block mb-1 fw-semibold text-dark">📢 No Announcements Today</span>
                            <p class="small mb-0 opacity-75 fs-6">System is running completely normal.</p>
                        </div>

                        <div class="border-top pt-4">
                            <h5 class="fw-bold mb-3" style="color: #002855;">Support Requests</h5>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: #f1f5f9; border-radius: 10px;">
                                <span class="text-secondary fw-medium fs-5 ps-2">Open Help Tickets</span>
                                <span class="badge bg-secondary rounded-circle px-3 py-2 fs-6">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>