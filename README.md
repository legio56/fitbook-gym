# FitBook Gym

A PHP/MySQL gym equipment booking system for XAMPP.

## Run with XAMPP

1. Copy this folder to `C:/xampp/htdocs/gym-booking`.
2. Start Apache and MySQL in the XAMPP Control Panel.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`.
4. Import `database/gym_booking.sql`.
5. Visit `http://localhost/gym-booking/`.

## Run from VS Code

1. Start MySQL in the XAMPP Control Panel and import `database/gym_booking.sql` in phpMyAdmin if you have not already.
2. Open this folder in VS Code and press F5. The configured task starts PHP's built-in server at `http://127.0.0.1:8080` and opens the site in Chrome.

The VS Code task expects XAMPP at `C:/xampp`. If it is installed elsewhere, update the PHP executable path in `.vscode/tasks.json`.

Register a member account to test booking. To create an admin, register an account and run this in phpMyAdmin:

```sql
UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';
```

The booking check rejects confirmed bookings when `new_start < existing_end AND new_end > existing_start`.

## Test the complete flow

1. Register two member accounts and log in as the first member.
2. Open Equipment, choose a station, and book a future 30-minute slot.
3. Open My Bookings and confirm the reservation appears; cancel it and confirm its status changes.
4. Log in as the second member and attempt the same equipment, date, and overlapping time. The page must show `Slot unavailable`.
5. Register an account for an administrator, run the role update above, then open Admin to test equipment, booking, and user management.
