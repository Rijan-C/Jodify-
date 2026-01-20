<?php
include("config/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format');</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        $safeName = mysqli_real_escape_string($conn, $name);
        $safeEmail = mysqli_real_escape_string($conn, $email);
        
        $checkSql = "SELECT user_id FROM users WHERE email = '$safeEmail'";
        $result = mysqli_query($conn, $checkSql);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Email already registered');</script>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertSql = "INSERT INTO users (full_name, email, password_hash, role) VALUES ('$safeName', '$safeEmail', '$hashedPassword', 'user')";
            if (mysqli_query($conn, $insertSql)) {
                header("Location: login.php");
                exit;
            } else {
                echo "<script>alert('Registration failed');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Jodify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FFFFFF; }
        .input-pill { border-radius: 1.25rem; transition: all 0.3s ease; }
        .input-pill:focus { border-color: #F43F5E; box-shadow: 0 0 0 4px rgba(244, 63, 94, 0.1); }
    </style>
</head>
<body class="text-slate-900">
    <div class="flex min-h-screen">
        <div class="hidden lg:flex w-1/2 relative bg-slate-900 items-center justify-center p-12">
            <div class="absolute inset-0 opacity-40 bg-[url('https://images.unsplash.com/photo-1491438590914-bc09fcaaf77a?auto=format&fit=crop&q=80')] bg-cover bg-center"></div>
            <div class="relative z-10 text-center max-w-md">
                <h1 class="text-5xl font-extrabold text-white tracking-tighter mb-6">Start Your Story.</h1>
                <p class="text-slate-300 text-lg font-medium leading-relaxed">Join thousands of people finding meaningful, human-first relationships on Jodify.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-8 py-12">
            <div class="w-full max-w-md">
                <div class="mb-8 text-center lg:text-left">
                    <a href="../index.php" class="inline-flex items-center gap-2 mb-6 group">
                        <div class="w-10 h-10 bg-slate-900 rounded-2xl flex items-center justify-center text-white font-black italic">J</div>
                        <span class="text-xl font-bold tracking-tighter">Jodify</span>
                    </a>
                    <h2 class="text-3xl font-extrabold tracking-tight">Create Account</h2>
                    <p class="text-slate-500 font-medium mt-2">Sign up today and meet your perfect match.</p>
                </div>

                <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 ml-1">Full Name</label>
                        <div class="relative">
                            <i class='bx bx-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
                            <input type="text" name="name" required placeholder="John Doe"
                                   class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 input-pill outline-none text-sm font-semibold">
                        </div>
                    </div>

                    <div class="md:col-span-2 space-y-1">
                        <label class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 ml-1">Email Address</label>
                        <div class="relative">
                            <i class='bx bx-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
                            <input type="email" name="email" required placeholder="john@example.com"
                                   class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 input-pill outline-none text-sm font-semibold">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 ml-1">Password</label>
                        <div class="relative">
                            <i class='bx bx-lock-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
                            <input type="password" name="password" required placeholder="••••••••"
                                   class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 input-pill outline-none text-sm font-semibold">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 ml-1">Confirm</label>
                        <div class="relative">
                            <i class='bx bx-check-shield absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
                            <input type="password" name="confirm_password" required placeholder="••••••••"
                                   class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 input-pill outline-none text-sm font-semibold">
                        </div>
                    </div>

                    <div class="md:col-span-2 mt-4">
                        <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-rose-100 transition-all transform hover:-translate-y-1 active:scale-[0.98]">
                            Create Account
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-sm font-semibold text-slate-500">
                    Already have an account? <a href="login.php" class="text-rose-500 font-extrabold hover:underline">Login here</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>