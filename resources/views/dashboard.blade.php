<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">SK360° PORTAL</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text text-white me-3">
                Hello, <strong>{{ Auth::user()->name }}</strong>
            </span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-light btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D6EFD&color=fff" 
                             class="rounded-circle" alt="Profile">
                    </div>
                    <h5>{{ Auth::user()->name }}</h5>
                    <p class="text-muted small">{{ Auth::user()->email }}</p>
                    <hr>
                    <p class="badge bg-primary">Student Account</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Account Overview</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <strong>Login Success!</strong> Your authentication logic is working perfectly.
                    </div>
                    
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" style="width: 30%;">Full Name:</td>
                            <td><strong>{{ Auth::user()->name }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email Address:</td>
                            <td>{{ Auth::user()->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">User Role:</td>
                            <td><span class="badge border text-primary border-primary">Student</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4 p-3 bg-white border rounded shadow-sm">
                <h6>Next Phase:</h6>
                <p class="small text-muted mb-0">Ang susunod nating gagawin dito ay ang **Module System** at **Voucher Registration**.</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>