<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | JODIFY</title>
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

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-lg shadow-xl">

        <!-- Brand -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-primaryRed tracking-wide">
                JODIFY
            </h1>
            <p class="text-gray-600 mt-1">
                Begin your journey to find the perfect match
            </p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="">
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-1 font-medium">
                    Email Address
                </label>
                <input 
                    type="email" 
                    name="email"
                    placeholder="you@example.com"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primaryRed"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1 font-medium">
                    Password
                </label>
                <input 
                    type="password" 
                    name="password"
                    placeholder="••••••••"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primaryRed"
                    required>
            </div>
            
       

            <button 
                type="submit"
                name="login"
                class="w-full bg-primaryRed text-white py-2 rounded-lg font-semibold hover:bg-red-900 transition duration-200">
                Login
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-600 mt-6">
            New to JODIFY?
            <a href="register.php" class="text-primaryRed font-semibold hover:underline">
                Create an account
            </a>
        </p>

    </div>

</body>
</html>
