<?php
session_start();
include(__DIR__ . "/../public/config/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /jodify/login.php");
    exit();
}

$error = '';
$success = '';
$user_id = $_SESSION['user_id'];

// Check if user already has a profile
$check_sql = "SELECT id FROM profiles WHERE user_id = $user_id";
$check_result = mysqli_query($conn, $check_sql);
if (mysqli_num_rows($check_result) > 0) {
    $error = "You already have a profile. You can edit it instead.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    // Get form data
    $full_name =  trim($_POST['full_name']);
    $gender =  trim($_POST['gender']);
    $age = intval($_POST['age']);
    $religion =  trim($_POST['religion'] ?? '');
    $caste =  trim($_POST['caste'] ?? '');
    $education =  trim($_POST['education'] ?? '');
    $location =  trim($_POST['location'] ?? '');
    $bio =  trim($_POST['bio'] ?? '');

    // Initialize profile photo name
    $profile_photo_name = 'default.jpg';

    // File upload handling
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['profile_photo']['name'];
        $file_tmp = $_FILES['profile_photo']['tmp_name'];
        $file_size = $_FILES['profile_photo']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_extensions)) {
            if ($file_size <= 2 * 1024 * 1024) {
                $new_name = uniqid('profile_', true) . '.' . $file_ext;
                $upload_dir = __DIR__ . '/../uploads/profiles/';

                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $destination = $upload_dir . $new_name;

                if (move_uploaded_file($file_tmp, $destination)) {
                    $profile_photo_name = $new_name;
                } else {
                    $error = "Failed to upload file.";
                }
            } else {
                $error = "File is too large. Max size is 2MB.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // If no errors, insert into database
    if (empty($error)) {
        if (empty($full_name) || empty($gender) || $age < 18 || $age > 80) {
            $error = "Please fill all required fields correctly.";
        } else {
            // Get user email for reference
            $user_email = $_SESSION['email'];

            // Create SQL query with user_id
            $sql = "INSERT INTO profiles (user_id, full_name, gender, age, religion, caste, education, location, bio, profile_photo, created_at) 
                    VALUES ($user_id, '$full_name', '$gender', $age, '$religion', '$caste', '$education', '$location', '$bio', '$profile_photo_name', NOW())";

            if (mysqli_query($conn, $sql)) {
                $success = "Profile created successfully!";

                // Redirect to dashboard or profile view
                header("Location: ../public/dashboard.php?success=1");
                exit();
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'C:\xampp\htdocs\JODIFY\public\resources\css\head.php'; ?>
    <title>Create Profile - Jodify</title>
</head>

<body>

    <div class="max-w-3xl mx-auto py-12 px-6">
        <div class="bg-white rounded-2xl shadow-lg border border-nepal-border p-8">
            <!-- Header with user info -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="font-heading text-3xl text-nepal-primary">
                        Create Your Profile
                    </h1>
                    <p class="text-nepal-text/70 mt-1">Welcome, User ID: <?php echo htmlspecialchars($user_id); ?></p>
                </div>
                <a href="logout.php" class="text-sm text-nepal-primary hover:underline">Logout</a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data" class="space-y-5">

                <div>
                    <label class="block text-sm font-medium mb-1">Full Name *</label>
                    <input type="text" name="full_name" required
                        value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>"
                        class="w-full px-4 py-3 border border-nepal-border rounded-xl">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Gender *</label>
                        <select name="gender" required class="w-full px-4 py-3 border border-nepal-border rounded-xl">
                            <option value="">Select</option>
                            <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Age *</label>
                        <input type="number" name="age" min="18" max="80" required
                            value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>"
                            class="w-full px-4 py-3 border border-nepal-border rounded-xl">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="religion" placeholder="Religion"
                            value="<?php echo isset($_POST['religion']) ? htmlspecialchars($_POST['religion']) : ''; ?>"
                            class="w-full px-4 py-3 border border-nepal-border rounded-xl">
                    </div>
                    <div>
                        <input type="text" name="caste" placeholder="Caste"
                            value="<?php echo isset($_POST['caste']) ? htmlspecialchars($_POST['caste']) : ''; ?>"
                            class="w-full px-4 py-3 border border-nepal-border rounded-xl">
                    </div>
                </div>

                <div>
                    <input type="text" name="education" placeholder="Education"
                        value="<?php echo isset($_POST['education']) ? htmlspecialchars($_POST['education']) : ''; ?>"
                        class="w-full px-4 py-3 border border-nepal-border rounded-xl">
                </div>

                <div>
                    <input type="text" name="location" placeholder="Location (District)"
                        value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>"
                        class="w-full px-4 py-3 border border-nepal-border rounded-xl">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Profile Photo</label>
                    <input type="file" name="profile_photo" accept="image/*"
                        class="w-full px-4 py-3 border border-nepal-border rounded-xl">
                    <p class="text-xs text-gray-500 mt-1">Max file size: 2MB. Allowed types: JPG, PNG, GIF</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Bio</label>
                    <textarea name="bio" rows="4" class="w-full px-4 py-3 border border-nepal-border rounded-xl"
                        placeholder="Write something about yourself..."><?php echo isset($_POST['bio']) ? htmlspecialchars($_POST['bio']) : ''; ?></textarea>
                </div>

                <div class="text-center mt-6">
                    <button type="submit"
                        class="bg-nepal-primary text-white px-8 py-3 rounded-full font-medium hover:bg-nepal-primary/90 transition hover:shadow-lg">
                        Save Profile
                    </button>
                    <a href="../public/dashboard.php"
                        class="ml-4 px-8 py-3 border border-nepal-border text-nepal-text rounded-full font-medium hover:bg-nepal-bg transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>