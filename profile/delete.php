<?php
include "../public/config/db.php";

// 1. Get the ID from the URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id === 0) {
    die("Error: No User ID provided.");
}

// 2. Optional: Fetch the profile photo name to delete the file from the server
$find_photo = mysqli_query($conn, "SELECT profile_photo FROM profiles WHERE user_id = $user_id");
$photo_data = mysqli_fetch_assoc($find_photo);

if ($photo_data) {
    $file_path = "../uploads/profiles/" . $photo_data['profile_photo'];
    if (!empty($photo_data['profile_photo']) && file_exists($file_path)) {
        unlink($file_path); // This deletes the actual image file from your folder
    }
}

// 3. Simple mysqli_query to delete the record
$sql = "DELETE FROM profiles WHERE user_id = $user_id";

if (mysqli_query($conn, $sql)) {
    // Redirect to a specific page after deletion (e.g., back to dashboard or home)
    header("Location: ../public/user/dashboard.php?status=deleted");
    exit;
} else {
    echo "Error deleting profile: " . mysqli_error($conn);
}
?>