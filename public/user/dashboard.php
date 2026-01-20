<?php
session_start();
include("../config/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /jodify/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// Fetch user profile data
$profiles_sql = "SELECT * FROM profiles WHERE user_id = '$user_id'";
$profiles_result = mysqli_query($conn, $profiles_sql);

if (!$profiles_result) {
    die("Profile query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($profiles_result) === 0) {
    // User doesn't have a profile, redirect to create
    header("Location: /jodify/profile/create.php");
    exit();
}


$profiles = mysqli_fetch_assoc($profiles_result);


$user_sql = "SELECT email, full_name, created_at FROM users WHERE user_id = '$user_id'";
$user_result = mysqli_query($conn, $user_sql);

if (!$user_result) {
    die("User query failed: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($user_result);


// Fetch recommended matches (opposite gender, similar age)
// Check if gender field exists in profile
if (isset($profiles['gender']) && isset($profiles['age'])) {
    $match_sql = "SELECT p.* FROM profiles p 
                  WHERE p.user_id != '$user_id' 
                  AND p.gender != '{$profiles['gender']}'
                  AND p.age BETWEEN {$profiles['age']} - 5 AND {$profiles['age']} + 5
                  LIMIT 3";
}

$match_result = mysqli_query($conn, $match_sql);
$matches = [];
if ($match_result) {
    while ($row = mysqli_fetch_assoc($match_result)) {
        $matches[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Jodify</title>
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

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 10px;
        }

        .sidebar-gradient {
            background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);
        }
    </style>
</head>

<body class="bg-brand-bg text-brand-dark antialiased ">

    <div class="flex h-screen ">

        <aside
            class="w-[380px] h-full sidebar-gradient border-r border-slate-200 flex flex-col flex-shrink-0 z-50 shadow-xl">
            <div class="p-8 flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-brand-dark rounded-2xl flex items-center justify-center text-white font-black italic shadow-lg shadow-slate-200">
                    J</div>
                <span class="font-extrabold text-xl tracking-tighter">Jodify</span>
            </div>

            <div class="flex-1 px-8 overflow-y-auto custom-scrollbar">
                <div class="relative w-32 h-32 mx-auto mb-6 group">
                    <div
                        class="absolute inset-0 bg-brand-accent rounded-[2.5rem] rotate-6 opacity-10 group-hover:rotate-12 transition-transform">
                    </div>
                    <img src="<?php echo !empty($profiles['profile_photo']) ? '/jodify/uploads/profiles/' . $profiles['profile_photo'] : 'https://ui-avatars.com/api/?name=' . urlencode($user_data['full_name']); ?>"
                        class="relative w-full h-full rounded-[2.5rem] object-cover shadow-2xl border-4 border-white">
                </div>

                <div class="text-center mb-8">
                    <h2 class="text-2xl font-extrabold tracking-tight">
                        <?php echo htmlspecialchars($profiles['full_name']); ?>
                    </h2>
                    <div class="flex items-center justify-center gap-2 text-slate-500 text-sm font-medium mt-1">
                        <i class='bx bxs-map text-brand-accent'></i>
                        <?php echo htmlspecialchars($profiles['location']); ?> • <?php echo $profiles['age']; ?> Yrs
                    </div>
                </div>

                <div class="">
                    <a href="dashboard.php"
                        class="flex items-center gap-3 p-4 bg-brand-accent/5 text-brand-accent rounded-2xl font-bold transition">
                        <i class='bx bxs-grid-alt text-xl'></i> Dashboard
                    </a>

                    <a href="../discover.php"
                        class="flex items-center gap-3 p-4 text-slate-500 hover:text-brand-dark font-semibold transition">
                        <i class='bx bx-compass text-xl'></i> Discover
                    </a>

                    <a href="../../profile/view.php?id=<?php echo $user_id; ?>"
                        class="flex items-center gap-3 p-4 text-slate-500 hover:text-brand-dark font-semibold transition">
                        <i class='bx bx-user-circle text-xl'></i> View My Profile
                    </a>

                    <a href="../prefer_view.php"
                        class="flex items-center gap-3 p-4 text-slate-500 hover:text-brand-dark font-semibold transition">
                        <i class='bx bx-slider-alt text-xl'></i> My Preferences
                    </a>
                </div>
            </div>

            <div class="p-8 space-y-3">
                <a href="/jodify/profile/edit.php?id=<?php echo $user_id; ?>"
                    class="flex items-center justify-center gap-2 w-full py-4 bg-brand-dark text-white rounded-2xl font-bold text-xs hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                    <i class='bx bx-edit-alt'></i> EDIT PROFILE
                </a>
                <a href="/jodify/logout.php"
                    class="flex items-center justify-center gap-2 w-full py-4 bg-white border border-slate-200 text-rose-500 rounded-2xl font-bold text-xs hover:bg-rose-50 transition">
                    <i class='bx bx-log-out'></i> LOGOUT
                </a>
            </div>
        </aside>

        <main class="flex-1 h-full overflow-y-auto custom-scrollbar p-12">

            <header class="flex justify-between items-end mb-12">
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight text-slate-900">Recommended Matches</h1>
                    <p class="text-slate-500 font-medium mt-2">Personalized suggestions based on your preferences.</p>
                </div>

            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                <?php if (empty($matches)): ?>
                    <div
                        class="col-span-full py-32 bg-white border-2 border-dashed border-slate-200 rounded-[3rem] flex flex-col items-center justify-center text-center px-6">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                            <i class='bx bx-search-alt text-4xl text-slate-300'></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">No matches yet</h3>
                        <p class="text-slate-500 max-w-xs mt-2">Expand your age range or update your location to see more
                            recommendations.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($matches as $match):
                        $photo = !empty($match['profile_photo']) ? '/jodify/uploads/profiles/' . $match['profile_photo'] : 'https://via.placeholder.com/300x400';
                        ?>
                        <div
                            class="group bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-slate-200 transition-all duration-500">
                            <div class="relative h-80">
                                <img src="<?php echo $photo; ?>"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60">
                                </div>
                                <div class="absolute bottom-6 left-6 right-6">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span
                                            class="bg-white/20 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest border border-white/30">
                                            <?php echo htmlspecialchars($match['location']); ?>
                                        </span>
                                    </div>
                                    <h4 class="text-white text-2xl font-bold tracking-tight">
                                        <?php echo htmlspecialchars($match['full_name']); ?>, <?php echo $match['age']; ?>
                                    </h4>
                                </div>
                            </div>
                            <div class="p-8">
                                <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold mb-6">
                                    <i class='bx bx-briefcase-alt-2 text-brand-accent'></i>
                                    <?php echo htmlspecialchars($match['education']); ?>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>



        </main>

    </div>
    <?php include('../../public/resources/partials/footer.php'); ?>

</body>

</html>