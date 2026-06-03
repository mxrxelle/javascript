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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0" role="alert" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-4 border-0 text-dark" role="alert" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background-color: #fff3cd;">
                {{ session('warning') }}
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
                                        <button 
                                            class="btn btn-warning btn-sm fw-bold px-3 text-dark" 
                                            style="background-color: #ffca28; border: none;"
                                            onclick="openReview({{ json_encode($course->load('modules.lessons')) }})"
                                        >
                                            Review
                                        </button>
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

    <!-- Review Course Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header" style="border-bottom: none; padding-top: 30px; padding-left: 30px;">
                    <h5 class="modal-title fw-bold" id="reviewModalLabel" style="color: #002855; font-size: 24px;">Review Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 30px;">
                    <h4 id="modalCourseTitle" class="fw-bold mb-2" style="color: #00336b;">Course Title</h4>
                    <p id="modalCourseDesc" class="text-muted mb-4" style="font-size: 15px; line-height: 1.5;">Course description.</p>

                    <h6 class="fw-bold mb-2" style="color: #002855;">Course Structure & Outline:</h6>
                    <div id="modalCourseStructure" class="mb-4 p-3 bg-light rounded" style="max-height: 250px; overflow-y: auto; border: 1px solid #e2e8f0;">
                        <!-- Dynamically populated modules/lessons -->
                    </div>

                    <!-- Actions forms -->
                    <form id="approveForm" method="POST" action="">
                        @csrf
                    </form>

                    <form id="rejectForm" method="POST" action="">
                        @csrf
                        <div class="mb-3">
                            <label for="feedbackText" class="form-label fw-bold" style="color: #002855; font-size: 15px;">Feedback (Required for returns/rejections)</label>
                            <textarea class="form-control" id="feedbackText" name="feedback" rows="3" placeholder="Explain what the facilitator needs to change, correct, or add..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: none; padding-bottom: 30px; padding-right: 30px; gap: 12px;">
                    <button type="button" class="btn btn-secondary fw-bold px-4" data-bs-dismiss="modal" style="border-radius: 10px; padding: 10px 20px;">Cancel</button>
                    <button type="button" onclick="submitReturn()" class="btn btn-outline-danger fw-bold px-4" style="border-radius: 10px; padding: 10px 20px;">Return to Facilitator</button>
                    <button type="button" onclick="submitApprove()" class="btn btn-success fw-bold px-4" style="border-radius: 10px; background-color: #28a745; border: none; padding: 10px 24px;">Approve & Publish</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentModal = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            currentModal = new bootstrap.Modal(document.getElementById('reviewModal'));
        });

        const modalCourseTitle = document.getElementById('modalCourseTitle');
        const modalCourseDesc = document.getElementById('modalCourseDesc');
        const modalCourseStructure = document.getElementById('modalCourseStructure');
        const approveForm = document.getElementById('approveForm');
        const rejectForm = document.getElementById('rejectForm');

        function openReview(course) {
            modalCourseTitle.textContent = course.title;
            modalCourseDesc.textContent = course.description || 'No description provided.';
            
            // Set form action URLs
            approveForm.action = `/admin/courses/${course.id}/approve`;
            rejectForm.action = `/admin/courses/${course.id}/reject`;
            
            // Build structure HTML outline
            let html = '';
            if (course.modules && course.modules.length > 0) {
                course.modules.forEach(mod => {
                    html += `
                    <div class="mb-3 border-bottom pb-2">
                        <div class="fw-bold text-dark" style="font-size: 16px; color: #002855;">
                            Module ${mod.sort_order}: ${mod.title}
                        </div>`;
                    
                    if (mod.lessons && mod.lessons.length > 0) {
                        html += `<ul class="list-unstyled ms-3 mt-2">`;
                        mod.lessons.forEach(les => {
                            let icon = '🗎';
                            let typeLabel = les.type.charAt(0).toUpperCase() + les.type.slice(1);
                            
                            if (les.type === 'video') icon = '▷ Video';
                            else if (les.type === 'presentation') icon = '▣ Slide Deck';
                            else if (les.type === 'quiz') icon = '❓ Quiz';
                            else if (les.type === 'reading') icon = '📖 Reading';

                            html += `
                            <li class="text-muted small py-1 d-flex justify-content-between align-items-center">
                                <span>${icon}: ${les.title}</span>`;
                            
                            if (les.type === 'presentation' && les.presentation_path) {
                                html += `<span class="badge bg-secondary text-white ms-2" style="font-size: 11px;">${les.presentation_size || 'PPT/PDF'}</span>`;
                            }
                            if (les.type === 'quiz') {
                                html += `<span class="badge bg-info text-dark ms-2" style="font-size: 11px; background-color: #e0f2fe !important;">${les.quiz_questions_count} Pool Qs</span>`;
                            }
                            html += `</li>`;
                        });
                        html += `</ul>`;
                    } else {
                        html += `<div class="text-muted small ms-3 mt-1 italic">No lessons or subtopics inside this module.</div>`;
                    }
                    html += `</div>`;
                });
            } else {
                html = '<div class="text-muted py-2 text-center">No modules or content added yet.</div>';
            }
            
            modalCourseStructure.innerHTML = html;
            document.getElementById('feedbackText').value = '';
            currentModal.show();
        }

        function submitApprove() {
            approveForm.submit();
        }

        function submitReturn() {
            const feedback = document.getElementById('feedbackText').value.trim();
            if (!feedback) {
                alert('Please provide feedback explaining why the course is being returned to the facilitator.');
                return;
            }
            rejectForm.submit();
        }
    </script>
</body>
</html>