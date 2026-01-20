<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: /jodify/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// 1. Fetch existing preferences to pre-fill the form
$fetch_sql = "SELECT * FROM search_preferences WHERE user_id = $user_id";
$res = mysqli_query($conn, $fetch_sql);
$prefs = mysqli_fetch_assoc($res);

// 2. Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_gender = $_POST['preferred_gender'];
    $min_age = $_POST['min_age'];
    $max_age = $_POST['max_age'];
    $p_religion = $_POST['preferred_religion'];
    $p_caste = $_POST['preferred_caste'];
    $p_education = $_POST['preferred_education'];
    $p_location = $_POST['preferred_location'];

    if ($prefs) {
        // UPDATE existing
        $sql = "UPDATE search_preferences SET 
                preferred_gender='$p_gender', min_age=$min_age, max_age=$max_age, 
                preferred_religion='$p_religion', preferred_caste='$p_caste', 
                preferred_education='$p_education', preferred_location='$p_location' 
                WHERE user_id = $user_id";
    } else {
        // INSERT new
        $sql = "INSERT INTO search_preferences (user_id, preferred_gender, min_age, max_age, preferred_religion, preferred_caste, preferred_education, preferred_location) 
                VALUES ($user_id, '$p_gender', $min_age, $max_age, '$p_religion', '$p_caste', '$p_education', '$p_location')";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?status=pref_saved");
        exit();
    } else {
        $error = "Failed to save preferences: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Match Preferences | Jodify</title>
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

<body class="bg-brand-bg font-body antialiased">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-[300px] h-full bg-white border-r border-slate-200 hidden lg:flex flex-col p-8">
            <div class="flex items-center gap-3 mb-10">
                <div
                    class="w-10 h-10 bg-brand-dark rounded-xl flex items-center justify-center text-white font-bold italic">
                    J</div>
                <span class="font-extrabold text-2xl tracking-tighter">Jodify</span>
            </div>
            <nav class="space-y-2">
                <a href="dashboard.php"
                    class="flex items-center gap-3 p-4 text-slate-500 hover:text-brand-dark font-bold transition">
                    <i class='bx bx-grid-alt text-xl'></i> Dashboard
                </a>
               
            </nav>
        </aside>

        <main class="flex-1 h-full overflow-y-auto p-12">
            <div class="max-w-3xl mx-auto">
                <div class="mb-10">
                    <h1 class="text-4xl font-extrabold tracking-tight">Partner Preferences</h1>
                    <p class="text-slate-500 font-medium mt-2">Set your criteria to find your perfect match.</p>
                </div>

                <form method="POST" class="bg-white rounded-[3rem] border border-slate-200 p-10 shadow-sm space-y-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">I am
                                looking for</label>
                            <select name="preferred_gender"
                                class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold border-none focus:ring-2 focus:ring-brand-accent/20 outline-none">
                                <option value="Female" <?= ($prefs['preferred_gender'] ?? '') == 'Female' ? 'selected' : '' ?>>A Female Partner</option>
                                <option value="Male" <?= ($prefs['preferred_gender'] ?? '') == 'Male' ? 'selected' : '' ?>>
                                    A Male Partner</option>
                                <option value="Other" <?= ($prefs['preferred_gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Any</option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Minimum
                                Age</label>
                            <input type="number" name="min_age" value="<?= $prefs['min_age'] ?? 18 ?>"
                                class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold border-none focus:ring-2 focus:ring-brand-accent/20 outline-none">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Maximum
                                Age</label>
                            <input type="number" name="max_age" value="<?= $prefs['max_age'] ?? 40 ?>"
                                class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold border-none focus:ring-2 focus:ring-brand-accent/20 outline-none">
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Preferred
                                Religion</label>
                            <input type="text" name="preferred_religion"
                                value="<?= $prefs['preferred_religion'] ?? '' ?>" placeholder="E.g. Hindu"
                                class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold border-none focus:ring-2 focus:ring-brand-accent/20 outline-none">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Preferred
                                Caste</label>
                            <input type="text" name="preferred_caste" value="<?= $prefs['preferred_caste'] ?? '' ?>"
                                placeholder="E.g. Brahmin"
                                class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold border-none focus:ring-2 focus:ring-brand-accent/20 outline-none">
                        </div>

                        <div class="col-span-2">
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Preferred
                                Location</label>
                            <input type="text" name="preferred_location"
                                value="<?= $prefs['preferred_location'] ?? '' ?>" placeholder="City or District"
                                class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold border-none focus:ring-2 focus:ring-brand-accent/20 outline-none">
                        </div>
                    </div>

                    <div class="flex justify-between">

                        <button type="submit"
                            class="w-[fit-content] bg-brand-dark text-white p-5 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-slate-800 transition shadow-xl shadow-slate-200">
                            Update My Preferences
                        </button>

                        <button type="submit"
                            class="w-[fit-content] bg-[#F43F5E] text-white p-5 rounded-2xl font-black text-sm uppercase tracking-widest transition shadow-xl shadow-slate-200">
                            Skip for Now!
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
</body>

</html>