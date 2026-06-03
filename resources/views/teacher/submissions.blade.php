<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Submissions - Certly</title>

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
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 sidebar text-white relative flex flex-col justify-between fixed h-screen z-10">

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

                <a href="{{ route('teacher.dashboard') }}" class="text-gray-300 hover:bg-[#003b73] hover:text-white w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left no-underline block">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('teacher.courses.create') }}" class="text-gray-300 hover:bg-[#003b73] hover:text-white w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left no-underline block">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Create Course</span>
                </a>

                <a href="{{ route('teacher.submissions') }}" class="sidebar-active w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left no-underline block">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span>My Submissions</span>
                </a>

                <a href="{{ route('teacher.dashboard') }}#students-section" class="text-gray-300 hover:bg-[#003b73] hover:text-white w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left no-underline block">
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

    <!-- Main Content Area -->
    <main class="flex-1 pl-64 overflow-y-auto">

        <div class="max-w-7xl mx-auto p-8 pb-20">

            <!-- Heading -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-[#002855] mb-2">
                    My Submissions
                </h1>
            </div>

            <!-- Tab Filtering Buttons Bar -->
            <div class="bg-white p-2 rounded-xl card-shadow flex gap-3 mb-8 border border-gray-100 flex-wrap">
                <button onclick="filterTab('all', this)" class="tab-btn bg-[#002855] text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                    All Submissions ({{ $all->count() }})
                </button>
                <button onclick="filterTab('draft', this)" class="tab-btn bg-transparent text-[#002855] hover:bg-gray-100 px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                    Drafts ({{ $drafts->count() }})
                </button>
                <button onclick="filterTab('pending', this)" class="tab-btn bg-transparent text-[#002855] hover:bg-gray-100 px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                    Pending Review ({{ $pending->count() }})
                </button>
                <button onclick="filterTab('returned', this)" class="tab-btn bg-transparent text-[#002855] hover:bg-gray-100 px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                    Returned for Revision ({{ $returned->count() }})
                </button>
            </div>

            <!-- Submissions list container -->
            <div class="space-y-6">

                @forelse($all as $course)
                    @php
                        // Determine layout details matching status
                        if ($course->status === 'pending') {
                            $badgeLabel = 'Pending Admin Review';
                            $badgeClass = 'bg-[#fffbeb] text-[#d97706] border border-[#fef3c7]';
                        } elseif ($course->status === 'returned') {
                            $badgeLabel = 'Returned for Revision';
                            $badgeClass = 'bg-[#fef2f2] text-[#ef4444] border border-[#fee2e2]';
                        } else {
                            $badgeLabel = 'Draft';
                            $badgeClass = 'bg-[#f3f4f6] text-[#4b5563] border border-[#e5e7eb]';
                        }
                    @endphp

                    <div class="submission-card bg-white rounded-2xl card-shadow p-6 border border-gray-100 transition duration-200" data-status="{{ $course->status }}">
                        
                        <!-- Card Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-[#002855] mb-1">
                                    {{ $course->title }}
                                </h3>
                                <p class="text-xs text-gray-400 font-medium">
                                    @if($course->status === 'draft')
                                        Saved on {{ $course->updated_at->format('Y-m-d') }}
                                    @else
                                        Submitted on {{ $course->updated_at->format('Y-m-d') }}
                                    @endif
                                </p>
                            </div>

                            <span class="{{ $badgeClass }} px-4 py-1.5 rounded-full text-xs font-bold shadow-sm">
                                {{ $badgeLabel }}
                            </span>
                        </div>

                        <!-- Card Body / Actions -->
                        @if($course->status === 'returned')
                            <div class="mt-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <!-- Feedback info box -->
                                <div class="bg-[#fff5f5] border-l-4 border-[#ef4444] p-4 rounded-r-xl flex items-start gap-3 flex-1 border border-red-100">
                                    <i data-lucide="alert-circle" class="w-5 h-5 text-[#ef4444] flex-shrink-0 mt-0.5"></i>
                                    <div class="text-xs text-[#ef4444] leading-relaxed">
                                        <span class="font-bold block mb-1">Admin Feedback:</span>
                                        {{ $course->admin_feedback ?? 'Please review your course outline and details.' }}
                                    </div>
                                </div>

                                <!-- Action button -->
                                <a href="{{ route('teacher.courses.edit', $course->id) }}" class="bg-[#002855] hover:opacity-95 text-white font-bold px-6 py-3 rounded-xl no-underline text-xs flex-shrink-0 flex items-center justify-center shadow-sm">
                                    Edit & Resubmit
                                </a>
                            </div>
                        @elseif($course->status === 'draft')
                            <div class="mt-4">
                                <a href="{{ route('teacher.courses.edit', $course->id) }}" class="bg-[#002855] hover:opacity-95 text-white font-bold px-6 py-3 rounded-xl no-underline text-xs inline-flex items-center justify-center shadow-sm">
                                    Continue Editing
                                </a>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="bg-white rounded-2xl card-shadow p-12 text-center border border-gray-100 text-gray-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto text-gray-300 mb-3"></i>
                        <p class="font-medium text-lg">No submissions found</p>
                        <p class="text-xs text-gray-400 mt-1">Courses in draft or pending review will show up here.</p>
                    </div>
                @endforelse

            </div>

        </div>

    </main>

</div>

<script>
    lucide.createIcons();

    // Tab Filtering script
    function filterTab(status, tabElement) {
        // Reset and highlight active button
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-[#002855]', 'text-white');
            btn.classList.add('bg-transparent', 'text-[#002855]', 'hover:bg-gray-100');
        });
        tabElement.classList.remove('bg-transparent', 'text-[#002855]', 'hover:bg-gray-100');
        tabElement.classList.add('bg-[#002855]', 'text-white');

        // Hide/Show cards
        document.querySelectorAll('.submission-card').forEach(card => {
            if (status === 'all' || card.dataset.status === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

</body>
</html>
