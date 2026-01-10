<?php
include("config/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 1️⃣ Check if email already exists (Prepared Statement)
    $checkSql = "SELECT user_id FROM users WHERE email = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "s", $email);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        echo "<script>alert('Email already registered!');</script>";
    } else {

        // 2️⃣ Insert new user
        $insertSql = "INSERT INTO users (full_name, email, password_hash)
                      VALUES (?, ?, ?)";

        $insertStmt = mysqli_prepare($conn, $insertSql);
        mysqli_stmt_bind_param(
            $insertStmt,
            "sss",
            $name,
            $email,
            $hashedPassword
        );

        if (mysqli_stmt_execute($insertStmt)) {
            echo "<script>alert('Registration successful! Please login.');</script>";
            header("Location: login.php");
            exit;
        } else {
            echo "<script>alert('Registration failed. Try again.');</script>";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'resources/css/head.php'; ?>
    <title>Register - Jodify</title>
</head>
<body class="bg-nepal-bg font-body flex items-center justify-center min-h-screen">

  <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-nepal-border">
    <!-- Logo -->
    <h1 class="font-logo text-nepal-primary text-4xl text-center mb-6">Jodify</h1>
    
    <h2 class="font-heading text-nepal-primary text-2xl mb-6 text-center">Create Your Account</h2>

    <form  method="POST" class="space-y-5" enctype="multipart/form-data">
      <div>
        <label class="block text-nepal-text font-medium mb-1" for="name">Full Name</label>
        <input type="text" name="name" id="name" required value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>"
               class="w-full px-4 py-2 border border-nepal-border rounded-lg focus:outline-none focus:ring-2 focus:ring-nepal-primary">
      </div>

      <div>
        <label class="block text-nepal-text font-medium mb-1" for="email">Email</label>
        <input type="email" name="email" id="email" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>"
               class="w-full px-4 py-2 border border-nepal-border rounded-lg focus:outline-none focus:ring-2 focus:ring-nepal-primary">
      </div>

      <div>
        <label class="block text-nepal-text font-medium mb-1" for="password">Password</label>
        <input type="password" name="password" id="password" required 
               class="w-full px-4 py-2 border border-nepal-border rounded-lg focus:outline-none focus:ring-2 focus:ring-nepal-primary">
      </div>

     

      <button type="submit" 
              class="w-full bg-nepal-primary text-white py-2 rounded-lg font-button hover:bg-nepal-secondary transition">
        Register
      </button>
    </form>

    <p class="text-center text-sm text-nepal-text mt-4">
      Already have an account? 
      <a href="login.php" class="text-nepal-primary font-medium hover:underline">Login</a>
    </p>
  </div>

</body>
</html>
