<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Progress - {{ $course->title }}</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: Arial, sans-serif; }
        .sidebar { background: #002855; }
        .sidebar-active { background: #ffca28; color: #002855 !important; }
        .sidebar a:not(.sidebar-active):hover { background: rgba(255,255,255,0.08); color: white !important; }
        .primary-btn { background: #002855; color: white; }
        .primary-btn:hover { opacity: .9; }
        .card-shadow { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
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
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-full text-gray-300 hover:bg-[#003b73] hover:text-white flex items-center gap-3 px-4 py-3 rounded-lg transition text-left">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span>Log Out</span>
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 overflow-y-auto min-h-screen">
        <div class="max-w-7xl mx-auto p-8">
            
            <div class="mb-6 flex items-center gap-4">
                <a href="{{ route('teacher.courses.index') }}" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-500 hover:text-[#002855] hover:bg-gray-50 card-shadow transition">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-[#002855] leading-tight">Student Progress Tracker</h1>
                    <p class="text-gray-600">{{ $course->title }}</p>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Enrolled -->
                <div class="bg-white p-6 rounded-xl card-shadow flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">{{ $totalEnrolled }}</div>
                        <div class="text-sm text-gray-500 font-semibold">Active Students</div>
                    </div>
                </div>
                
                <!-- Average Completion -->
                <div class="bg-white p-6 rounded-xl card-shadow flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                        <i data-lucide="trending-up" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">{{ $avgCompletion }}%</div>
                        <div class="text-sm text-gray-500 font-semibold">Average Course Completion</div>
                    </div>
                </div>

                <!-- Class Average Score -->
                <div class="bg-white p-6 rounded-xl card-shadow flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                        <i data-lucide="award" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">{{ $classAverageScore }}%</div>
                        <div class="text-sm text-gray-500 font-semibold">Class Average Score</div>
                    </div>
                </div>
            </div>

            <!-- Filters & Table -->
            <div class="bg-white rounded-xl card-shadow overflow-hidden">
                
                <!-- Toolbar -->
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row gap-4 justify-between items-center bg-gray-50/50">
                    <form method="GET" action="{{ route('teacher.courses.students', $course->id) }}" class="flex flex-col md:flex-row gap-4 w-full md:w-auto" id="filterForm">
                        
                        <!-- Search -->
                        <div class="relative">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Status Filter -->
                        <select name="status" onchange="document.getElementById('filterForm').submit()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="All Students" {{ request('status') == 'All Students' || !request('status') ? 'selected' : '' }}>All Statuses</option>
                            <option value="Certified" {{ request('status') == 'Certified' ? 'selected' : '' }}>Certified</option>
                            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Not Started" {{ request('status') == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                        </select>

                        <!-- Module Filter -->
                        <select name="module" onchange="document.getElementById('filterForm').submit()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Modules (Hidden Scores)</option>
                            @foreach($modules as $mod)
                                <option value="{{ $mod->id }}" {{ request('module') == $mod->id ? 'selected' : '' }}>{{ $mod->title }}</option>
                            @endforeach
                        </select>
                        
                        <noscript><button type="submit" class="primary-btn px-4 py-2 rounded-lg text-sm font-semibold">Apply</button></noscript>
                    </form>

                    <div>
                        <!-- Export CSV (mocked for now) -->
                        <button class="flex items-center gap-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-semibold transition">
                            <i data-lucide="download" class="w-4 h-4"></i> Export CSV
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-[#002855] text-white">
                            <tr>
                                <th class="py-3 px-6 font-semibold text-sm">Student Name</th>
                                <th class="py-3 px-6 font-semibold text-sm">Overall Progress</th>
                                <th class="py-3 px-6 font-semibold text-sm text-center">Module Score</th>
                                <th class="py-3 px-6 font-semibold text-sm text-center">Takes</th>
                                <th class="py-3 px-6 font-semibold text-sm text-center">Final Exam</th>
                                <th class="py-3 px-6 font-semibold text-sm text-center">Status Tag</th>
                                <th class="py-3 px-6 font-semibold text-sm text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($students as $enrollment)
                                @php
                                    $user = $enrollment->user;
                                    $progress = $enrollment->progress_percentage;
                                    
                                    $tagColor = 'bg-gray-100 text-gray-600';
                                    if ($enrollment->status == 'Certified') $tagColor = 'bg-green-100 text-green-700';
                                    if ($enrollment->status == 'In Progress') $tagColor = 'bg-yellow-100 text-yellow-700';
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    
                                    <td class="py-4 px-6 align-middle">
                                        <div class="flex items-center gap-3">
                                            <div class="w-full max-w-[120px] bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-gray-700 w-8">{{ $progress }}%</span>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 text-center">
                                        @if($enrollment->latest_quiz_score !== null)
                                            <span class="text-green-600 font-bold text-sm">{{ $enrollment->latest_quiz_score }}%</span>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center text-sm text-gray-600 font-medium">
                                        {{ $enrollment->quiz_takes }} / 3
                                    </td>

                                    <td class="py-4 px-6 text-center">
                                        @if($enrollment->final_exam_taken)
                                            @if($enrollment->final_exam_passed)
                                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold tracking-wide">
                                                    ✅ {{ $enrollment->final_exam_score }}% — Passed
                                                </span>
                                            @else
                                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold tracking-wide">
                                                    ❌ {{ $enrollment->final_exam_score }}% — Failed
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-gray-400 text-sm italic">Not Taken</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($enrollment->quiz_locked)
                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold tracking-wide">
                                                🔒 Locked - Max Attempts
                                            </span>
                                        @else
                                            <span class="{{ $tagColor }} px-3 py-1 rounded-full text-xs font-bold tracking-wide">
                                                {{ $enrollment->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <button onclick="openQuizReviewModal({{ $course->id }}, {{ $user->id }}, '{{ addslashes($user->name) }}')" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
                                            View Quiz Review
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-500 italic">No students match the current filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- Quiz Review Modal -->
    <div id="quizReviewModal" class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h2 id="modalStudentName" class="text-xl font-bold text-[#002855]">Quiz Review</h2>
                <button onclick="closeQuizReviewModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto flex-1">
                <!-- Attempt Tabs -->
                <div id="attemptTabs" class="flex flex-wrap gap-2 mb-6"></div>
                
                <!-- Attempt Details -->
                <div id="attemptDetails" class="hidden">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-4">
                            <div class="text-sm font-semibold text-gray-600">Score: <span id="attemptScore" class="text-gray-900"></span>%</div>
                            <div id="attemptStatus" class="px-3 py-1 rounded-full text-xs font-bold tracking-wide"></div>
                            <div class="text-sm text-gray-500">Module: <span id="attemptModule"></span></div>
                        </div>
                        <div id="unlockQuizContainer" class="hidden">
                            <button onclick="unlockStudentQuiz()" class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-1.5 px-4 rounded-lg text-sm transition flex items-center gap-2 border border-blue-200">
                                <i data-lucide="unlock" class="w-4 h-4"></i> Unlock Quiz for this Student
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-700">
                                <tr>
                                    <th class="py-2 px-4 border-b">#</th>
                                    <th class="py-2 px-4 border-b">Question</th>
                                    <th class="py-2 px-4 border-b">Selected Answer</th>
                                    <th class="py-2 px-4 border-b">Correct Answer</th>
                                    <th class="py-2 px-4 border-b">Result</th>
                                </tr>
                            </thead>
                            <tbody id="attemptQuestionsList" class="divide-y divide-gray-100">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="noAttemptsMessage" class="hidden text-center py-10 text-gray-500 italic">
                    This student has no quiz attempts recorded for this course.
                </div>
                <div id="modalLoading" class="text-center py-10 text-gray-500">
                    Loading attempts...
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        let currentStudentAttempts = [];

        function openQuizReviewModal(courseId, studentId, studentName) {
            document.getElementById('quizReviewModal').classList.remove('hidden');
            document.getElementById('modalStudentName').textContent = `Quiz Review - ${studentName}`;
            
            document.getElementById('attemptTabs').innerHTML = '';
            document.getElementById('attemptDetails').classList.add('hidden');
            document.getElementById('noAttemptsMessage').classList.add('hidden');
            document.getElementById('modalLoading').classList.remove('hidden');

            fetch(`/teacher/courses/${courseId}/students/${studentId}/quiz-attempts`)
                .then(res => {
                    if (res.status === 403) throw new Error("Forbidden access");
                    return res.json();
                })
                .then(attempts => {
                    document.getElementById('modalLoading').classList.add('hidden');
                    currentStudentAttempts = attempts;
                    
                    if (attempts.length === 0) {
                        document.getElementById('noAttemptsMessage').classList.remove('hidden');
                        return;
                    }

                    renderAttemptTabs(attempts);
                    renderAttempt(attempts[0]); // Render the first (latest) attempt by default
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('modalLoading').classList.add('hidden');
                    document.getElementById('noAttemptsMessage').textContent = "Failed to load attempts or access denied.";
                    document.getElementById('noAttemptsMessage').classList.remove('hidden');
                });
        }

        function closeQuizReviewModal() {
            document.getElementById('quizReviewModal').classList.add('hidden');
        }

        function renderAttemptTabs(attempts) {
            const tabsContainer = document.getElementById('attemptTabs');
            tabsContainer.innerHTML = '';
            
            attempts.forEach((attempt, index) => {
                const btn = document.createElement('button');
                btn.className = `px-4 py-2 text-sm font-semibold rounded-md border transition ${index === 0 ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'}`;
                btn.textContent = `Attempt ${attempt.attempt_number} (Module: ${attempt.lesson.module.title})`;
                if (index === 0) btn.textContent += " [Latest]";
                
                btn.onclick = () => {
                    // Update tab active state
                    Array.from(tabsContainer.children).forEach(c => {
                        c.className = 'px-4 py-2 text-sm font-semibold rounded-md border transition bg-white text-gray-700 border-gray-300 hover:bg-gray-50';
                    });
                    btn.className = 'px-4 py-2 text-sm font-semibold rounded-md border transition bg-blue-600 text-white border-blue-600';
                    renderAttempt(attempt);
                };
                
                tabsContainer.appendChild(btn);
            });
        }

        function renderAttempt(attempt) {
            document.getElementById('attemptDetails').classList.remove('hidden');
            document.getElementById('attemptScore').textContent = attempt.score;
            document.getElementById('attemptModule').textContent = attempt.lesson.module.title;
            
            const statusBadge = document.getElementById('attemptStatus');
            if (attempt.passed) {
                statusBadge.textContent = 'Passed';
                statusBadge.className = 'px-3 py-1 rounded-full text-xs font-bold tracking-wide bg-green-100 text-green-700';
            } else if (attempt.is_locked) {
                statusBadge.innerHTML = '<i data-lucide="lock" class="w-3 h-3 inline"></i> Locked';
                statusBadge.className = 'px-3 py-1 rounded-full text-xs font-bold tracking-wide bg-red-100 text-red-700';
            } else {
                statusBadge.textContent = 'Failed';
                statusBadge.className = 'px-3 py-1 rounded-full text-xs font-bold tracking-wide bg-red-100 text-red-700';
            }

            const unlockContainer = document.getElementById('unlockQuizContainer');
            if (attempt.is_locked || (attempt.attempt_number >= attempt.max_attempts && !attempt.passed)) {
                unlockContainer.classList.remove('hidden');
                // Store data for the unlock button
                unlockContainer.dataset.lessonId = attempt.lesson_id;
                unlockContainer.dataset.studentId = attempt.student_id;
            } else {
                unlockContainer.classList.add('hidden');
            }
            
            const list = document.getElementById('attemptQuestionsList');
            list.innerHTML = '';
            
            attempt.questions.forEach((qRecord, index) => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-gray-50";
                
                const questionText = qRecord.question ? qRecord.question.question : "Unknown Question";
                const choiceText = qRecord.choice ? qRecord.choice.text : "<span class='text-gray-400 italic'>No Answer</span>";
                
                // Find correct choice text if we can, else just state it
                let correctChoiceText = "-";
                if (qRecord.question && qRecord.question.choices) {
                    const correct = qRecord.question.choices.find(c => c.is_correct);
                    if (correct) correctChoiceText = correct.text;
                }
                
                const resultBadge = qRecord.is_correct 
                    ? `<span class="text-green-600 font-semibold"><i data-lucide="check" class="w-4 h-4 inline"></i> Correct</span>`
                    : `<span class="text-red-600 font-semibold"><i data-lucide="x" class="w-4 h-4 inline"></i> Wrong</span>`;
                    
                tr.innerHTML = `
                    <td class="py-3 px-4 border-b text-gray-500">${index + 1}</td>
                    <td class="py-3 px-4 border-b font-medium text-gray-800">${questionText}</td>
                    <td class="py-3 px-4 border-b">${choiceText}</td>
                    <td class="py-3 px-4 border-b">${correctChoiceText}</td>
                    <td class="py-3 px-4 border-b">${resultBadge}</td>
                `;
                
                list.appendChild(tr);
            });
            
            lucide.createIcons();
        }

        function unlockStudentQuiz() {
            const container = document.getElementById('unlockQuizContainer');
            const lessonId = container.dataset.lessonId;
            const studentId = container.dataset.studentId;
            const courseId = {{ $course->id }};
            
            if (!lessonId || !studentId) return;

            if (confirm("Are you sure you want to unlock this quiz? This will reset all their attempts for this lesson.")) {
                fetch(`/teacher/courses/${courseId}/unlock-quiz/${lessonId}/${studentId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("Quiz unlocked successfully.");
                        location.reload();
                    } else {
                        alert("Failed to unlock quiz.");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("An error occurred.");
                });
            }
        }
    </script>
</body>
</html>
