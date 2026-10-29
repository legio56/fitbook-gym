<?php
$rootPath = '../'; require_once __DIR__ . '/../config/auth.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { $stmt = $pdo->prepare('DELETE FROM equipment WHERE id = ?'); $stmt->execute([(int) ($_POST['id'] ?? 0)]); flash('success', 'Equipment deleted.'); }
redirect('equipment.php');
