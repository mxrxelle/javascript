<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vouchers - {{ $course->title }}</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: Arial, sans-serif; }
        .sidebar { background: #002855; }
        .sidebar-active { background: #ffca28; color: #002855 !important; }
        .sidebar a:not(.sidebar-active):hover { background: rgba(255,255,255,0.08); color: white !important; }
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
        <div class="max-w-5xl mx-auto p-8">
            
            <div class="mb-6 flex items-center gap-4">
                <a href="{{ route('teacher.courses.index') }}" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-500 hover:text-[#002855] hover:bg-gray-50 card-shadow transition">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-[#002855] leading-tight">Voucher Codes</h1>
                    <p class="text-gray-600">{{ $course->title }}</p>
                </div>
            </div>

            @php
                $unused = $vouchers->whereNull('claimed_by');
                $used = $vouchers->whereNotNull('claimed_by');
            @endphp

            <div class="bg-white rounded-xl card-shadow overflow-hidden">
                <div class="bg-[#002855] p-6 text-white flex justify-between items-center">
                    <h2 class="text-xl font-bold">Voucher Codes – {{ $course->title }}</h2>
                    <div class="bg-white/10 px-4 py-1.5 rounded-full text-sm font-semibold">
                        {{ $unused->count() }} unused / {{ $vouchers->count() }} total
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-200">
                    
                    <!-- Unused Codes -->
                    <div class="p-6">
                        <h3 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i data-lucide="key" class="w-4 h-4 text-green-600"></i> UNUSED CODES
                        </h3>
                        
                        @if($unused->count() > 0)
                            <div class="space-y-3">
                                @foreach($unused as $voucher)
                                    <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg bg-gray-50 hover:bg-gray-100 transition">
                                        <div class="font-mono font-bold text-gray-800">{{ $voucher->code }}</div>
                                        <button onclick="copyToClipboard('{{ $voucher->code }}')" class="text-blue-600 hover:text-blue-800 flex items-center gap-1 text-xs font-semibold bg-blue-50 px-2 py-1 rounded">
                                            <i data-lucide="copy" class="w-3 h-3"></i> Copy
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm italic">No unused codes remaining.</p>
                        @endif
                    </div>

                    <!-- Used Codes -->
                    <div class="p-6 bg-gray-50/50">
                        <h3 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i data-lucide="lock" class="w-4 h-4 text-orange-500"></i> USED CODES
                        </h3>
                        
                        @if($used->count() > 0)
                            <div class="space-y-3">
                                @foreach($used as $voucher)
                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-white opacity-80">
                                        <div>
                                            <div class="font-mono font-bold text-gray-500 line-through decoration-gray-400">{{ $voucher->code }}</div>
                                            <div class="text-xs text-gray-500 mt-1">Claimed by: <span class="font-semibold text-gray-700">{{ $voucher->student->name ?? 'Unknown' }}</span></div>
                                        </div>
                                        <span class="bg-gray-200 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Used</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm italic">No codes have been used yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Toast Notification -->
            <div id="toast" class="fixed bottom-6 right-6 bg-gray-800 text-white px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-y-20 opacity-0 flex items-center gap-2 z-50">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-400"></i>
                <span id="toast-msg" class="font-semibold text-sm">Copied to clipboard!</span>
            </div>

        </div>
    </main>

    <script>
        lucide.createIcons();

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                const toast = document.getElementById('toast');
                toast.classList.remove('translate-y-20', 'opacity-0');
                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 2500);
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
</body>
</html>
