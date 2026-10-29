<?php
$rootPath = '../'; require_once __DIR__ . '/../config/auth.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?"); $stmt->execute([(int) ($_POST['id'] ?? 0)]); flash('success', 'Booking cancelled.'); }
redirect('bookings.php');
