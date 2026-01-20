<?php
session_start();
include(__DIR__ . "/../public/config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: /jodify/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Get preferences in one simple query
$pref_res = mysqli_query($conn, "SELECT * FROM search_preferences WHERE user_id = $user_id");
$p = mysqli_fetch_assoc($pref_res);

// 2. Simple logic: If preferences exist, find matching profiles
$result = null;
if ($p) {
    $gender = $p['preferred_gender'];
    $min = $p['min_age'];
    $max = $p['max_age'];
    $rel = $p['preferred_religion'];

    // Constructing a simple filtered query
    $query = "SELECT * FROM profiles WHERE user_id != $user_id 
              AND gender = '$gender' 
              AND age BETWEEN $min AND $max";

    // Add religion check only if it's set
    if (!empty($rel)) {
        $query .= " AND religion = '$rel'";
    }

    $query .= " ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preferred Matches | Jodify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 'brand-dark': '#0F172A', 'brand-accent': '#F43F5E', 'brand-bg': '#F8FAFC' },
                    fontFamily: { 'body': ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
</head>

<body class="bg-brand-bg font-body text-brand-dark antialiased">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-[300px] h-full bg-white border-r border-slate-200 hidden lg:flex flex-col p-8">
            <div class="flex items-center gap-3 mb-10">
                <div
                    class="w-10 h-10 bg-brand-dark rounded-xl flex items-center justify-center text-white font-bold italic shadow-lg">
                    J</div>
                <span class="font-extrabold text-2xl tracking-tighter">Jodify</span>
            </div>

            <nav class="space-y-2">
                <a href="../public/user/dashboard.php"
                    class="flex items-center gap-3 p-4 text-slate-500 hover:text-brand-dark font-semibold transition">
                    <i class='bx bx-grid-alt text-xl'></i> Dashboard
                </a>

                <a href="discover.php"
                    class="flex items-center gap-3 p-4 text-slate-500 hover:text-brand-dark font-semibold transition">
                    <i class='bx bxs-compass text-xl'></i> Discover
                </a>

                <a href="prefer_view.php"
                    class="flex items-center gap-3 p-4 bg-brand-accent/5 text-brand-accent rounded-2xl font-bold transition">
                    <i class='bx bx-slider-alt text-xl'></i> My Preferences
                </a>
            </nav>


        </aside>

        <main class="flex-1 h-full overflow-y-auto p-10">
            <div class="max-w-6xl mx-auto">

                <header class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold tracking-tight">Your Smart Matches</h1>
                        <p class="text-slate-500 font-medium mt-1">Showing profiles that match your specific partner
                            preferences.</p>
                    </div>
                    <a href="set_preferences.php"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-sm hover:bg-slate-50 transition shadow-sm">
                        <i class='bx bx-edit-alt'></i> Edit Preferences
                    </a>
                </header>

                <?php if (!$p): ?>
                    <div class="bg-white rounded-[3rem] p-16 text-center border border-slate-100 shadow-sm">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class='bx bx-search-alt text-4xl text-slate-300'></i>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">No Preferences Found</h2>
                        <p class="text-slate-500 mb-8 max-w-sm mx-auto">Tell us what you are looking for in a partner so we
                            can show you the best matches.</p>
                        <a href="set_preferences.php"
                            class="bg-brand-dark text-white px-8 py-4 rounded-2xl font-bold uppercase text-xs tracking-widest hover:bg-slate-800 transition">Setup
                            Preferences Now</a>
                    </div>

                <?php elseif (mysqli_num_rows($result) > 0): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php while ($match = mysqli_fetch_assoc($result)):
                            $photo = !empty($match['profile_photo']) ? '../uploads/profiles/' . $match['profile_photo'] : 'https://ui-avatars.com/api/?name=' . urlencode($match['full_name']);
                            ?>
                            <div
                                class="group bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-slate-200 transition-all duration-500">
                                <div class="relative h-72 overflow-hidden">
                                    <img src="<?= $photo ?>"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                    <div class="absolute bottom-4 left-4 flex gap-2">
                                        <span
                                            class="bg-white/90 backdrop-blur-md px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider shadow-sm">
                                            <?= htmlspecialchars($match['religion']) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="p-8">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-xl font-extrabold tracking-tight">
                                                <?= htmlspecialchars($match['full_name']) ?>, <?= $match['age'] ?>
                                            </h3>
                                            <p class="text-slate-400 text-sm font-semibold flex items-center gap-1">
                                                <i class='bx bxs-map-pin text-brand-accent'></i>
                                                <?= htmlspecialchars($match['location']) ?>
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-slate-500 text-sm line-clamp-2 mb-6 italic leading-relaxed">
                                        "<?= htmlspecialchars($match['bio'] ?: 'No bio available.') ?>"
                                    </p>
                                    <div class="flex gap-3">
                                        <a href="../profile/match_view.php?id=<?= $match['user_id'] ?>"
                                            class="flex-1 bg-brand-dark text-white py-4 rounded-xl text-center font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition shadow-lg shadow-slate-100">
                                            View Details
                                        </a>
                                        <button
                                            class="w-12 h-12 rounded-xl border-2 border-slate-50 flex items-center justify-center text-slate-300 hover:text-brand-accent hover:border-brand-accent/20 transition">
                                            <i class='bx bxs-heart text-xl'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                <?php else: ?>
                    <div class="bg-white rounded-[3rem] p-16 text-center border border-slate-100 shadow-sm">
                        <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class='bx bx-ghost text-4xl text-brand-accent'></i>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">No Matches Right Now</h2>
                        <p class="text-slate-500 mb-8 max-w-sm mx-auto">Try broadening your age range or location
                            preferences to see more people.</p>
                        <a href="set_preferences.php" class="text-brand-accent font-bold hover:underline">Adjust Criteria
                            →</a>
                    </div>
                <?php endif; ?>

            </div>
        </main>
    </div>

</body>

</html>