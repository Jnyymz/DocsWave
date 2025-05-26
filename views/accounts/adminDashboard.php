<?php
session_start();
require_once __DIR__ . '/../../core/dbConfig.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Fetch all users
$stmt = $pdo->query("SELECT id, username, email, role, suspended, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

// Get statistics
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$adminCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
$suspendedCount = $pdo->query("SELECT COUNT(*) FROM users WHERE suspended = 1")->fetchColumn();
$documentCount = $pdo->query("SELECT COUNT(*) FROM documents")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GDocs Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .glass-effect {
            backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);
            border-radius: 12px;
            border: 1px solid rgba(209, 213, 219, 0.3);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #bbf7d0;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6ee7b7;
        }
    </style>
</head>
<body class=" min-h-screen" style="background-color:#F5F5F5">
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Page Header (always on top) -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold mb-2 flex items-center justify-center gap-2" style="color:#4B164C" >
                <i class="fas fa-tachometer-alt " style="color:#4B164C"></i>
                Admin Dashboard
            </h1>
            <p class=" text-lg" style="color:#4B164C">Manage users and monitor system activity</p>
        </div>

        <!-- Flex row: Sidebar + Main Content -->
        <div class="flex flex-row gap-8 items-start">
            <!-- Sidebar Statistics -->
            

            <!-- Main Content -->
            <div class="flex-1 flex flex-col gap-8">
                <!-- Users Management Section -->
                <div class="glass-effect p-6 shadow-2xl mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <h2 class="text-2xl font-bold mb-4 sm:mb-0" style="color:#4B164C">
                            <i class="fas fa-users-cog mr-2 " style="color:#4B164C"></i>User Management
                        </h2>
                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            <input type="text" placeholder="Search users..." id="userSearch" 
                                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-transparent">
                            <button class=" px-4 py-2 rounded-lg transition-colors duration-200 font-medium" style="color:#4B164C; background-color: #DD88CF">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                        </div>
                    </div>
                    
                    <!-- User Management Table -->
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full border-separate border-spacing-y-3">
                            <thead>
                                <tr>
                                    <th class="text-left px-6 py-3 font-semibold text-[#4B164C] bg-transparent">User</th>
                                    <th class="text-left px-6 py-3 font-semibold text-[#4B164C] bg-transparent">Email</th>
                                    <th class="text-left px-6 py-3 font-semibold text-[#4B164C] bg-transparent">Role</th>
                                    <th class="text-left px-6 py-3 font-semibold text-[#4B164C] bg-transparent">Status</th>
                                    <th class="text-left px-6 py-3 font-semibold text-[#4B164C] bg-transparent">Joined</th>
                                    <th class="text-left px-6 py-3 font-semibold text-[#4B164C] bg-transparent">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr class="bg-white/80 hover:bg-green-50/80 transition rounded-xl shadow group">
                                    <td class="py-4 px-6 rounded-l-xl">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shadow" style="background-image: linear-gradient(#4B164C, #DD88CF);">
                                                <?php echo strtoupper(substr($user['username'], 0, 2)); ?>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-[#4B164C]"><?php echo htmlspecialchars($user['username']); ?></p>
                                                <p class="text-xs text-[#4B164C]">ID: <?php echo $user['id']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-[#4B164C]"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            <?php echo $user['role'] === 'admin' ? 'background-color: #DD88CF;' : 'background-color: #DD88CF'; ?>">
                                            <?php if ($user['role'] === 'admin'): ?>
                                                <i class="fas fa-crown mr-1"></i>
                                            <?php else: ?>
                                                <i class="fas fa-user mr-1"></i>
                                            <?php endif; ?>
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            <?php echo $user['suspended'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-[#4B164C]'; ?>">
                                            <?php if ($user['suspended']): ?>
                                                <i class="fas fa-ban mr-1"></i>Suspended
                                            <?php else: ?>
                                                <i class="fas fa-check mr-1"></i>Active
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-[#4B164C text-xs"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                    <td class="py-4 px-6 rounded-r-xl">
                                        <?php if ($user['role'] !== 'admin'): ?>
                                            <form method="POST" action="../../controllers/UserController.php" class="inline">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" name="suspended" value="<?php echo $user['suspended'] ? '0' : '1'; ?>"
                                                        class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-colors duration-200
                                                        <?php echo $user['suspended'] ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-red-600 hover:bg-red-700 text-white'; ?>">
                                                    <?php if ($user['suspended']): ?>
                                                        <i class="fas fa-unlock mr-1"></i>Unsuspend
                                                    <?php else: ?>
                                                        <i class="fas fa-ban mr-1"></i>Suspend
                                                    <?php endif; ?>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-xs italic" style="color: #DD88CF">Protected</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="glass-effect p-6 shadow-2xl">
                    <h2 class="text-2xl font-bold  text-[#4B164C] mb-6">
                        <i class="fas fa-file-alt mr-2 text-[#4B164C]"></i>All Documents
                    </h2>
                    
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-4 px-6 font-semibold text-[#4B164C]">Document</th>
                                    <th class="text-left py-4 px-6 font-semibold text-[#4B164C]">Owner</th>
                                    <th class="text-left py-4 px-6 font-semibold text-[#4B164C]">Last Updated</th>
                                    <th class="text-left py-4 px-6 font-semibold text-[#4B164C]">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once __DIR__ . '/../../models/Document.php';
                                $documentModel = new Document($pdo);
                                $docs = $documentModel->getAll();
                                foreach ($docs as $doc): ?>
                                <tr class="border-b border-gray-100 hover:bg-green-50/50 transition-colors duration-200">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white mr-3" style="background-image: linear-gradient(#4B164C, #DD88CF);">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-green-800"><?php echo htmlspecialchars($doc['title']); ?></p>
                                                <p class="text-sm text-[#4B164C]">ID: <?php echo $doc['id']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8  rounded-full flex items-center justify-center text-white font-bold text-xs mr-2" style="background-image: linear-gradient(#4B164C, #DD88CF);">
                                                <?php echo strtoupper(substr($doc['username'], 0, 2)); ?>
                                            </div>
                                            <span class="text-[#4B164C]"><?php echo htmlspecialchars($doc['username']); ?></span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-[#4B164C] text-sm"><?php echo date('M j, Y \a\t g:i A', strtotime($doc['updated_at'])); ?></td>
                                    <td class="py-4 px-6">
                                        <a href="../document/editor.php?id=<?php echo $doc['id']; ?>"
                                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-[#4B164C] hover:text-teal-600 transition-colors duration-200">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple search functionality
        document.getElementById('userSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
