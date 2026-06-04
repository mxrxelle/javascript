<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certly — Build Your Skills. Define Your Future.</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: '#002855',
              accent: '#ffca28',
              muted: {
                DEFAULT: '#f1f5f9',
                foreground: '#64748b'
              },
              foreground: '#0f172a',
              border: '#e2e8f0',
            }
          }
        }
      }
    </script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-white">

    <!-- Navigation Bar -->
    <nav class="sticky top-0 z-50 bg-white shadow-sm border-b border-border">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-8">
            <!-- Left Side -->
            <div class="flex items-center gap-8">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <span class="text-2xl text-primary">C</span>
                    </div>
                    <span class="text-2xl text-primary" style="font-weight: 600;">Certly</span>
                </div>

                <a href="#" class="flex items-center gap-1 text-foreground hover:text-primary transition-colors">
                    Explore Courses <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </a>

                <a href="#" class="text-foreground hover:text-primary transition-colors">About</a>
            </div>

            <!-- Center - Search Bar -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground"></i>
                    <input
                        type="text"
                        placeholder="Search courses..."
                        class="w-full pl-11 pr-4 py-2.5 bg-muted border border-border rounded-full focus:outline-none focus:ring-2 focus:ring-accent transition-all"
                    />
                </div>
            </div>

            <!-- Right Side -->
            <a
                href="{{ route('login') }}"
                class="px-6 py-2.5 border-2 border-accent text-accent rounded-lg hover:bg-accent hover:text-primary transition-colors inline-block"
                style="font-weight: 600;"
            >
                Login
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="h-1 w-24 bg-accent mb-6"></div>
                <h1 class="text-6xl text-primary mb-6 leading-tight" style="font-weight: 700;">
                    Build Your Skills. Define Your Future.
                </h1>
                <p class="text-xl text-muted-foreground mb-10 leading-relaxed">
                    Start your journey today with industry-recognized certification courses in Tech, Business, and more. Free to join.
                </p>
                <div class="flex gap-4">
                    <a
                        href="{{ route('register') }}"
                        class="px-10 py-4 bg-accent text-primary rounded-lg hover:opacity-90 transition-opacity shadow-md inline-block"
                        style="font-weight: 600;"
                    >
                        Get Started
                    </a>
                    <a
                        href="/"
                        class="px-10 py-4 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity shadow-md inline-block"
                        style="font-weight: 600;"
                    >
                        Explore Subjects
                    </a>
                </div>
            </div>

            <div class="bg-muted rounded-3xl p-12 flex items-center justify-center h-[450px]">
                <div class="grid grid-cols-3 gap-6">
                    <div class="w-28 h-28 bg-primary/10 rounded-full flex items-center justify-center">
                        <div class="w-20 h-20 bg-primary rounded-full"></div>
                    </div>
                    <div class="w-28 h-28 bg-primary/10 rounded-full flex items-center justify-center">
                        <div class="w-20 h-20 bg-primary rounded-full"></div>
                    </div>
                    <div class="w-28 h-28 bg-primary/10 rounded-full flex items-center justify-center">
                        <div class="w-20 h-20 bg-primary rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Engineered for True Mastery Section -->
    <section class="bg-muted py-20">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl text-primary text-center mb-16" style="font-weight: 700;">
                Engineered for True Mastery
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-accent/20 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="ticket" class="w-8 h-8 text-primary"></i>
                    </div>
                    <div class="text-2xl text-primary mb-2" style="font-weight: 700;">1. Activate with Voucher</div>
                    <p class="text-muted-foreground leading-relaxed">
                        Enter your unique institutional access key to instantly unlock premium training modules assigned to your track.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-accent/20 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="video" class="w-8 h-8 text-primary"></i>
                    </div>
                    <div class="text-2xl text-primary mb-2" style="font-weight: 700;">2. Consume Rich Materials</div>
                    <p class="text-muted-foreground leading-relaxed">
                        Dive into structured lessons curated by expert facilitators, featuring integrated lecture slide decks and deep-dive video content.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-accent/20 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="lock" class="w-8 h-8 text-primary"></i>
                    </div>
                    <div class="text-2xl text-primary mb-2" style="font-weight: 700;">3. Complete Gatekept Assessments</div>
                    <p class="text-muted-foreground leading-relaxed">
                        Prove your comprehension through randomized module quizzes. Achieve passing scores to sequentially unlock subsequent advanced topics.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tailored Frameworks for Every Role Section -->
    <section class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl text-primary text-center mb-16" style="font-weight: 700;">
                Tailored Frameworks for Every Role
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="bg-white border-2 border-border rounded-2xl p-10 hover:border-accent transition-colors">
                    <div class="w-20 h-20 bg-primary rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="user" class="w-10 h-10 text-white"></i>
                    </div>
                    <h3 class="text-3xl text-primary mb-4" style="font-weight: 700;">For Students</h3>
                    <p class="text-lg text-muted-foreground leading-relaxed">
                        Experience a distraction-free student dashboard with immediate progress trackers, transparent gradebooks, and sequential module locks that ensure comprehensive learning.
                    </p>
                </div>

                <div class="bg-white border-2 border-border rounded-2xl p-10 hover:border-accent transition-colors">
                    <div class="w-20 h-20 bg-primary rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="settings" class="w-10 h-10 text-white"></i>
                    </div>
                    <h3 class="text-3xl text-primary mb-4" style="font-weight: 700;">For Facilitators</h3>
                    <p class="text-lg text-muted-foreground leading-relaxed">
                        Leverage a modular quiz constructor with 20-question randomized test pools and complete visibility into real-time student performance.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Certly Section -->
    <section class="bg-muted py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-5xl text-primary leading-tight" style="font-weight: 700;">
                        Bridging the gap between baseline knowledge and certified excellence.
                    </h2>
                </div>
                <div>
                    <p class="text-lg text-foreground leading-relaxed mb-6">
                        Certly is more than a learning platform — it's a comprehensive internal learning management environment designed for structural completeness and rigorous academic validation.
                    </p>
                    <p class="text-lg text-foreground leading-relaxed mb-6">
                        Our mission is to provide institutions with a trusted framework for delivering certification programs that demand sequential progression, validated assessments, and transparent administrator oversight.
                    </p>
                    <p class="text-lg text-foreground leading-relaxed">
                        From voucher-based access control to randomized quiz pools and gated content unlocking, every feature is engineered to ensure learners achieve genuine mastery before advancing.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom Call-To-Action Banner -->
    <section class="bg-white py-20">
        <div class="max-w-5xl mx-auto px-6">
            <div class="bg-primary rounded-3xl p-16 text-center shadow-2xl">
                <h2 class="text-5xl text-white mb-8 leading-tight" style="font-weight: 700;">
                    Ready to define your learning curve?
                </h2>
                <a
                    href="{{ route('register') }}"
                    class="inline-block px-12 py-5 bg-accent text-primary rounded-xl hover:opacity-90 transition-opacity shadow-lg text-xl"
                    style="font-weight: 700;"
                >
                    Create Your Free Account
                </a>
            </div>
        </div>
    </section>

    <!-- Redesigned Footer -->
    <footer class="bg-primary text-white py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                            <span class="text-2xl text-primary">C</span>
                        </div>
                        <span class="text-2xl" style="font-weight: 600;">Certly</span>
                    </div>
                    <p class="text-white/70 leading-relaxed">Building skills for the future</p>
                </div>
                <div>
                    <h3 class="mb-4 text-lg" style="font-weight: 600;">Company</h3>
                    <ul class="space-y-3 text-white/70">
                        <li><a href="#" class="hover:text-accent transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-lg" style="font-weight: 600;">Support</h3>
                    <ul class="space-y-3 text-white/70">
                        <li><a href="#" class="hover:text-accent transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-lg" style="font-weight: 600;">Legal</h3>
                    <ul class="space-y-3 text-white/70">
                        <li><a href="#" class="hover:text-accent transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-white/20 text-center">
                <p class="text-white/50 text-sm">© 2026 Certly. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
