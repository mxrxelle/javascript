<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilitator Dashboard - Certly</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            background: #002855;
        }

        .sidebar-active {
            background: #ffca28;
            color: #002855 !important;
        }

        .sidebar a:not(.sidebar-active):hover {
            background: rgba(255,255,255,0.08);
            color: white !important;
        }

        .primary-btn {
            background: #002855;
        }

        .primary-btn:hover {
            opacity: .9;
        }

        .card-shadow {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* Dropdown custom style */
        .actions-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            z-index: 50;
            background: white;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
            width: 160px;
        }
        .actions-dropdown.show {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 sidebar text-white relative flex flex-col justify-between">

        <div class="p-6 flex-1">

            <!-- Logo -->
            <div class="flex items-center gap-3 mb-10">
                <img src="{{ asset('images/certly-logo.png') }}" alt="Certly Logo" class="w-10 h-10 object-contain rounded-lg bg-white p-0.5">

                <span class="text-2xl font-semibold">
                    Certly
                </span>
            </div>

            <!-- Navigation -->
            <nav class="space-y-3">

                <a href="{{ route('teacher.dashboard') }}" class="sidebar-active w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left no-underline block">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('teacher.courses.create') }}" class="text-gray-300 hover:bg-[#003b73] hover:text-white w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left no-underline block">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Create Course</span>
                </a>

                <a href="{{ route('teacher.submissions') }}" class="text-gray-300 hover:bg-[#003b73] hover:text-white w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left no-underline block">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span>My Submissions</span>
                </a>

                <a href="#students-section" onclick="scrollToSection('students-section')" class="text-gray-300 hover:bg-[#003b73] hover:text-white w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left no-underline block">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    <span>User Analytics</span>
                </a>

            </nav>

        </div>

        <!-- Logout Form & Button -->
        <div class="p-6">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-full text-gray-300 hover:bg-[#003b73] hover:text-white flex items-center gap-3 px-4 py-3 rounded-lg transition text-left">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span>Log Out</span>
            </button>
        </div>

    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">

        <div class="max-w-7xl mx-auto p-8">

            <!-- Alert messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 flex justify-between items-center card-shadow" role="alert">
                    <span class="font-medium">{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900 font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 flex justify-between items-center card-shadow" role="alert">
                    <span class="font-medium">{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900 font-bold">&times;</button>
                </div>
            @endif

            <!-- Heading -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-[#002855] mb-2">
                    Facilitator Dashboard
                </h1>
                <p class="text-gray-600 text-lg">
                    Welcome back, {{ Auth::user()->name }}! Manage your courses and student enrollments.
                </p>
            </div>

            @if(isset($returnedCourses) && $returnedCourses->count() > 0)
                @foreach($returnedCourses as $returnedCourse)
                    <div class="bg-red-50 border border-red-200 text-red-800 p-6 rounded-xl mb-6 flex gap-4 card-shadow" role="alert">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="alert-circle" class="w-6 h-6 text-red-600"></i>
                        </div>
                        <div class="flex-1 text-left">
                            <div class="text-lg font-bold text-red-800 mb-1">
                                Course Returned for Revision: "{{ $returnedCourse->title }}"
                            </div>
                            <div class="text-sm text-red-700 mb-3 leading-relaxed">
                                <strong>Admin Feedback:</strong> {{ $returnedCourse->admin_feedback ?? 'Please review your course content.' }}
                            </div>
                            <div>
                                <a href="{{ route('teacher.courses.edit', $returnedCourse->id) }}" class="inline-flex items-center gap-2 bg-[#002855] hover:opacity-90 text-white text-xs font-bold px-4 py-2 rounded-lg no-underline transition">
                                    Edit & Resubmit
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <!-- Create Course -->
                <a href="{{ route('teacher.courses.create') }}" class="bg-[#ffca28] p-6 rounded-xl card-shadow flex items-center gap-4 hover:opacity-95 transition no-underline block">
                    <div class="w-12 h-12 bg-[#002855] rounded-lg flex items-center justify-center">
                        <i data-lucide="plus" class="w-6 h-6 text-white"></i>
                    </div>

                    <div class="text-left">
                        <div class="text-xl font-bold text-[#002855] mb-1">
                            Create New Course
                        </div>

                        <div class="text-sm text-[#002855] opacity-90">
                            Design and submit a new course for approval
                        </div>
                    </div>
                </a>

                <!-- Draft Submissions -->
                <a href="#submissions-section" onclick="scrollToSection('submissions-section')" class="bg-white p-6 rounded-xl card-shadow flex items-center gap-4 hover:bg-gray-50 transition no-underline block">
                    <div class="w-12 h-12 bg-[#002855] rounded-lg flex items-center justify-center">
                        <i data-lucide="file-text" class="w-6 h-6 text-white"></i>
                    </div>

                    <div class="text-left">
                        <div class="text-xl font-bold text-[#002855] mb-1">
                            Draft Submissions
                        </div>

                        <div class="text-sm text-gray-500">
                            View and edit your draft courses
                        </div>
                    </div>
                </a>

            </div>

            <!-- Registered Students List -->
            <div id="students-section" class="bg-white rounded-xl card-shadow p-6 mb-8">

                <h2 class="text-2xl font-semibold text-[#002855] mb-6 flex items-center gap-2">
                    <i data-lucide="users" class="w-6 h-6 text-[#002855]"></i>
                    Registered Students
                </h2>

                <div class="overflow-x-auto">

                    <table class="w-full text-left border-collapse">

                        <thead>
                        <tr class="border-b border-gray-150">

                            <th class="py-3 px-4 text-[#002855] font-bold">
                                Name
                            </th>

                            <th class="py-3 px-4 text-[#002855] font-bold">
                                Email
                            </th>

                            <th class="py-3 px-4 text-[#002855] font-bold">
                                Affiliation
                            </th>

                            <th class="py-3 px-4 text-[#002855] font-bold">
                                Registered Date
                            </th>

                        </tr>
                        </thead>

                        <tbody>

                        @forelse($students as $student)
                        @php
                            $badgeClass = match($student->affiliation) {
                                'NU Lipa' => 'bg-blue-100 text-[#002855]',
                                'NU' => 'bg-[#e0f2fe] text-[#0369a1]',
                                'Student' => 'bg-[#cbd5e1] text-[#1e293b]',
                                default => 'bg-gray-200 text-gray-700'
                            };
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

                            <td class="py-4 px-4 font-semibold text-gray-800">
                                {{ $student->name }}
                            </td>

                            <td class="py-4 px-4 text-gray-600">
                                {{ $student->email }}
                            </td>

                            <td class="py-4 px-4">
                                <span class="{{ $badgeClass }} px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $student->affiliation ?? 'Student' }}
                                </span>
                            </td>

                            <td class="py-4 px-4 text-gray-500">
                                {{ $student->created_at ? $student->created_at->format('Y-m-d') : 'N/A' }}
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500">
                                No students registered yet.
                            </td>
                        </tr>
                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- Approved Courses -->
            <div class="bg-white rounded-xl card-shadow p-6 mb-8">

                <h2 class="text-2xl font-semibold text-[#002855] mb-6">
                    Manage Approved Courses
                </h2>

                <div class="overflow-x-auto">

                    <table class="w-full text-left">

                        <thead>
                        <tr class="border-b">

                            <th class="py-3 px-4 text-[#002855] font-bold">
                                Course Title
                            </th>

                            <th class="py-3 px-4 text-[#002855] font-bold">
                                Date Approved
                            </th>

                            <th class="py-3 px-4 text-[#002855] font-bold">
                                Status
                            </th>

                            <th class="py-3 px-4 text-[#002855] font-bold">
                                Active Enrollments
                            </th>

                            <th class="py-3 px-4 text-[#002855] font-bold">
                                Actions
                            </th>

                        </tr>
                        </thead>

                        <tbody>

                        @forelse($approvedCourses as $course)
                        <tr class="border-b hover:bg-gray-50 transition">

                             <td class="py-4 px-4">
                                 <div class="font-semibold text-gray-800">{{ $course->title }}</div>
                                 @if($course->codes && $course->codes->isNotEmpty())
                                     <div class="text-xs text-blue-600 font-mono mt-1 flex items-center gap-1">
                                         Code: <span class="bg-blue-50 px-1.5 py-0.5 rounded border border-blue-150 select-all font-bold">{{ $course->codes->first()->code }}</span>
                                     </div>
                                 @endif
                             </td>

                            <td class="py-4 px-4 text-gray-500">
                                {{ $course->approved_at ? \Carbon\Carbon::parse($course->approved_at)->format('Y-m-d') : 'N/A' }}
                            </td>

                            <td class="py-4 px-4">
                                @if($course->is_active)
                                    <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-semibold">
                                        Active
                                    </span>
                                @else
                                    <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-sm font-semibold">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-4">
                                {{ $course->active_enrollments ?? 0 }}
                            </td>

                            <td class="py-4 px-4 relative">
                                <button onclick="toggleDropdown(event, {{ $course->id }})" class="primary-btn text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                                    Actions
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </button>
                                
                                <div id="dropdown-{{ $course->id }}" class="actions-dropdown">
                                    <button onclick="viewCourseDetails({{ $course->id }})" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-[#002855] font-medium text-sm border-b">
                                        View Details
                                    </button>
                                    <form action="{{ route('teacher.courses.toggleStatus', $course->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm font-medium">
                                            {{ $course->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                No approved courses yet.
                            </td>
                        </tr>
                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- Submissions -->
            <div id="submissions-section" class="bg-white rounded-xl card-shadow p-6 mb-8">

                <h2 class="text-2xl font-semibold text-[#002855] mb-6">
                    My Submissions for Approval
                </h2>

                <div class="space-y-4">

                    @forelse($submissions as $sub)
                    @php
                        $badgeClass = match($sub->status) {
                            'pending' => 'bg-amber-100 text-amber-700',
                            'returned' => 'bg-red-100 text-red-600',
                            'draft' => 'bg-gray-100 text-gray-600',
                            default => 'bg-gray-100 text-gray-600'
                        };
                    @endphp
                    <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition rounded-lg border border-gray-100">

                        <div>
                            <h3 class="text-lg font-semibold text-[#002855]">
                                {{ $sub->title }}
                            </h3>

                            <p class="text-sm text-gray-500">
                                @if($sub->status === 'draft')
                                    Last saved on {{ $sub->updated_at->format('Y-m-d') }}
                                @else
                                    Submitted on {{ $sub->updated_at->format('Y-m-d') }}
                                @endif
                            </p>
                        </div>

                        <div class="flex items-center gap-4">

                            <span class="{{ $badgeClass }} px-4 py-2 rounded-full text-sm font-semibold capitalize">
                                {{ $sub->status }}
                            </span>

                            @if($sub->status === 'draft' || $sub->status === 'returned')
                                <a href="{{ route('teacher.courses.edit', $sub->id) }}" class="bg-[#ffca28] hover:opacity-90 text-[#002855] font-bold px-4 py-2 rounded-lg no-underline text-sm block">
                                    Edit Course
                                </a>
                            @endif

                            <button onclick="viewCourseDetails({{ $sub->id }})" class="primary-btn text-white px-4 py-2 rounded-lg text-sm transition">
                                View Details
                            </button>

                        </div>

                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No submissions or drafts at the moment.</p>
                    @endforelse

                </div>

            </div>

            <!-- Statistics -->
            <div id="analytics-section" class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Card 1 -->
                <div class="bg-[#002855] text-white p-6 rounded-xl card-shadow">

                    <div class="text-sm opacity-80 mb-2">
                        Total Active Users
                    </div>

                    <div class="text-4xl font-bold mb-2">
                        {{ $totalStudentsCount }}
                    </div>

                    <div class="text-sm opacity-80">
                        Across all your courses
                    </div>

                </div>

                <!-- Card 2 -->
                <div class="bg-[#002855] text-white p-6 rounded-xl card-shadow">

                    <div class="text-sm opacity-80 mb-2">
                        Average Completion Rate
                    </div>

                    <div class="text-4xl font-bold mb-2">
                        73%
                    </div>

                    <div class="text-sm opacity-80">
                        Students completing courses
                    </div>

                </div>

                <!-- Card 3 -->
                <div class="bg-[#002855] text-white p-6 rounded-xl card-shadow">

                    <div class="text-sm opacity-80 mb-2">
                        Pending Approvals
                    </div>

                    <div class="text-4xl font-bold mb-2">
                        {{ $pendingApprovalsCount }}
                    </div>

                    <div class="text-sm opacity-80">
                        Awaiting admin review
                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDetailsModal()"></div>

        <!-- Center modal content -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-200">
            <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-4">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-2xl font-bold text-[#002855]" id="modal-course-title">
                        Course Title
                    </h3>
                    <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 text-3xl font-light">&times;</button>
                </div>
                
                <div class="mb-4 flex items-center flex-wrap gap-2">
                    <span id="modal-course-category" class="bg-blue-100 text-[#002855] px-3 py-1 rounded-full text-xs font-semibold">
                        Category
                    </span>
                    <span id="modal-course-status" class="px-3 py-1 rounded-full text-xs font-semibold capitalize">
                        Status
                    </span>
                    <span id="modal-course-code-wrapper" class="bg-indigo-50 border border-indigo-150 text-indigo-700 px-3 py-1 rounded-full text-xs font-semibold font-mono hidden">
                        🔑 Code: <span id="modal-course-code" class="select-all font-bold"></span>
                    </span>
                </div>

                <p id="modal-course-description" class="text-gray-600 mb-6 text-sm leading-relaxed">
                    Description...
                </p>

                <!-- Feedback alert box if returned -->
                <div id="modal-feedback-box" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg hidden">
                    <h5 class="text-red-800 font-bold text-sm mb-1">Feedback from Administrator:</h5>
                    <p id="modal-feedback-text" class="text-red-700 text-sm">Comments here...</p>
                </div>

                <h4 class="font-bold text-[#002855] border-b pb-2 mb-3 text-lg">Course Structure</h4>
                <div id="modal-course-structure" class="space-y-4 max-h-60 overflow-y-auto pr-2">
                    <!-- Modules tree outline -->
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 sm:px-8 sm:flex sm:flex-row-reverse gap-3">
                <button type="button" onclick="closeDetailsModal()" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm">
                    Close
                </button>
                <a id="modal-edit-btn" href="#" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-[#ffca28] text-base font-semibold text-[#002855] hover:opacity-90 focus:outline-none sm:w-auto sm:text-sm hidden no-underline">
                    Edit Course
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    // Scroll to section script
    function scrollToSection(id) {
        const el = document.getElementById(id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // Toggle dropdown menus
    let openDropdownId = null;
    function toggleDropdown(event, courseId) {
        event.stopPropagation();
        const currentDropdown = document.getElementById('dropdown-' + courseId);
        
        // Close other dropdown
        if (openDropdownId && openDropdownId !== courseId) {
            const other = document.getElementById('dropdown-' + openDropdownId);
            if (other) other.classList.remove('show');
        }
        
        if (currentDropdown) {
            currentDropdown.classList.toggle('show');
            openDropdownId = currentDropdown.classList.contains('show') ? courseId : null;
        }
    }

    // Close dropdown on click outside
    window.addEventListener('click', function() {
        if (openDropdownId) {
            const other = document.getElementById('dropdown-' + openDropdownId);
            if (other) {
                other.classList.remove('show');
                openDropdownId = null;
            }
        }
    });

    // Details Modal functions
    const detailsModal = document.getElementById('detailsModal');
    const modalTitle = document.getElementById('modal-course-title');
    const modalCategory = document.getElementById('modal-course-category');
    const modalStatus = document.getElementById('modal-course-status');
    const modalDesc = document.getElementById('modal-course-description');
    const modalFeedbackBox = document.getElementById('modal-feedback-box');
    const modalFeedbackText = document.getElementById('modal-feedback-text');
    const modalStructure = document.getElementById('modal-course-structure');
    const modalEditBtn = document.getElementById('modal-edit-btn');

    function viewCourseDetails(courseId) {
        fetch(`/teacher/courses/${courseId}/details`)
            .then(res => res.json())
            .then(data => {
                const course = data.course;
                modalTitle.textContent = course.title;
                modalCategory.textContent = course.category || 'General';
                modalDesc.textContent = course.description || 'No description provided.';
                
                // Enrollment Code display
                const codeWrapper = document.getElementById('modal-course-code-wrapper');
                const codeElem = document.getElementById('modal-course-code');
                if (course.codes && course.codes.length > 0) {
                    codeElem.textContent = course.codes[0].code;
                    codeWrapper.classList.remove('hidden');
                } else {
                    codeWrapper.classList.add('hidden');
                }
                
                // Status badge styling
                modalStatus.textContent = course.status;
                modalStatus.className = 'px-3 py-1 rounded-full text-xs font-semibold capitalize ';
                if (course.status === 'approved') {
                    modalStatus.className += 'bg-green-100 text-green-700';
                } else if (course.status === 'pending') {
                    modalStatus.className += 'bg-amber-100 text-amber-700';
                } else if (course.status === 'returned') {
                    modalStatus.className += 'bg-red-100 text-red-700';
                } else {
                    modalStatus.className += 'bg-gray-100 text-gray-700';
                }

                // Feedback box
                if (course.status === 'returned' && course.admin_feedback) {
                    modalFeedbackText.textContent = course.admin_feedback;
                    modalFeedbackBox.classList.remove('hidden');
                } else {
                    modalFeedbackBox.classList.add('hidden');
                }

                // Edit button for drafts/returned
                if (course.status === 'draft' || course.status === 'returned') {
                    modalEditBtn.href = `/teacher/courses/${course.id}/edit`;
                    modalEditBtn.classList.remove('hidden');
                } else {
                    modalEditBtn.classList.add('hidden');
                }

                // Render course modules list
                let structureHtml = '';
                if (course.modules && course.modules.length > 0) {
                    course.modules.forEach(mod => {
                        structureHtml += `
                            <div class="border border-gray-100 p-3 rounded-lg bg-gray-50">
                                <div class="font-bold text-[#002855] text-sm">
                                    Module ${mod.sort_order}: ${mod.title}
                                </div>
                        `;
                        if (mod.lessons && mod.lessons.length > 0) {
                            structureHtml += `<ul class="mt-2 space-y-1.5 pl-4">`;
                            mod.lessons.forEach(les => {
                                let typeIcon = '📄';
                                if (les.type === 'video') typeIcon = '🎥 Video';
                                else if (les.type === 'presentation') typeIcon = '▣ Slides';
                                else if (les.type === 'quiz') typeIcon = '❓ Quiz';
                                
                                structureHtml += `
                                    <li class="text-xs text-gray-600 flex justify-between">
                                        <span>${typeIcon}: ${les.title}</span>
                                        ${les.type === 'presentation' && les.presentation_path ? `<span class="text-gray-400 font-mono text-[10px]">${les.presentation_size || ''}</span>` : ''}
                                        ${les.type === 'quiz' ? `<span class="text-sky-600 font-semibold text-[10px]">${les.quiz_questions_count} Pool Qs</span>` : ''}
                                    </li>
                                `;
                            });
                            structureHtml += `</ul>`;
                        } else {
                            structureHtml += `<p class="text-xs text-gray-400 italic mt-1 pl-4">No content inside this module.</p>`;
                        }
                        structureHtml += `</div>`;
                    });
                } else {
                    structureHtml = `<p class="text-sm text-gray-500 italic text-center py-4">No modules built for this course.</p>`;
                }
                modalStructure.innerHTML = structureHtml;

                // Show modal
                detailsModal.classList.remove('hidden');
            });
    }

    function closeDetailsModal() {
        detailsModal.classList.add('hidden');
    }

    // Scroll to sections on load if set by flash session
    @if(session('scroll_to'))
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                scrollToSection("{{ session('scroll_to') }}-section");
            }, 300);
        });
    @endif

    // Real-time polling
    let lastTeacherCoursesState = null;
    function pollTeacherStatus() {
        fetch('/api/courses/status-check')
            .then(res => res.json())
            .then(data => {
                const courses = data.teacher_courses || [];
                const currentStateString = JSON.stringify(courses.map(c => ({
                    id: c.id,
                    status: c.status,
                    is_active: c.is_active,
                    admin_feedback: c.admin_feedback,
                    code: c.code
                })));
                
                if (lastTeacherCoursesState !== null && lastTeacherCoursesState !== currentStateString) {
                    location.reload();
                }
                
                lastTeacherCoursesState = currentStateString;
            })
            .catch(err => console.error("Error polling statuses: ", err));
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        pollTeacherStatus();
        setInterval(pollTeacherStatus, 5000);
    });
</script>

</body>
</html>