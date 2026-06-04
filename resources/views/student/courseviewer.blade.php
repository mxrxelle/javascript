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

        /* ── Final Exam Sidebar Entry ─────────────────────────────────── */
        .final-exam-card {
            border: 2px solid #002244;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(135deg, #001a33, #002e5c);
            margin-top: 4px;
        }
        .final-exam-card.locked-state {
            border-color: #94a3b8;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        }
        .final-exam-card.done-state {
            border-color: #10b981;
            background: linear-gradient(135deg, #064e3b, #065f46);
        }
        .final-exam-header {
            width: 100%;
            border: none;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-align: left;
            transition: background 0.2s;
            background: transparent;
            color: #ffc32b;
        }
        .final-exam-card.locked-state .final-exam-header {
            color: #94a3b8;
            cursor: not-allowed;
        }
        .final-exam-card.done-state .final-exam-header {
            color: #6ee7b7;
        }
        .final-exam-card:not(.locked-state):not(.done-state) .final-exam-header:hover {
            background: rgba(255,255,255,0.06);
        }
        .final-exam-title-text { display: flex; align-items: center; gap: 10px; }
        .fe-badge { font-size: 11px; padding: 2px 8px; border-radius: 20px; font-weight: 700; }
        .fe-badge-gold { background: #ffc32b; color: #002244; }
        .fe-badge-gray { background: #94a3b8; color: white; }
        .fe-badge-green { background: #10b981; color: white; }

        /* ── Final Exam Main Content Panel ───────────────────────────── */
        .final-exam-panel {
            padding: 40px;
            max-width: 860px;
            margin: 0 auto;
            width: 100%;
        }
        .fe-header-row {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid #e2e8f0;
        }
        .fe-icon-badge {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #002244, #003580);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(0,34,68,0.25);
        }
        .fe-header-info h1 { font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
        .fe-header-info p { font-size: 14px; color: #64748b; }

        .fe-progress-bar-wrap {
            background: #f1f5f9;
            border-radius: 20px;
            height: 10px;
            margin-bottom: 6px;
            overflow: hidden;
        }
        .fe-progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #002244, #0056b3);
            border-radius: 20px;
            transition: width 0.3s ease;
        }
        .fe-progress-text {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 24px;
        }

        /* FE Result box */
        .fe-result-box {
            text-align: center;
            padding: 48px 40px;
            border-radius: 20px;
            border: 2px solid;
        }
        .fe-result-box.pass { border-color: #10b981; background: #ecfdf5; }
        .fe-result-box.fail { border-color: #ef4444; background: #fef2f2; }
        .fe-result-icon { font-size: 64px; display: block; margin-bottom: 16px; }
        .fe-result-title { font-size: 30px; font-weight: 800; margin-bottom: 8px; }
        .fe-result-title.pass { color: #065f46; }
        .fe-result-title.fail { color: #991b1b; }
        .fe-result-score { font-size: 56px; font-weight: 900; margin: 16px 0 4px; }
        .fe-result-score.pass { color: #10b981; }
        .fe-result-score.fail { color: #ef4444; }
        .fe-result-desc { font-size: 15px; color: #475569; margin-bottom: 28px; line-height: 1.6; }

        .fe-cert-download {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #002244, #003580);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(0,34,68,0.3);
        }
        .fe-cert-download:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,34,68,0.4); }

        .fe-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .fe-stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        .fe-stat-label { font-size: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; margin-bottom: 6px; }
        .fe-stat-value { font-size: 24px; font-weight: 800; color: #0f172a; }

        /* Lock/Intro screens */
        .fe-lock-screen, .fe-intro-screen {
            text-align: center;
            padding: 48px 40px;
        }
        .fe-lock-icon { font-size: 64px; display: block; margin-bottom: 20px; }
        .fe-lock-title { font-size: 26px; font-weight: 800; color: #1e293b; margin-bottom: 12px; }
        .fe-lock-desc { font-size: 15px; color: #64748b; line-height: 1.6; max-width: 420px; margin: 0 auto 24px; }
        .fe-lock-progress-info {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
        }
        .fe-start-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #002244, #003580);
            color: white;
            border: none;
            padding: 16px 36px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(0,34,68,0.3);
            margin-top: 24px;
        }
        .fe-start-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,34,68,0.4); }
        .fe-submit-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #10b981;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        .fe-submit-btn:hover { background: #059669; }
        .fe-submit-btn:disabled { background: #cbd5e1; color: #94a3b8; cursor: not-allowed; }
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

        {{-- ── Final Exam Sidebar Entry ── --}}
        @php
            $hasFinalExam = $course->finalExam !== null;
            $feAttempted  = $finalExamAttempt !== null;
            $fePassed     = $feAttempted && $finalExamAttempt->passed;
            $courseProgress = $studentCourse->progress ?? 0;

            // Collect all quiz lesson IDs across modules
            $quizLessonIds = $course->modules->flatMap(fn($m) => $m->lessons->where('type', 'quiz')->pluck('id'));

            // All quizzes must have been passed at least once
            $allQuizzesPassed = $quizLessonIds->every(function ($lid) use ($quizAttemptData) {
                return !empty($quizAttemptData[$lid]['ever_passed']) && $quizAttemptData[$lid]['ever_passed'] === true;
            });

            // Unlock only when: 100% progress AND all quizzes passed
            $feUnlocked = ($courseProgress >= 100) && $allQuizzesPassed;

            // Count how many quizzes remain to be passed
            $quizTotal  = $quizLessonIds->count();
            $quizPassed = $quizLessonIds->filter(fn($lid) => !empty($quizAttemptData[$lid]['ever_passed']) && $quizAttemptData[$lid]['ever_passed'] === true)->count();
        @endphp

        @if ($hasFinalExam)
            <div class="final-exam-card {{ !$feUnlocked ? 'locked-state' : ($fePassed ? 'done-state' : '') }}"
                 id="final-exam-sidebar-card">
                <button class="final-exam-header"
                        onclick="{{ $feUnlocked ? 'selectFinalExam()' : 'return false;' }}"
                        {{ !$feUnlocked ? 'disabled' : '' }}
                        id="final-exam-sidebar-btn">
                    <span class="final-exam-title-text">
                        <span style="font-size:20px;">{{ $fePassed ? '🏆' : ($feUnlocked ? '📋' : '🔒') }}</span>
                        <span>Final Course Exam</span>
                    </span>
                    <span class="fe-badge {{ $fePassed ? 'fe-badge-green' : ($feUnlocked ? 'fe-badge-gold' : 'fe-badge-gray') }}">
                        {{ $fePassed ? 'Passed' : ($feUnlocked ? 'Unlocked' : 'Locked') }}
                    </span>
                </button>
            </div>
        @endif
    </div>

    <div class="content">
        {{-- ── Final Exam Content Panel ──────────────────────────── --}}
        <div class="content-card" id="finalExamContainer" style="display: none; padding: 0;">
            <div id="finalExamView"></div>
        </div>

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
    const submitFinalExamUrl = "{{ route('student.courseviewer.submitFinalExam', $course->id) }}";
    const csrfToken = "{{ csrf_token() }}";

    // Quiz attempt tracking: { lessonId: { total, best_score, ever_passed } }
    let quizAttemptData = @json($quizAttemptData);
    const MAX_ATTEMPTS = 3;

    // Final Exam server-side state injected from PHP
    const finalExamData       = @json(optional($course->finalExam));
    const finalExamQuestions  = @json(optional($course->finalExam)->questions ?? []);
    const finalExamAttemptData = @json($finalExamAttempt);
    const certificateData      = @json($certificate);
    const courseProgress       = {{ $studentCourse->progress ?? 0 }};
    const allQuizzesPassed     = {{ $allQuizzesPassed ? 'true' : 'false' }};
    const quizPassedCount      = {{ $quizPassed ?? 0 }};
    const quizTotalCount       = {{ $quizTotal ?? 0 }};

    // Final exam client-side state
    let feState = null; // { questions: [], currentIndex: 0, answers: {}, submitted: false }

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

            // Check if this is a quiz that has hit max attempts
            const isQuizExhausted = lesson.type === 'quiz' &&
                (quizAttemptData[lesson.id]?.total ?? 0) >= MAX_ATTEMPTS;

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
                } else if (isQuizExhausted) {
                    // Show lock icon in sidebar for quiz with exhausted attempts
                    document.getElementById(`status-icon-${lesson.id}-${lesson.type}`).textContent = "🔒";
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

        // ── Check locked state (max attempts reached) ────────────────────────
        const attemptInfo = quizAttemptData[lesson.id] || { total: 0, best_score: 0, ever_passed: false };
        if (attemptInfo.total >= MAX_ATTEMPTS && !quizStates[lesson.id]?.result) {
            renderQuizLocked(lesson, attemptInfo);
            return;
        }
        // ─────────────────────────────────────────────────────────────────────
        
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

                // Update local attempt tracking
                const prevInfo = quizAttemptData[lessonId] || { total: 0, best_score: 0, ever_passed: false };
                quizAttemptData[lessonId] = {
                    total:       data.attempts_used,
                    best_score:  data.best_score,
                    ever_passed: prevInfo.ever_passed || data.passed,
                };
                
                // Add lesson id to completed array if passed
                if (data.passed) {
                    if (!completedLessonIds.includes(lessonId)) {
                        completedLessonIds.push(lessonId);
                    }
                }
                
                recalculateLocks();
                renderQuiz(lesson);
                updateBottomControls();
            } else if (data.locked) {
                // Server rejected — already locked
                const attemptInfo = quizAttemptData[lessonId] || { total: MAX_ATTEMPTS, best_score: 0, ever_passed: false };
                renderQuizLocked(lesson, attemptInfo);
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
        const view = document.getElementById("lessonContentView");
        const attemptsUsed = result.attempts_used || result.attempt_number || 1;
        const maxAttempts  = result.max_attempts  || MAX_ATTEMPTS;
        const attemptsLeft = maxAttempts - attemptsUsed;
        const isLocked     = attemptsUsed >= maxAttempts;
        const bestScore    = result.best_score ?? result.percentage;

        // Build retake / locked section
        let actionHtml = '';
        if (result.passed) {
            actionHtml = `<div style="color:#10b981;font-weight:700;font-size:16px;margin-top:8px;">✓ Module Lock Cleared</div>`;
        } else if (isLocked) {
            actionHtml = `
                <div style="margin-top:16px;padding:14px 20px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;color:#991b1b;font-size:14px;font-weight:600;">
                    🔒 No retakes remaining. Contact your facilitator for assistance.
                </div>`;
        } else {
            actionHtml = `
                <button class="quiz-btn quiz-btn-primary" onclick="retryQuiz(${lesson.id})" style="margin-top:8px;">
                    Retake Quiz
                </button>
                <p style="font-size:13px;color:#64748b;margin-top:10px;">
                    You have <strong>${attemptsLeft}</strong> retake${attemptsLeft !== 1 ? 's' : ''} remaining.
                </p>`;
        }

        view.innerHTML = `
            <div class="quiz-container" style="max-width:900px;">
                <div class="quiz-result-box">
                    <h2 class="quiz-result-title ${result.passed ? 'pass' : 'fail'}">
                        ${result.passed ? '✅ Quiz Submitted!' : '❌ Quiz Submitted'}
                    </h2>

                    <table style="margin:20px auto;border-collapse:collapse;text-align:left;font-size:15px;min-width:280px;">
                        <tr><td style="padding:6px 12px;color:#64748b;font-weight:600;">Your Score</td><td style="padding:6px 12px;font-weight:700;color:#0f172a;">${result.percentage}%</td></tr>
                        <tr><td style="padding:6px 12px;color:#64748b;font-weight:600;">Result</td><td style="padding:6px 12px;font-weight:700;color:${result.passed ? '#10b981' : '#ef4444'};">${result.passed ? 'Passed' : 'Failed'}</td></tr>
                        <tr><td style="padding:6px 12px;color:#64748b;font-weight:600;">Correct Answers</td><td style="padding:6px 12px;font-weight:700;color:#0f172a;">${result.score} out of ${result.total}</td></tr>
                        <tr><td style="padding:6px 12px;color:#64748b;font-weight:600;">Attempt</td><td style="padding:6px 12px;font-weight:700;color:#0f172a;">${attemptsUsed} of ${maxAttempts}</td></tr>
                    </table>

                    ${actionHtml}
                </div>
            </div>
        `;
    }

    function renderQuizLocked(lesson, attemptInfo) {
        const view = document.getElementById("lessonContentView");
        const everPassed = attemptInfo.ever_passed;
        const bestScore  = attemptInfo.best_score ?? 0;

        let footerHtml = '';
        if (everPassed) {
            footerHtml = `
                <div style="color:#10b981;font-weight:700;font-size:15px;margin-top:12px;">✓ You passed this quiz on a previous attempt.</div>
                <p style="color:#64748b;font-size:13px;margin-top:6px;">You may continue to the next lesson.</p>`;
        } else {
            footerHtml = `
                <div style="margin-top:16px;padding:14px 20px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;color:#991b1b;font-size:14px;font-weight:600;">
                    Contact your facilitator for assistance to unlock this quiz.
                </div>`;
        }

        view.innerHTML = `
            <div class="quiz-container" style="max-width:900px;">
                <div class="quiz-result-box">
                    <div style="font-size:48px;margin-bottom:8px;">🔒</div>
                    <h2 class="quiz-result-title fail">Quiz Locked</h2>
                    <p style="color:#64748b;font-size:15px;margin-bottom:20px;">
                        You have used all ${MAX_ATTEMPTS} attempts for this quiz.
                    </p>

                    <table style="margin:0 auto 16px;border-collapse:collapse;text-align:left;font-size:15px;min-width:260px;">
                        <tr><td style="padding:6px 12px;color:#64748b;font-weight:600;">Your Best Score</td><td style="padding:6px 12px;font-weight:700;color:#0f172a;">${bestScore}%</td></tr>
                        <tr><td style="padding:6px 12px;color:#64748b;font-weight:600;">Total Attempts</td><td style="padding:6px 12px;font-weight:700;color:#0f172a;">${MAX_ATTEMPTS} of ${MAX_ATTEMPTS}</td></tr>
                    </table>

                    ${footerHtml}
                </div>
            </div>
        `;
    }

    function retryQuiz(lessonId) {
        // Guard: do not allow retry if max attempts reached
        const attemptInfo = quizAttemptData[lessonId] || { total: 0 };
        if (attemptInfo.total >= MAX_ATTEMPTS) {
            const lesson = lessons.find(l => l.id === lessonId);
            if (lesson) renderQuizLocked(lesson, attemptInfo);
            return;
        }

        delete quizStates[lessonId];
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

    // ═══════════════════════════════════════════════════════════════════════
    // FINAL EXAM ENGINE
    // ═══════════════════════════════════════════════════════════════════════

    function selectFinalExam() {
        if (courseProgress < 100 || !allQuizzesPassed) {
            showToast('You must pass all module quizzes before taking the Final Exam. (' + quizPassedCount + ' / ' + quizTotalCount + ' quizzes passed)', 'error');
            return;
        }

        // Switch panels
        document.getElementById('emptyContainer').style.display = 'none';
        document.getElementById('lessonContainer').style.display = 'none';
        document.getElementById('finalExamContainer').style.display = 'flex';

        // Mark sidebar active
        document.querySelectorAll('.topic').forEach(el => el.classList.remove('active'));
        const feBtn = document.getElementById('final-exam-sidebar-btn');
        if (feBtn) feBtn.style.background = 'rgba(255,195,43,0.12)';

        activeIndex = -1;

        // If already attempted — show result directly
        if (finalExamAttemptData) {
            renderFEResult(finalExamAttemptData);
            return;
        }

        // Show intro/instructions screen
        renderFEIntro();
    }

    function renderFEIntro() {
        const view = document.getElementById('finalExamView');
        const questionCount = finalExamQuestions.length;
        const passingScore  = finalExamData ? finalExamData.passing_score : 70;

        view.innerHTML = `
            <div class="final-exam-panel">
                <div class="fe-header-row">
                    <div class="fe-icon-badge">📋</div>
                    <div class="fe-header-info">
                        <h1>Final Course Exam</h1>
                        <p>Complete all questions to earn your certificate.</p>
                    </div>
                </div>

                <div class="fe-intro-screen">
                    <span class="fe-lock-icon">🎯</span>
                    <div class="fe-lock-title">Ready to take the Final Exam?</div>
                    <div class="fe-lock-desc">
                        This exam consists of <strong>${questionCount} questions</strong>.
                        You need a score of <strong>${passingScore}%</strong> or higher to pass and earn your certificate.
                        <br><br>
                        ⚠️ <strong>You only get ONE attempt.</strong> Answer all questions carefully.
                    </div>

                    <div style="display:flex; gap:24px; justify-content:center; flex-wrap:wrap; margin-bottom:24px;">
                        <div class="fe-stat-card" style="padding:16px 24px;">
                            <div class="fe-stat-label">Questions</div>
                            <div class="fe-stat-value">${questionCount}</div>
                        </div>
                        <div class="fe-stat-card" style="padding:16px 24px;">
                            <div class="fe-stat-label">Passing Score</div>
                            <div class="fe-stat-value">${passingScore}%</div>
                        </div>
                        <div class="fe-stat-card" style="padding:16px 24px;">
                            <div class="fe-stat-label">Attempts Allowed</div>
                            <div class="fe-stat-value">1</div>
                        </div>
                    </div>

                    <button class="fe-start-btn" onclick="startFinalExam()">
                        🚀 Begin Final Exam
                    </button>
                </div>
            </div>
        `;
    }

    function startFinalExam() {
        if (!finalExamQuestions || finalExamQuestions.length === 0) {
            showToast('No questions found for this exam.', 'error');
            return;
        }

        // Shuffle questions (Fisher-Yates)
        const pool = finalExamQuestions.map(q => ({
            ...q,
            choices: [...(q.choices || [])].sort(() => Math.random() - 0.5)
        }));
        for (let i = pool.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [pool[i], pool[j]] = [pool[j], pool[i]];
        }

        feState = {
            questions:    pool,
            currentIndex: 0,
            answers:      {},
            submitted:    false
        };

        renderFEQuestion();
    }

    function renderFEQuestion() {
        if (!feState) return;
        const view = document.getElementById('finalExamView');
        const q    = feState.questions[feState.currentIndex];
        const idx  = feState.currentIndex;
        const total = feState.questions.length;
        const progress = ((idx) / total) * 100;
        const hasAnswer = feState.answers[q.id] !== undefined;
        const isLast    = idx === total - 1;

        let choicesHtml = '';
        (q.choices || []).forEach(choice => {
            const isSelected = feState.answers[q.id] == choice.id;
            choicesHtml += `
                <label class="quiz-option-label ${isSelected ? 'selected' : ''}" 
                       onclick="fePick(${q.id}, ${choice.id})">
                    <input type="radio" name="feq-${q.id}" value="${choice.id}" ${isSelected ? 'checked' : ''} style="pointer-events:none;">
                    <span>${choice.choice_text || choice.text || ''}</span>
                </label>
            `;
        });

        view.innerHTML = `
            <div class="final-exam-panel">
                <div class="fe-header-row">
                    <div class="fe-icon-badge">📋</div>
                    <div class="fe-header-info">
                        <h1>Final Course Exam</h1>
                        <p>Answer all ${total} questions to submit.</p>
                    </div>
                </div>

                <div class="fe-progress-bar-wrap">
                    <div class="fe-progress-bar-fill" style="width: ${progress}%;"></div>
                </div>
                <div class="fe-progress-text">
                    Question ${idx + 1} of ${total} &nbsp;·&nbsp;
                    ${Object.keys(feState.answers).length} answered
                </div>

                <div class="quiz-question-card">
                    <div class="quiz-question-text">${idx + 1}. ${q.question}</div>
                    <div class="quiz-options-list">
                        ${choicesHtml}
                    </div>
                </div>

                <div class="quiz-nav-row" style="display:flex; justify-content:space-between; margin-top:20px;">
                    <button class="quiz-btn quiz-btn-secondary" onclick="fePrev()"
                        ${idx === 0 ? 'disabled style="opacity:0;pointer-events:none;"' : ''}>
                        &larr; Previous
                    </button>

                    ${isLast
                        ? `<button class="fe-submit-btn" onclick="feSubmitConfirm()" ${!hasAnswer ? 'disabled' : ''}>
                                ✓ Submit Final Exam
                           </button>`
                        : `<button class="quiz-btn quiz-btn-primary" onclick="feNext()" ${!hasAnswer ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''}>
                                Next &rarr;
                           </button>`
                    }
                </div>
            </div>
        `;
    }

    function fePick(questionId, choiceId) {
        if (!feState || feState.submitted) return;
        feState.answers[questionId] = choiceId;
        renderFEQuestion();
    }

    function fePrev() {
        if (!feState || feState.currentIndex <= 0) return;
        feState.currentIndex--;
        renderFEQuestion();
    }

    function feNext() {
        if (!feState) return;
        const q = feState.questions[feState.currentIndex];
        if (feState.answers[q.id] === undefined) return;
        feState.currentIndex++;
        renderFEQuestion();
    }

    function feSubmitConfirm() {
        const total     = feState.questions.length;
        const answered  = Object.keys(feState.answers).length;
        const unanswered = total - answered;

        if (unanswered > 0) {
            showToast(`You still have ${unanswered} unanswered question(s). Please answer all before submitting.`, 'error');
            return;
        }

        // Visual confirmation instead of browser confirm()
        const view = document.getElementById('finalExamView');
        view.innerHTML += `
            <div id="fe-confirm-overlay" style="
                position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;
                display:flex;align-items:center;justify-content:center;
            ">
                <div style="background:white;border-radius:20px;padding:40px;max-width:440px;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
                    <div style="font-size:48px;margin-bottom:16px;">⚠️</div>
                    <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin-bottom:12px;">Submit Final Exam?</h2>
                    <p style="color:#475569;font-size:14px;line-height:1.6;margin-bottom:24px;">
                        You are about to submit all <strong>${total}</strong> answers.
                        This action <strong>cannot be undone</strong>. You only have one attempt.
                    </p>
                    <div style="display:flex;gap:12px;justify-content:center;">
                        <button onclick="document.getElementById('fe-confirm-overlay').remove()"
                            style="padding:12px 24px;border-radius:10px;border:none;background:#f1f5f9;color:#475569;font-weight:700;font-size:14px;cursor:pointer;">
                            Cancel
                        </button>
                        <button onclick="feSubmit()"
                            style="padding:12px 28px;border-radius:10px;border:none;background:#002244;color:white;font-weight:700;font-size:14px;cursor:pointer;">
                            Yes, Submit Now
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function feSubmit() {
        const overlay = document.getElementById('fe-confirm-overlay');
        if (overlay) overlay.remove();
        feState.submitted = true;

        // Show loading state
        document.getElementById('finalExamView').innerHTML = `
            <div class="final-exam-panel" style="text-align:center;padding:80px 40px;">
                <div style="font-size:48px;margin-bottom:16px;animation:spin 1s linear infinite;">⏳</div>
                <div style="font-size:18px;font-weight:700;color:#475569;">Submitting your answers...</div>
                <style>@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}</style>
            </div>
        `;

        fetch(submitFinalExamUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ answers: feState.answers })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderFEResult(data);
                updateFESidebar(data.passed);
            } else {
                showToast(data.error || 'Submission failed. Please refresh and try again.', 'error');
                feState.submitted = false;
                renderFEQuestion();
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Network error. Please try again.', 'error');
            feState.submitted = false;
            renderFEQuestion();
        });
    }

    function renderFEResult(data) {
        const view      = document.getElementById('finalExamView');
        const passed    = data.passed;
        const score     = data.score ?? data.score_percentage;
        const correct   = data.correct_count ?? '-';
        const total     = data.total ?? finalExamQuestions.length;
        const passing   = data.passing_score ?? (finalExamData ? finalExamData.passing_score : 70);
        const certUid   = data.certificate_uid ?? (certificateData ? certificateData.certificate_uid : null);

        let certHtml = '';
        if (passed && certUid) {
            certHtml = `
                <a href="/storage/certificates/${certUid}.pdf" target="_blank" class="fe-cert-download">
                    📄 Download Your Certificate
                </a>
                <p style="font-size:13px;color:#64748b;margin-top:8px;">Certificate ID: <strong>${certUid}</strong></p>
            `;
        }

        view.innerHTML = `
            <div class="final-exam-panel">
                <div class="fe-result-box ${passed ? 'pass' : 'fail'}">
                    <span class="fe-result-icon">${passed ? '🎓' : '📝'}</span>
                    <div class="fe-result-title ${passed ? 'pass' : 'fail'}">
                        ${passed ? 'Congratulations! You Passed!' : 'You Did Not Pass'}
                    </div>
                    <div class="fe-result-score ${passed ? 'pass' : 'fail'}">${score}%</div>

                    <div class="fe-stat-grid">
                        <div class="fe-stat-card">
                            <div class="fe-stat-label">Correct Answers</div>
                            <div class="fe-stat-value">${correct} / ${total}</div>
                        </div>
                        <div class="fe-stat-card">
                            <div class="fe-stat-label">Your Score</div>
                            <div class="fe-stat-value">${score}%</div>
                        </div>
                        <div class="fe-stat-card">
                            <div class="fe-stat-label">Passing Score</div>
                            <div class="fe-stat-value">${passing}%</div>
                        </div>
                    </div>

                    <div class="fe-result-desc">
                        ${passed
                            ? `Outstanding work! You have successfully completed the course and earned your official <strong>Certly Certificate of Completion</strong>. Your certificate has been emailed to you.`
                            : `You scored ${score}%, but the passing score is ${passing}%. Unfortunately, only one attempt is allowed for the final exam. Please contact your facilitator for further assistance.`
                        }
                    </div>

                    ${certHtml}

                    ${!passed ? `
                        <div style="margin-top:20px;padding:16px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;color:#991b1b;font-size:14px;font-weight:600;">
                            🔒 No retakes available. Contact your facilitator for assistance.
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }

    function updateFESidebar(passed) {
        const card = document.getElementById('final-exam-sidebar-card');
        const btn  = document.getElementById('final-exam-sidebar-btn');
        if (!card || !btn) return;

        card.classList.remove('locked-state');
        if (passed) {
            card.classList.add('done-state');
            btn.querySelector('.fe-badge').textContent = 'Passed';
            btn.querySelector('.fe-badge').className   = 'fe-badge fe-badge-green';
            btn.querySelector('span:first-child span:first-child').textContent = '🏆';
        } else {
            btn.querySelector('.fe-badge').textContent = 'Failed';
            btn.querySelector('.fe-badge').className   = 'fe-badge fe-badge-gray';
        }
    }
</script>

</body>
</html>