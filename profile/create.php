<?php
session_start();
include(__DIR__ . "/../public/config/db.php");

// 1. Authentication Check
if (!isset($_SESSION['user_id'])) {
    header("Location: /jodify/login.php");
    exit();
}

$error = '';
$success = '';
$user_id = $_SESSION['user_id'];

// 2. Check if user already has a profile
$check_sql = "SELECT id FROM profiles WHERE user_id = $user_id";
$check_result = mysqli_query($conn, $check_sql);
if (mysqli_num_rows($check_result) > 0) {
    // Redirecting to edit if profile exists prevents duplicate entries
    header("Location: edit_profile.php?id=$user_id&notice=exists");
    exit();
}

// 3. Logic for Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $gender = mysqli_real_escape_string($conn, trim($_POST['gender']));
    $age = intval($_POST['age']);
    $religion = mysqli_real_escape_string($conn, trim($_POST['religion'] ?? ''));
    $caste = mysqli_real_escape_string($conn, trim($_POST['caste'] ?? ''));
    $education = mysqli_real_escape_string($conn, trim($_POST['education'] ?? ''));
    $location = mysqli_real_escape_string($conn, trim($_POST['location'] ?? ''));
    $bio = mysqli_real_escape_string($conn, trim($_POST['bio'] ?? ''));

    $profile_photo_name = 'default.jpg';

    // File Upload logic
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['profile_photo']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed)) {
            if ($_FILES['profile_photo']['size'] <= 2 * 1024 * 1024) {
                $new_name = uniqid('profile_', true) . '.' . $file_ext;
                $upload_dir = __DIR__ . '/../uploads/profiles/';
                
                if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

                if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_dir . $new_name)) {
                    $profile_photo_name = $new_name;
                } else { $error = "Upload failed."; }
            } else { $error = "Max 2MB allowed."; }
        } else { $error = "Invalid file type."; }
    }

    if (empty($error)) {
        if (empty($full_name) || empty($gender) || $age < 18) {
            $error = "Please fill all required fields correctly.";
        } else {
            $sql = "INSERT INTO profiles (user_id, full_name, gender, age, religion, caste, education, location, bio, profile_photo, created_at) 
                    VALUES ($user_id, '$full_name', '$gender', $age, '$religion', '$caste', '$education', '$location', '$bio', '$profile_photo_name', NOW())";

            if (mysqli_query($conn, $sql)) {
                header("Location: ../public/user/set_preferences.php?success=profile_created");
                exit();
            } else { $error = "Database error: " . mysqli_error($conn); }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Profile | Jodify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .custom-input {
            background-color: #F1F5F9;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }
        .custom-input:focus {
            background-color: #FFFFFF;
            border-color: #F43F5E;
            box-shadow: 0 0 0 4px rgba(244, 63, 94, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="text-brand-dark antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-[320px] bg-white border-r border-slate-200 hidden lg:flex flex-col p-8">
            <div class="flex items-center gap-3 mb-12">
                <div class="w-10 h-10 bg-brand-dark rounded-xl flex items-center justify-center text-white font-bold italic shadow-lg shadow-slate-300">J</div>
                <span class="font-extrabold text-2xl tracking-tighter">Jodify</span>
            </div>
            
            <div class="space-y-4">
                <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">Onboarding</p>
                    <h4 class="font-bold text-sm leading-relaxed mb-4">Complete your profile to start meeting people.</h4>
                    <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-brand-accent w-2/3 h-full"></div>
                    </div>
                </div>
            </div>

            <div class="mt-auto">
                <a href="logout.php" class="flex items-center gap-3 p-4 text-slate-400 hover:text-brand-accent font-bold transition">
                    <i class='bx bx-log-out-circle text-xl'></i> Logout
                </a>
            </div>
        </aside>

        <main class="flex-1 h-full overflow-y-auto bg-brand-bg px-6 py-12 md:px-12">
            <div class="max-w-3xl mx-auto">
                
                <div class="mb-10 text-center md:text-left">
                    <h1 class="text-4xl font-extrabold tracking-tight mb-2">Build Your Identity</h1>
                    <p class="text-slate-500 font-medium italic">"A good profile is the first step toward a great match."</p>
                </div>

                <?php if ($error): ?>
                <div class="flex items-center gap-3 bg-rose-50 text-rose-600 p-5 rounded-3xl mb-8 border border-rose-100 animate-bounce">
                    <i class='bx bxs-error-circle text-2xl'></i>
                    <span class="font-bold text-sm"><?= $error ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="bg-white rounded-[3rem] border border-slate-200 p-8 md:p-12 shadow-sm space-y-8">
                    
                    <div class="flex flex-col md:flex-row items-center gap-8 pb-8 border-b border-slate-50">
                        <div class="relative group">
                            <div class="w-28 h-28 bg-slate-100 rounded-[2.5rem] flex items-center justify-center border-2 border-dashed border-slate-300 group-hover:border-brand-accent transition-colors cursor-pointer">
                                <i class='bx bx-image-add text-3xl text-slate-400 group-hover:text-brand-accent'></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Profile Picture *</label>
                            <input type="file" name="profile_photo" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-brand-dark file:text-white hover:file:bg-slate-700 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Full Name</label>
                            <input type="text" name="full_name" placeholder="John Doe" class="custom-input w-full rounded-2xl px-6 py-4 font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Gender</label>
                            <select name="gender" class="custom-input w-full rounded-2xl px-6 py-4 font-semibold appearance-none">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Age</label>
                            <input type="number" name="age" min="18" max="80" placeholder="18" class="custom-input w-full rounded-2xl px-6 py-4 font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Religion</label>
                            <input type="text" name="religion" placeholder="E.g. Hindu" class="custom-input w-full rounded-2xl px-6 py-4 font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Caste</label>
                            <input type="text" name="caste" placeholder="E.g. Brahmin" class="custom-input w-full rounded-2xl px-6 py-4 font-semibold">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Current Location</label>
                            <input type="text" name="location" placeholder="City, Country" class="custom-input w-full rounded-2xl px-6 py-4 font-semibold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Your Story (Bio)</label>
                        <textarea name="bio" rows="4" placeholder="Tell us about your hobbies, values, and what you're looking for..." class="custom-input w-full rounded-3xl px-6 py-4 font-medium italic leading-relaxed"></textarea>
                    </div>

                    <div class="pt-6 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-brand-accent text-white py-5 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-rose-600 transition shadow-xl shadow-rose-100 transform hover:-translate-y-1">
                            Launch My Profile
                        </button>
                       
                    </div>
                </form>

                <p class="text-center mt-8 text-slate-400 text-xs font-bold uppercase tracking-widest">
                    Secure & Private &bull; Jodify 2026
                </p>
            </div>
        </main>
    </div>

</body>
</html>