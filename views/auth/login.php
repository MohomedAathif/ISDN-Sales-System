<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php include "../layouts/header.php"; ?>

<div class="flex items-center justify-center min-h-[80vh]">

    <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8 transform transition duration-500 hover:scale-105">

        <div class="text-center mb-6">

            <div class="w-16 h-16 mx-auto mb-3 bg-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                <span class="text-white text-2xl font-bold">I</span>
            </div>

            <h2 class="text-3xl font-bold text-indigo-700">
                Login to ISDN
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                IslandLink Sales Distribution Network
            </p>

        </div>

        <?php if (isset($_GET['registered'])): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">
                Registration successful. You can now login.
            </div>

            <script>
                alert("Registration successful! Please login.");
            </script>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
                Invalid email or password
            </div>
        <?php endif; ?>

        <form method="POST" action="/ISDN/controllers/AuthController.php">

            <div class="mb-4">
                <label class="block text-gray-700 mb-1 font-medium">
                    Email
                </label>
                <input type="email"
                       name="email"
                       required
                       class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
            </div>

            <div class="mb-6 relative">
                <label class="block text-gray-700 mb-1 font-medium">
                    Password
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    
                    <button type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-10 text-gray-500 hover:text-indigo-600">
                        Show
                    </button>
            </div>

            <script>
            function togglePassword() {
                const input = document.getElementById('password');
                const btn = event.target;

                if (input.type === 'password') {
                    input.type = 'text';
                    btn.innerText = 'Hide';
                } else {
                    input.type = 'password';
                    btn.innerText = 'Show';
                }
            }
            </script>

            <div class="flex items-center mb-4">
                <input type="checkbox"
                    name="remember"
                    id="remember"
                    class="mr-2">
                <label for="remember" class="text-gray-600 text-sm">
                    Remember Me
                </label>
            </div>

            <button type="submit"
                    id="loginBtn"
                    name="login"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg shadow-lg transition duration-200 flex justify-center items-center gap-2">
                <span id="loginText">Login</span>

                <svg id="loginSpinner"
                    class="hidden ml-2 animate-spin h-5 w-5 text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4">
                    </circle>
                    <path class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v8z">
                    </path>
                </svg>
            </button>

            <script>
            document.querySelector("form").addEventListener("submit", function () {

                const btn = document.getElementById("loginBtn");
                const text = document.getElementById("loginText");
                const spinn = document.getElementById("loginSpinner");

                btn.disabled = true;
                btn.classList.add("opacity-75", "cursor-not-allowed");

                text.innerText = "Logging in...";
                spinner.classList.remove("hidden");
            });
            </script>
        </form>

        

        <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm">
                Don't have an account?
                <a href="/ISDN/views/auth/register.php"
                   class="text-indigo-600 hover:text-indigo-800 font-semibold transition">
                    Register here
                </a>
            </p>
        </div>

    </div>

</div>

<?php include "../layouts/footer.php"; ?>