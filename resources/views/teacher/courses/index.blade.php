<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Master Directory - Certly</title>

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
            color: white;
        }
        .primary-btn:hover {
            opacity: .9;
        }
        .secondary-btn {
            border: 1px solid #002855;
            color: #002855;
        }
        .secondary-btn:hover {
            background: #f8fafc;
        }
        .card-shadow {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 sidebar text-white flex flex-col justify-between fixed h-full z-10">
        <div class="p-6 flex-1">
            <div class="flex items-center gap-3 mb-10">
                <img src="{{ asset('images/certly-logo.png') }}" alt="Certly Logo" class="w-10 h-10 object-contain rounded-lg bg-white p-0.5">
                <span class="text-2xl font-semibold">Certly</span>
            </div>

            <nav class="space-y-3">
                <a href="{{ route('teacher.dashboard') }}" class="text-gray-300 hover:bg-[#003b73] hover:text-white w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left no-underline block transition">
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
                <a href="{{ route('teacher.courses.index') }}" class="sidebar-active w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left no-underline block transition">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    <span>Courses</span>
                </a>
            </nav>
        </div>

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
    <main class="flex-1 ml-64 overflow-y-auto min-h-screen">
        <div class="max-w-7xl mx-auto p-8">
            
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <h1 class="text-4xl font-bold text-[#002855] mb-2">Courses Master Directory</h1>
                    <p class="text-gray-600 text-lg">Manage all your approved and live courses.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($courses as $course)
                    @php
                        $utilization = $course->total_vouchers > 0 ? ($course->used_vouchers / $course->total_vouchers) * 100 : 0;
                        $isLow = $utilization >= 80;
                        $progressColor = $isLow ? 'bg-orange-500' : 'bg-green-500';
                        if ($utilization >= 95) $progressColor = 'bg-red-600';
                    @endphp
                    <div class="bg-white rounded-xl card-shadow p-6 flex flex-col relative">
                        <!-- Approved Badge -->
                        <div class="absolute top-4 right-4 bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                            <i data-lucide="check-circle" class="w-3 h-3"></i> Approved
                        </div>

                        <!-- Header -->
                        <div class="flex items-start gap-4 mb-4 mt-2">
                            <div class="w-12 h-12 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center font-bold text-[#002855] flex-shrink-0">
                                {{ strtoupper(substr($course->title, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#002855] leading-tight mb-1 pr-16">{{ $course->title }}</h3>
                                <div class="text-xs text-gray-500 font-mono">
                                    Code: {{ $course->codes->first()->code ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="mb-6 bg-gray-50 rounded-lg p-3">
                            <div class="flex items-center gap-2 mb-1">
                                <i data-lucide="users" class="w-4 h-4 text-gray-500"></i>
                                <span class="text-sm font-semibold text-gray-700">{{ $course->active_enrollments }}</span>
                                <span class="text-xs text-gray-500">Active Students</span>
                            </div>
                        </div>

                        <!-- Vouchers -->
                        <div class="mb-6">
                            <div class="flex justify-between items-end mb-2">
                                <div class="text-sm font-semibold text-gray-700">Voucher Utilization</div>
                                <div class="text-xs text-gray-500 font-bold">
                                    {{ $course->used_vouchers }} / {{ $course->total_vouchers }} Claimed
                                </div>
                            </div>
                            
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                <div class="{{ $progressColor }} h-2 rounded-full transition-all" style="width: {{ $utilization }}%"></div>
                            </div>

                            @if($isLow)
                                <div class="inline-flex items-center gap-1 text-xs font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded">
                                    <i data-lucide="alert-triangle" class="w-3 h-3"></i> Running Low on Vouchers
                                </div>
                            @endif
                        </div>

                        <div class="mt-auto flex flex-col gap-2">
                            <a href="{{ route('teacher.courses.students', $course->id) }}" class="primary-btn text-center py-2.5 rounded-lg text-sm font-bold no-underline w-full">
                                View Student Progress
                            </a>
                            <a href="{{ route('teacher.courses.vouchers', $course->id) }}" class="secondary-btn text-center py-2.5 rounded-lg text-sm font-bold no-underline w-full">
                                View Available Vouchers
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 text-center rounded-xl card-shadow">
                        <i data-lucide="book-x" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                        <h3 class="text-xl font-bold text-gray-700 mb-1">No Approved Courses</h3>
                        <p class="text-gray-500 mb-4">You do not have any active courses yet. Create one to get started.</p>
                        <a href="{{ route('teacher.courses.create') }}" class="primary-btn inline-block px-6 py-2.5 rounded-lg font-bold text-sm no-underline">Create Course</a>
                    </div>
                @endforelse
            </div>

        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
