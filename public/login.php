<?php
session_start();
include("config/db.php");
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $password = $_POST["password"];

    $sql = "SELECT user_id, email, password_hash, role FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user["password_hash"])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: /jodify/public/admin/dashboard.php");
                exit();
            }

            $profile_sql = "SELECT id FROM profiles WHERE user_id = '{$user['user_id']}'";
            $profile_result = mysqli_query($conn, $profile_sql);

            if ($profile_result && mysqli_num_rows($profile_result) > 0) {
                header("Location: /jodify/public/user/dashboard.php");
            } else {
                header("Location: /jodify/profile/create.php");
            }
            exit();
        } else { $error = "Invalid email or password!"; }
    } else { $error = "Invalid email or password!"; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Jodify</title>
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
            <div class="absolute inset-0 opacity-40 bg-[url('https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&q=80')] bg-cover bg-center"></div>
            <div class="relative z-10 text-center max-w-md">
                <h1 class="text-5xl font-extrabold text-white tracking-tighter mb-6">Welcome Back.</h1>
                <p class="text-slate-300 text-lg font-medium leading-relaxed">Continue your journey in finding authentic connections across Nepal.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-8 py-12">
            <div class="w-full max-w-sm">
                <div class="mb-10 text-center lg:text-left">
                    <a href="../index.php" class="inline-flex items-center gap-2 mb-8 group">
                        <div class="w-10 h-10 bg-slate-900 rounded-2xl flex items-center justify-center text-white font-black italic">J</div>
                        <span class="text-xl font-bold tracking-tighter">Jodify</span>
                    </a>
                    <h2 class="text-3xl font-extrabold tracking-tight">Login</h2>
                    <p class="text-slate-500 font-medium mt-2">Enter your credentials to access your account.</p>
                </div>

                <?php if($error): ?>
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl text-sm font-bold flex items-center gap-2">
                        <i class='bx bx-error-circle text-lg'></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 ml-1">Email Address</label>
                        <div class="relative">
                            <i class='bx bx-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
                            <input type="email" name="email" required placeholder="name@example.com"
                                   class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 input-pill outline-none text-sm font-semibold">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Password</label>
                            <a href="#" class="text-xs font-bold text-rose-500 hover:underline">Forgot?</a>
                        </div>
                        <div class="relative">
                            <i class='bx bx-lock-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
                            <input type="password" name="password" required placeholder="••••••••"
                                   class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 input-pill outline-none text-sm font-semibold">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-rose-100 transition-all transform hover:-translate-y-1 active:scale-[0.98] mt-4">
                        Sign In
                    </button>
                </form>

                <p class="mt-8 text-center text-sm font-semibold text-slate-500">
                    Don't have an account? <a href="register.php" class="text-rose-500 font-extrabold hover:underline">Create one</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>