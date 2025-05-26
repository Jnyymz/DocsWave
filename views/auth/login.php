<?php
require_once __DIR__ . '/../../core/dbConfig.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

if (!class_exists('AuthController')) {
    die('AuthController class not found! Check your file paths and class definition.');
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController($pdo);
    $result = $auth->login($_POST['username'], $_POST['password']);
    if (isset($result['success'])) {
        if ($result['role'] === 'admin') {
            header('Location: ../accounts/adminDashboard.php');
        } else {
            header('Location: ../accounts/userDashboard.php');
        }
        exit;
    } else {
        $message = $result['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GDocs Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F5F5F5;
        }

        .card {
            background-color: #F8E7F6;
            border-radius: 24px;
            border: 1px solid #DD88CF;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .input-focus:focus {
            border-color: #DD88CF;
            box-shadow: 0 0 0 3px rgba(221, 136, 207, 0.3);
        }

        .text-primary {
            color: #4B164C;
        }

        .btn-primary {
            background-color: #DD88CF;
            color: #4B164C;
        }

        .btn-primary:hover {
            background-color: #c46fb9;
        }

        .link {
            color: #DD88CF;
        }

        .link:hover {
            color: #4B164C;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="card p-8 md:p-12 w-full max-w-md relative z-10">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary mb-2">DocsWave</h1>
            <p class="text-[#4B164C]/70">Log in to access your documents and keep the ideas flowing.</p>
        </div>

        <!-- Error Message -->
        <?php if ($message): ?>
            <div class="mb-6 p-4 bg-red-100 border border-red-300 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <p class="text-red-700 text-sm"><?php echo htmlspecialchars($message); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-primary mb-2">
                    <i class="fas fa-user mr-2"></i>Username
                </label>
                <input type="text" name="username" required 
                       class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200"
                       placeholder="Enter your username">
            </div>

            <div>
                <label class="block text-sm font-semibold text-primary mb-2">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>
                <div class="relative">
                    <input type="password" name="password" required id="password"
                           class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 pr-12"
                           placeholder="Enter your password">
                    <button type="button" onclick="togglePassword()" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-[#DD88CF] hover:text-[#4B164C]">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" class="rounded border-gray-300 text-[#DD88CF] focus:ring-[#DD88CF]">
                    <span class="ml-2 text-sm text-[#4B164C]">Remember me</span>
                </label>
                <a href="#" class="text-sm link font-medium">
                    Forgot password?
                </a>
            </div>

            <button type="submit" 
                    class="btn-primary w-full font-bold py-4 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                <i class="fas fa-sign-in-alt mr-2"></i>Sign In
            </button>
        </form>

        <!-- Register Link -->
        <div class="mt-8 text-center">
            <p class="text-[#4B164C]">
                Don't have an account? 
                <a href="register.php" class="link font-semibold">
                    Create one here
                </a>
            </p>
        </div>
    </div>

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

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="username"]').focus();
        });

        window.addEventListener('load', function() {
            const form = document.querySelector('.card');
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
