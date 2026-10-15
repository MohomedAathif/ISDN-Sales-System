<?php include "../layouts/header.php"; ?>

<div class="max-w-md mx-auto mt-16 bg-white shadow-xl rounded-xl p-8">

    <div class="text-center mb-6">

    <div class="w-16 h-16 mx-auto mb-3 bg-indigo-600 rounded-full flex items-center justify-center shadow-lg">
        <span class="text-white text-2xl font-bold">I</span>
    </div>

    <h2 class="text-3xl font-bold text-indigo-700">
        Create Account
    </h2>

    <p class="text-gray-500 text-sm mt-1">
        IslandLink Sales Distribution Network
    </p>

</div>

    <form method="POST" action="/ISDN/controllers/AuthController.php">

        <input type="text" name="name" placeholder="Full Name"
               required
               class="w-full p-3 border rounded-lg mb-4 focus:ring-2 focus:ring-indigo-500">

        <input type="email" name="email" placeholder="Email"
               required
               class="w-full p-3 border rounded-lg mb-4 focus:ring-2 focus:ring-indigo-500">

        <input type="password" name="password" placeholder="Password"
               required
               class="w-full p-3 border rounded-lg mb-6 focus:ring-2 focus:ring-indigo-500">

        <button type="submit" name="register"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg shadow transition duration-200">
            Register
        </button>

    </form>

    <div class="mt-6 text-center">
        <p class="text-gray-600 text-sm">
            Already have an account?
            <a href="/ISDN/views/auth/login.php"
               class="text-indigo-600 hover:text-indigo-800 font-semibold transition">
                Login here
            </a>
        </p>
    </div>

</div>

<?php include "../layouts/footer.php"; ?>