<?php
require_once __DIR__ . '/config/auth.php'; require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'confirmed'"); $stmt->execute([(int) ($_POST['booking_id'] ?? 0), $_SESSION['user_id']]); flash($stmt->rowCount() ? 'success' : 'error', $stmt->rowCount() ? 'Booking cancelled.' : 'Booking could not be cancelled.'); }
redirect('my_bookings.php');
