<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #F5F5F5;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        border-radius: 9999px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-dashboard {
        background-color: #DD88CF;
        color: #4B164C;
        box-shadow: 0 4px 12px rgba(75, 22, 76, 0.15);
    }

    .btn-dashboard:hover {
        background-color: #c770b8;
        transform: translateY(-2px);
    }

    .btn-user {
        background-color: #4B164C;
        color: #fff;
        box-shadow: 0 4px 12px rgba(75, 22, 76, 0.15);
    }

    .btn-user:hover {
        background-color: #3b0f3d;
        transform: translateY(-2px);
    }

    .dropdown-content {
        visibility: hidden;
        opacity: 0;
        transform: translateY(-10px);
        transition: visibility 0s, opacity 0.2s, transform 0.2s;
    }

    .dropdown:hover .dropdown-content {
        visibility: visible;
        opacity: 1;
        transform: translateY(0);
    }
</style>

<header class="w-full bg-[#F5F5F5] shadow-md border-b border-[#4B164C] z-30">
    <div class="max-w-full mx-auto flex items-center justify-between px-8 py-4">
        <!-- Brand -->
        <div class="flex-1 flex items-center">
            <span class="font-extrabold text-2xl text-[#4B164C] tracking-wide">DocsWave</span>
        </div>
        <!-- Actions -->
        <div class="flex items-center gap-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= $_SESSION['role'] === 'admin' ? '/test/Gdocs/views/accounts/adminDashboard.php' : '/test/Gdocs/views/accounts/userDashboard.php'; ?>" class="btn btn-dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <div class="relative dropdown">
                    <button class="btn btn-user">
                        <?= htmlspecialchars($_SESSION['username'] ?? $_SESSION['role']) ?>
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>
                    <div class="dropdown-content absolute right-0 mt-2 w-56 bg-white border border-[#4B164C] rounded-lg shadow-lg z-20">
                        <div class="px-4 py-2 text-sm text-gray-600 border-b">
                            <span>Signed in as</span><br>
                            <span class="font-semibold text-[#4B164C]"><?= htmlspecialchars($_SESSION['role']) ?></span>
                        </div>
                        <a href="/test/Gdocs/views/auth/logout.php" class="block px-4 py-2 text-sm text-[#4B164C] hover:bg-[#F8E7F6] hover:text-red-700 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/test/Gdocs/views/auth/login.php" class="btn btn-dashboard">Login</a>
                <a href="/test/Gdocs/views/auth/register.php" class="btn btn-user">Register</a>
            <?php endif; ?>
        </div>
    </div>
</header>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdown = document.querySelector('.dropdown');
        if (dropdown) {
            dropdown.addEventListener('click', function (e) {
                const dropdownContent = this.querySelector('.dropdown-content');
                if (dropdownContent.style.visibility === 'visible') {
                    dropdownContent.style.visibility = 'hidden';
                    dropdownContent.style.opacity = '0';
                    dropdownContent.style.transform = 'translateY(-10px)';
                } else {
                    dropdownContent.style.visibility = 'visible';
                    dropdownContent.style.opacity = '1';
                    dropdownContent.style.transform = 'translateY(0)';
                }
            });
        }
    });
</script>
