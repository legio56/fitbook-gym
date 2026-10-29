<?php
require_once __DIR__ . '/../config/auth.php';
$rootPath = $rootPath ?? '';
$pageTitle = $pageTitle ?? 'FitBook Gym';
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> | FitBook Gym</title>
    <link rel="stylesheet" href="<?= e($rootPath) ?>css/style.css">
</head>
<body>
<header class="site-header">
    <nav class="nav container">
        <a class="brand" href="<?= e($rootPath) ?>index.php"><span class="brand-mark">F</span> FitBook Gym</a>
        <button class="nav-toggle" type="button" aria-label="Toggle navigation">Menu</button>
        <div class="nav-links">
            <a href="<?= e($rootPath) ?>index.php">Home</a>
            <a href="<?= e($rootPath) ?>equipment.php">Equipment</a>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <?php if ($isAdmin): ?><a href="<?= e($rootPath) ?>admin/dashboard.php">Admin</a><?php else: ?><a href="<?= e($rootPath) ?>my_bookings.php">My Bookings</a><?php endif; ?>
                <a class="nav-cta" href="<?= e($rootPath) ?>logout.php">Log out</a>
            <?php else: ?>
                <a href="<?= e($rootPath) ?>login.php">Login</a>
                <a class="nav-cta" href="<?= e($rootPath) ?>register.php">Join now</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<?php if ($message = flash('success')): ?><div class="container"><div class="message success"><?= e($message) ?></div></div><?php endif; ?>
<?php if ($message = flash('error')): ?><div class="container"><div class="message error"><?= e($message) ?></div></div><?php endif; ?>
<main>
