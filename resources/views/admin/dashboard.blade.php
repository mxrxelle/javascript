<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LMS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f6; }
        .navbar { background-color: #1e293b; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .table thead { background-color: #f8fafc; }
    </style>
</head>
<body>

    <!-- Simple Navbar -->
    <nav class="navbar navbar-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">LMS ADMIN PANEL</a>
            <div class="d-flex align-items-center text-white">
                <span class="me-3">Hello, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <!-- Left Side: Registration Form -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-white fw-bold py-3">Register New Teacher/Staff</div>
                    <div class="card-body">
                        
                        @if(session('success'))
                            <div class="alert alert-success py-2 small">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger py-2 small">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.storeTeacher') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Juan Dela Cruz" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="teacher@lms.com" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Temporary Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Create Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side: Teachers List -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                        <span>Staff Directory</span>
                        <span class="badge bg-secondary">{{ $teachers->count() }} Total</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="small text-uppercase">
                                    <tr>
                                        <th class="px-4 py-3">Name</th>
                                        <th class="py-3">Email</th>
                                        <th class="py-3">Role</th>
                                        <th class="py-3">Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($teachers as $teacher)
                                        <tr>
                                            <td class="px-4 py-3 fw-semibold">{{ $teacher->name }}</td>
                                            <td class="py-3">{{ $teacher->email }}</td>
                                            <td class="py-3">
                                                <span class="badge bg-info text-dark px-2 py-1">{{ ucfirst($teacher->role) }}</span>
                                            </td>
                                            <td class="py-3 text-muted small">{{ $teacher->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">No teachers found. Use the form to add one.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>