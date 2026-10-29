CREATE DATABASE IF NOT EXISTS gym_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gym_booking;

DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS equipment;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('member', 'admin') NOT NULL DEFAULT 'member',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE equipment (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(60) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('available', 'maintenance') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    equipment_id INT UNSIGNED NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('confirmed', 'cancelled') NOT NULL DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_booking_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_booking_equipment FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE,
    INDEX booking_lookup (equipment_id, booking_date, status, start_time, end_time)
) ENGINE=InnoDB;

INSERT INTO equipment (name, category, image, status) VALUES
('Treadmill', 'Cardio', 'treadmill', 'available'),
('Bench Press', 'Strength', 'bench', 'available'),
('Squat Rack', 'Strength', 'squat', 'available'),
('Exercise Bike', 'Cardio', 'bike', 'available'),
('Cable Machine', 'Functional', 'cable', 'available'),
('Leg Press', 'Strength', 'leg-press', 'available'),
('Dumbbells', 'Free weights', 'dumbbells', 'available'),
('Rowing Machine', 'Cardio', 'rowing', 'available');

-- Create an administrator through register.php, then change the role to admin:
-- UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';
