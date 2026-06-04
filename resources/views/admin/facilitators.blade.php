<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certly - Facilitator Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght=400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; font-size: 16px; }
        .sidebar { width: 280px; height: 100vh; position: fixed; top: 0; left: 0; background-color: #002855; color: white; padding-top: 35px; z-index: 100; }
        .sidebar .brand { padding: 10px 30px; font-size: 28px; font-weight: 700; display: flex; align-items: center; gap: 12px; }
        .sidebar .brand span.logo-box { background-color: #ffca28; color: #002855; padding: 4px 16px; border-radius: 10px; }
        .sidebar-menu { list-style: none; padding: 0; margin-top: 40px; }
        .sidebar-menu li a { display: block; padding: 16px 30px; color: #cbd5e1; text-decoration: none; font-size: 17px; transition: all 0.3s; }
        .sidebar-menu li a:hover:not(.active):not(.disabled-link) { background-color: rgba(255,255,255,0.08); color: white; }
        .sidebar-menu li a.active { background-color: #ffca28; color: #002855; font-weight: 600; border-radius: 0 50px 50px 0; margin-right: 20px; }
        .sidebar-menu li a.disabled-link { opacity: 0.45; cursor: not-allowed; pointer-events: none; }
        .logout-btn { position: absolute; bottom: 40px; left: 30px; background: none; border: none; color: #cbd5e1; display: flex; align-items: center; gap: 12px; text-decoration: none; font-size: 17px; }
        .logout-btn:hover { color: #ff4d4d; }
        .main-content { margin-left: 280px; padding: 50px; }
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .card-header { border-bottom: none; font-weight: 700; font-size: 22px; color: #002855; }
        
        .bg-navy-card { background-color: #1a365d; color: white; }
        .metric-mini-label { font-size: 14px; opacity: 0.85; font-weight: 500; }
        .metric-mini-value { font-size: 2rem; font-weight: 700; }
        
        .form-select-custom, .form-control-custom {
            padding: 10px 16px;
            font-size: 15px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <span class="logo-box">C</span> Certly
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
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 mb-4 p-3 shadow-sm" role="alert" style="border-radius: 12px;">
                🎉 {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 mb-4 p-3 shadow-sm" role="alert" style="border-radius: 12px;">
                ⚠️ {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 mb-4 p-3 shadow-sm" role="alert" style="border-radius: 12px;">
                ⚠️ {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold m-0" style="color: #002855; font-size: 36px;">Facilitator Management</h1>
                <p class="text-muted mt-2 fs-6">Monitor active platform instructors and deploy new facilitator invites.</p>
            </div>
            <button type="button" class="btn fw-semibold px-4 py-2" data-bs-toggle="modal" data-bs-target="#inviteFacilitatorModal" style="background-color: #ffca28; color: #002855; border-radius: 12px; height: fit-content;">
                + Invite Facilitator
            </button>
        </div>

        <div class="row mb-4">
            <div class="col-12 col-md-4 mb-3">
                <div class="card bg-navy-card p-3 shadow-sm h-100 d-flex flex-column justify-content-center" style="min-height: 110px;">
                    <div class="metric-mini-label text-truncate">Active Facilitators</div>
                    <div class="metric-mini-value mt-1 fw-bold">{{ $totalFacilitators }}</div>
                </div>
            </div>
        </div>

        <div class="card p-2 mb-5">
            <form action="{{ route('admin.facilitators') }}" method="GET" class="card-header bg-white pt-4 px-4 pb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <span class="fs-4">Facilitators Directory</span>
                <div class="d-flex flex-wrap gap-2">
                    <input type="text" name="search" class="form-control form-control-custom" placeholder="Search instructor..." style="width: 260px;" value="{{ request('search') }}">
                    @if(request('search'))
                        <a href="{{ route('admin.facilitators') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" style="border-radius: 10px; padding: 0 15px;">✕</a>
                    @endif
                    <button type="submit" class="d-none"></button>
                </div>
            </form>

            <div class="card-body px-4 pb-3">
                <div class="table-responsive">
                    <table class="table align-middle fs-6">
                        <thead class="text-muted small text-uppercase">
                            <tr class="border-bottom">
                                <th class="pb-3">Name</th>
                                <th class="pb-3">Email Address</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Courses</th>
                                <th class="pb-3">Date Added</th>
                                <th class="pb-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($facilitators as $facilitator)
                                <tr class="border-bottom">
                                    <td class="fw-semibold py-3 text-dark fs-5">{{ $facilitator->name }}</td>
                                    <td class="text-secondary">{{ $facilitator->email }}</td>
                                    <td>
                                        <span class="badge {{ $facilitator->status_class }} px-3 py-2 rounded-pill fw-semibold">
                                            {{ $facilitator->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1 rounded fw-medium">
                                            📚 {{ $facilitator->courses_count }} Courses
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $facilitator->created_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        @if($facilitator->status_label == 'Pending')
                                            <form action="{{ route('admin.facilitators.resend', $facilitator->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to generate a new temporary random password and resend it to this facilitator?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning border-0 me-1" title="Resend New Password Kit" style="font-size: 16px;">🔄</button>
                                            </form>
                                        @endif

                                        <button type="button" class="btn btn-sm btn-outline-primary border-0 me-1 edit-facilitator-btn" 
                                                data-id="{{ $facilitator->id }}" 
                                                data-name="{{ $facilitator->name }}" 
                                                data-email="{{ $facilitator->email }}"
                                                title="Edit Account" style="font-size: 16px;">
                                            ✏️
                                        </button>
                                        
                                        <form action="{{ route('admin.users.delete', $facilitator->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to revoke system access for this facilitator?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Remove Facilitator" style="font-size: 16px;">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5 fs-5">
                                        No facilitators found matching the search context.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 px-2">
                    <div class="text-muted small">
                        Showing {{ $facilitators->firstItem() ?? 0 }} to {{ $facilitators->lastItem() ?? 0 }} of {{ $facilitators->total() }} entries
                    </div>
                    <div>
                        {{ $facilitators->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="inviteFacilitatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="color: #002855; font-size: 22px;">Invite New Facilitator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.storeTeacher') }}">
                    @csrf
                    <div class="modal-body px-4 pb-4">
                        <p class="text-muted small mb-3">An automation script will deploy a random 10-character alphanumeric security credential configuration layout to the assigned receiver email address.</p>
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-semibold">Facilitator Email Address</label>
                            <input type="email" name="email" class="form-control form-control-custom" placeholder="name@example.com" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                        <button type="button" class="btn btn-light text-secondary fw-semibold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">Cancel</button>
                        <button type="submit" class="btn fw-semibold px-4 py-2" style="background-color: #ffca28; color: #002855; border-radius: 10px;">Send Invite Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editFacilitatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="color: #002855; font-size: 22px;">Edit Facilitator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editFacilitatorForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="role" value="teacher">
                    <div class="modal-body px-4 pb-4">
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-semibold">Full Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control form-control-custom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-semibold">Email Address</label>
                            <input type="email" name="email" id="edit_email" class="form-control form-control-custom" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                        <button type="button" class="btn btn-light text-secondary fw-semibold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">Cancel</button>
                        <button type="submit" class="btn fw-semibold px-4 py-2" style="background-color: #ffca28; color: #002855; border-radius: 10px;">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.edit-facilitator-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const email = this.getAttribute('data-email');

                document.getElementById('editFacilitatorForm').action = `/admin/users/${id}`;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_email').value = email;

                const editModal = new bootstrap.Modal(document.getElementById('editFacilitatorModal'));
                editModal.show();
            });
        });
    </script>
</body>
</html>