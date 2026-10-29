<?php
require_once __DIR__ . '/config/auth.php';
if (!empty($_SESSION['user_id'])) redirect('index.php');
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? '')); $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = ?'); $stmt->execute([$email]); $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) { session_regenerate_id(true); $_SESSION['user_id'] = $user['id']; $_SESSION['name'] = $user['name']; $_SESSION['role'] = $user['role']; redirect($user['role'] === 'admin' ? 'admin/dashboard.php' : 'dashboard.php'); }
    $error = 'Email or password is incorrect.';
}
$pageTitle = 'Log in'; require __DIR__ . '/includes/header.php';
?><section class="auth-wrap container"><div class="auth-panel"><p class="eyebrow">WELCOME BACK</p><h1>Log in to book.</h1><p class="muted">Your next focused session is a few clicks away.</p><?php if ($error): ?><div class="message error"><?= e($error) ?></div><?php endif; ?><form class="form" method="post"><label>Email address<input type="email" name="email" required autofocus></label><label>Password<input type="password" name="password" required></label><button class="button button-primary" type="submit">Log in</button></form><p class="form-foot">New here? <a href="register.php">Create an account</a></p></div></section><?php require __DIR__ . '/includes/footer.php'; ?>
