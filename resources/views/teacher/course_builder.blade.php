<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Course - Certly</title>

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

        /* Drag and Drop Zone styling */
        .dropzone.dragover {
            border-color: #ffca28;
            background-color: #fffbeb;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<div class="flex flex-1">

    <!-- Sidebar -->
    <aside class="w-64 sidebar text-white flex flex-col justify-between fixed h-screen z-10">

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

                <a href="{{ route('teacher.dashboard') }}" class="text-gray-300 hover:bg-[#003b73] hover:text-white w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left no-underline block">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('teacher.courses.create') }}" class="sidebar-active w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left no-underline block">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Create Course</span>
                </a>

                <a href="{{ route('teacher.submissions') }}" class="text-gray-300 hover:bg-[#003b73] hover:text-white w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left no-underline block">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span>My Submissions</span>
                </a>

                <a href="{{ route('teacher.courses.index') }}" class="text-gray-300 hover:bg-[#003b73] hover:text-white w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-left no-underline block">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    <span>Courses</span>
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

    <!-- Main Workspace Area -->
    <div class="flex-1 pl-64 flex flex-col pb-24">

        <!-- Top Header Info -->
        <header class="bg-white border-b px-8 py-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-[#002855]">
                {{ $course ? 'Edit Course' : 'Create Course' }}
            </h1>
            <a href="{{ route('teacher.dashboard') }}" class="text-gray-500 hover:text-gray-700 font-semibold flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Dashboard
            </a>
        </header>

        <!-- Builder Content Frame -->
        <div class="p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 flex-1">

            <!-- Left Panel: Course Structure tree (Col span 4) -->
            <div class="lg:col-span-4 bg-white rounded-2xl card-shadow p-6 h-fit border border-gray-100">
                <h3 class="text-lg font-bold text-[#002855] mb-6">Course Structure</h3>

                <div id="treeContainer" class="space-y-4">
                    <!-- Course Info node (Always present) -->
                    <button id="node-course-info" onclick="selectNode('course-info')" class="w-full text-left px-4 py-3 rounded-xl font-medium text-sm transition hover:bg-gray-50 flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        <span>Course Info</span>
                    </button>

                    <!-- Modules list will be dynamically rendered here -->
                    <div id="treeModules" class="space-y-4"></div>
                </div>

                <!-- Add Module Button -->
                <button onclick="addNewModule()" class="mt-6 w-full py-2.5 border border-dashed border-[#002855] text-[#002855] hover:bg-amber-50 hover:border-[#ffca28] rounded-xl font-semibold text-sm transition flex items-center justify-center gap-2">
                    <span class="text-lg">+</span> Add New Module
                </button>
            </div>

            <!-- Right Panel: Editor Area (Col span 8) -->
            <div class="lg:col-span-8">

                <!-- 1. COURSE INFO EDITOR -->
                <div id="editor-course-info" class="editor-pane bg-white rounded-2xl card-shadow p-8 border border-gray-100 hidden">
                    <h2 class="text-2xl font-bold text-[#002855] mb-6 border-b pb-4">Course Details</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Course Title</label>
                            <input type="text" id="courseTitleInput" oninput="updateCourseTitle(this.value)" placeholder="Enter course title (e.g. Advanced JavaScript Concepts)" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ffca28] transition">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Category</label>
                            <select id="courseCategoryInput" onchange="updateCourseCategory(this.value)" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ffca28] transition">
                                <option value="Tech">Technology & Programming</option>
                                <option value="Data Science">Data Science & AI</option>
                                <option value="Business">Business & Marketing</option>
                                <option value="Design">Design & Creatives</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                            <textarea id="courseDescriptionInput" oninput="updateCourseDesc(this.value)" rows="5" placeholder="Enter a comprehensive course description..." class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ffca28] transition"></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. SUBTOPIC EDITOR -->
                <div id="editor-subtopic" class="editor-pane bg-white rounded-2xl card-shadow p-8 border border-gray-100 hidden">
                    <h2 class="text-2xl font-bold text-[#002855] mb-6 border-b pb-4">Subtopic Content Builder</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Subtopic Title</label>
                            <input type="text" id="subtopicTitleInput" oninput="updateSubtopicTitle(this.value)" placeholder="e.g. 4.1 Loops: while and do...while" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ffca28] transition">
                        </div>

                        <!-- Drag and Drop Presentation Upload -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            
                            <!-- Slide Deck Section -->
                            <div class="border border-gray-100 p-6 rounded-2xl bg-gray-50 flex flex-col justify-between">
                                <div>
                                    <h4 class="text-base font-bold text-[#002855] mb-4">Presentation & Slide Deck</h4>
                                    
                                    <!-- Drag zone -->
                                    <div id="dropzone" class="dropzone border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer bg-white transition hover:border-[#ffca28]" onclick="triggerFileInput()">
                                        <i data-lucide="upload" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                                        <p class="text-xs text-gray-500 font-medium">Drag & drop your PPT / PDF presentation here</p>
                                        <p class="text-[10px] text-gray-400 mt-1">or click to browse files</p>
                                        <input type="file" id="fileInput" accept=".pdf,.ppt,.pptx" class="hidden" onchange="handleFileSelect(event)">
                                    </div>
                                </div>

                                <!-- Uploaded File Status Row -->
                                <div id="uploadedFileBox" class="mt-4 hidden p-3 bg-blue-50 border border-blue-100 rounded-xl flex items-center justify-between">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                            <i data-lucide="file-text" class="w-5 h-5"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div id="uploadedFileName" class="text-xs font-semibold text-[#002855] truncate">Presentation.pptx</div>
                                            <div id="uploadedFileSize" class="text-[10px] text-gray-500">4.2 MB</div>
                                        </div>
                                    </div>
                                    <button onclick="removeUploadedFile()" class="text-red-500 hover:text-red-700 p-1 flex-shrink-0">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>

                                <!-- Uploading spinner progress -->
                                <div id="uploadProgress" class="mt-4 hidden flex items-center gap-3 p-3 bg-gray-100 rounded-xl text-xs text-gray-600">
                                    <div class="animate-spin rounded-full h-4 w-4 border-2 border-[#002855] border-t-transparent"></div>
                                    <span>Uploading slide deck...</span>
                                </div>
                            </div>

                            <!-- YouTube Reference Video Section -->
                            <div class="border border-gray-100 p-6 rounded-2xl bg-gray-50 flex flex-col justify-between">
                                <div>
                                    <h4 class="text-base font-bold text-[#002855] mb-4">Supplementary Reference Video</h4>
                                    
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-600 mb-1">YouTube Video URL</label>
                                        <input type="url" id="youtubeUrlInput" oninput="updateYoutubeUrl(this.value)" placeholder="https://www.youtube.com/watch?v=..." class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ffca28] transition">
                                    </div>
                                </div>

                                <!-- YouTube Preview Area -->
                                <div id="videoPreviewBox" class="w-full h-36 bg-gray-200 rounded-xl flex items-center justify-center overflow-hidden border border-gray-200 relative">
                                    <!-- Video iframe -->
                                    <div id="iframeContainer" class="w-full h-full hidden">
                                        <iframe id="youtubeIframe" class="w-full h-full border-none" src="" allowfullscreen></iframe>
                                    </div>
                                    
                                    <!-- Video Placeholder -->
                                    <div id="videoPlaceholder" class="text-center flex flex-col items-center">
                                        <div class="w-12 h-12 bg-[#002855] rounded-full flex items-center justify-center text-white mb-2 cursor-pointer hover:opacity-90">
                                            <i data-lucide="play" class="w-6 h-6 fill-current ml-0.5"></i>
                                        </div>
                                        <span class="text-[10px] text-gray-500 font-semibold">Video Preview</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- 3. MODULE QUIZ EDITOR -->
                <div id="editor-quiz" class="editor-pane bg-white rounded-2xl card-shadow p-8 border border-gray-100 hidden">
                    <div class="flex justify-between items-start mb-6 border-b pb-4">
                        <div>
                            <h2 id="quizModuleTitle" class="text-2xl font-bold text-[#002855]">Module 4 Quiz Builder</h2>
                            <p class="text-sm text-gray-500 mt-1">Configure questions and pool options for this module assessment.</p>
                        </div>

                        <!-- Info Banner Rules -->
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 max-w-xs flex gap-2.5">
                            <i data-lucide="info" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                            <div class="text-[10px] text-[#002855] leading-relaxed">
                                <span class="font-bold block mb-0.5">Exam Rules</span>
                                <span id="examRulesText">5 questions randomly generated from this 20-question pool per student attempt</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Rule config inputs -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Quiz Title</label>
                                <input type="text" id="quizTitleInput" oninput="updateQuizTitle(this.value)" placeholder="e.g. Module 4 Assessment: Control Flow" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ffca28] transition">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Questions Selection Rule</label>
                                <div class="flex items-center gap-3">
                                    <input type="number" id="quizPoolCountInput" min="1" oninput="updateQuizPoolCount(this.value)" placeholder="5" class="w-24 px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ffca28] text-center font-bold">
                                    <span class="text-xs text-gray-500 font-semibold">questions randomly selected per student attempt</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Quiz Description / Instructions</label>
                            <textarea id="quizDescriptionInput" oninput="updateQuizDesc(this.value)" rows="3" placeholder="Test your understanding of..." class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ffca28] transition"></textarea>
                        </div>

                        <!-- Questions Pool Header -->
                        <div class="pt-4 border-t flex justify-between items-center">
                            <h3 class="text-lg font-bold text-[#002855]">Questions Pool</h3>
                            <span id="questionsCountBadge" class="bg-blue-100 text-[#002855] text-xs px-2.5 py-1 rounded-full font-bold">0 Questions</span>
                        </div>

                        <!-- Questions list container -->
                        <div id="questionsContainer" class="space-y-6"></div>

                        <!-- Add Question Button -->
                        <button onclick="addNewQuestion()" class="w-full py-3.5 border border-dashed border-gray-300 text-gray-500 hover:border-[#ffca28] hover:text-[#002855] bg-gray-50 hover:bg-amber-50 rounded-xl font-bold text-sm transition flex items-center justify-center gap-2">
                            <span class="text-lg">+</span> Add Question
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- Persistent Footer Bar -->
<footer class="bg-white border-t border-gray-200 fixed bottom-0 left-64 right-0 px-8 py-4 flex justify-between items-center shadow-lg z-20">
    <div class="text-xs text-gray-500 font-semibold" id="saveStatusText">
        <!-- Draft status indicators -->
    </div>
    
    <div class="flex gap-4">
        <!-- Save as Draft -->
        <button onclick="saveCourse('draft')" class="px-6 py-3 border border-gray-300 bg-white hover:bg-gray-50 text-gray-800 font-bold rounded-xl text-sm transition shadow-sm">
            Save as Draft
        </button>

        <!-- Submit for Approval -->
        <button onclick="saveCourse('pending')" class="px-6 py-3 bg-[#002855] hover:opacity-90 text-white font-bold rounded-xl text-sm transition shadow-sm">
            Submit for Approval
        </button>
    </div>
