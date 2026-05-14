-- ============================================
-- CinéBook - Database Schema
-- Plateforme de Réservation Cinéma
-- ============================================

CREATE DATABASE IF NOT EXISTS cinebook
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cinebook;

-- ============================================
-- Users Table
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Movies Table
-- ============================================
CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    poster VARCHAR(500) DEFAULT NULL,
    trailer_url VARCHAR(500) DEFAULT NULL,
    duration_min INT NOT NULL DEFAULT 90,
    genre VARCHAR(100) NOT NULL,
    release_date DATE DEFAULT NULL,
    rating VARCHAR(10) DEFAULT 'TP',
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Halls Table
-- ============================================
CREATE TABLE IF NOT EXISTS halls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    rows_count INT NOT NULL DEFAULT 8,
    cols_count INT NOT NULL DEFAULT 12,
    hall_type ENUM('Standard', 'IMAX', 'VIP') DEFAULT 'Standard',
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Screenings Table
-- ============================================
CREATE TABLE IF NOT EXISTS screenings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    hall_id INT NOT NULL,
    show_date DATE NOT NULL,
    show_time TIME NOT NULL,
    price DECIMAL(8, 2) NOT NULL DEFAULT 50.00,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (hall_id) REFERENCES halls(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Bookings Table
-- ============================================
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    screening_id INT NOT NULL,
    total_seats INT NOT NULL DEFAULT 1,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('en_attente', 'confirme', 'annule') DEFAULT 'en_attente',
    booked_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (screening_id) REFERENCES screenings(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Booking Seats Table
-- ============================================
CREATE TABLE IF NOT EXISTS booking_seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    seat_row CHAR(1) NOT NULL,
    seat_number INT NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    UNIQUE KEY unique_seat_screening (booking_id, seat_row, seat_number)
) ENGINE=InnoDB;

-- ============================================
-- Favorites Table (Films Préférés)
-- ============================================
CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    movie_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, movie_id)
) ENGINE=InnoDB;

-- ============================================
-- Seed Data
-- ============================================

-- Admin user (password: admin123)
INSERT INTO users (name, email, password, phone, role) VALUES
('Administrateur', 'admin@cinebook.ma', '$2y$10$suHRVeTWBUXvbLy0E0LWuOXnjHDQ1/Q5LkrQSVxetOkF1lFWt1tcy', '0600000000', 'admin');

-- Test user (password: user1234)
INSERT INTO users (name, email, password, phone, role) VALUES
('Ayoub Testeur', 'ayoub@test.ma', '$2y$10$X9bYBni1AUtDqPV.ZvWxceHORGmJn6pnyCV2/xnqkyTAUeYvkgQwi', '0611111111', 'user');

-- Sample Movies
INSERT INTO movies (title, description, poster, trailer_url, duration_min, genre, release_date, rating) VALUES
('Interstellar', 'Dans un futur proche, la Terre est devenue hostile pour l''homme. Une équipe d''explorateurs utilise un vaisseau interstellaire pour franchir un trou de ver récemment découvert.', 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg', 'https://www.youtube.com/embed/zSWdZVtXT7E', 169, 'Science-Fiction', '2014-11-05', 'TP'),
('The Dark Knight', 'Batman doit accepter l''une des plus grandes épreuves psychologiques et physiques de sa croisade pour combattre l''injustice quand le Joker sème le chaos à Gotham.', 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911BTUgMe1YdBx.jpg', 'https://www.youtube.com/embed/EXeTwQWrcwY', 152, 'Action', '2008-07-18', '-12'),
('Inception', 'Dom Cobb est un voleur expérimenté dans l''art de l''extraction : il s''approprie les secrets les plus précieux d''un individu enfouis au plus profond de son subconscient.', 'https://image.tmdb.org/t/p/w500/ljsZTbVsrQSqZgWeep2B1QiDKuh.jpg', 'https://www.youtube.com/embed/YoHD9XEInc0', 148, 'Science-Fiction', '2010-07-16', 'TP'),
('Le Parrain', 'Le patriarche vieillissant d''une dynastie du crime organisé transfère le contrôle de son empire clandestin à son fils réticent.', 'https://image.tmdb.org/t/p/w500/3bhkrj58Vtu7enYsRolD1fZdja1.jpg', 'https://www.youtube.com/embed/sY1S34973zA', 175, 'Drame', '1972-03-24', '-16'),
('Parasite', 'Toute la famille de Ki-taek est au chômage. Elle s''intéresse particulièrement au train de vie de la richissime famille Park.', 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg', 'https://www.youtube.com/embed/5xH0HfJHsaY', 132, 'Thriller', '2019-05-30', '-12'),
('Dune', 'L''histoire de Paul Atréides, jeune homme brillant destiné à connaître un destin hors du commun. Il doit se rendre sur la planète la plus dangereuse de l''univers.', 'https://image.tmdb.org/t/p/w500/d5NXSklXo0qyIYkgV94XAgMIckC.jpg', 'https://www.youtube.com/embed/n9xhJrPXop4', 155, 'Science-Fiction', '2021-10-22', 'TP'),
('Oppenheimer', 'L''histoire de J. Robert Oppenheimer et de son rôle dans le développement de la bombe atomique.', 'https://image.tmdb.org/t/p/w500/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg', 'https://www.youtube.com/embed/uYPbbksJxIg', 180, 'Drame', '2023-07-21', '-12'),
('Spider-Man: No Way Home', 'Pour la première fois dans l''histoire cinématographique de Spider-Man, l''identité du héros est révélée, mettant en conflit ses responsabilités.', 'https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg', 'https://www.youtube.com/embed/JfVOs4VSpmA', 148, 'Action', '2021-12-17', 'TP');

-- Sample Halls
INSERT INTO halls (name, rows_count, cols_count, hall_type) VALUES
('Salle Atlas', 10, 14, 'Standard'),
('Salle IMAX Prestige', 8, 16, 'IMAX'),
('Salle VIP Lounge', 6, 8, 'VIP');

-- Sample Screenings (future dates - adjust as needed)
INSERT INTO screenings (movie_id, hall_id, show_date, show_time, price) VALUES
(1, 2, CURDATE(), '14:30:00', 80.00),
(1, 2, CURDATE(), '20:00:00', 90.00),
(2, 1, CURDATE(), '16:00:00', 60.00),
(2, 1, CURDATE(), '21:00:00', 65.00),
(3, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '15:00:00', 60.00),
(3, 3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '19:30:00', 120.00),
(6, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '17:00:00', 85.00),
(6, 2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '20:30:00', 90.00),
(7, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:00:00', 65.00),
(7, 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '21:00:00', 130.00),
(5, 1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '18:00:00', 55.00),
(8, 2, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '16:30:00', 80.00);

-- Sample Bookings
INSERT INTO bookings (user_id, screening_id, total_seats, total_price, status) VALUES
(2, 1, 2, 160.00, 'confirme'),
(2, 3, 1, 60.00, 'confirme');

INSERT INTO booking_seats (booking_id, seat_row, seat_number) VALUES
(1, 'E', 7),
(1, 'E', 8),
(2, 'D', 5);
