<?php
session_start();
include("../config/db.php");

// 1. Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /jodify/login.php");
    exit();
}

// 2. Handle Deletion
if (isset($_GET['delete_id'])) {
    $id_to_delete = mysqli_real_escape_string($conn, $_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM users WHERE user_id = '$id_to_delete'");
    header("Location: dashboard.php?msg=deleted");
    exit();
}

// 3. Get Stats
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='user'"))['count'];
$total_profiles = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM profiles"))['count'];

// 4. Handle Search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$search_query = "";
if ($search) {
    $search_query = " AND (users.full_name LIKE '%$search%' OR users.email LIKE '%$search%') ";
}

// 5. Fetch Users
$sql = "SELECT users.user_id, users.full_name, users.email, users.created_at, 
               profiles.location, profiles.age, profiles.gender 
        FROM users 
        LEFT JOIN profiles ON users.user_id = profiles.user_id 
        WHERE users.role = 'user' $search_query
        ORDER BY users.created_at DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | Jodify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="antialiased">

    <div class="flex min-h-screen ">
        <aside class="w-64 bg-slate-900 text-white flex flex-col p-6 fixed h-full shadow-2xl z-1">
            <div class="flex items-center gap-3 mb-10">
                <div class="w-10 h-10 bg-rose-500 rounded-2xl flex items-center justify-center font-bold italic shadow-lg shadow-rose-500/20 text-xl">J</div>
                <span class="font-extrabold text-2xl tracking-tighter">Jodify</span>
            </div>
            
            <nav class="flex-1 space-y-2">
                <a href="dashboard.php" class="flex items-center gap-3 bg-white/10 p-4 rounded-2xl text-white font-semibold">
                    <i class='bx bxs-group text-xl text-rose-400'></i> Users
                </a>
                <a href="/jodify/public/discover.php" class="flex items-center gap-3 p-4 text-slate-400 hover:bg-white/5 rounded-2xl transition">
                    <i class='bx bx-globe text-xl'></i> Public View
                </a>
            </nav>

            <div class="mt-auto pt-6 border-t border-white/10">
                <a href="/jodify/logout.php" class="flex items-center gap-3 p-4 text-rose-400 font-bold hover:bg-rose-500/10 rounded-2xl transition">
                    <i class='bx bx-log-out text-xl'></i> Logout
                </a>
            </div>
        </aside>

        <main class="ml-64 flex-1 p-10 ">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
                <div>
                    <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">System Console</h1>
                    <p class="text-slate-500 font-medium mt-1">Manage accounts and moderate connections.</p>
                </div>

                <div class="flex gap-4">
                    <div class="bg-white p-4 px-6 rounded-3xl shadow-sm border border-slate-200">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Users</span>
                        <span class="text-2xl font-black text-slate-900"><?php echo $total_users; ?></span>
                    </div>
                    <div class="bg-white p-4 px-6 rounded-3xl shadow-sm border border-slate-200">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Profiles</span>
                        <span class="text-2xl font-black text-rose-500"><?php echo $total_profiles; ?></span>
                    </div>
                </div>
            </div>

            <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
                <form method="GET" class="relative w-full md:w-96">
                    <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Search name or email..." 
                           class="w-full pl-12 pr-4 py-4 bg-white border border-slate-200 rounded-2xl outline-none focus:border-rose-500 focus:ring-4 focus:ring-rose-500/5 transition-all font-medium text-sm">
                </form>

                <?php if(isset($_GET['msg'])): ?>
                    <div class="flex items-center gap-2 bg-emerald-50 text-emerald-600 px-6 py-4 rounded-2xl border border-emerald-100 font-bold text-sm">
                        <i class='bx bxs-check-circle text-lg'></i> Entry Deleted Successfully
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="p-6 text-[11px] font-bold uppercase tracking-widest text-slate-400">User Identity</th>
                                <th class="p-6 text-[11px] font-bold uppercase tracking-widest text-slate-400">Demographics</th>
                                <th class="p-6 text-[11px] font-bold uppercase tracking-widest text-slate-400">Joined</th>
                                <th class="p-6 text-[11px] font-bold uppercase tracking-widest text-slate-400 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="hover:bg-slate-50/80 transition-all group">
                                <td class="p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold">
                                            <?php echo strtoupper(substr($row['full_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900"><?php echo htmlspecialchars($row['full_name']); ?></div>
                                            <div class="text-xs text-slate-400 font-medium"><?php echo htmlspecialchars($row['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <?php if($row['age']): ?>
                                        <div class="text-sm font-bold text-slate-700"><?php echo $row['gender']; ?>, <?php echo $row['age']; ?></div>
                                        <div class="flex items-center gap-1 text-[11px] text-slate-400 font-medium uppercase tracking-tight">
                                            <i class='bx bxs-map-pin text-rose-400'></i> <?php echo htmlspecialchars($row['location']); ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-bold uppercase tracking-tighter">Profile Incomplete</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-6">
                                    <div class="text-xs font-bold text-slate-600"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></div>
                                </td>
                                <td class="p-6">
                                    <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="?delete_id=<?php echo $row['user_id']; ?>" 
                                           onclick="return confirm('WARNING: This will permanently delete this user and all their profile data.')"
                                           class="px-4 py-2 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all font-bold text-xs">
                                            DELETE
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if(mysqli_num_rows($result) === 0): ?>
                    <div class="py-24 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class='bx bx-search-alt text-4xl text-slate-200'></i>
                        </div>
                        <p class="text-slate-400 font-bold">No users match your criteria.</p>
                        <a href="dashboard.php" class="text-rose-500 text-sm font-bold mt-2 inline-block">Clear filters</a>
                    </div>
                <?php endif; ?>
            </div>
             <?php include('../../public/resources/partials/footer.php'); ?>
        </main>
    </div>
    

</body>
</html>