</footer>

<script>
    lucide.createIcons();

    // 1. Initial State Definition
    let courseState = {
        id: {{ $course ? $course->id : 'null' }},
        title: "{{ $course ? addslashes($course->title) : '' }}",
        category: "{{ $course ? addslashes($course->category) : 'Tech' }}",
        description: `{!! $course ? addslashes($course->description) : '' !!}`,
        status: 'draft',
        modules: []
    };

    // Populate courseState from existing course model if editing
    @if($course)
        @foreach($course->modules as $mod)
            let mod_{{ $mod->id }} = {
                id: {{ $mod->id }},
                title: "{{ addslashes($mod->title) }}",
                sort_order: {{ $mod->sort_order }},
                items: []
            };

            @foreach($mod->lessons as $les)
                let les_{{ $les->id }} = {
                    id: {{ $les->id }},
                    title: "{{ addslashes($les->title) }}",
                    type: "{{ $les->type }}",
                    content: `{!! addslashes($les->content) !!}`,
                    youtube_url: "{{ $les->youtube_url }}",
                    presentation_path: "{{ $les->presentation_path }}",
                    presentation_size: "{{ $les->presentation_size }}",
                    quiz_questions_count: {{ $les->quiz_questions_count }},
                    sort_order: {{ $les->sort_order }},
                    questions: []
                };

                @if($les->type === 'quiz')
                    @foreach($les->questions as $q)
                        let q_{{ $q->id }} = {
                            id: {{ $q->id }},
                            question_text: `{!! addslashes($q->question_text) !!}`,
                            question_type: "{{ $q->question_type }}",
                            options: []
                        };

                        @foreach($q->options as $opt)
                            q_{{ $q->id }}.options.push({
                                id: {{ $opt->id }},
                                option_text: "{{ addslashes($opt->option_text) }}",
                                is_correct: {{ $opt->is_correct ? 'true' : 'false' }}
                            });
                        @endforeach

                        les_{{ $les->id }}.questions.push(q_{{ $q->id }});
                    @endforeach
                @endif

                mod_{{ $mod->id }}.items.push(les_{{ $les->id }});
            @endforeach

            courseState.modules.push(mod_{{ $mod->id }});
        @endforeach
    @endif

    // Track active selection
    // Possible formats: 'course-info', 'subtopic-{modIndex}-{lesIndex}', 'quiz-{modIndex}'
    let activeNode = 'course-info';

    // On Load Initializer
    document.addEventListener('DOMContentLoaded', () => {
        // Hydrate form values if editing
        document.getElementById('courseTitleInput').value = courseState.title;
        document.getElementById('courseCategoryInput').value = courseState.category;
        document.getElementById('courseDescriptionInput').value = courseState.description;

        // Render Tree Outline
        renderTree();
        selectNode('course-info');

        // Setup Drag & Drop Listeners
        setupDragAndDrop();
    });

    // 2. Tree Rendering Engine
    function renderTree() {
        const modulesContainer = document.getElementById('treeModules');
        modulesContainer.innerHTML = '';

        courseState.modules.forEach((mod, modIdx) => {
            const modDiv = document.createElement('div');
            modDiv.className = 'border border-gray-200 rounded-xl overflow-hidden';
            
            // Module Header block
            const modHeader = document.createElement('div');
            modHeader.className = 'bg-[#002855] text-white px-4 py-3 flex justify-between items-center';
            modHeader.innerHTML = `
                <span class="text-xs font-bold truncate">Module ${mod.sort_order}: ${mod.title}</span>
                <div class="flex gap-1.5 flex-shrink-0">
                    <button onclick="editModuleName(${modIdx})" title="Rename Module" class="hover:text-amber-300"><i data-lucide="edit" class="w-3.5 h-3.5"></i></button>
                    <button onclick="removeModule(${modIdx})" title="Delete Module" class="hover:text-red-400"><i data-lucide="trash" class="w-3.5 h-3.5"></i></button>
                </div>
            `;
            modDiv.appendChild(modHeader);

            // Subtopics & Quiz nodes
            const listDiv = document.createElement('div');
            listDiv.className = 'p-3 bg-gray-50 space-y-2';

            mod.items.forEach((item, itemIdx) => {
                const nodeKey = item.type === 'quiz' ? `quiz-${modIdx}` : `subtopic-${modIdx}-${itemIdx}`;
                const isActive = activeNode === nodeKey;
                const activeClass = isActive ? 'sidebar-active font-semibold shadow-sm' : 'bg-white hover:bg-gray-100 text-gray-700';

                const itemBtn = document.createElement('button');
                itemBtn.className = `w-full text-left px-3 py-2 rounded-lg text-xs transition flex items-center justify-between gap-2 ${activeClass}`;
                
                let icon = 'file';
                if (item.type === 'video') icon = 'video';
                if (item.type === 'presentation') icon = 'presentation';
                if (item.type === 'quiz') icon = 'help-circle';

                itemBtn.innerHTML = `
                    <div onclick="selectNode('${nodeKey}')" class="flex items-center gap-2 flex-1 truncate py-0.5">
                        <i data-lucide="${icon}" class="w-3.5 h-3.5"></i>
                        <span class="truncate">${item.type === 'quiz' ? 'Module Quiz' : `${mod.sort_order}.${item.sort_order} ${item.title || 'Untitled Subtopic'}`}</span>
                    </div>
                    ${item.type !== 'quiz' ? `
                        <button onclick="removeSubtopic(${modIdx}, ${itemIdx})" class="text-gray-400 hover:text-red-500 pl-1"><i data-lucide="x" class="w-3 h-3"></i></button>
                    ` : ''}
                `;
                listDiv.appendChild(itemBtn);
            });

            // If no quiz yet, ensure one is added implicitly
            const hasQuiz = mod.items.some(i => i.type === 'quiz');
            if (!hasQuiz) {
                // Autocreate a quiz placeholder inside the model state for safety
                mod.items.push({
                    id: null,
                    title: `Module ${mod.sort_order} Assessment: Control Flow`,
                    type: 'quiz',
                    content: `Test your understanding of control flow and loops in programming.`,
                    quiz_questions_count: 5,
                    sort_order: mod.items.length + 1,
                    questions: []
                });
                // Rerender tree to show it
                setTimeout(renderTree, 0);
                return;
            }

            // Add Subtopic button
            const addSubBtn = document.createElement('button');
            addSubBtn.className = 'w-full py-1.5 border border-dashed border-gray-300 text-gray-500 hover:bg-white rounded-lg text-xs font-semibold transition flex items-center justify-center gap-1';
            addSubBtn.onclick = () => addNewSubtopic(modIdx);
            addSubBtn.innerHTML = `<span>+</span> Add New Subtopic`;
            listDiv.appendChild(addSubBtn);

            modDiv.appendChild(listDiv);
            modulesContainer.appendChild(modDiv);
        });

        lucide.createIcons();
    }

    // 3. Node Selection Handler
    function selectNode(key) {
        activeNode = key;
        
        // Update tree highlighting
        const allNodes = document.querySelectorAll('#treeContainer button, #treeModules button');
        // Course Info highlights
        const courseInfoNode = document.getElementById('node-course-info');
        if (key === 'course-info') {
            courseInfoNode.className = "w-full text-left px-4 py-3 rounded-xl font-medium text-sm transition sidebar-active shadow-sm flex items-center gap-2";
        } else {
            courseInfoNode.className = "w-full text-left px-4 py-3 rounded-xl font-medium text-sm transition hover:bg-gray-50 text-gray-700 flex items-center gap-2";
        }

        // Hide all panes
        document.querySelectorAll('.editor-pane').forEach(p => p.classList.add('hidden'));

        // Rerender tree to fix subtopic highlighting
        renderTree();

        if (key === 'course-info') {
            document.getElementById('editor-course-info').classList.remove('hidden');
        } else if (key.startsWith('subtopic-')) {
            const parts = key.split('-');
            const modIdx = parseInt(parts[1]);
            const lesIdx = parseInt(parts[2]);
            const subtopic = courseState.modules[modIdx].items[lesIdx];

            document.getElementById('editor-subtopic').classList.remove('hidden');

            // Bind values
            document.getElementById('subtopicTitleInput').value = subtopic.title;
            document.getElementById('youtubeUrlInput').value = subtopic.youtube_url || '';
            
            // YouTube preview
            updateYoutubePreview(subtopic.youtube_url);

            // Uploaded presentation box
            updatePresentationFileBox(subtopic);

        } else if (key.startsWith('quiz-')) {
            const parts = key.split('-');
            const modIdx = parseInt(parts[1]);
            const module = courseState.modules[modIdx];
            const quiz = module.items.find(i => i.type === 'quiz');

            document.getElementById('editor-quiz').classList.remove('hidden');

            // Set titles
            document.getElementById('quizModuleTitle').textContent = `Module ${module.sort_order} Quiz Builder`;
            document.getElementById('quizTitleInput').value = quiz.title;
            document.getElementById('quizDescriptionInput').value = quiz.content || '';
            document.getElementById('quizPoolCountInput').value = quiz.quiz_questions_count || 5;

            // Render Questions
            renderQuestionsList(quiz);
        }
    }

    // 4. State Update Bindings
    function updateCourseTitle(val) { courseState.title = val; }
    function updateCourseCategory(val) { courseState.category = val; }
    function updateCourseDesc(val) { courseState.description = val; }

    function updateSubtopicTitle(val) {
        if (activeNode.startsWith('subtopic-')) {
            const parts = activeNode.split('-');
            const modIdx = parseInt(parts[1]);
            const lesIdx = parseInt(parts[2]);
            courseState.modules[modIdx].items[lesIdx].title = val;
            
            // Debounce or instant tree title update
            const activeBtnSpan = document.querySelector(`.sidebar-active span`);
            if (activeBtnSpan) {
                const modNo = courseState.modules[modIdx].sort_order;
                const lesNo = courseState.modules[modIdx].items[lesIdx].sort_order;
                activeBtnSpan.textContent = `${modNo}.${lesNo} ${val || 'Untitled Subtopic'}`;
            }
        }
    }

    function updateYoutubeUrl(val) {
        if (activeNode.startsWith('subtopic-')) {
            const parts = activeNode.split('-');
            const modIdx = parseInt(parts[1]);
            const lesIdx = parseInt(parts[2]);
            courseState.modules[modIdx].items[lesIdx].youtube_url = val;
            updateYoutubePreview(val);
        }
    }

    function updateQuizTitle(val) {
        if (activeNode.startsWith('quiz-')) {
            const parts = activeNode.split('-');
            const modIdx = parseInt(parts[1]);
            const quiz = courseState.modules[modIdx].items.find(i => i.type === 'quiz');
            quiz.title = val;
        }
    }

    function updateQuizDesc(val) {
        if (activeNode.startsWith('quiz-')) {
            const parts = activeNode.split('-');
            const modIdx = parseInt(parts[1]);
            const quiz = courseState.modules[modIdx].items.find(i => i.type === 'quiz');
            quiz.content = val;
        }
    }

    function updateQuizPoolCount(val) {
        if (activeNode.startsWith('quiz-')) {
            const parts = activeNode.split('-');
            const modIdx = parseInt(parts[1]);
            const quiz = courseState.modules[modIdx].items.find(i => i.type === 'quiz');
            quiz.quiz_questions_count = parseInt(val) || 5;
            
            // Update Exam Rules text box
            updateExamRulesBanner(quiz.quiz_questions_count, quiz.questions.length);
        }
    }

    function updateExamRulesBanner(questionsToShow, poolSize) {
        document.getElementById('examRulesText').textContent = 
            `${questionsToShow} questions randomly generated from this ${poolSize}-question pool per student attempt`;
    }

    // 5. YouTube URL Parser
    function updateYoutubePreview(url) {
        const iframeContainer = document.getElementById('iframeContainer');
        const videoPlaceholder = document.getElementById('videoPlaceholder');
        const iframe = document.getElementById('youtubeIframe');

        if (!url) {
            iframeContainer.classList.add('hidden');
            videoPlaceholder.classList.remove('hidden');
            iframe.src = '';
            return;
        }

        let videoId = null;
        if (url.includes('watch?v=')) {
            videoId = url.split('watch?v=')[1].split('&')[0];
        } else if (url.includes('youtu.be/')) {
            videoId = url.split('youtu.be/')[1].split('?')[0];
        } else if (url.includes('/embed/')) {
            videoId = url.split('/embed/')[1].split('?')[0];
        }

        if (videoId) {
            iframe.src = `https://www.youtube.com/embed/${videoId}`;
            iframeContainer.classList.remove('hidden');
            videoPlaceholder.classList.add('hidden');
        } else {
            iframeContainer.classList.add('hidden');
            videoPlaceholder.classList.remove('hidden');
            iframe.src = '';
        }
    }

    // 6. Dynamic Adding and Deleting Structure Nodes
    function addNewModule() {
        const title = prompt("Enter Module Title:");
        if (!title) return;

        const nextOrder = courseState.modules.length + 1;
        courseState.modules.push({
            id: null,
            title: title,
            sort_order: nextOrder,
            items: []
        });

        renderTree();
        // Automatically select course info or the new module
        selectNode('course-info');
    }

    function editModuleName(modIdx) {
        const oldTitle = courseState.modules[modIdx].title;
        const title = prompt("Rename Module Title:", oldTitle);
        if (!title) return;

        courseState.modules[modIdx].title = title;
        renderTree();
    }

    function removeModule(modIdx) {
        if (confirm("Are you sure you want to delete this module and all its subtopics & quiz?")) {
            courseState.modules.splice(modIdx, 1);
            // Re-order modules sort order
            courseState.modules.forEach((mod, idx) => {
                mod.sort_order = idx + 1;
            });
            renderTree();
            selectNode('course-info');
        }
    }

    function addNewSubtopic(modIdx) {
        const title = prompt("Enter Subtopic Title:");
        if (!title) return;

        const module = courseState.modules[modIdx];
        const nextOrder = module.items.length + 1; // quizzes are also in items, let's adjust order later

        // Put subtopic BEFORE quiz
        const quizIdx = module.items.findIndex(i => i.type === 'quiz');
        const newSubtopic = {
            id: null,
            title: title,
            type: 'presentation',
            content: '',
            youtube_url: '',
            presentation_path: '',
            presentation_size: '',
            quiz_questions_count: 5,
            sort_order: nextOrder
        };

        if (quizIdx !== -1) {
            module.items.splice(quizIdx, 0, newSubtopic);
        } else {
            module.items.push(newSubtopic);
        }

        // Reorder sort_order for all items in the module
        module.items.forEach((item, idx) => {
            item.sort_order = idx + 1;
        });

        renderTree();
        
        // Find index of newly inserted subtopic
        const newIdx = module.items.indexOf(newSubtopic);
        selectNode(`subtopic-${modIdx}-${newIdx}`);
    }

    function removeSubtopic(modIdx, itemIdx) {
        if (confirm("Are you sure you want to remove this subtopic?")) {
            courseState.modules[modIdx].items.splice(itemIdx, 1);
            
            // Reorder remaining items
            courseState.modules[modIdx].items.forEach((item, idx) => {
                item.sort_order = idx + 1;
            });

            renderTree();
            selectNode('course-info');
        }
    }

    // 7. Drag-and-drop Presentation file upload logic
    function setupDragAndDrop() {
        const dropzone = document.getElementById('dropzone');

        window.addEventListener('dragenter', (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        window.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        window.addEventListener('dragleave', (e) => {
            e.preventDefault();
            if (e.target === window || e.target === document) {
                dropzone.classList.remove('dragover');
            }
        });

        dropzone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                uploadPresentationFile(files[0]);
            }
        });
    }

    function triggerFileInput() {
        document.getElementById('fileInput').click();
    }

    function handleFileSelect(event) {
        const files = event.target.files;
        if (files.length > 0) {
            uploadPresentationFile(files[0]);
        }
    }

    function uploadPresentationFile(file) {
        // Validate MIME type
        const extension = file.name.split('.').pop().toLowerCase();
        if (!['pdf', 'ppt', 'pptx'].includes(extension)) {
            alert('Invalid file format. Only PDF, PPT, and PPTX presentation files are accepted.');
            return;
        }

        const formData = new FormData();
        formData.append('file', file);

        // Show uploading progress spinner
        document.getElementById('uploadProgress').classList.remove('hidden');
        document.getElementById('uploadedFileBox').classList.add('hidden');

        fetch("{{ route('teacher.courses.upload') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('uploadProgress').classList.add('hidden');
            if (data.success) {
                if (activeNode.startsWith('subtopic-')) {
                    const parts = activeNode.split('-');
                    const modIdx = parseInt(parts[1]);
                    const lesIdx = parseInt(parts[2]);
                    const subtopic = courseState.modules[modIdx].items[lesIdx];

                    subtopic.presentation_path = data.path;
                    subtopic.presentation_size = data.size;
                    
                    updatePresentationFileBox(subtopic);
                }
            } else {
                alert(data.error || 'Failed to upload presentation file.');
            }
        })
        .catch(err => {
            document.getElementById('uploadProgress').classList.add('hidden');
            alert('An error occurred during file upload.');
        });
    }

    function updatePresentationFileBox(subtopic) {
        const fileBox = document.getElementById('uploadedFileBox');
        if (subtopic.presentation_path) {
            document.getElementById('uploadedFileName').textContent = subtopic.presentation_path.split('/').pop();
            document.getElementById('uploadedFileSize').textContent = subtopic.presentation_size || 'N/A';
            fileBox.classList.remove('hidden');
        } else {
            fileBox.classList.add('hidden');
        }
    }

    function removeUploadedFile() {
        if (activeNode.startsWith('subtopic-')) {
            const parts = activeNode.split('-');
            const modIdx = parseInt(parts[1]);
            const lesIdx = parseInt(parts[2]);
            const subtopic = courseState.modules[modIdx].items[lesIdx];

            subtopic.presentation_path = '';
            subtopic.presentation_size = '';
            updatePresentationFileBox(subtopic);
        }
    }

    // 8. Quiz Builder Questions Pool render engine
    function renderQuestionsList(quiz) {
        const container = document.getElementById('questionsContainer');
        container.innerHTML = '';

        const badge = document.getElementById('questionsCountBadge');
        badge.textContent = `${quiz.questions.length} Questions`;

        // Update exam rules details block
        updateExamRulesBanner(quiz.quiz_questions_count, quiz.questions.length);

        quiz.questions.forEach((q, qIdx) => {
            const qCard = document.createElement('div');
            qCard.className = 'border border-gray-200 rounded-xl p-5 bg-white space-y-4 shadow-sm relative';
            qCard.innerHTML = `
                <!-- Header -->
                <div class="flex justify-between items-center pb-2 border-b">
                    <span class="text-xs font-bold text-gray-500">Question ${qIdx + 1} of ${quiz.questions.length}</span>
                    <div class="flex items-center gap-3">
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2 py-0.5 rounded font-semibold uppercase">Multiple Choice</span>
                        <button onclick="removeQuestion(${qIdx})" class="text-gray-400 hover:text-red-500"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </div>
                </div>

                <!-- Question Prompt -->
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5">Question Text</label>
                    <input type="text" value="${q.question_text || ''}" oninput="updateQuestionPrompt(${qIdx}, this.value)" placeholder="Enter the question prompt here..." class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ffca28] transition">
                </div>

                <!-- Answer Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${[0, 1, 2, 3].map(optIdx => {
                        const opt = q.options[optIdx] || { id: null, option_text: '', is_correct: false };
                        // ensure options array size is correct in state
                        if (!q.options[optIdx]) q.options[optIdx] = opt;
                        
                        const isCorrect = opt.is_correct;
                        const optionClass = isCorrect 
                            ? 'border-green-500 bg-green-50/50' 
                            : 'border-gray-200 hover:border-gray-300';
                        
                        return `
                        <div id="q-${qIdx}-opt-${optIdx}" class="border rounded-xl p-3.5 flex items-center gap-3 transition ${optionClass}">
                            <input type="radio" name="q-${qIdx}-correct" ${isCorrect ? 'checked' : ''} onclick="markOptionCorrect(${qIdx}, ${optIdx})" class="w-4 h-4 text-green-600 focus:ring-green-500">
                            <div class="flex-1 flex items-center justify-between gap-2">
                                <input type="text" value="${opt.option_text || ''}" oninput="updateOptionText(${qIdx}, ${optIdx}, this.value)" placeholder="Option ${String.fromCharCode(65 + optIdx)}" class="w-full text-xs bg-transparent border-none outline-none font-semibold text-gray-700">
                                ${isCorrect ? '<i data-lucide="check" class="w-4 h-4 text-green-600 flex-shrink-0"></i>' : ''}
                            </div>
                        </div>
                        `;
                    }).join('')}
                </div>
            `;
            container.appendChild(qCard);
        });

        lucide.createIcons();
    }

    function addNewQuestion() {
        if (activeNode.startsWith('quiz-')) {
            const parts = activeNode.split('-');
            const modIdx = parseInt(parts[1]);
            const quiz = courseState.modules[modIdx].items.find(i => i.type === 'quiz');

            quiz.questions.push({
                id: null,
                question_text: '',
                question_type: 'multiple_choice',
                options: [
                    { id: null, option_text: '', is_correct: true }, // Default first option correct
                    { id: null, option_text: '', is_correct: false },
                    { id: null, option_text: '', is_correct: false },
                    { id: null, option_text: '', is_correct: false }
                ]
            });

            renderQuestionsList(quiz);
        }
    }

    function removeQuestion(qIdx) {
        if (activeNode.startsWith('quiz-')) {
            const parts = activeNode.split('-');
            const modIdx = parseInt(parts[1]);
            const quiz = courseState.modules[modIdx].items.find(i => i.type === 'quiz');

            quiz.questions.splice(qIdx, 1);
            renderQuestionsList(quiz);
        }
    }

    function updateQuestionPrompt(qIdx, val) {
        if (activeNode.startsWith('quiz-')) {
            const parts = activeNode.split('-');
            const modIdx = parseInt(parts[1]);
            const quiz = courseState.modules[modIdx].items.find(i => i.type === 'quiz');
            quiz.questions[qIdx].question_text = val;
        }
    }

    function updateOptionText(qIdx, optIdx, val) {
        if (activeNode.startsWith('quiz-')) {
            const parts = activeNode.split('-');
            const modIdx = parseInt(parts[1]);
            const quiz = courseState.modules[modIdx].items.find(i => i.type === 'quiz');
            quiz.questions[qIdx].options[optIdx].option_text = val;
        }
    }

    function markOptionCorrect(qIdx, optIdx) {
        if (activeNode.startsWith('quiz-')) {
            const parts = activeNode.split('-');
            const modIdx = parseInt(parts[1]);
            const quiz = courseState.modules[modIdx].items.find(i => i.type === 'quiz');

            quiz.questions[qIdx].options.forEach((opt, idx) => {
                opt.is_correct = (idx === optIdx);
            });

            // Rerender questions list to update option styling instantly
            renderQuestionsList(quiz);
        }
    }

    // 9. Save Course AJAX Submission handler
    function saveCourse(status) {
        // Validate Course state
        if (!courseState.title.trim()) {
            alert('Please enter a Course Title in the "Course Info" section.');
            selectNode('course-info');
            return;
        }

        if (courseState.modules.length === 0) {
            alert('Please add at least one module to the course structure.');
            return;
        }

        // Deep validation for subtopics and quizzes before submitting for approval
        if (status === 'pending') {
            let errorMsg = null;
            let errorNode = null;

            for (let mIdx = 0; mIdx < courseState.modules.length; mIdx++) {
                const mod = courseState.modules[mIdx];
                if (mod.items.length <= 1) { // 1 means only quiz is present
                    errorMsg = `Module "${mod.title}" needs at least one subtopic lesson.`;
                    break;
                }

                for (let iIdx = 0; iIdx < mod.items.length; iIdx++) {
                    const item = mod.items[iIdx];
                    if (item.type === 'presentation') {
                        if (!item.title.trim()) {
                            errorMsg = `A subtopic in Module ${mod.sort_order} is missing a title.`;
                            errorNode = `subtopic-${mIdx}-${iIdx}`;
                            break;
                        }
                        if (!item.presentation_path) {
                            errorMsg = `Please upload a presentation file (PPT/PDF) for subtopic "${item.title}".`;
                            errorNode = `subtopic-${mIdx}-${iIdx}`;
                            break;
                        }
                    } else if (item.type === 'quiz') {
                        if (!item.title.trim()) {
                            errorMsg = `The quiz in Module ${mod.sort_order} is missing a title.`;
                            errorNode = `quiz-${mIdx}`;
                            break;
                        }
                        if (item.questions.length === 0) {
                            errorMsg = `Please add at least one question to the quiz "${item.title}".`;
                            errorNode = `quiz-${mIdx}`;
                            break;
                        }
                        // Validate question texts and option texts
                        for (let qIdx = 0; qIdx < item.questions.length; qIdx++) {
                            const q = item.questions[qIdx];
                            if (!q.question_text.trim()) {
                                errorMsg = `Question ${qIdx+1} in quiz "${item.title}" is missing the question prompt.`;
                                errorNode = `quiz-${mIdx}`;
                                break;
                            }
                            let hasFilledOptions = true;
                            q.options.forEach((opt, oIdx) => {
                                if (!opt.option_text.trim()) {
                                    hasFilledOptions = false;
                                }
                            });
                            if (!hasFilledOptions) {
                                errorMsg = `All 4 options for Question ${qIdx+1} in quiz "${item.title}" must have text.`;
                                errorNode = `quiz-${mIdx}`;
                                break;
                            }
                        }
                    }
                }
                if (errorMsg) break;
            }

            if (errorMsg) {
                alert(errorMsg);
                if (errorNode) selectNode(errorNode);
                return;
            }
        }

        // Apply status
        courseState.status = status;

        // Prepare files array for backend sync
        courseState.modules.forEach(mod => {
            mod.items.forEach(item => {
                if (item.presentation_path) {
                    item.files = [{
                        path: item.presentation_path,
                        filename: item.presentation_path.split('/').pop(),
                        type: item.presentation_path.split('.').pop().toLowerCase()
                    }];
                } else {
                    item.files = [];
                }
            });
        });

        // Display saving status
        const statusText = document.getElementById('saveStatusText');
        statusText.innerHTML = `
            <div class="flex items-center gap-2 text-blue-600">
                <div class="animate-spin rounded-full h-3 w-3 border-2 border-blue-600 border-t-transparent"></div>
                <span>Saving course structure...</span>
            </div>
        `;

        fetch("{{ route('teacher.courses.store') }}", {
            method: 'POST',
            body: JSON.stringify(courseState),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                statusText.innerHTML = `<span class="text-green-600">Saved successfully! Redirecting...</span>`;
                window.location.href = data.redirect;
            } else {
                statusText.innerHTML = `<span class="text-red-500">Error saving course</span>`;
                alert(data.error || 'Failed to save course.');
            }
        })
        .catch(err => {
            statusText.innerHTML = `<span class="text-red-500">Network Error</span>`;
            alert('An error occurred while saving the course.');
        });
    }
</script>

</body>
</html>
