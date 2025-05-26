<?php
require_once __DIR__ . '/../../core/dbConfig.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController($pdo);
    $role = $_POST['role'] ?? 'user';
    $result = $auth->register($_POST['username'], $_POST['password'], $_POST['email'], $role);
    
    if (isset($result['success'])) {
        header('Location: login.php?registered=1');
        exit;
    } else {
        $message = $result['error'];
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - DocsWave</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .input-focus:focus {
            border-color: #DD88CF;
            box-shadow: 0 0 0 3px rgba(221, 136, 207, 0.3);
        }
  </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-[#F8E7F6] border border-solid border-[#DD88CF] p-8 rounded-2xl shadow-md w-full max-w-md">
    <h2 class="text-3xl font-bold text-center text-[#4B164C] mb-2">DocsWave</h2>
    <p class="text-center text-[#4B164C] mb-6">Create your account and start writing smarter.</p>

     <!-- Register Form -->
        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: #4B164C">
                    <i class="fas fa-user mr-2"></i>Username
                </label>
                <input type="text" name="username" required 
                    class="input-focus w-full px-4 py-3 border border-gray-300 text-green-800 rounded-lg focus:outline-none transition-all duration-200"
                    placeholder="Choose a username">
            </div>

            <div>
                <label class="block text-sm font-semibold  mb-2" style="color: #4B164C">
                    <i class="fas fa-envelope mr-2"></i>Email
                </label>
                <input type="email" name="email" required 
                    class="input-focus w-full px-4 py-3 border text-green-800 border-gray-300 text-green-800 rounded-lg focus:outline-none transition-all duration-200"
                    placeholder="Enter your email">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2" style="color: #4B164C">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>
                <div class="relative">
                    <input type="password" name="password" required id="password"
                        class="input-focus w-full px-4 py-3 border text-green-800 border-gray-300 rounded-lg focus:outline-none transition-all duration-200 pr-12"
                        placeholder="Create a strong password">
                    <button type="button" onclick="togglePassword()" 
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-green-400 hover:text-green-600">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2" style="color: #4B164C">
                    <i class="fas fa-user-shield mr-2"></i>Account Type
                </label>
                <select name="role" required 
                    class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200" style="color: #4B164C">
                    <option value="user">Regular User</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <button type="submit" 
                class="w-full  hover:from-green-600 hover:to-teal-500 font-bold py-4 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300" style="color: #4B164C; background-color: #DD88CF">
                <i class="fas fa-user-plus mr-2"></i>Create Account
            </button>
        </form>

    

    <!-- Footer -->
    <p class="mt-6 text-center text-sm text-gray-700">
      Already have an account?
      <a href="login.php" class="text-[#c46fb9] hover:underline">Login here</a>
    </p>
  </div>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
   <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form animation
        window.addEventListener('load', function() {
            const form = document.querySelector('.glass-effect');
            form.style.opacity = '0';
            form.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                form.style.transition = 'all 0.6s ease';
                form.style.opacity = '1';
                form.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
