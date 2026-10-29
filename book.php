<?php
require_once __DIR__ . '/config/auth.php'; require_login();
$equipmentId = (int) ($_GET['equipment_id'] ?? $_POST['equipment_id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM equipment WHERE id = ?'); $stmt->execute([$equipmentId]); $equipment = $stmt->fetch();
if (!$equipment || $equipment['status'] !== 'available') { flash('error', 'That equipment is not available.'); redirect('equipment.php'); }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['booking_date'] ?? ''; $start = $_POST['start_time'] ?? ''; $duration = (int) ($_POST['duration'] ?? 0);
    $today = date('Y-m-d'); $now = date('Y-m-d H:i:s');
    $validDate = DateTime::createFromFormat('!Y-m-d', $date);
    $validStart = DateTime::createFromFormat('!H:i', $start);
    if (!$validDate || $validDate->format('Y-m-d') !== $date || $date < $today) $errors[] = 'Choose today or a future date.';
    if (!$validStart || $validStart->format('H:i') !== $start || (int) $validStart->format('i') % 30 !== 0 || $duration < 30 || $duration > 180 || $duration % 30 !== 0) $errors[] = 'Choose a valid 30 to 180 minute duration.';
    $end = $validStart ? $validStart->modify('+' . $duration . ' minutes')->format('H:i') : '';
    if ($start && ($start < '06:00' || $end > '21:00' || $end <= $start)) $errors[] = 'Bookings must fit within gym hours, 06:00 to 21:00.';
    if (!$errors && $date === $today && "$date $start" <= $now) $errors[] = 'Booking time must be in the future.';
    if (!$errors) {
        try {
            $pdo->beginTransaction();
            $lock = $pdo->prepare("SELECT id FROM equipment WHERE id = ? AND status = 'available' FOR UPDATE");
            $lock->execute([$equipmentId]);
            $check = $pdo->prepare("SELECT id FROM bookings WHERE equipment_id = ? AND booking_date = ? AND status = 'confirmed' AND start_time < ? AND end_time > ? LIMIT 1");
            $check->execute([$equipmentId, $date, $end, $start]);
            if ($check->fetch()) {
                $pdo->rollBack();
                $errors[] = 'Slot unavailable. Choose another time.';
            } else {
                $insert = $pdo->prepare('INSERT INTO bookings (user_id, equipment_id, booking_date, start_time, end_time) VALUES (?, ?, ?, ?, ?)');
                $insert->execute([$_SESSION['user_id'], $equipmentId, $date, $start, $end]);
                $pdo->commit();
                flash('success', 'Booking confirmed for ' . $equipment['name'] . '.');
                redirect('my_bookings.php');
            }
        } catch (PDOException $error) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            error_log($error->getMessage());
            $errors[] = 'The booking could not be saved. Please try again.';
        }
    }
}
$pageTitle = 'Book ' . $equipment['name']; require __DIR__ . '/includes/header.php';
?><section class="booking-layout container"><div><p class="eyebrow">RESERVE A STATION</p><h1><?= e($equipment['name']) ?></h1><p class="muted"><?= e($equipment['category']) ?> station. Choose a date and a focused block of time.</p><div class="booking-highlight"><span class="status available">Available</span><strong>06:00 - 21:00</strong><span>Daily gym hours</span></div></div><div class="form-panel"><?php if ($errors): ?><div class="message error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form class="form" method="post"><input type="hidden" name="equipment_id" value="<?= $equipmentId ?>"><label>Date<input type="date" name="booking_date" min="<?= date('Y-m-d') ?>" value="<?= e($_POST['booking_date'] ?? date('Y-m-d')) ?>" required></label><label>Start time<input type="time" name="start_time" min="06:00" max="20:30" step="1800" value="<?= e($_POST['start_time'] ?? '08:00') ?>" required></label><label>Duration<select name="duration" required><?php foreach ([30,60,90,120,150,180] as $minutes): ?><option value="<?= $minutes ?>" <?= (int) ($_POST['duration'] ?? 60) === $minutes ? 'selected' : '' ?>><?= $minutes ?> minutes</option><?php endforeach; ?></select></label><button class="button button-primary" type="submit">Confirm booking</button></form></div></section><?php require __DIR__ . '/includes/footer.php'; ?>
