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

        .primary-btn {
            background: #002855;
        }

        .primary-btn:hover {
            opacity: .9;
        }

        .card-shadow {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
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
                <div class="w-10 h-10 bg-[#ffca28] rounded-lg flex items-center justify-center">
                    <span class="text-2xl font-bold text-[#002855]">C</span>
                </div>

                <span class="text-2xl font-semibold">
                    Certly
                </span>
            </div>

            <!-- Navigation -->
            <nav class="space-y-3">

                <button class="sidebar-active w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </button>

                <button class="hover:bg-[#003b73] w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Create Course</span>
                </button>

                <button class="hover:bg-[#003b73] w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span>My Submissions</span>
                </button>

                <button class="hover:bg-[#003b73] w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    <span>User Analytics</span>
                </button>

            </nav>

        </div>

        <!-- Logout Form & Button -->
        <div class="p-6">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-full hover:bg-[#003b73] flex items-center gap-3 px-4 py-3 rounded-lg transition text-left">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span>Log Out</span>
            </button>
        </div>

    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">

        <div class="max-w-7xl mx-auto p-8">

            <!-- Heading -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-[#002855] mb-2">
                    Facilitator Dashboard
                </h1>
                <p class="text-gray-600 text-lg">
                    Welcome back, {{ Auth::user()->name }}! Manage your courses and student enrollments.
                </p>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <!-- Create Course -->
                <button class="bg-[#ffca28] p-6 rounded-xl card-shadow flex items-center gap-4 hover:opacity-95 transition">

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

                </button>

                <!-- Draft Submissions -->
                <button class="bg-white p-6 rounded-xl card-shadow flex items-center gap-4 hover:bg-gray-50 transition">

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

                </button>

            </div>

            <!-- Registered Students List -->
            <div class="bg-white rounded-xl card-shadow p-6 mb-8">

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
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

                            <td class="py-4 px-4 font-semibold text-gray-800">
                                {{ $student->name }}
                            </td>

                            <td class="py-4 px-4 text-gray-600">
                                {{ $student->email }}
                            </td>

                            <td class="py-4 px-4">
                                <span class="bg-blue-100 text-[#002855] px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $student->affiliation ?? 'N/A' }}
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

                        <!-- Row 1 -->
                        <tr class="border-b hover:bg-gray-50 transition">

                            <td class="py-4 px-4">
                                Advanced Cybersecurity
                            </td>

                            <td class="py-4 px-4 text-gray-500">
                                2026-04-15
                            </td>

                            <td class="py-4 px-4">
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-semibold">
                                    Active
                                </span>
                            </td>

                            <td class="py-4 px-4">
                                234
                            </td>

                            <td class="py-4 px-4">

                                <button class="primary-btn text-white px-4 py-2 rounded-lg flex items-center gap-2">
                                    Actions
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </button>

                            </td>

                        </tr>

                        <!-- Row 2 -->
                        <tr class="border-b hover:bg-gray-50 transition">

                            <td class="py-4 px-4">
                                Cloud Computing Fundamentals
                            </td>

                            <td class="py-4 px-4 text-gray-500">
                                2026-03-22
                            </td>

                            <td class="py-4 px-4">
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-semibold">
                                    Active
                                </span>
                            </td>

                            <td class="py-4 px-4">
                                187
                            </td>

                            <td class="py-4 px-4">

                                <button class="primary-btn text-white px-4 py-2 rounded-lg flex items-center gap-2">
                                    Actions
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </button>

                            </td>

                        </tr>

                        <!-- Row 3 -->
                        <tr class="hover:bg-gray-50 transition">

                            <td class="py-4 px-4">
                                Data Analytics with Python
                            </td>

                            <td class="py-4 px-4 text-gray-500">
                                2026-02-10
                            </td>

                            <td class="py-4 px-4">
                                <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-sm font-semibold">
                                    Inactive
                                </span>
                            </td>

                            <td class="py-4 px-4">
                                145
                            </td>

                            <td class="py-4 px-4">

                                <button class="primary-btn text-white px-4 py-2 rounded-lg flex items-center gap-2">
                                    Actions
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </button>

                            </td>

                        </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- Submissions -->
            <div class="bg-white rounded-xl card-shadow p-6 mb-8">

                <h2 class="text-2xl font-semibold text-[#002855] mb-6">
                    My Submissions for Approval
                </h2>

                <div class="space-y-4">

                    <!-- Submission 1 -->
                    <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg">

                        <div>
                            <h3 class="text-lg font-semibold text-[#002855]">
                                Machine Learning Basics
                            </h3>

                            <p class="text-sm text-gray-500">
                                Submitted on 2026-05-10
                            </p>
                        </div>

                        <div class="flex items-center gap-4">

                            <span class="bg-amber-100 text-amber-700 px-4 py-2 rounded-full text-sm font-semibold">
                                Pending
                            </span>

                            <button class="primary-btn text-white px-4 py-2 rounded-lg">
                                View Details
                            </button>

                        </div>

                    </div>

                    <!-- Submission 2 -->
                    <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg">

                        <div>
                            <h3 class="text-lg font-semibold text-[#002855]">
                                DevOps Essentials
                            </h3>

                            <p class="text-sm text-gray-500">
                                Submitted on 2026-05-05
                            </p>
                        </div>

                        <div class="flex items-center gap-4">

                            <span class="bg-red-100 text-red-600 px-4 py-2 rounded-full text-sm font-semibold">
                                Returned
                            </span>

                            <button class="primary-btn text-white px-4 py-2 rounded-lg">
                                View Details
                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Card 1 -->
                <div class="bg-[#002855] text-white p-6 rounded-xl card-shadow">

                    <div class="text-sm opacity-80 mb-2">
                        Total Active Users
                    </div>

                    <div class="text-4xl font-bold mb-2">
                        {{ $students->count() }}
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
                        1
                    </div>

                    <div class="text-sm opacity-80">
                        Awaiting admin review
                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>