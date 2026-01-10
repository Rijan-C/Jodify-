<?php
session_start();
include("config/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Prepared statement (SECURE)
    $sql = "SELECT user_id, email, password_hash FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {

        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user["password_hash"])) {

            // ✅ Store session values
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];

            header("Location: /jodify/profile/create.php");
            exit;

            exit;
            
        } else {
            echo "<script>alert('Invalid email or password!');</script>";
        }

    } else {
        echo "<script>alert('Invalid email or password!');</script>";
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