<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - SK360</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .stat-card { border-left: 5px solid #0d6efd; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">SK360 Staff Portal</a>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-light btn-sm">Logout</button>
        </form>
    </div>
</nav>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>Welcome, {{ Auth::user()->name }}!</h3>
            <p class="text-muted">Manage your barangay youth members here.</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm p-3">
                <h6 class="text-muted">Total Registered Youth</h6>
                <h2 class="fw-bold">{{ $students->count() }}</h2>
            </div>
        </div>
    </div>

    <!-- Student Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">Youth Members List</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Affiliation</th>
                        <th>Registered Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->affiliation }}</td>
                        <td>{{ $student->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No students registered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>