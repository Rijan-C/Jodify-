<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register | JODIFY</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Custom Theme -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primaryRed: '#8B0000',
                        softCream: '#F5EFE6'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-softCream min-h-screen flex items-center justify-center">

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-lg">

        <!-- Brand -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-primaryRed tracking-wide">JODIFY</h1>
            <p class="text-gray-600 mt-1">
                Create your account to find your perfect match
            </p>
        </div>

        <!-- Registration Form -->
        <form method="POST" action="" enctype="multipart/form-data">

            <!-- Full Name -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1 font-medium">Full Name</label>
                <input type="text" name="name" placeholder="John Doe"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primaryRed" required>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1 font-medium">Email Address</label>
                <input type="email" name="email" placeholder="you@example.com"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primaryRed" required>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1 font-medium">Password</label>
                <input type="password" name="password" placeholder="••••••••"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primaryRed" required>
            </div>

            <!-- Gender -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1 font-medium">Gender</label>
                <select name="gender"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primaryRed" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>


            <!-- Submit Button -->
            <button type="submit" name="register"
                class="w-full bg-primaryRed text-white py-2 rounded-lg font-semibold hover:bg-red-900 transition duration-200">
                Register
            </button>

        </form>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-600 mt-6">
            Already have an account?
            <a href="login.php" class="text-primaryRed font-semibold hover:underline">
                Login here
            </a>
        </p>

    </div>

</body>

</html>