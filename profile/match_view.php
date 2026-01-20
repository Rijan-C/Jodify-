<?php
session_start();
include("../public/config/db.php");

// 1. Auth Check (Ensure viewer is logged in)
if (!isset($_SESSION['user_id'])) {
    header("Location: /jodify/login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$view_user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($view_user_id === 0) {
    die("Invalid User ID.");
}

// 2. Fetch the profile being viewed
$sql = "SELECT * FROM profiles WHERE user_id = $view_user_id";
$result = mysqli_query($conn, $sql);
$profile = mysqli_fetch_assoc($result);

if (!$profile) {
    die("Profile not found.");
}

// 3. Fetch current user data for the sidebar
$me_sql = "SELECT * FROM profiles WHERE user_id = $current_user_id";
$me_result = mysqli_query($conn, $me_sql);
$me = mysqli_fetch_assoc($me_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($profile['full_name']); ?> | Jodify</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#0F172A',
                        'brand-accent': '#F43F5E',
                        'brand-bg': '#F8FAFC'
                    },
                    fontFamily: { 'body': ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-gradient { background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%); }
    </style>
</head>

<body class="bg-brand-bg text-brand-dark antialiased overflow-hidden">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-[320px] h-full sidebar-gradient border-r border-slate-200 flex flex-col flex-shrink-0 z-50">
            <div class="p-8">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-9 h-9 bg-brand-dark rounded-xl flex items-center justify-center text-white font-bold italic">J</div>
                    <span class="font-bold text-lg tracking-tight">Jodify</span>
                </div>

                <nav class="space-y-2">
                    <a href="/jodify/public/user/dashboard.php" class="flex items-center gap-3 p-4 text-slate-500 hover:text-brand-dark font-semibold transition">
                        <i class='bx bx-grid-alt text-xl'></i> Dashboard
                    </a>
                    <a href="/jodify/public/discover.php" class="flex items-center gap-3 p-4 bg-brand-accent/5 text-brand-accent rounded-2xl font-bold transition">
                        <i class='bx bx-search text-xl'></i> Discover
                    </a>
                    
                </nav>
            </div>

            <div class="mt-auto p-8 border-t border-slate-100">
                <div class="flex items-center gap-3">
                    <img src="/jodify/uploads/profiles/<?php echo $me['profile_photo']; ?>" class="w-10 h-10 rounded-full object-cover">
                    <div>
                        <p class="text-xs font-bold"><?php echo htmlspecialchars($me['full_name']); ?></p>
                        <a href="/jodify/logout.php" class="text-[10px] font-bold text-brand-accent uppercase tracking-widest">Logout</a>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 h-full overflow-y-auto p-12">
            
            <div class="mb-8">
                <a href="/jodify/public/user/dashboard.php" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-brand-accent transition">
                    <i class='bx bx-left-arrow-alt text-xl'></i> BACK TO DISCOVER
                </a>
            </div>

            <div class="max-w-4xl">
                <div class="bg-white rounded-[3rem] border border-slate-200 overflow-hidden shadow-sm">
                    <div class="relative h-64 bg-slate-100">
                        <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/20 to-brand-dark/60"></div>
                        <div class="absolute -bottom-16 left-12 p-1 bg-white rounded-[2.5rem] shadow-xl">
                            <img src="/jodify/uploads/profiles/<?php echo $profile['profile_photo']; ?>" 
                                 class="w-44 h-44 rounded-[2.3rem] object-cover">
                        </div>
                    </div>

                    <div class="pt-20 px-12 pb-12">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-4xl font-extrabold tracking-tight">
                                    <?php echo htmlspecialchars($profile['full_name']); ?>, <?php echo $profile['age']; ?>
                                </h1>
                                <p class="text-slate-500 font-medium mt-1 flex items-center gap-2">
                                    <i class='bx bxs-map text-brand-accent'></i> <?php echo htmlspecialchars($profile['location']); ?>
                                </p>
                            </div>
                            
                        </div>

                        <div class="grid grid-cols-3 gap-6 mt-12">
                            <div class="p-5 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                                <i class='bx bx-book-bookmark text-2xl text-brand-accent mb-2'></i>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Education</p>
                                <p class="text-sm font-bold text-slate-700"><?php echo htmlspecialchars($profile['education']); ?></p>
                            </div>
                            <div class="p-5 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                                <i class='bx bx-sun text-2xl text-brand-accent mb-2'></i>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Religion</p>
                                <p class="text-sm font-bold text-slate-700"><?php echo htmlspecialchars($profile['religion']); ?></p>
                            </div>
                            <div class="p-5 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                                <i class='bx bx-group text-2xl text-brand-accent mb-2'></i>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Caste</p>
                                <p class="text-sm font-bold text-slate-700"><?php echo htmlspecialchars($profile['caste'] ?: 'Not Specified'); ?></p>
                            </div>
                        </div>

                        <div class="mt-12">
                            <h3 class="text-lg font-extrabold mb-4 flex items-center gap-2">
                                <span class="w-8 h-1 bg-brand-accent rounded-full"></span>
                                About Me
                            </h3>
                            <div class="bg-slate-50/50 p-8 rounded-[2rem] border border-dashed border-slate-200">
                                <p class="text-slate-600 leading-relaxed font-medium italic">
                                    "<?php echo nl2br(htmlspecialchars($profile['bio'])); ?>"
                                </p>
                            </div>
                        </div>

                    
                    </div>
                </div>
            </div>

            <footer class="mt-12 text-center pb-8">
                <p class="text-slate-300 text-[10px] font-bold tracking-[0.2em] uppercase">© 2024 Jodify Matchmaking</p>
            </footer>
        </main>
    </div>

</body>
</html>