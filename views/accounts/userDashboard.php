<?php
session_start();
require_once __DIR__ . '/../../core/dbConfig.php';
require_once __DIR__ . '/../../models/Document.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$documentModel = new Document($pdo);
$owned = $documentModel->getByUser($_SESSION['user_id']);
$shared = $documentModel->getSharedWith($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - GDocs Clone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Palette colors */
        :root {
            --bg-light: #F5F5F5;
            --bg-lighter: #F8E7F6;
            --accent: #DD88CF;
            --dark: #4B164C;
            --white: #ffffff;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: var(--bg-light);
            color: var(--dark);
            min-height: 100vh;
            padding-bottom: 80px; /* space for fixed button */
        }


        main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-section h2 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .welcome-section p {
            color: var(--accent);
            font-size: 1.2rem;
        }

        /* Container for the two cards side by side */
        .documents-row {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            background-color: var(--white);
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(75, 22, 76, 0.15);
            padding: 1.5rem;
            flex: 1 1 400px; /* grow and shrink, minimum width 400px */
            max-height: 450px;
            display: flex;
            flex-direction: column;
        }

        .card h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card .documents-list {
            overflow-y: auto;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .document-item {
            background-color: var(--bg-lighter);
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100px;
            transition: background-color 0.3s ease;
        }

        .document-item:hover {
            background-color: var(--accent);
            color: var(--white);
            cursor: pointer;
        }

        .document-title {
            font-weight: 600;
            font-size: 1.1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .document-meta {
            font-size: 0.85rem;
            color: var(--dark);
            opacity: 0.7;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .document-actions {
            margin-top: 0.5rem;
            display: flex;
            gap: 0.5rem;
        }

        .document-actions a,
        .document-actions button {
            background: none;
            border: none;
            color: var(--dark);
            cursor: pointer;
            font-size: 1.1rem;
            padding: 0.25rem;
            border-radius: 6px;
            transition: background-color 0.2s ease;
        }

        .document-actions a:hover,
        .document-actions button:hover {
            background-color: var(--accent);
            color: var(--white);
        }

        /* Fixed Create New Document button */
        .fixed-create-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background-color: var(--accent);
            color: var(--white);
            border: none;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 40px;
            box-shadow: 0 4px 12px rgba(221, 136, 207, 0.5);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
            z-index: 1000;
        }

        .fixed-create-btn:hover {
            background-color: var(--dark);
        }

        /* Scrollbar styling for documents list */
        .documents-list::-webkit-scrollbar {
            width: 6px;
        }
        .documents-list::-webkit-scrollbar-thumb {
            background-color: var(--accent);
            border-radius: 3px;
        }
        .documents-list::-webkit-scrollbar-track {
            background: transparent;
        }

        /* Responsive tweaks */
        @media (max-width: 900px) {
            .documents-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <main>
        <section class="welcome-section">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>!</h2>
            <p>Ready to create something great or collaborate with others? Your documents await below.</p>
        </section>

        <section class="documents-row">
            <!-- My Documents -->
            <div class="card">
                <h3><i class="fas fa-folder-open"></i> My Documents</h3>
                <div class="documents-list">
                    <?php if (empty($owned)): ?>
                        <div style="text-align:center; color: var(--accent); font-weight: 600;">
                            You haven't created any documents yet.
                        </div>
                    <?php else: ?>
                        <?php foreach ($owned as $doc): ?>
                            <div class="document-item" onclick="window.location.href='../document/editor.php?id=<?php echo $doc['id']; ?>'">
                                <div class="document-title"><?php echo htmlspecialchars($doc['title'] ?: 'Untitled Document'); ?></div>
                                <div class="document-meta">
                                    <i class="far fa-clock"></i>
                                    <?php echo date('M j, Y g:i A', strtotime($doc['updated_at'])); ?>
                                </div>
                                <div class="document-actions">
                                    <a href="../document/editor.php?id=<?php echo $doc['id']; ?>" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="../document/activity.php?document_id=<?php echo $doc['id']; ?>" title="View Activity"><i class="fas fa-history"></i></a>
                                    <form method="POST" action="../../controllers/DocumentController.php" onsubmit="return confirm('Are you sure you want to delete this document?');" style="display:inline;">
                                        <input type="hidden" name="delete_id" value="<?php echo $doc['id']; ?>" />
                                        <button type="submit" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Shared With Me -->
            <div class="card">
                <h3><i class="fas fa-share-alt"></i> Shared with Me</h3>
                <div class="documents-list">
                    <?php if (empty($shared)): ?>
                        <div style="text-align:center; color: var(--accent); font-weight: 600;">
                            No documents have been shared with you yet.
                        </div>
                    <?php else: ?>
                        <?php foreach ($shared as $doc): ?>
                            <div class="document-item" onclick="window.location.href='../document/editor.php?id=<?php echo $doc['id']; ?>'">
                                <div class="document-title"><?php echo htmlspecialchars($doc['title'] ?: 'Untitled Document'); ?></div>
                                <div class="document-meta">
                                    <i class="far fa-clock"></i>
                                    <?php echo date('M j, Y g:i A', strtotime($doc['updated_at'])); ?>
                                </div>
                                <div class="document-actions">
                                    <a href="../document/editor.php?id=<?php echo $doc['id']; ?>" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="../document/activity.php?document_id=<?php echo $doc['id']; ?>" title="View Activity"><i class="fas fa-history"></i></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <button class="fixed-create-btn" onclick="window.location.href='../document/editor.php'">
        <i class="fas fa-plus"></i> Create New Document
    </button>
</body>
</html>
