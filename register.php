<?php
require_once __DIR__ . '/config/auth.php';
if (!empty($_SESSION['user_id'])) redirect('index.php');
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    if (strlen($name) < 2) $errors[] = 'Enter your full name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
    if (!$errors) {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ?'); $check->execute([$email]);
        if ($check->fetch()) $errors[] = 'An account with that email already exists.';
        else { $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)'); $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]); flash('success', 'Account created. You can now log in.'); redirect('login.php'); }
    }
}
$pageTitle = 'Create account'; require __DIR__ . '/includes/header.php';
?><section class="auth-wrap container"><div class="auth-panel"><p class="eyebrow">START STRONG</p><h1>Create your account.</h1><p class="muted">Join the gym booking desk and make every visit count.</p><?php if ($errors): ?><div class="message error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form class="form" method="post"><label>Full name<input type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>" required></label><label>Email address<input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required></label><label>Password<input type="password" name="password" minlength="8" required></label><button class="button button-primary" type="submit">Create account</button></form><p class="form-foot">Already a member? <a href="login.php">Log in</a></p></div></section><?php require __DIR__ . '/includes/footer.php'; ?>
