<?php
session_start();
include("config/db.php");

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $password = $_POST["password"];

    // Simple SQL query without prepared statements
    $sql = "SELECT user_id, email, password_hash FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user["password_hash"])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            
            // Check if user already has a profile
            $profile_sql = "SELECT id FROM profiles WHERE user_id = '{$user['user_id']}'";
            $profile_result = mysqli_query($conn, $profile_sql);
            
            if (mysqli_num_rows($profile_result) > 0) {
                // User has profile, redirect to dashboard
                header("Location: /jodify/public/dashboard.php");
            } else {
                // No profile yet, redirect to create profile
                header("Location: /jodify/profile/create.php");
            }
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Invalid email or password!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'resources/css/head.php'; ?>
    <title>Login - Jodify</title>
</head>

<body class="bg-nepal-bg font-body flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-nepal-border">
        <!-- Logo -->
        <h1 class="font-logo text-nepal-primary text-4xl text-center mb-6">Jodify</h1>

        <h2 class="font-heading text-nepal-primary text-2xl mb-6 text-center">Login to Your Account</h2>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-nepal-text font-medium mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2 border border-nepal-border rounded-lg focus:outline-none focus:ring-2 focus:ring-nepal-primary">
            </div>

            <div>
                <label class="block text-nepal-text font-medium mb-1" for="password">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-nepal-border rounded-lg focus:outline-none focus:ring-2 focus:ring-nepal-primary">
            </div>

            <button type="submit"
                class="w-full bg-nepal-primary text-white py-2 rounded-lg font-button hover:bg-nepal-secondary transition">
                Login
            </button>
        </form>

        <p class="text-center text-sm text-nepal-text mt-4">
            Don't have an account?
            <a href="register.php" class="text-nepal-primary font-medium hover:underline">Register</a>
        </p>
    </div>

</body>

</html>