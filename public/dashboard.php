<?php
session_start();
include(__DIR__ . "/config/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /jodify/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// Fetch user profile data
$profile_sql = "SELECT * FROM profiles WHERE user_id = '$user_id'";
$profile_result = mysqli_query($conn, $profile_sql);

if (!$profile_result) {
    die("Profile query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($profile_result) === 0) {
    // User doesn't have a profile, redirect to create
    header("Location: /jodify/profile/create.php");
    exit();
}

$profile = mysqli_fetch_assoc($profile_result);


$user_sql = "SELECT email, full_name, created_at FROM users WHERE user_id = '$user_id'";
$user_result = mysqli_query($conn, $user_sql);

if (!$user_result) {
    die("User query failed: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($user_result);


// Fetch recommended matches (opposite gender, similar age)
// Check if gender field exists in profile
if (isset($profile['gender']) && isset($profile['age'])) {
    $match_sql = "SELECT p.* FROM profiles p 
                  WHERE p.user_id != '$user_id' 
                  AND p.gender != '{$profile['gender']}'
                  AND p.age BETWEEN {$profile['age']} - 5 AND {$profile['age']} + 5
                  LIMIT 3";
} 

$match_result = mysqli_query($conn, $match_sql);
$matches = [];
if ($match_result) {
    while ($row = mysqli_fetch_assoc($match_result)) {
        $matches[] = $row;
    }
}

// Calculate profile completion percentage
$completion_fields = [
    'full_name' => !empty($profile['full_name']),
    'gender' => !empty($profile['gender']),
    'age' => !empty($profile['age']) && $profile['age'] > 0,
    'religion' => !empty($profile['religion']),
    'education' => !empty($profile['education']),
    'location' => !empty($profile['location']),
    'profile_photo' => !empty($profile['profile_photo']) && $profile['profile_photo'] != 'default.jpg',
    'bio' => !empty($profile['bio'])
];

$filled_fields = array_sum($completion_fields);
$total_fields = count($completion_fields);
$completion_percentage = $total_fields > 0 ? round(($filled_fields / $total_fields) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Jodify</title>
    
    <?php include 'resources/css/head.php'; ?>
    
    <style>
        :root {
            --primary: #9D2235;
            --secondary: #C9A227;
            --bg: #FAF7F2;
            --text: #2B2B2B;
            --border: #E5E5E5;
        }
        
        body {
            background-color: var(--bg);
            font-family: 'Inter', sans-serif;
        }
        
        .profile-card {
            transition: all 0.3s ease;
        }
        
        .profile-card:hover {
            box-shadow: 0 10px 30px rgba(157, 34, 53, 0.08);
            transform: translateY(-2px);
        }
        
        .progress-bar {
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            height: 6px;
            border-radius: 3px;
        }
        
        .match-card {
            transition: all 0.3s ease;
        }
        
        .match-card:hover {
            box-shadow: 0 8px 25px rgba(157, 34, 53, 0.1);
        }
    </style>
</head>
<body class="text-gray-800">

<!-- Header -->
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-heart text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-red-600 font-['Playfair_Display']">Jodify</h1>
                    <p class="text-xs text-gray-500">Dashboard</p>
                </div>
            </div>
            
            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <a href="/jodify/logout.php" class="text-red-600 hover:text-red-800 text-sm">Logout</a>
                <div class="relative">
                    <?php if (!empty($profile['profile_photo']) && $profile['profile_photo'] != 'default.jpg'): ?>
                        <img src="/jodify/uploads/profiles/<?php echo htmlspecialchars($profile['profile_photo']); ?>" 
                             class="w-10 h-10 rounded-full object-cover border-2 border-red-600">
                    <?php else: ?>
                        <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center text-white font-bold">
                            <?php echo strtoupper(substr($profile['full_name'] ?? 'U', 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success Message -->
    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>
            Profile created successfully! Welcome to your dashboard.
        </div>
    <?php endif; ?>
    
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Left Sidebar - Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 profile-card">
                <!-- Profile Image -->
                <div class="flex flex-col items-center mb-6">
                    <div class="relative mb-4">
                        <?php if (!empty($profile['profile_photo']) && $profile['profile_photo'] != 'default.jpg'): ?>
                            <img src="/jodify/uploads/profiles/<?php echo htmlspecialchars($profile['profile_photo']); ?>" 
                                 class="w-24 h-24 rounded-full object-cover border-3 border-red-600">
                        <?php else: ?>
                            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center border-3 border-red-600">
                                <span class="text-3xl font-bold text-red-600">
                                    <?php echo strtoupper(substr($profile['full_name'] ?? 'U', 0, 1)); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <div class="absolute bottom-0 right-0 w-8 h-8 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>
                    
                    <!-- User Info -->
                    <h2 class="text-xl font-bold text-gray-800 text-center"><?php echo htmlspecialchars($profile['full_name'] ?? 'User'); ?></h2>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="text-sm text-gray-600"><?php echo htmlspecialchars($profile['age'] ?? ''); ?> • <?php echo htmlspecialchars($profile['gender'] ?? ''); ?></span>
                        <?php if (!empty($profile['location'])): ?>
                        <span class="text-gray-400">•</span>
                        <span class="text-sm text-gray-600"><?php echo htmlspecialchars($profile['location']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Profile Completion -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Profile Strength</span>
                        <span class="text-sm font-bold text-red-600"><?php echo $completion_percentage; ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="progress-bar rounded-full" style="width: <?php echo $completion_percentage; ?>%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Complete your profile for better matches</p>
                </div>
                
                <!-- Quick Info -->
                <div class="space-y-3 mb-6">
                  
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-graduation-cap text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Education</p>
                            <p class="text-sm font-medium"><?php echo htmlspecialchars($profile['education']); ?></p>
                        </div>
                    </div>
                   
          
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-hands-praying text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Religion</p>
                            <p class="text-sm font-medium"><?php echo htmlspecialchars($profile['religion']); ?></p>
                        </div>
                    </div>
                
                 
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-users text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Caste</p>
                            <p class="text-sm font-medium"><?php echo htmlspecialchars($profile['caste']); ?></p>
                        </div>
                    </div>
                   
                </div>
                
                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a href="/jodify/profile/edit.php?id=<?php echo $user_id; ?>" 
                       class="block w-full py-3 bg-red-600 text-white rounded-lg font-medium text-center hover:bg-red-700 transition">
                        <i class="fas fa-pen mr-2"></i>Edit Profile
                    </a>
                    <a href="/jodify/profile/view.php?id=<?php echo $user_id; ?>" 
                       class="block w-full py-3 border border-gray-300 text-gray-700 rounded-lg font-medium text-center hover:bg-gray-50 transition">
                        <i class="fas fa-eye mr-2"></i>View Profile
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Dashboard Content -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Welcome & Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Welcome back, <?php echo htmlspecialchars(explode(' ', $profile['full_name'] ?? 'User')[0]); ?>!</h2>
                        <p class="text-gray-600 mt-1">Here's your activity summary</p>
                    </div>
                    
                    <!-- Quick Tip -->
                    <div class="mt-6 md:mt-0">
                        <div class="inline-flex items-center px-4 py-2 bg-yellow-50 border border-yellow-200 rounded-full">
                            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                            <div>
                                <p class="text-xs font-medium">Daily Tip</p>
                                <p class="text-xs text-gray-600">Add more photos to your profile</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recommended Matches -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Recommended Matches</h3>
                        <p class="text-gray-600 text-sm">Based on your profile and preferences</p>
                    </div>
                    <a href="/jodify/public/discover.php" class="text-red-600 hover:text-red-800 text-sm font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php if (empty($matches)): ?>
                        <div class="col-span-3 text-center py-8">
                            <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600">No matches found yet. Update your profile preferences!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($matches as $match): 
                            $match_photo = !empty($match['profile_photo']) && $match['profile_photo'] != 'default.jpg' 
                                ? '/jodify/uploads/profiles/' . htmlspecialchars($match['profile_photo']) 
                                : 'https://via.placeholder.com/150';
                        ?>
                        <div class="match-card border border-gray-200 rounded-xl p-4 text-center">
                            <div class="relative mb-4">
                                <img src="<?php echo $match_photo; ?>" 
                                     class="w-20 h-20 rounded-full object-cover mx-auto border-2 border-red-600">
                                <div class="absolute top-0 right-0 w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-heart text-white text-xs"></i>
                                </div>
                            </div>
                            <h4 class="font-bold text-gray-800"><?php echo htmlspecialchars($match['full_name']); ?></h4>
                            <p class="text-sm text-gray-600 mt-1">
                                <?php echo htmlspecialchars($match['age']); ?> • <?php echo htmlspecialchars($match['location']); ?>
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                <?php echo htmlspecialchars($match['education'] ?? ''); ?>
                            </p>
                            <button class="mt-4 w-full py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition">
                                Send Interest
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Recent Activity & Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-eye text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium">Someone viewed your profile</p>
                                <p class="text-xs text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-heart text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium">New match suggested</p>
                                <p class="text-xs text-gray-500">Yesterday</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-comment text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium">You received a message</p>
                                <p class="text-xs text-gray-500">2 days ago</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="/jodify/public/discover.php" class="p-4 bg-red-50 rounded-lg text-center hover:bg-red-100 transition">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-search text-red-600"></i>
                            </div>
                            <p class="text-sm font-medium">Discover</p>
                            <p class="text-xs text-gray-500">Find matches</p>
                        </a>
                        
                        <a href="/jodify/public/messages.php" class="p-4 bg-red-50 rounded-lg text-center hover:bg-red-100 transition">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-envelope text-red-600"></i>
                            </div>
                            <p class="text-sm font-medium">Messages</p>
                            <p class="text-xs text-gray-500">Check inbox</p>
                        </a>
                        
                        <a href="/jodify/profile/edit.php#photos" class="p-4 bg-red-50 rounded-lg text-center hover:bg-red-100 transition">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-camera text-red-600"></i>
                            </div>
                            <p class="text-sm font-medium">Photos</p>
                            <p class="text-xs text-gray-500">Add photos</p>
                        </a>
                        
                        <a href="/jodify/public/help.php" class="p-4 bg-red-50 rounded-lg text-center hover:bg-red-100 transition">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-question-circle text-red-600"></i>
                            </div>
                            <p class="text-sm font-medium">Help</p>
                            <p class="text-xs text-gray-500">Get support</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-800 text-white mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center space-x-3 mb-4 md:mb-0">
                <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-heart"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Jodify</h3>
                    <p class="text-xs text-gray-400">Connecting Hearts Across Nepal</p>
                </div>
            </div>
            
            <div class="flex space-x-6">
                <a href="#" class="text-sm text-gray-400 hover:text-white">About</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white">Privacy</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white">Terms</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white">Contact</a>
            </div>
            
            <div class="mt-4 md:mt-0">
                <p class="text-sm text-gray-400">
                    © 2026 Jodify — Made with ❤️ in Nepal
                </p>
            </div>
        </div>
    </div>
</footer>


</body>
</html>