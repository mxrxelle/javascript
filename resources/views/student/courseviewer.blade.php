<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Viewer</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *{ margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
        body{ background:#f5f6f8; overflow:hidden; }
        .topbar{ height:108px; background:#00336b; display:flex; align-items:center; justify-content:space-between; padding:0 48px; color:white; }
        .topbar-left{ display:flex; align-items:center; gap:30px; }
        .close-btn{ font-size:42px; cursor:pointer; font-weight:300; }
        .course-title{ font-size:32px; font-weight:700; }
        .dashboard-link{ color:white; text-decoration:none; font-size:24px; font-weight:500; }
        .main-layout{ display:flex; height:calc(100vh - 108px); }
        .sidebar{ width:455px; background:white; border-right:1px solid #ddd; overflow-y:auto; padding:28px 20px; }
        .sidebar-title{ font-size:28px; color:#00336b; font-weight:700; margin-bottom:28px; }
        .module{ margin-bottom:22px; }
        .module-header{ width:100%; height:78px; border:none; border-radius:18px; background:#00336b; color:white; padding:0 22px; display:flex; justify-content:space-between; align-items:center; font-size:22px; font-weight:700; cursor:pointer; }
        .arrow{ font-size:34px; transition:transform 0.3s ease; }
        .module.open .arrow{ transform:rotate(90deg); }
        .topics{ margin-top:14px; display:flex; flex-direction:column; gap:8px; max-height:0; overflow:hidden; transition:max-height 0.35s ease; }
        .module.open .topics{ max-height:600px; }
        .topic{ height:72px; border-radius:16px; display:flex; align-items:center; padding:0 22px; font-size:18px; background:#f2f4f7; color:#222; gap:18px; cursor:pointer; }
        .topic.active{ background:#ffc62d; }
        .topic.locked{ color:#666; cursor:not-allowed; opacity:0.75; }
        .topic-title{ flex:1; }
        .check{ width:30px; height:30px; border-radius:50%; background:#28a745; color:white; display:flex; align-items:center; justify-content:center; font-size:17px; }
        .content{ flex:1; overflow-y:auto; padding:44px 70px; }
        .content-card{ background:white; border-radius:24px; padding:48px; box-shadow:0 4px 12px rgba(0,0,0,0.12); }
        .lesson-title{ display:flex; align-items:center; gap:20px; margin-bottom:38px; }
        .lesson-title h1{ font-size:58px; color:#00336b; font-weight:800; }
        .video-box{ width:100%; height:520px; background:#dfe3e9; border-radius:18px; display:flex; align-items:center; justify-content:center; margin-bottom:40px; overflow:hidden; }
        .video-box iframe{ width:100%; height:100%; border:none; }
        .play-icon{ font-size:130px; color:#00336b; }
        .lesson-description{ font-size:24px; color:#222; margin-bottom:35px; line-height:1.5; }
        .reading-content{ font-size:24px; color:#222; line-height:1.5; margin-bottom:35px; }
        .reading-content h2{ color:#00336b; font-size:30px; margin:30px 0 15px; }
        .reading-content ul{ margin-left:32px; }
        .reading-content li{ margin-bottom:14px; }
        .quiz-box{ background:#f2f4f7; border-radius:16px; padding:30px; margin-bottom:45px; font-size:22px; }
        .quiz-box h3{ color:#00336b; margin-bottom:24px; }
        .quiz-option{ display:flex; align-items:center; gap:14px; margin-bottom:18px; cursor:pointer; }
        .quiz-option input{ width:22px; height:22px; }
        .bottom-nav{ border-top:1px solid #ddd; padding-top:35px; display:flex; justify-content:space-between; align-items:center; }
        .nav-btn{ height:72px; padding:0 34px; border:none; border-radius:16px; font-size:22px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:14px; }
        .prev-btn{ background:#f2f4f7; color:#222; }
        .next-btn{ background:#00336b; color:white; }
        .complete-btn{ background:#ffc62d; color:#00336b; }
        .icon{ font-size:30px; }

        /* PDF styles */
        .pdf-box {
            width: 100%;
            height: 620px;
            background: #f2f4f7;
            border: 1px solid #ddd;
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 35px;
        }
        .pdf-box iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .lesson-section-title {
            font-size: 26px;
            font-weight: 800;
            color: #00336b;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-left">
        <div class="close-btn" onclick="window.location.href='{{ route('student.dashboard') }}'">×</div>
        <div class="course-title">{{ $course->title }}</div>
    </div>

    <a href="{{ route('student.dashboard') }}" class="dashboard-link">
        Back to Dashboard
    </a>
</div>

<div class="main-layout">
    <div class="sidebar">
        <div class="sidebar-title">Course Content</div>

        @php $lessonIndex = 0; @endphp

        @forelse ($course->modules as $moduleIndex => $module)
            <div class="module {{ $moduleIndex === 0 ? 'open' : '' }}">
                <button class="module-header">
                    <span>{{ $module->sort_order }}. {{ $module->title }}</span>
                    <span class="arrow">›</span>
                </button>

                <div class="topics">
                    @foreach ($module->lessons as $lesson)
                        <div class="topic {{ $lessonIndex === 0 ? 'active' : 'locked' }}" data-index="{{ $lessonIndex }}">
                            <span class="icon">{{ $lessonIndex === 0 ? getLessonIcon($lesson->type) : '🔒' }}</span>
                            <span class="topic-title">
                                {{ $module->sort_order }}.{{ $lesson->sort_order }} {{ $lesson->title }}
                            </span>
                        </div>

                        @php $lessonIndex++; @endphp
                    @endforeach
                </div>
            </div>
        @empty
            <p style="font-size:20px;color:#555;">No modules available for this course yet.</p>
        @endforelse
    </div>

    <div class="content">
        <div class="content-card">
            <div class="lesson-title">
                <span id="contentIcon" style="font-size:42px;">▷</span>
                <h1 id="contentTitle">Loading...</h1>
            </div>

            <div id="contentBody"></div>

            <div class="bottom-nav">
                <button class="nav-btn prev-btn" id="prevBtn">‹ Previous</button>
                <button class="nav-btn complete-btn" id="completeBtn">Complete & Continue</button>
                <button class="nav-btn next-btn" id="nextBtn">Next ›</button>
            </div>
        </div>
    </div>
</div>

@php
    function getLessonIcon($type) {
        return match($type) {
            'video' => '▷',
            'reading' => '🗎',
            'presentation' => '▣',
            'quiz' => '?',
            default => '🗎',
        };
    }

    $lessons = [];

    foreach ($course->modules as $module) {
        foreach ($module->lessons as $lesson) {
            $lessons[] = [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'type' => $lesson->type,
                'content' => $lesson->content,
                'youtube_url' => $lesson->youtube_url,
                'files' => $lesson->files->map(fn($file) => [
                    'path' => asset('storage/' . $file->path),
                    'type' => $file->type,
                    'filename' => $file->filename,
                ]),
                'icon' => getLessonIcon($lesson->type),
            ];
        }
    }
@endphp

<script>
    const modules = document.querySelectorAll('.module');
    const topics = document.querySelectorAll('.topic');

    const contentIcon = document.getElementById('contentIcon');
    const contentTitle = document.getElementById('contentTitle');
    const contentBody = document.getElementById('contentBody');

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const completeBtn = document.getElementById('completeBtn');

    const lessons = @json($lessons);

    let currentIndex = 0;
    let unlockedIndex = 0;

    const originalIcons = lessons.map(lesson => lesson.icon);

    modules.forEach(module => {
        const header = module.querySelector('.module-header');

        header.addEventListener('click', () => {
            module.classList.toggle('open');
        });
    });

    topics.forEach(topic => {
        topic.addEventListener('click', () => {
            const selectedIndex = Number(topic.dataset.index);
            if (selectedIndex > unlockedIndex) return;

            currentIndex = selectedIndex;
            showLesson(currentIndex);
        });
    });

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            showLesson(currentIndex);
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentIndex < lessons.length - 1 && currentIndex < unlockedIndex) {
            currentIndex++;
            showLesson(currentIndex);
        }
    });

    completeBtn.addEventListener('click', () => {
        markCompleted(currentIndex);

        if (currentIndex === unlockedIndex && unlockedIndex < lessons.length - 1) {
            unlockedIndex++;
            unlockTopic(unlockedIndex);
        }

        if (currentIndex < lessons.length - 1) {
            currentIndex++;
            showLesson(currentIndex);
        }
    });

    function markCompleted(index) {
        const currentTopic = document.querySelector(`.topic[data-index="${index}"]`);
        if (currentTopic && !currentTopic.querySelector('.check')) {
            const check = document.createElement('div');
            check.className = 'check';
            check.textContent = '✓';
            currentTopic.appendChild(check);
        }
    }

    function unlockTopic(index) {
        const topic = document.querySelector(`.topic[data-index="${index}"]`);
        if (topic) {
            topic.classList.remove('locked');
            const icon = topic.querySelector('.icon');
            icon.textContent = originalIcons[index];

            const parentModule = topic.closest('.module');
            if (parentModule) parentModule.classList.add('open');
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

    function showLesson(index) {
        if (lessons.length === 0) {
            contentIcon.textContent = '🗎';
            contentTitle.textContent = 'No Lessons Available';
            contentBody.innerHTML = `<div class="lesson-description">This course has no lessons yet.</div>`;
            prevBtn.style.visibility = 'hidden';
            nextBtn.style.visibility = 'hidden';
            completeBtn.style.visibility = 'hidden';
            return;
        }

        const lesson = lessons[index];
        contentIcon.textContent = lesson.icon;
        contentTitle.textContent = lesson.title;

        topics.forEach(topic => topic.classList.remove('active'));
        const activeTopic = document.querySelector(`.topic[data-index="${index}"]`);
        if (activeTopic) activeTopic.classList.add('active');

        // Clear previous content
        contentBody.innerHTML = '';

        // Render PDFs first
        if (lesson.files && lesson.files.length > 0) {
            contentBody.innerHTML += `<div class="lesson-section-title">Files</div>`;
            lesson.files.forEach(file => {
                if (file.type === 'pdf') {
                    contentBody.innerHTML += `
                        <div class="pdf-box">
                            <iframe src="${file.path}"></iframe>
                        </div>
                    `;
                }
            });
        }

        // Render YouTube video
        if (lesson.youtube_url) {
            const embedUrl = getYoutubeEmbedUrl(lesson.youtube_url);
            contentBody.innerHTML += `<div class="lesson-section-title">Video Lesson</div>`;
            contentBody.innerHTML += embedUrl
                ? `<div class="video-box"><iframe src="${embedUrl}" allowfullscreen></iframe></div>`
                : `<div class="video-box"><div class="play-icon">▷</div></div>`;
        }

        // Render reading content
        if (lesson.type === 'reading' || lesson.content) {
            contentBody.innerHTML += `<div class="reading-content">${lesson.content ?? ''}</div>`;
        }

        // Render quiz placeholder
        if (lesson.type === 'quiz') {
            contentBody.innerHTML += `
                <div class="quiz-box">
                    <h3>Quiz Placeholder</h3>
                    <label class="quiz-option"><input type="radio" name="quizOption"><span>Option A</span></label>
                    <label class="quiz-option"><input type="radio" name="quizOption"><span>Option B</span></label>
                    <label class="quiz-option"><input type="radio" name="quizOption"><span>Option C</span></label>
                </div>
            `;
        }

        prevBtn.style.visibility = index === 0 ? 'hidden' : 'visible';
        nextBtn.style.visibility = index < unlockedIndex ? 'visible' : 'hidden';
        completeBtn.style.visibility = 'visible';
    }

    showLesson(currentIndex);
</script>

</body>
</html>