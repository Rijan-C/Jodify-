<?php
session_start();
include "../public/config/db.php";

// 1. Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: /jodify/login.php");
    exit();
}

$user_id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];

// 2. FETCH existing data
$fetch_result = mysqli_query($conn, "SELECT * FROM profiles WHERE user_id = $user_id");
$row = mysqli_fetch_assoc($fetch_result);

if (!$row) {
    die("Profile not found.");
}

// 3. UPDATE Logic
if (isset($_POST['update'])) {
    // Sanitize inputs to prevent SQL errors with single quotes
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $age = intval($_POST['age']);
    $religion = $_POST['religion'];
    $caste = $_POST['caste'];
    $education = $_POST['education'];
    $location = $_POST['location'];
    $bio = $_POST['bio'];

    // Handle Image Upload
    $photo_query = "";
    if (!empty($_FILES['profile_photo']['name'])) {
        $target_dir = "../uploads/profiles/";
        $file_name = time() . "_" . basename($_FILES["profile_photo"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            $photo_query = ", profile_photo = '$file_name'";
        }
    }

    $updateSql = "UPDATE profiles SET 
                  full_name = '$full_name', 
                  gender = '$gender', 
                  age = $age, 
                  religion = '$religion', 
                  caste = '$caste', 
                  education = '$education', 
                  location = '$location', 
                  bio = '$bio' 
                  $photo_query
                  WHERE user_id = $user_id";

    if (mysqli_query($conn, $updateSql)) {
        // Option A: Using Curly Braces (Cleanest)
        header("Location: view.php?id={$row['user_id']}");
        exit;
    } else {
        // Option B: Concatenation (Safest for older PHP versions)
        $error = "Update failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile | Jodify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#0F172A',
                        'brand-accent': '#F43F5E',
                        'brand-bg': '#F8FAFC'
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .input-field {
            width: 100%;
            padding: 12px 20px;
            background-color: #F1F5F9;
            border: 2px solid transparent;
            border-radius: 1.25rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .input-field:focus {
            background-color: #FFF;
            border-color: #F43F5E;
            outline: none;
            box-shadow: 0 10px 15px -3px rgba(244, 63, 94, 0.1);
        }
    </style>
</head>

<body class="bg-brand-bg min-h-screen p-6 md:p-12">

    <div class="max-w-2xl mx-auto">
        <a href="view.php?id=<?php echo $row['user_id']; ?>"
            class="inline-flex items-center gap-2 text-slate-400 font-bold text-xs uppercase tracking-widest mb-8 hover:text-brand-accent transition">
            <i class='bx bx-arrow-back text-lg'></i> Cancel Changes
        </a>

        <div class="bg-white rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-10 md:p-16">
            <header class="mb-10">
                <h1 class="text-4xl font-black tracking-tight text-brand-dark">Edit Profile</h1>
                <p class="text-slate-500 font-medium mt-2">Update your information to get better matches.</p>
            </header>

            <form method="POST" action="" enctype="multipart/form-data" class="space-y-8">

                <div
                    class="flex items-center gap-8 p-6 bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200">
                    <img src="/jodify/uploads/profiles/<?php echo $row['profile_photo']; ?>"
                        class="w-24 h-24 rounded-[2rem] object-cover shadow-md border-4 border-white">
                    <div class="flex-1">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Profile
                            Photo</label>
                        <input type="file" name="profile_photo"
                            class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-brand-accent file:text-white hover:file:bg-rose-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3 ml-2">Full
                            Name *</label>
                        <input type="text" name="full_name" required class="input-field"
                            value="<?php echo htmlspecialchars($row['full_name']); ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3 ml-2">Age
                            *</label>
                        <input type="number" name="age" min="18" max="99" required class="input-field"
                            value="<?php echo $row['age']; ?>">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3 ml-2">Gender
                            *</label>
                        <select name="gender" class="input-field appearance-none">
                            <option value="Male" <?php if ($row['gender'] == 'Male')
                                echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if ($row['gender'] == 'Female')
                                echo 'selected'; ?>>Female
                            </option>
                            <option value="Other" <?php if ($row['gender'] == 'Other')
                                echo 'selected'; ?>>Other</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3 ml-2">Location
                            *</label>
                        <input type="text" name="location" required class="input-field"
                            value="<?php echo htmlspecialchars($row['location']); ?>">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3 ml-2">Religion</label>
                        <input type="text" name="religion" class="input-field"
                            value="<?php echo htmlspecialchars($row['religion']); ?>">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3 ml-2">Caste</label>
                        <input type="text" name="caste" class="input-field"
                            value="<?php echo htmlspecialchars($row['caste']); ?>">
                    </div>
                </div>

                <div>
                    <label
                        class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3 ml-2">Education</label>
                    <input type="text" name="education" class="input-field"
                        value="<?php echo htmlspecialchars($row['education']); ?>">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3 ml-2">Bio /
                        About Me</label>
                    <textarea name="bio" rows="4"
                        class="input-field resize-none"><?php echo htmlspecialchars($row['bio']); ?></textarea>
                </div>

                <div class="pt-6">
                    <button type="submit" name="update"
                        class="w-full py-5 bg-brand-dark text-white rounded-[2rem] font-black text-xs tracking-[0.2em] uppercase hover:bg-slate-800 transition-all shadow-xl shadow-slate-200">
                        Update My Identity
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>