<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Viewer</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Inter',sans-serif;
        }

        body{
            background:#f5f6f8;
            overflow:hidden;
        }

        .topbar{
            height:108px;
            background:#00336b;
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:0 48px;
            color:white;
        }

        .topbar-left{
            display:flex;
            align-items:center;
            gap:30px;
        }

        .close-btn{
            font-size:42px;
            cursor:pointer;
            font-weight:300;
        }

        .course-title{
            font-size:32px;
            font-weight:700;
        }

        .dashboard-link{
            color:white;
            text-decoration:none;
            font-size:24px;
            font-weight:500;
        }

        .main-layout{
            display:flex;
            height:calc(100vh - 108px);
        }

        .sidebar{
            width:455px;
            background:white;
            border-right:1px solid #ddd;
            overflow-y:auto;
            padding:28px 20px;
        }

        .sidebar-title{
            font-size:28px;
            color:#00336b;
            font-weight:700;
            margin-bottom:28px;
        }

        .module{
            margin-bottom:22px;
        }

        .module-header{
            width:100%;
            height:78px;
            border:none;
            border-radius:18px;
            background:#00336b;
            color:white;
            padding:0 22px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            font-size:22px;
            font-weight:700;
            cursor:pointer;
        }

        .arrow{
            font-size:34px;
            transition:transform 0.3s ease;
        }

        .module.open .arrow{
            transform:rotate(90deg);
        }

        .topics{
            margin-top:14px;
            display:flex;
            flex-direction:column;
            gap:8px;
            max-height:0;
            overflow:hidden;
            transition:max-height 0.35s ease;
        }

        .module.open .topics{
            max-height:400px;
        }

        .topic{
            height:72px;
            border-radius:16px;
            display:flex;
            align-items:center;
            padding:0 22px;
            font-size:18px;
            background:#f2f4f7;
            color:#222;
            gap:18px;
            cursor:pointer;
        }

        .topic.active{
            background:#ffc62d;
        }

        .topic.locked{
            color:#666;
            cursor:not-allowed;
            opacity:0.75;
        }

        .topic-title{
            flex:1;
        }

        .check{
            width:30px;
            height:30px;
            border-radius:50%;
            background:#28a745;
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:17px;
        }

        .content{
            flex:1;
            overflow-y:auto;
            padding:44px 70px;
        }

        .content-card{
            background:white;
            border-radius:24px;
            padding:48px;
            box-shadow:0 4px 12px rgba(0,0,0,0.12);
        }

        .lesson-title{
            display:flex;
            align-items:center;
            gap:20px;
            margin-bottom:38px;
        }

        .lesson-title h1{
            font-size:58px;
            color:#00336b;
            font-weight:800;
        }

        .video-box{
            width:100%;
            height:520px;
            background:#dfe3e9;
            border-radius:18px;
            display:flex;
            align-items:center;
            justify-content:center;
            margin-bottom:40px;
        }

        .play-icon{
            font-size:130px;
            color:#00336b;
        }

        .lesson-description{
            font-size:24px;
            color:#222;
            margin-bottom:35px;
            line-height:1.5;
        }

        .reading-content{
            font-size:24px;
            color:#222;
            line-height:1.5;
            margin-bottom:35px;
        }

        .reading-content h2{
            color:#00336b;
            font-size:30px;
            margin:30px 0 15px;
        }

        .reading-content ul{
            margin-left:32px;
        }

        .reading-content li{
            margin-bottom:14px;
        }

        .quiz-box{
            background:#f2f4f7;
            border-radius:16px;
            padding:30px;
            margin-bottom:45px;
            font-size:22px;
        }

        .quiz-box h3{
            color:#00336b;
            margin-bottom:24px;
        }

        .quiz-progress{
            color:#00336b;
            font-weight:700;
            margin-bottom:18px;
            font-size:20px;
        }

        .quiz-option{
            display:flex;
            align-items:center;
            gap:14px;
            margin-bottom:18px;
            cursor:pointer;
        }

        .quiz-option input{
            width:22px;
            height:22px;
        }

        .bottom-nav{
            border-top:1px solid #ddd;
            padding-top:35px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .nav-btn{
            height:72px;
            padding:0 34px;
            border:none;
            border-radius:16px;
            font-size:22px;
            font-weight:700;
            cursor:pointer;
            display:flex;
            align-items:center;
            gap:14px;
        }

        .prev-btn{
            background:#f2f4f7;
            color:#222;
        }

        .next-btn{
            background:#00336b;
            color:white;
        }

        .complete-btn{
            background:#ffc62d;
            color:#00336b;
        }

        .icon{
            font-size:30px;
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="topbar-left">
            <div class="close-btn">×</div>
            <div class="course-title">Intro to Python Programming</div>
        </div>

        <a href="/dashboard" class="dashboard-link">
            Back to Dashboard
        </a>
    </div>

    <div class="main-layout">

        <div class="sidebar">

            <div class="sidebar-title">Course Content</div>

            <div class="module open">
                <button class="module-header">
                    <span>1. Basics</span>
                    <span class="arrow">›</span>
                </button>

                <div class="topics">
                    <div class="topic active" data-index="0">
                        <span class="icon">▷</span>
                        <span class="topic-title">1.1 Welcome</span>
                    </div>

                    <div class="topic locked" data-index="1">
                        <span class="icon">🔒</span>
                        <span class="topic-title">1.2 Data Types</span>
                    </div>

                    <div class="topic locked" data-index="2">
                        <span class="icon">🔒</span>
                        <span class="topic-title">1.3 Variables Quiz</span>
                    </div>
                </div>
            </div>

            <div class="module">
                <button class="module-header">
                    <span>2. Data Structures</span>
                    <span class="arrow">›</span>
                </button>

                <div class="topics">
                    <div class="topic locked" data-index="3">
                        <span class="icon">🔒</span>
                        <span class="topic-title">2.1 Lists & Arrays</span>
                    </div>

                    <div class="topic locked" data-index="4">
                        <span class="icon">🔒</span>
                        <span class="topic-title">2.2 Dictionaries</span>
                    </div>

                    <div class="topic locked" data-index="5">
                        <span class="icon">🔒</span>
                        <span class="topic-title">2.3 Data Structures Quiz</span>
                    </div>
                </div>
            </div>

            <div class="module">
                <button class="module-header">
                    <span>3. Functions</span>
                    <span class="arrow">›</span>
                </button>

                <div class="topics">
                    <div class="topic locked" data-index="6">
                        <span class="icon">🔒</span>
                        <span class="topic-title">3.1 Functions Introduction</span>
                    </div>

                    <div class="topic locked" data-index="7">
                        <span class="icon">🔒</span>
                        <span class="topic-title">3.2 Parameters</span>
                    </div>

                    <div class="topic locked" data-index="8">
                        <span class="icon">🔒</span>
                        <span class="topic-title">3.3 Functions Quiz</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="content">
            <div class="content-card">

                <div class="lesson-title">
                    <span id="contentIcon" style="font-size:42px;">▷</span>
                    <h1 id="contentTitle">Welcome</h1>
                </div>

                <div id="contentBody"></div>

                <div class="bottom-nav">
                    <button class="nav-btn prev-btn" id="prevBtn">
                        ‹ Previous
                    </button>

                    <button class="nav-btn complete-btn" id="completeBtn">
                        Complete & Continue
                    </button>

                    <button class="nav-btn next-btn" id="nextBtn">
                        Next ›
                    </button>
                </div>

            </div>
        </div>

    </div>

    <script>
        const modules = document.querySelectorAll('.module');
        const topics = document.querySelectorAll('.topic');

        const contentIcon = document.getElementById('contentIcon');
        const contentTitle = document.getElementById('contentTitle');
        const contentBody = document.getElementById('contentBody');

        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const completeBtn = document.getElementById('completeBtn');

        let currentIndex = 0;
        let unlockedIndex = 0;

        const originalIcons = ["▷", "🗎", "?", "▷", "🗎", "?", "▷", "🗎", "?"];

        const lessons = [
            {
                title: "Welcome",
                icon: "▷",
                type: "video",
                description: "Watch this video to learn about welcome."
            },
            {
                title: "Data Types",
                icon: "🗎",
                type: "reading",
                description: "This reading covers important concepts about data types.",
                paragraph: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.",
                points: [
                    "Understanding fundamental concepts",
                    "Practical applications",
                    "Best practices and common patterns"
                ]
            },
            {
                title: "Variables Quiz",
                icon: "?",
                type: "quiz",
                description: "Test your knowledge with this quiz.",
                currentQuestion: 0,
                questions: [
                    {
                        question: "Question 1: What is the correct way to define a variable in Python?",
                        options: ["var x = 10", "x = 10", "let x = 10"]
                    },
                    {
                        question: "Question 2: Which symbol is used to assign a value in Python?",
                        options: ["=", "==", "==="]
                    },
                    {
                        question: "Question 3: Which one is a valid Python variable name?",
                        options: ["my_name", "2name", "first-name"]
                    }
                ]
            },
            {
                title: "Lists & Arrays",
                icon: "▷",
                type: "video",
                description: "Watch this video to learn about lists & arrays."
            },
            {
                title: "Dictionaries",
                icon: "🗎",
                type: "reading",
                description: "This reading explains how dictionaries store data using key-value pairs.",
                paragraph: "Placeholder text for dictionaries. This lesson will explain how to create, access, update, and remove dictionary values in Python.",
                points: [
                    "Dictionary keys and values",
                    "Accessing values using keys",
                    "Updating and deleting dictionary items"
                ]
            },
            {
                title: "Data Structures Quiz",
                icon: "?",
                type: "quiz",
                description: "Test your knowledge about data structures.",
                currentQuestion: 0,
                questions: [
                    {
                        question: "Question 1: Which data structure stores key-value pairs?",
                        options: ["List", "Dictionary", "String"]
                    },
                    {
                        question: "Question 2: Which one is used to store multiple values in one variable?",
                        options: ["List", "Print", "Input"]
                    },
                    {
                        question: "Question 3: Which symbol is commonly used for lists in Python?",
                        options: ["[]", "{}", "()"]
                    }
                ]
            },
            {
                title: "Functions Introduction",
                icon: "▷",
                type: "video",
                description: "Watch this video to learn the basics of functions."
            },
            {
                title: "Parameters",
                icon: "🗎",
                type: "reading",
                description: "This lesson explains how function parameters work.",
                paragraph: "Placeholder text for parameters. Parameters allow functions to receive values and use them inside reusable blocks of code.",
                points: [
                    "Creating functions with parameters",
                    "Passing values into functions",
                    "Using return values properly"
                ]
            },
            {
                title: "Functions Quiz",
                icon: "?",
                type: "quiz",
                description: "Test your knowledge about functions.",
                currentQuestion: 0,
                questions: [
                    {
                        question: "Question 1: Which keyword is used to create a function in Python?",
                        options: ["function", "def", "func"]
                    },
                    {
                        question: "Question 2: What is the purpose of a function?",
                        options: ["To reuse a block of code", "To delete variables", "To install Python"]
                    },
                    {
                        question: "Question 3: What keyword can send a value back from a function?",
                        options: ["return", "send", "give"]
                    }
                ]
            }
        ];

        modules.forEach(module => {
            const header = module.querySelector('.module-header');

            header.addEventListener('click', () => {
                module.classList.toggle('open');
            });
        });

        topics.forEach(topic => {
            topic.addEventListener('click', () => {
                const selectedIndex = Number(topic.dataset.index);

                if(selectedIndex > unlockedIndex){
                    return;
                }

                currentIndex = selectedIndex;
                showLesson(currentIndex);
            });
        });

        nextBtn.addEventListener('click', () => {
            const lesson = lessons[currentIndex];

            if(lesson.type === "quiz"){
                goToNextQuestion();
            }
        });

        prevBtn.addEventListener('click', () => {
            const lesson = lessons[currentIndex];

            if(lesson.type === "quiz" && lesson.currentQuestion > 0){
                lesson.currentQuestion--;
                showLesson(currentIndex);
            }else{
                if(currentIndex > 0){
                    currentIndex--;

                    if(lessons[currentIndex].type === "quiz"){
                        lessons[currentIndex].currentQuestion = lessons[currentIndex].questions.length - 1;
                    }

                    showLesson(currentIndex);
                }
            }
        });

        completeBtn.addEventListener('click', () => {
            markCompleted(currentIndex);

            if(currentIndex === unlockedIndex && unlockedIndex < lessons.length - 1){
                unlockedIndex++;
                unlockTopic(unlockedIndex);
            }

            if(currentIndex < lessons.length - 1){
                currentIndex++;
                showLesson(currentIndex);
            }
        });

        function markCompleted(index){
            const currentTopic = document.querySelector(`.topic[data-index="${index}"]`);

            if(currentTopic && !currentTopic.querySelector('.check')){
                const check = document.createElement('div');
                check.className = 'check';
                check.textContent = '✓';
                currentTopic.appendChild(check);
            }
        }

        function unlockTopic(index){
            const topic = document.querySelector(`.topic[data-index="${index}"]`);

            if(topic){
                topic.classList.remove('locked');

                const icon = topic.querySelector('.icon');
                icon.textContent = originalIcons[index];

                const parentModule = topic.closest('.module');

                if(parentModule){
                    parentModule.classList.add('open');
                }
            }
        }

        function goToNextQuestion(){
            const lesson = lessons[currentIndex];

            if(lesson.currentQuestion < lesson.questions.length - 1){
                lesson.currentQuestion++;
                showLesson(currentIndex);
            }
        }

        function showLesson(index){
            const lesson = lessons[index];

            contentIcon.textContent = lesson.icon;
            contentTitle.textContent = lesson.title;

            topics.forEach(topic => {
                topic.classList.remove('active');
            });

            const activeTopic = document.querySelector(`.topic[data-index="${index}"]`);

            if(activeTopic){
                activeTopic.classList.add('active');

                const parentModule = activeTopic.closest('.module');

                if(parentModule){
                    parentModule.classList.add('open');
                }
            }

            if(lesson.type === "video"){
                contentBody.innerHTML = `
                    <div class="video-box">
                        <div class="play-icon">▷</div>
                    </div>

                    <div class="lesson-description">
                        ${lesson.description}
                    </div>
                `;
            }

            if(lesson.type === "reading"){
                contentBody.innerHTML = `
                    <div class="reading-content">
                        <p>${lesson.description}</p>

                        <p style="margin-top:28px;">
                            ${lesson.paragraph}
                        </p>

                        <h2>Key Points</h2>

                        <ul>
                            ${lesson.points.map(point => `<li>${point}</li>`).join('')}
                        </ul>
                    </div>
                `;
            }

            if(lesson.type === "quiz"){
                const questionData = lesson.questions[lesson.currentQuestion];

                contentBody.innerHTML = `
                    <div class="lesson-description">
                        ${lesson.description}
                    </div>

                    <div class="quiz-box">
                        <div class="quiz-progress">
                            Question ${lesson.currentQuestion + 1} of ${lesson.questions.length}
                        </div>

                        <h3>${questionData.question}</h3>

                        ${questionData.options.map(option => `
                            <label class="quiz-option">
                                <input type="radio" name="quizOption">
                                <span>${option}</span>
                            </label>
                        `).join('')}
                    </div>
                `;
            }

            prevBtn.style.visibility = index === 0 ? "hidden" : "visible";

            if(lesson.type === "quiz"){
                nextBtn.style.visibility = lesson.currentQuestion === lesson.questions.length - 1 ? "hidden" : "visible";
            }else{
                nextBtn.style.visibility = "hidden";
            }
        }

        showLesson(currentIndex);
    </script>

</body>
</html>