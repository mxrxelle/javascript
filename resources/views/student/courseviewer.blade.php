<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Viewer | Certly</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #f8fafc; overflow: hidden; height: 100vh; color: #1e293b; }
        
        /* Topbar styling */
        .topbar {
            height: 80px;
            background: #002244;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 10;
            position: relative;
        }
        .topbar-left { display: flex; align-items: center; gap: 24px; }
        .logo-container img { height: 40px; width: auto; object-fit: contain; }
        .divider { height: 24px; width: 1px; background: rgba(255,255,255,0.2); }
        .course-title { font-size: 20px; font-weight: 700; color: #f1f5f9; letter-spacing: -0.025em; }
        .dashboard-link {
            color: #94a3b8;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dashboard-link:hover { color: #f1f5f9; }

        /* Main layout split */
        .main-layout { display: flex; height: calc(100vh - 80px); }
        
        /* Sidebar layout */
        .sidebar {
            width: 400px;
            background: white;
            border-right: 1px solid #e2e8f0;
            overflow-y: auto;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .sidebar-title {
            font-size: 20px;
            color: #0f172a;
            font-weight: 800;
            padding-left: 8px;
            border-left: 4px solid #0056b3;
            margin-bottom: 8px;
        }
        .module-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            transition: border-color 0.2s;
        }
        .module-card.locked {
            opacity: 0.65;
        }
        .module-header {
            width: 100%;
            border: none;
            background: #f1f5f9;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            cursor: pointer;
            text-align: left;
            transition: background 0.2s;
        }
        .module-header:hover { background: #e2e8f0; }
        .module-card.locked .module-header { cursor: not-allowed; }
        .arrow { font-size: 18px; color: #64748b; transition: transform 0.2s ease; }
        .module-card.open .arrow { transform: rotate(90deg); }
        
        .topics {
            display: none;
            flex-direction: column;
            border-top: 1px solid #e2e8f0;
            background: #fff;
        }
        .module-card.open .topics { display: flex; }
        
        /* Sidebar items */
        .topic {
            padding: 14px 20px;
            font-size: 14px;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s;
            position: relative;
        }
        .topic:last-child { border-bottom: none; }
        .topic:hover { background: #f8fafc; color: #0f172a; }
        .topic.active { background: #eff6ff; color: #1d4ed8; font-weight: 600; border-left: 4px solid #2563eb; }
        .topic.locked { color: #94a3b8; cursor: not-allowed; background: #fafafa; }
        .topic.locked:hover { background: #fafafa; color: #94a3b8; }
        .topic-title { flex: 1; line-height: 1.4; }
        
        .status-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            font-size: 16px;
        }
        .check-circle {
            background: #10b981;
            color: white;
            border-radius: 50%;
            font-size: 11px;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* Content panel styling */
        .content { flex: 1; overflow-y: auto; padding: 40px; }
        .content-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.02);
            border: 1px solid #e2e8f0;
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .lesson-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 32px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 20px;
        }
        .lesson-icon-badge {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 10px;
            border-radius: 12px;
            font-size: 24px;
            font-weight: 600;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .lesson-header h1 { font-size: 28px; color: #0f172a; font-weight: 800; letter-spacing: -0.02em; }
        .lesson-type-badge {
            background: #f1f5f9;
            color: #64748b;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Main views dynamically inserted */
        .view-container { flex: 1; margin-bottom: 32px; }
        
        /* 60-40 split layout for Video */
        .video-split-layout { display: flex; gap: 32px; width: 100%; }
        .video-column-left { flex: 6; display: flex; flex-direction: column; gap: 20px; }
        .video-column-right {
            flex: 4;
            background: #f8fafc;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .video-description-title { font-size: 18px; font-weight: 700; color: #1e293b; }
        .video-description-text { font-size: 15px; color: #475569; line-height: 1.6; }

        .video-player-container {
            width: 100%;
            aspect-ratio: 16/9;
            background: #000;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .video-player-container iframe { width: 100%; height: 100%; border: none; }
        
        /* PDF Layout */
        .pdf-viewer-container {
            width: 100%;
            height: 650px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .pdf-viewer-container iframe { width: 100%; height: 100%; border: none; }

        /* Quiz Layout styling */
        .quiz-container {
            background: #f8fafc;
            border-radius: 12px;
            padding: 32px;
            border: 1px solid #e2e8f0;
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
        }
        .quiz-settings-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px dashed #cbd5e1;
        }
        .quiz-toggle-mode {
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
        }
        .quiz-toggle-mode:hover { background: #f1f5f9; border-color: #94a3b8; }
        .quiz-progress-text { font-size: 14px; font-weight: 600; color: #64748b; }
        
        .quiz-question-card {
            background: white;
            border-radius: 8px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .quiz-question-text {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 18px;
            line-height: 1.4;
        }
        .quiz-options-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .quiz-option-label {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 18px;
            font-size: 15px;
            font-weight: 500;
            color: #334155;
            cursor: pointer;
            transition: all 0.2s;
        }
        .quiz-option-label:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }
        .quiz-option-label.selected {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #1d4ed8;
        }
        .quiz-option-label input[type="radio"], 
        .quiz-option-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #2563eb;
        }

        /* Quiz navigation buttons */
        .quiz-nav-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
        }
        .quiz-btn {
            height: 48px;
            padding: 0 24px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        .quiz-btn-secondary { background: #e2e8f0; color: #475569; }
        .quiz-btn-secondary:hover { background: #cbd5e1; }
        .quiz-btn-primary { background: #2563eb; color: white; }
        .quiz-btn-primary:hover { background: #1d4ed8; }
        .quiz-btn-success { background: #10b981; color: white; }
        .quiz-btn-success:hover { background: #059669; }

        /* Quiz Feedback overlays */
        .quiz-result-box {
            text-align: center;
            background: white;
            border-radius: 12px;
            padding: 40px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            max-width: 500px;
            margin: 0 auto;
        }
        .quiz-result-title { font-size: 24px; font-weight: 800; margin-bottom: 8px; }
        .quiz-result-title.pass { color: #10b981; }
        .quiz-result-title.fail { color: #ef4444; }
        .quiz-result-score { font-size: 48px; font-weight: 800; color: #0f172a; margin: 16px 0; }
        .quiz-result-desc { font-size: 15px; color: #475569; margin-bottom: 24px; line-height: 1.5; }

        /* Feedback display indicators */
        .quiz-option-label.feedback-correct {
            background: #ecfdf5;
            border-color: #10b981;
            color: #065f46;
        }
        .quiz-option-label.feedback-incorrect {
            background: #fef2f2;
            border-color: #f87171;
            color: #991b1b;
        }

        /* Bottom navigation controls */
        .bottom-nav {
            border-top: 1px solid #e2e8f0;
            padding-top: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }
        .nav-btn {
            height: 52px;
            padding: 0 28px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .prev-btn { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .prev-btn:hover { background: #e2e8f0; }
        .next-btn { background: #002244; color: white; }
        .next-btn:hover { background: #003366; }
        .complete-btn { background: #f59e0b; color: white; }
        .complete-btn:hover { background: #d97706; }
        .complete-btn:disabled { background: #cbd5e1; color: #94a3b8; cursor: not-allowed; }
    </style>
</head>
<body>

@php
    // Server-side data processing and dynamic split matching
    $modulesList = [];
    $lessonsList = [];
    $globalLessonIndex = 0;

    foreach ($course->modules as $mIndex => $module) {
        $moduleLessons = [];
        $presIndex = 1;
        $videoIndex = 1;

        // Sort module lessons strictly: Presentations first, then Videos, then Quiz
        $dbLessons = $module->lessons->sort(function ($a, $b) {
            $typeOrder = [
                'pdf' => 1,
                'presentation' => 1,
                'reading' => 1,
                'video' => 2,
                'quiz' => 3,
            ];
            $aType = $typeOrder[$a->type] ?? 4;
            $bType = $typeOrder[$b->type] ?? 4;

            if ($aType !== $bType) {
                return $aType <=> $bType;
            }

            return ($a->sort_order ?? $a->order ?? 0) <=> ($b->sort_order ?? $b->order ?? 0);
        })->values();

        // Update database order to be correct in DB
        foreach ($dbLessons as $idx => $sortedLesson) {
            $newOrder = $idx + 1;
            if ($sortedLesson->sort_order !== $newOrder || $sortedLesson->order !== $newOrder) {
                $sortedLesson->update([
                    'sort_order' => $newOrder,
                    'order' => $newOrder,
                ]);
            }
        }

        foreach ($dbLessons as $lesson) {
            $hasPdf = !empty($lesson->presentation_path) || !empty($lesson->file_path);
            $hasVideo = !empty($lesson->youtube_url) || !empty($lesson->video_url);

            if ($lesson->type === 'quiz') {
                $questions = [];
                $dbQs = \App\Models\QuizQuestion::with('choices')->where('lesson_id', $lesson->id)->get();
                foreach ($dbQs as $q) {
                    $choices = [];
                    foreach ($q->choices as $c) {
                        $choices[] = [
                            'id' => $c->id,
                            'text' => $c->choice_text,
                        ];
                    }
                    $questions[] = [
                        'id' => $q->id,
                        'question' => $q->question,
                        'type' => $q->type,
                        'choices' => $choices
                    ];
                }

                $moduleLessons[] = [
                    'id' => $lesson->id,
                    'module_id' => $module->id,
                    'title' => $lesson->title,
                    'sidebar_label' => "❓ Quiz: " . $lesson->title,
                    'type' => 'quiz',
                    'content' => $lesson->content,
                    'youtube_url' => null,
                    'files' => [],
                    'questions'            => $questions,
                    'quiz_questions_count'  => $lesson->quiz_questions_count ?? 5,
                    'icon' => '❓',
                ];
            } 
            elseif ($lesson->type === 'video') {
                $videoUrl = $lesson->video_url ?? $lesson->youtube_url;
                if (!$videoUrl) {
                    foreach ($lesson->files as $f) {
                        $fileType = strtolower($f->type ?? $f->file_type ?? '');
                        if (in_array($fileType, ['mp4', 'webm', 'ogg', 'video'])) {
                            $videoUrl = asset('storage/' . ($f->path ?? $f->file_path));
                            break;
                        }
                    }
                }

                $moduleLessons[] = [
                    'id' => $lesson->id,
                    'module_id' => $module->id,
                    'title' => $lesson->title,
                    'sidebar_label' => "🎬 Video " . $videoIndex . ": " . $lesson->title,
                    'type' => 'video',
                    'content' => $lesson->content,
                    'youtube_url' => $videoUrl,
                    'files' => [],
                    'questions' => [],
                    'icon' => '🎬',
                ];
                $videoIndex++;
            } 
            elseif (in_array($lesson->type, ['pdf', 'presentation', 'reading'])) {
                // If it has presentation or doesn't have video URL, create presentation node
                if ($hasPdf || !$hasVideo) {
                    $files = [];
                    foreach ($lesson->files as $f) {
                        $files[] = [
                            'path' => asset('storage/' . ($f->path ?? $f->file_path)),
                            'type' => $f->type ?? $f->file_type,
                            'name' => $f->filename ?? $f->file_name,
                        ];
                    }
                    $filePath = $lesson->file_path ?? $lesson->presentation_path;
                    if ($filePath && empty($files)) {
                        $files[] = [
                            'path' => asset('storage/' . $filePath),
                            'type' => 'pdf',
                            'name' => basename($filePath),
                        ];
                    }

                    $moduleLessons[] = [
                        'id' => $lesson->id,
                        'module_id' => $module->id,
                        'title' => $lesson->title,
                        'sidebar_label' => "<strong>" . ($mIndex + 1) . "." . $presIndex . "</strong> " . $lesson->title,
                        'type' => 'presentation',
                        'content' => $lesson->content,
                        'youtube_url' => null,
                        'files' => $files,
                        'questions' => [],
                        'icon' => '▣',
                    ];
                    $presIndex++;
                }

                // If it also contains video URL, create a split video node
                if ($hasVideo) {
                    $videoUrl = $lesson->video_url ?? $lesson->youtube_url;

                    $moduleLessons[] = [
                        'id' => $lesson->id,
                        'module_id' => $module->id,
                        'title' => $lesson->title,
                        'sidebar_label' => "🎬 Video " . $videoIndex . ": " . $lesson->title,
                        'type' => 'video',
                        'content' => $lesson->content,
                        'youtube_url' => $videoUrl,
                        'files' => [],
                        'questions' => [],
                        'icon' => '🎬',
                    ];
                    $videoIndex++;
                }
            }
        }

        // Re-sort current module lessons lists
        usort($moduleLessons, function($a, $b) {
            $typeOrder = [
                'presentation' => 1,
                'video' => 2,
                'quiz' => 3,
            ];
            return $typeOrder[$a['type']] <=> $typeOrder[$b['type']];
        });

        // Hydrate global index
        foreach ($moduleLessons as &$ml) {
            $ml['global_index'] = $globalLessonIndex;
            $lessonsList[] = $ml;
            $globalLessonIndex++;
        }
        unset($ml);

        $modulesList[] = [
            'id' => $module->id,
            'title' => $module->title,
            'lessons' => $moduleLessons,
        ];
    }
@endphp

<div class="topbar">
    <div class="topbar-left">
        <div class="logo-container">
            <img src="{{ asset('images/certly-logo.png') }}" alt="Certly Logo">
        </div>
        <div class="divider"></div>
        <div class="course-title">{{ $course->title }}</div>
    </div>

    <a href="{{ route('student.dashboard') }}" class="dashboard-link">
        <span style="font-size: 20px;">←</span> Back to Dashboard
    </a>
</div>

<div class="main-layout">
    <div class="sidebar">
        <div class="sidebar-title">Course Content</div>

        @forelse ($modulesList as $mIndex => $modData)
            <div class="module-card open" id="module-{{ $modData['id'] }}">
                <button class="module-header" onclick="toggleModule({{ $modData['id'] }})">
                    <span>{{ $mIndex + 1 }}. {{ $modData['title'] }}</span>
                    <span class="arrow">›</span>
                </button>

                <div class="topics" id="topics-{{ $modData['id'] }}">
                    @foreach ($modData['lessons'] as $les)
                        <div class="topic" 
                             id="topic-{{ $les['id'] }}-{{ $les['type'] }}" 
                             data-lesson-id="{{ $les['id'] }}"
                             data-lesson-type="{{ $les['type'] }}"
                             data-module-id="{{ $les['module_id'] }}"
                             data-global-index="{{ $les['global_index'] }}"
                             onclick="selectTopic({{ $les['global_index'] }})">
                            <span class="status-icon" id="status-icon-{{ $les['id'] }}-{{ $les['type'] }}">🔒</span>
                            <span class="topic-title" id="topic-title-{{ $les['id'] }}-{{ $les['type'] }}">
                                {!! $les['sidebar_label'] !!}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p style="padding: 16px; color: #64748b; font-size: 14px;">No content available yet.</p>
        @endforelse
    </div>

    <div class="content">
        <div class="content-card" id="lessonContainer" style="display: none;">
            <div class="lesson-header">
                <div class="lesson-icon-badge" id="badgeIcon">▣</div>
                <div>
                    <h1 id="lessonMainTitle">Lesson Title</h1>
                    <span class="lesson-type-badge" id="badgeType">Presentation</span>
                </div>
            </div>

            <div class="view-container" id="lessonContentView">
                <!-- Dynamically filled by javascript -->
            </div>

            <div class="bottom-nav">
                <button class="nav-btn prev-btn" id="prevBtn" onclick="goPrevious()">
                    <span>‹</span> Previous
                </button>
                <button class="nav-btn complete-btn" id="completeBtn" onclick="triggerComplete()">
                    Complete & Continue <span>✓</span>
                </button>
                <button class="nav-btn next-btn" id="nextBtn" onclick="goNext()">
                    Next <span>›</span>
                </button>
            </div>
        </div>
        
        <div class="content-card" id="emptyContainer" style="display: flex; align-items: center; justify-content: center; text-align: center;">
            <div>
                <span style="font-size: 64px; display: block; margin-bottom: 16px;">🎓</span>
                <h2 style="font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Welcome to the Course!</h2>
                <p style="color: #64748b; font-size: 15px;">Please select an unlocked lesson from the left panel to begin your learning.</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Embedded states from server
    const lessons = @json($lessonsList);
    const modules = @json($modulesList);
    let completedLessonIds = @json($completedLessonIds).map(id => Number(id));
    
    // Core routes config
    const completeUrl = "{{ route('student.courseviewer.completeLesson', $course->id) }}";
    const submitQuizUrl = "{{ route('student.courseviewer.submitQuiz', $course->id) }}";
    const csrfToken = "{{ csrf_token() }}";

    // Application state
    let activeIndex = -1;
    let quizStates = {}; // Stores quiz results and states locally: { lessonId: { currentQuestionIndex: 0, answers: {}, result: null } }

    // ── Notification helpers (replaces native alert / confirm) ──────────────
    function showToast(message, type = 'error') {
        const colors = type === 'error'
            ? { bg: '#fee2e2', border: '#fca5a5', text: '#991b1b', icon: '⚠️' }
            : { bg: '#d1fae5', border: '#6ee7b7',  text: '#065f46', icon: '✓'  };
        const toast = document.createElement('div');
        toast.style.cssText = [
            'position:fixed;bottom:24px;right:24px;z-index:9999',
            `background:${colors.bg};border:1px solid ${colors.border};color:${colors.text}`,
            'padding:14px 20px;border-radius:12px;font-size:14px;font-weight:600',
            'box-shadow:0 8px 24px rgba(0,0,0,0.15);display:flex;align-items:center;gap:10px;max-width:380px'
        ].join(';');
        toast.innerHTML = `<span style="font-size:18px;">${colors.icon}</span><span>${message}</span>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.transition = 'opacity 0.3s ease';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 350);
        }, 4000);
    }

    function showQuizError(message) {
        const existing = document.getElementById('quiz-inline-error');
        if (existing) existing.remove();
        const container = document.querySelector('.quiz-container');
        if (!container) return;
        const banner = document.createElement('div');
        banner.id = 'quiz-inline-error';
        banner.style.cssText = [
            'background:#fee2e2;border:1px solid #fca5a5;color:#991b1b',
            'padding:12px 16px;border-radius:8px;font-size:14px;font-weight:600',
            'display:flex;align-items:center;gap:8px;margin-bottom:16px'
        ].join(';');
        banner.innerHTML = `<span style="font-size:16px;">⚠️</span><span>${message}</span>`;
        const navRow = container.querySelector('.quiz-nav-row');
        if (navRow) container.insertBefore(banner, navRow);
        else container.appendChild(banner);
        banner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    // ────────────────────────────────────────────────────────────────────────

    document.addEventListener("DOMContentLoaded", () => {
        recalculateLocks();
        // If there are lessons, select the first unlocked lesson automatically
        const firstUnlockedIndex = lessons.findIndex(l => !isLessonLocked(l.id, l.type));
        if (firstUnlockedIndex !== -1) {
            selectTopic(firstUnlockedIndex);
        }
    });

    function toggleModule(moduleId) {
        const card = document.getElementById(`module-${moduleId}`);
        if (card) {
            card.classList.toggle("open");
        }
    }

    // Progression Engine
    function isLessonLocked(lessonId, type) {
        const lesson = lessons.find(l => l.id === lessonId && l.type === type);
        if (!lesson) return true;

        const currentMod = modules.find(m => m.id === lesson.module_id);
        if (!currentMod) return true;

        const currentModIndex = modules.findIndex(m => m.id === lesson.module_id);

        // 1. Module Lock validation: Next module fully locked until previous module's quiz is completed
        for (let i = 0; i < currentModIndex; i++) {
            const prevMod = modules[i];
            const prevModQuizIds = prevMod.lessons.filter(l => l.type === 'quiz').map(l => l.id);
            // If the previous module has a quiz, it must be completed
            if (prevModQuizIds.length > 0) {
                const quizCompleted = prevModQuizIds.every(qId => completedLessonIds.includes(Number(qId)));
                if (!quizCompleted) {
                    return true;
                }
            } else {
                // If previous module has no quiz, all lessons must be completed to unlock the next module
                const allCompleted = prevMod.lessons.every(l => completedLessonIds.includes(Number(l.id)));
                if (!allCompleted) {
                    return true;
                }
            }
        }

        // 2. Intra-module progression locks
        // Videos logic: Accessible as long as the student has started the module
        if (lesson.type === 'video') {
            return false; // Free access within unlocked module
        }

        // Quiz logic: Locked until all presentations are completed
        if (lesson.type === 'quiz') {
            const presentationIds = currentMod.lessons.filter(l => l.type === 'presentation').map(l => l.id);
            const allPresentationsCompleted = presentationIds.every(pId => completedLessonIds.includes(Number(pId)));
            return !allPresentationsCompleted;
        }

        // Presentations logic: Sequential locking within module (1.1 -> 1.2 -> 1.3)
        if (lesson.type === 'presentation') {
            const presentationIds = currentMod.lessons.filter(l => l.type === 'presentation').map(l => l.id);
            const presIndex = presentationIds.indexOf(lesson.id);
            if (presIndex > 0) {
                const prevPresId = presentationIds[presIndex - 1];
                return !completedLessonIds.includes(Number(prevPresId));
            }
            return false; // The first presentation is unlocked
        }

        return false;
    }

    function recalculateLocks() {
        lessons.forEach((lesson, index) => {
            const topicEl = document.getElementById(`topic-${lesson.id}-${lesson.type}`);
            if (!topicEl) return;

            const isLocked = isLessonLocked(lesson.id, lesson.type);
            const isCompleted = completedLessonIds.includes(Number(lesson.id));

            // Update DOM attributes and classes
            if (isLocked) {
                topicEl.classList.add("locked");
                topicEl.style.pointerEvents = "none";
                document.getElementById(`status-icon-${lesson.id}-${lesson.type}`).textContent = "🔒";
            } else {
                topicEl.classList.remove("locked");
                topicEl.style.pointerEvents = "auto";
                
                if (isCompleted) {
                    document.getElementById(`status-icon-${lesson.id}-${lesson.type}`).innerHTML = '<span style="font-size: 16px;">✅</span>';
                } else {
                    document.getElementById(`status-icon-${lesson.id}-${lesson.type}`).textContent = lesson.icon;
                }
            }
        });
    }

    // Content renderer
    function selectTopic(index) {
        if (index < 0 || index >= lessons.length) return;
        
        const lesson = lessons[index];
        if (isLessonLocked(lesson.id, lesson.type)) return;

        activeIndex = index;
        
        // Hide empty state, show content container
        document.getElementById("emptyContainer").style.display = "none";
        const container = document.getElementById("lessonContainer");
        container.style.display = "flex";

        // Update headers
        document.getElementById("lessonMainTitle").textContent = lesson.title;
        const badgeIcon = document.getElementById("badgeIcon");
        const badgeType = document.getElementById("badgeType");
        badgeIcon.textContent = lesson.icon;
        
        // Map badge labels
        if (lesson.type === 'video') badgeType.textContent = "Video Lesson";
        else if (lesson.type === 'quiz') badgeType.textContent = "Evaluation Quiz";
        else badgeType.textContent = "Presentation / PDF";

        // Update active topic class
        document.querySelectorAll(".topic").forEach(el => el.classList.remove("active"));
        const activeEl = document.getElementById(`topic-${lesson.id}-${lesson.type}`);
        if (activeEl) {
            activeEl.classList.add("active");
            // Open parent module
            const parentModule = activeEl.closest(".module-card");
            if (parentModule) parentModule.classList.add("open");
        }

        // Render main body layout per type
        renderLessonContent(lesson);

        // Update bottom buttons states
        updateBottomControls();
    }

    function renderLessonContent(lesson) {
        const view = document.getElementById("lessonContentView");
        view.innerHTML = "";

        if (lesson.type === 'video') {
            // Video split screen 60/40
            const embedUrl = getYoutubeEmbedUrl(lesson.youtube_url);
            const videoHtml = embedUrl 
                ? `<iframe src="${embedUrl}" allowfullscreen></iframe>`
                : `<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#94a3b8;font-size:48px;">🎬</div>`;

            view.innerHTML = `
                <div class="video-split-layout">
                    <div class="video-column-left">
                        <div class="video-player-container">
                            ${videoHtml}
                        </div>
                    </div>
                    <div class="video-column-right">
                        <div class="video-description-title">About this lesson</div>
                        <div class="video-description-text">
                            ${lesson.content || "No extra notes for this video lesson."}
                        </div>
                    </div>
                </div>
            `;
        } 
        else if (['pdf', 'presentation', 'reading'].includes(lesson.type)) {
            // Presentation full width
            const pdfFile = lesson.files.find(f => f.type === 'pdf' || f.name.endsWith('.pdf'));
            let viewerHtml = "";

            if (pdfFile) {
                viewerHtml = `
                    <div class="pdf-viewer-container">
                        <iframe src="${pdfFile.path}"></iframe>
                    </div>
                `;
            } else if (lesson.content) {
                viewerHtml = `
                    <div style="background: white; border: 1px solid #e2e8f0; padding: 32px; border-radius: 12px; font-size:16px; line-height:1.7; color:#334155;">
                        ${lesson.content}
                    </div>
                `;
            } else {
                viewerHtml = `
                    <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 48px; text-align: center; color: #64748b;">
                        No presentation files or content available for this lesson.
                    </div>
                `;
            }

            view.innerHTML = `
                <div style="width: 100%;">
                    ${viewerHtml}
                </div>
            `;
        } 
        else if (lesson.type === 'quiz') {
            // Quiz renderer
            renderQuiz(lesson);
        }
    }

    // Quiz dynamic handler
    function renderQuiz(lesson) {
        const view = document.getElementById("lessonContentView");
        
        // Initialize state if not present
        if (!quizStates[lesson.id]) {
            // Fisher-Yates shuffle the full question pool, then slice to quiz_questions_count
            const pool = [...lesson.questions];
            for (let i = pool.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [pool[i], pool[j]] = [pool[j], pool[i]];
            }
            const selectCount = lesson.quiz_questions_count || pool.length;
            const selected   = pool.slice(0, Math.min(selectCount, pool.length));
            quizStates[lesson.id] = {
                currentQuestionIndex: 0,
                answers:             {},
                result:              null,
                selectedQuestions:   selected
            };
        }

        const state = quizStates[lesson.id];

        if (state.result) {
            // If already submitted and graded, render the graded review screen
            renderQuizResult(lesson, state.result);
            return;
        }

        if (state.selectedQuestions.length === 0) {
            view.innerHTML = `
                <div class="quiz-container" style="text-align: center;">
                    <h3 style="margin-bottom:12px;">This evaluation has no questions.</h3>
                    <p style="color:#64748b;margin-bottom:20px;">You can mark it completed directly.</p>
                </div>
            `;
            return;
        }

        const currentQ = state.selectedQuestions[state.currentQuestionIndex];
        const hasAnswer = state.answers[currentQ.id] !== undefined;
        const isLastQuestion = state.currentQuestionIndex === state.selectedQuestions.length - 1;

        let nextBtnHtml = '';
        if (isLastQuestion) {
            nextBtnHtml = `
                <button class="quiz-btn quiz-btn-success" 
                        onclick="${hasAnswer ? `submitActiveQuiz(${lesson.id})` : 'return false;'}"
                        ${!hasAnswer ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''}>
                    Submit Quiz Answers
                </button>
            `;
        } else {
            nextBtnHtml = `
                <button class="quiz-btn quiz-btn-primary" 
                        onclick="${hasAnswer ? `nextQuizQuestion(${lesson.id})` : 'return false;'}"
                        ${!hasAnswer ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''}>
                    Next Question &rarr;
                </button>
            `;
        }

        view.innerHTML = `
            <div class="quiz-container">
                <div class="quiz-settings-header">
                    <span class="quiz-progress-text">
                        Question ${state.currentQuestionIndex + 1} of ${state.selectedQuestions.length}
                    </span>
                </div>

                ${renderSingleQuestionMarkup(currentQ, state, state.currentQuestionIndex, state.selectedQuestions.length, lesson.id)}

                <div class="quiz-nav-row" style="display:flex; justify-content:space-between; margin-top:20px;">
                    <button class="quiz-btn quiz-btn-secondary" 
                            onclick="prevQuizQuestion(${lesson.id})" 
                            ${state.currentQuestionIndex === 0 ? 'disabled style="opacity:0;cursor:default;pointer-events:none;"' : ''}>
                        &larr; Previous
                    </button>
                    
                    ${nextBtnHtml}
                </div>
            </div>
        `;
    }

    function renderSingleQuestionMarkup(q, state, index, total, lessonId) {
        let optionsHtml = "";
        
        q.choices.forEach(choice => {
            const isSelected = state.answers[q.id] == choice.id;
            optionsHtml += `
                <label class="quiz-option-label ${isSelected ? 'selected' : ''}" onclick="selectQuizChoice(${q.id}, ${choice.id}, ${lessonId})">
                    <input type="radio" 
                           name="q-${q.id}" 
                           value="${choice.id}" 
                           ${isSelected ? 'checked' : ''} 
                           style="pointer-events: none;">
                    <span>${choice.text}</span>
                </label>
            `;
        });

        return `
            <div class="quiz-question-card">
                <div class="quiz-question-text">${index + 1}. ${q.question}</div>
                <div class="quiz-options-list">
                    ${optionsHtml}
                </div>
            </div>
        `;
    }

    function selectQuizChoice(questionId, choiceId, lessonId) {
        const state = quizStates[lessonId];
        if (!state || state.result) return;

        state.answers[questionId] = choiceId;
        
        // Re-render to show selected class
        const lesson = lessons.find(l => l.id === lessonId);
        if (lesson) renderQuiz(lesson);
    }

    function prevQuizQuestion(lessonId) {
        const state = quizStates[lessonId];
        if (state && state.currentQuestionIndex > 0) {
            state.currentQuestionIndex--;
            const lesson = lessons.find(l => l.id === lessonId);
            renderQuiz(lesson);
        }
    }

    function nextQuizQuestion(lessonId) {
        const state = quizStates[lessonId];
        const lesson = lessons.find(l => l.id === lessonId);
        if (state && lesson && state.currentQuestionIndex < state.selectedQuestions.length - 1) {
            state.currentQuestionIndex++;
            renderQuiz(lesson);
        }
    }

    function submitActiveQuiz(lessonId) {
        const state = quizStates[lessonId];
        const lesson = lessons.find(l => l.id === lessonId);
        if (!state || !lesson) return;

        // Verify that all questions are answered
        const answeredCount = Object.keys(state.answers).length;
        if (answeredCount < state.selectedQuestions.length) {
            showQuizError("Please answer all questions before submitting.");
            return;
        }

        // Post answer choices to server
        fetch(submitQuizUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                lesson_id: lessonId,
                answers: state.answers
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                state.result = data;
                
                // Add lesson id to completed array if passed
                if (data.passed) {
                    if (!completedLessonIds.includes(lessonId)) {
                        completedLessonIds.push(lessonId);
                    }
                }
                
                recalculateLocks();
                renderQuiz(lesson);
                updateBottomControls();
            } else {
                showToast(data.error || "An error occurred while submitting your answers.");
            }
        })
        .catch(err => {
            console.error(err);
            showToast("Network connection error. Please try again.");
        });
    }

    function renderQuizResult(lesson, result) {
        const view  = document.getElementById("lessonContentView");

        view.innerHTML = `
            <div class="quiz-container" style="max-width: 900px;">
                <div class="quiz-result-box">
                    <h2 class="quiz-result-title ${result.passed ? 'pass' : 'fail'}">
                        ${result.passed ? '🎉 Congratulations, You Passed!' : '❌ Quiz Not Passed'}
                    </h2>
                    <div class="quiz-result-score">${result.score} / ${result.total}</div>
                    <p class="quiz-result-desc">
                        You scored ${result.percentage}%. The passing threshold is 60%.<br>
                        Attempt Number: <strong>${result.attempt_number}</strong><br><br>
                        ${result.passed 
                            ? 'Your progress has been recorded and the next module is now unlocked!' 
                            : 'Do not worry, you can retry the evaluation when you are ready.'}
                    </p>
                    
                    ${!result.passed ? `
                        <button class="quiz-btn quiz-btn-primary" onclick="retryQuiz(${lesson.id})">
                            Retry Evaluation Quiz
                        </button>
                    ` : `
                        <div style="color: #10b981; font-weight: 700;">✓ Module Lock Cleared</div>
                    `}
                </div>
            </div>
        `;
    }

    function retryQuiz(lessonId) {
        quizStates[lessonId] = {
            currentQuestionIndex: 0,
            answers: {},
            result: null
        };
        const lesson = lessons.find(l => l.id === lessonId);
        if (lesson) renderQuiz(lesson);
        updateBottomControls();
    }

    // Completion action
    function triggerComplete() {
        if (activeIndex === -1) return;
        const lesson = lessons[activeIndex];

        fetch(completeUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                lesson_id: lesson.id
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (!completedLessonIds.includes(lesson.id)) {
                    completedLessonIds.push(lesson.id);
                }
                recalculateLocks();
                
                // Go to next lesson automatically
                if (activeIndex < lessons.length - 1) {
                    const nextLessonId = lessons[activeIndex + 1].id;
                    const nextLessonType = lessons[activeIndex + 1].type;
                    if (!isLessonLocked(nextLessonId, nextLessonType)) {
                        selectTopic(activeIndex + 1);
                    } else {
                        selectTopic(activeIndex);
                    }
                } else {
                    selectTopic(activeIndex);
                }
            } else {
                showToast("Could not update progress. Please try again.");
            }
        })
        .catch(err => {
            console.error(err);
            showToast("Network error. Please try again.");
        });
    }

    // Bottom Controls & Nav
    function updateBottomControls() {
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");
        const completeBtn = document.getElementById("completeBtn");

        if (activeIndex === -1) return;

        const lesson = lessons[activeIndex];
        const isCompleted = completedLessonIds.includes(Number(lesson.id));

        // 1. Previous button visibility
        prevBtn.style.visibility = (activeIndex > 0 && !isLessonLocked(lessons[activeIndex - 1].id, lessons[activeIndex - 1].type)) ? "visible" : "hidden";

        // 2. Next button visibility: Only visible if next lesson exists AND is unlocked
        const nextLessonExists = activeIndex < lessons.length - 1;
        const nextLessonUnlocked = nextLessonExists && !isLessonLocked(lessons[activeIndex + 1].id, lessons[activeIndex + 1].type);
        nextBtn.style.visibility = nextLessonUnlocked ? "visible" : "hidden";

        // 3. Complete button visibility
        if (lesson.type === 'quiz') {
            completeBtn.style.display = "none";
        } else {
            completeBtn.style.display = "flex";
            if (isCompleted) {
                completeBtn.textContent = "Completed ✓";
                completeBtn.disabled = true;
                completeBtn.style.opacity = "0.6";
            } else {
                completeBtn.innerHTML = 'Complete & Continue <span>✓</span>';
                completeBtn.disabled = false;
                completeBtn.style.opacity = "1";
            }
        }
    }

    function goPrevious() {
        if (activeIndex > 0) {
            const prevLesson = lessons[activeIndex - 1];
            if (!isLessonLocked(prevLesson.id, prevLesson.type)) {
                selectTopic(activeIndex - 1);
            }
        }
    }

    // Go next
    function goNext() {
        if (activeIndex < lessons.length - 1) {
            const nextLesson = lessons[activeIndex + 1];
            if (!isLessonLocked(nextLesson.id, nextLesson.type)) {
                selectTopic(activeIndex + 1);
            }
        }
    }

    function getYoutubeEmbedUrl(url) {
        if (!url) return null;
        let videoId = null;
        if (url.includes('watch?v=')) videoId = url.split('watch?v=')[1].split('&')[0];
        else if (url.includes('youtu.be/')) videoId = url.split('youtu.be/')[1].split('?')[0];
        else if (url.includes('/embed/')) return url;
        return videoId ? `https://www.youtube.com/embed/${videoId}` : null;
    }
</script>

</body>
</html>