<?php
session_start(); // Ensure session is started to access user_id
include "../public/config/db.php";

// 1. Get current user ID from session
$current_user_id = $_SESSION['user_id'] ?? 0;

// 2. Collect and sanitize filters
$filters = [];

// ALWAYS exclude own profile
if ($current_user_id > 0) {
    $filters[] = "user_id != $current_user_id";
}

// Add user-selected filters (Religion, Gender)
foreach (['religion', 'gender'] as $key) {
    if (!empty($_GET[$key])) {
        // Sanitize input to prevent SQL injection
        $val =  $_GET[$key];
        $filters[] = "$key = '$val'";
    }
}

// 3. Construct Query
$sql = "SELECT * FROM profiles";

// If there are filters (including the self-exclusion), add WHERE clause
if (count($filters) > 0) {
    $sql .= " WHERE " . implode(' AND ', $filters);
}

$sql .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Matches | Jodify</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
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
                        'brand-bg': '#F8FAFC'
                    },
                    fontFamily: { 'body': ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .sidebar-gradient {
            background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-brand-bg text-brand-dark antialiased overflow-hidden">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-[300px] h-full sidebar-gradient border-r border-slate-200 flex flex-col flex-shrink-0 z-50">
            <div class="p-8">
                <div class="flex items-center gap-3 mb-10">
                    <div
                        class="w-10 h-10 bg-brand-dark rounded-xl flex items-center justify-center text-white font-bold italic shadow-lg shadow-slate-200">
                        J</div>
                    <span class="font-extrabold text-xl tracking-tighter">Jodify</span>
                </div>

                <nav class="space-y-2">
                    <a href="../public/user/dashboard.php"
                        class="flex items-center gap-3 p-4 text-slate-500 hover:text-brand-dark font-semibold transition">
                        <i class='bx bx-grid-alt text-xl'></i> Dashboard
                    </a>
                    <a href="discover.php"
                        class="flex items-center gap-3 p-4 bg-brand-accent/5 text-brand-accent rounded-2xl font-bold transition">
                        <i class='bx bxs-compass text-xl'></i> Discover
                    </a>

                </nav>
            </div>

            <div class="mt-auto p-8 border-t border-slate-100">
                <a href="/jodify/logout.php"
                    class="flex items-center gap-3 text-rose-500 font-bold text-xs uppercase tracking-widest">
                    <i class='bx bx-log-out text-lg'></i> Logout
                </a>
            </div>
        </aside>

        <main class="flex-1 h-full overflow-y-auto custom-scrollbar p-10">

            <div class="max-w-6xl mx-auto">
                <div class="mb-10">
                    <h1 class="text-4xl font-extrabold tracking-tight text-slate-900">Find Your Match</h1>
                    <p class="text-slate-500 font-medium mt-1">Discover verified profiles tailored to your values.</p>
                </div>

                <div class="sticky top-0 z-10 bg-brand-bg/80 backdrop-blur-md pb-8">
                    <div class="bg-white p-4 rounded-[2rem] shadow-sm border border-slate-200/60">
                        <form method="GET" class="flex flex-col md:flex-row gap-4 items-center">
                            <div class="relative flex-1 w-full">
                                <i
                                    class='bx bx-user text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 text-lg'></i>
                                <select name="gender"
                                    class="w-full bg-slate-50 border-none rounded-2xl pl-12 pr-4 py-4 text-sm font-bold appearance-none outline-none focus:ring-2 focus:ring-brand-accent/20 transition">
                                    <option value="">All Genders</option>
                                    <option value="Male" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>

                            <div class="relative flex-1 w-full">
                                <i
                                    class='bx bx-sun text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 text-lg'></i>
                                <input type="text" name="religion" placeholder="Religion (Hindu, Buddhist...)"
                                    value="<?php echo htmlspecialchars($_GET['religion'] ?? ''); ?>"
                                    class="w-full bg-slate-50 border-none rounded-2xl pl-12 pr-4 py-4 text-sm font-bold outline-none focus:ring-2 focus:ring-brand-accent/20 transition">
                            </div>

                            <button type="submit"
                                class="w-full md:w-auto px-10 py-4 bg-brand-dark text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                                Apply
                            </button>

                            <a href="discover.php"
                                class="px-4 text-slate-400 font-bold text-xs uppercase tracking-widest hover:text-brand-accent transition">Clear</a>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($match = mysqli_fetch_assoc($result)):
                            $photo = !empty($match['profile_photo']) ? '/jodify/uploads/profiles/' . $match['profile_photo'] : 'https://ui-avatars.com/api/?name=' . urlencode($match['full_name']);
                            ?>
                            <div
                                class="group bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-slate-200 hover:-translate-y-2 transition-all duration-500">
                                <div class="relative h-64 overflow-hidden">
                                    <img src="<?php echo $photo; ?>"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase text-brand-dark">
                                            <?php echo htmlspecialchars($match['religion']); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <h4 class="text-lg font-extrabold"><?php echo htmlspecialchars($match['full_name']); ?>,
                                        <?php echo $match['age']; ?>
                                    </h4>
                                    <p class="text-slate-400 text-xs font-semibold flex items-center gap-1 mt-1">
                                        <i class='bx bxs-map-pin text-brand-accent'></i>
                                        <?php echo htmlspecialchars($match['location']); ?>
                                    </p>

                                    <div class="mt-6 flex gap-2">
                                        <a href="../profile/match_view.php?id=<?php echo $match['user_id']; ?>"
                                            class="flex-1 py-3 bg-slate-50 text-brand-dark text-center rounded-xl font-bold text-[11px] tracking-widest uppercase hover:bg-brand-accent hover:text-white transition-all shadow-sm">
                                            View Profile
                                        </a>
                                        
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-span-full py-32 text-center">
                            <i class='bx bx-search-alt text-6xl text-slate-200 mb-4'></i>
                            <p class="text-slate-400 font-bold">No matches found for your criteria.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-20">
                    <?php include('../public/resources/partials/footer.php'); ?>
                </div>
            </div>
        </main>
    </div>

</body>

</html>