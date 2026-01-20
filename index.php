<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jodify - Connect, Match, Love</title>

    <?php include("public/config/db.php"); ?>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#0F172A',
                        'brand-accent': '#F43F5E',
                        'brand-soft': '#FFF1F2'
                    },
                    fontFamily: { 'body': ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #FFFFFF;
            letter-spacing: -0.02em;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
        }

        .hero-pill {
            border-radius: 3rem;
        }

        .feature-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 2rem;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.08);
        }
    </style>
</head>

<body class="font-body text-brand-dark antialiased">

    <header class="fixed top-0 left-0 right-0 z-50 glass-nav border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-brand-dark rounded-2xl flex items-center justify-center text-white font-black italic text-xl">
                    J</div>
                <div class="leading-none">
                    <h1 class="text-xl font-extrabold tracking-tighter">Jodify</h1>
                    <p class="text-[10px] uppercase tracking-widest text-brand-accent font-bold">Connect. Love.</p>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-10">
                <a href="#home" class="text-sm font-semibold hover:text-brand-accent transition">Home</a>
                <a href="#features" class="text-sm font-semibold hover:text-brand-accent transition">Features</a>
                <a href="public/login.php" class="text-sm font-semibold">Login</a>
                <a href="public/register.php"
                    class="bg-brand-dark text-white px-6 py-3 rounded-2xl text-sm font-bold hover:bg-slate-800 transition">Get
                    Started</a>
            </nav>
        </div>
    </header>

    <section class="pt-44 pb-24 px-6 overflow-hidden" id="home">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8">
                    <div
                        class="inline-flex items-center gap-2 bg-brand-soft text-brand-accent px-4 py-2 rounded-full text-xs font-bold tracking-wide uppercase">
                        <i class='bx bxs-bolt'></i> New generation matchmaking
                    </div>
                    <h1 class="text-6xl md:text-7xl font-extrabold tracking-tighter leading-[1.1]">
                        Find your <span class="text-brand-accent italic">forever</span> person.
                    </h1>
                    <p class="text-slate-500 text-lg md:text-xl max-w-lg leading-relaxed font-medium">
                        Ditch the swipe fatigue. Join Jodify for authentic, human-verified connections that actually
                        lead somewhere.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="public/register.php"
                            class="bg-brand-accent text-white px-10 py-5 hero-pill text-lg font-bold hover:scale-105 transition-transform duration-300 shadow-xl shadow-rose-200">
                            Create My Profile
                        </a>
                        <div class="flex -space-x-4 items-center pl-4">
                            <img class="w-12 h-12 rounded-full border-4 border-white"
                                src="https://i.pravatar.cc/100?u=1">
                            <img class="w-12 h-12 rounded-full border-4 border-white"
                                src="https://i.pravatar.cc/100?u=2">
                            <img class="w-12 h-12 rounded-full border-4 border-white"
                                src="https://i.pravatar.cc/100?u=3">
                            <span class="pl-6 text-sm font-bold text-slate-400">Join 5k+ singles</span>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div
                        class="absolute -top-10 -right-10 w-64 h-64 bg-brand-soft rounded-full filter blur-3xl opacity-60">
                    </div>
                    <div
                        class="relative z-10 rounded-[3rem] overflow-hidden rotate-2 hover:rotate-0 transition-transform duration-700 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=1200&q=80"
                            alt="Happy Couple" class="w-full h-[600px] object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-slate-50" id="features">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-20 space-y-4">
                <h2 class="text-4xl font-extrabold tracking-tight">Built for Humans</h2>
                <p class="text-slate-500 font-medium italic">Why Jodify feels different than any other app.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card bg-white p-10 border border-slate-100">
                    <div
                        class="w-14 h-14 bg-brand-soft text-brand-accent rounded-2xl flex items-center justify-center text-2xl mb-8">
                        <i class='bx bx-check-shield'></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 tracking-tight">Verified Humans</h3>
                    <p class="text-slate-500 leading-relaxed font-medium">No bots, no fakes. Every profile is manually
                        reviewed by our team to ensure safety.</p>
                </div>

                <div class="feature-card bg-white p-10 border border-slate-100">
                    <div
                        class="w-14 h-14 bg-brand-soft text-brand-accent rounded-2xl flex items-center justify-center text-2xl mb-8">
                        <i class='bx bx-search-alt-2'></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 tracking-tight">Value Search</h3>
                    <p class="text-slate-500 leading-relaxed font-medium">Filter by education, religion, and core life
                        values. Find exactly who you're looking for.</p>
                </div>

                <div class="feature-card bg-white p-10 border border-slate-100">
                    <div
                        class="w-14 h-14 bg-brand-soft text-brand-accent rounded-2xl flex items-center justify-center text-2xl mb-8">
                        <i class='bx bx-message-square-detail'></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 tracking-tight">Private Chat</h3>
                    <p class="text-slate-500 leading-relaxed font-medium">Secure, built-in messaging that keeps your
                        personal details safe until you're ready.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6">
        <div
            class="max-w-5xl mx-auto bg-brand-dark rounded-[3rem] p-12 md:p-24 text-center text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-brand-accent opacity-10 pointer-events-none"></div>
            <div class="relative z-10 space-y-8">
                <h2 class="text-4xl md:text-6xl font-extrabold tracking-tight">Ready to meet your match?</h2>
                <p class="text-slate-400 text-lg md:text-xl max-w-xl mx-auto">Join thousand of others who have found
                    meaningful relationships. It's free to start.</p>
                <div class="flex flex-col md:flex-row gap-4 justify-center pt-6">
                    <a href="public/register.php"
                        class="bg-brand-accent text-white px-12 py-5 rounded-2xl font-bold hover:scale-105 transition">Sign
                        Up for Free</a>
                    <a href="public/login.php"
                        class="bg-white/10 backdrop-blur-md text-white px-12 py-5 rounded-2xl font-bold hover:bg-white/20 transition">Member
                        Login</a>
                </div>
            </div>
        </div>
    </section>

    <?php include("public/resources/partials/footer.php"); ?>

</body>

</html>