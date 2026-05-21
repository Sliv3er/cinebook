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

-- Admin (password: admin123)
INSERT INTO users (name, email, password, phone, role) VALUES
('Administrateur', 'admin@cinebook.tn', '$2y$10$suHRVeTWBUXvbLy0E0LWuOXnjHDQ1/Q5LkrQSVxetOkF1lFWt1tcy', '55000001', 'admin');

-- Users (password: user1234)
INSERT INTO users (name, email, password, phone, role) VALUES
('Ayoub Ghodhbane', 'ayoub@test.tn', '$2y$10$X9bYBni1AUtDqPV.ZvWxceHORGmJn6pnyCV2/xnqkyTAUeYvkgQwi', '55000002', 'user'),
('Yousri Benalaya', 'yousri@test.tn', '$2y$10$X9bYBni1AUtDqPV.ZvWxceHORGmJn6pnyCV2/xnqkyTAUeYvkgQwi', '55000003', 'user'),
('Hamdi Ben Slimen', 'hamdi@test.tn', '$2y$10$X9bYBni1AUtDqPV.ZvWxceHORGmJn6pnyCV2/xnqkyTAUeYvkgQwi', '55000004', 'user'),
('Sara Mansouri', 'sara@test.tn', '$2y$10$X9bYBni1AUtDqPV.ZvWxceHORGmJn6pnyCV2/xnqkyTAUeYvkgQwi', '55000005', 'user');

-- Movies (20 films)
INSERT INTO movies (title, description, poster, trailer_url, duration_min, genre, release_date, rating) VALUES
('Interstellar', 'Dans un futur proche, la Terre est devenue hostile. Une équipe d''explorateurs utilise un trou de ver pour voyager au-delà de notre galaxie.', 'https://image.tmdb.org/t/p/w500/rAiYTfKGqDCRIIqo664sY9XZIvQ.jpg', 'https://www.youtube.com/embed/zSWdZVtXT7E', 169, 'Science-Fiction', '2014-11-05', 'TP'),
('The Dark Knight', 'Batman affronte le Joker, un criminel anarchiste qui plonge Gotham dans le chaos le plus total.', 'https://m.media-amazon.com/images/M/MV5BMTMxNTMwODM0NF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_SX300.jpg', 'https://www.youtube.com/embed/EXeTwQWrcwY', 152, 'Action', '2008-07-18', '-12'),
('Inception', 'Dom Cobb est un voleur spécialisé dans l''extraction de secrets enfouis dans le subconscient pendant le rêve.', 'https://image.tmdb.org/t/p/w500/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg', 'https://www.youtube.com/embed/YoHD9XEInc0', 148, 'Science-Fiction', '2010-07-16', 'TP'),
('Le Parrain', 'Le patriarche vieillissant d''une dynastie du crime organisé transfère le contrôle de son empire à son fils.', 'https://image.tmdb.org/t/p/w500/3bhkrj58Vtu7enYsRolD1fZdja1.jpg', 'https://www.youtube.com/embed/sY1S34973zA', 175, 'Drame', '1972-03-24', '-16'),
('Parasite', 'Toute la famille de Ki-taek est au chômage et s''intéresse au train de vie de la richissime famille Park.', 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg', 'https://www.youtube.com/embed/5xH0HfJHsaY', 132, 'Thriller', '2019-05-30', '-12'),
('Dune', 'Paul Atréides, jeune homme brillant, doit se rendre sur Arrakis, la planète la plus dangereuse de l''univers.', 'https://image.tmdb.org/t/p/w500/d5NXSklXo0qyIYkgV94XAgMIckC.jpg', 'https://www.youtube.com/embed/n9xhJrPXop4', 155, 'Science-Fiction', '2021-10-22', 'TP'),
('Oppenheimer', 'L''histoire de J. Robert Oppenheimer et son rôle dans le développement de la bombe atomique.', 'https://image.tmdb.org/t/p/w500/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg', 'https://www.youtube.com/embed/uYPbbksJxIg', 180, 'Drame', '2023-07-21', '-12'),
('Spider-Man: No Way Home', 'L''identité de Spider-Man est révélée, mettant en conflit ses responsabilités de super-héros.', 'https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg', 'https://www.youtube.com/embed/JfVOs4VSpmA', 148, 'Action', '2021-12-17', 'TP'),
('Avatar: La Voie de l''Eau', 'Jake Sully et Neytiri ont fondé une famille et font tout pour rester ensemble face à une nouvelle menace.', 'https://image.tmdb.org/t/p/w500/t6HIqrRAclMCA60NsSmeqe9RmNV.jpg', 'https://www.youtube.com/embed/d9MyW72ELq0', 192, 'Science-Fiction', '2022-12-14', 'TP'),
('Gladiator', 'Un général romain trahi par l''empereur corrompu devient gladiateur pour venger sa famille assassinée.', 'https://image.tmdb.org/t/p/w500/ehGpN04mLJIrSnxcZBMvHeG0eDc.jpg', 'https://www.youtube.com/embed/owK1qxDselE', 155, 'Action', '2000-05-05', '-12'),
('Joker', 'Arthur Fleck, un comédien raté, bascule dans la folie et devient le célèbre criminel connu sous le nom de Joker.', 'https://image.tmdb.org/t/p/w500/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg', 'https://www.youtube.com/embed/zAGVQLHvwOY', 122, 'Drame', '2019-10-02', '-16'),
('La La Land', 'À Los Angeles, Mia, une actrice en herbe, et Sebastian, un musicien de jazz, tombent amoureux.', 'https://image.tmdb.org/t/p/w500/uDO8zWDhfWwoFdKS4fzkUJt0Rf0.jpg', 'https://www.youtube.com/embed/0pdqf4P9MB8', 128, 'Romance', '2016-12-09', 'TP'),
('Fight Club', 'Un employé de bureau insomniaque et un vendeur de savon créent un club de combat clandestin.', 'https://image.tmdb.org/t/p/w500/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg', 'https://www.youtube.com/embed/SUXWAEX2jlg', 139, 'Thriller', '1999-10-15', '-16'),
('Avengers: Endgame', 'Les Avengers restants doivent trouver un moyen de ramener leurs alliés pour une confrontation finale avec Thanos.', 'https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg', 'https://www.youtube.com/embed/TcMBFSGVi1c', 181, 'Action', '2019-04-24', 'TP'),
('Le Roi Lion', 'Simba, un jeune lion destiné à régner sur la Terre des Lions, doit d''abord grandir et affronter son passé.', 'https://m.media-amazon.com/images/M/MV5BMjIwMjE1Nzc4NV5BMl5BanBnXkFtZTgwNDg4OTA1NzM@._V1_SX300.jpg', 'https://www.youtube.com/embed/7TavVZMewpY', 118, 'Animation', '2019-07-12', 'TP'),
('Matrix', 'Un programmeur découvre que la réalité telle qu''il la connaît est une simulation créée par des machines.', 'https://image.tmdb.org/t/p/w500/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg', 'https://www.youtube.com/embed/vKQi3bBA1y8', 136, 'Science-Fiction', '1999-03-31', '-12'),
('Titanic', 'Un jeune artiste et une aristocrate tombent amoureux à bord du paquebot Titanic lors de son voyage inaugural.', 'https://image.tmdb.org/t/p/w500/9xjZS2rlVxm8SFx8kPC3aIGCOYQ.jpg', 'https://www.youtube.com/embed/kVrqfYjkTdQ', 195, 'Romance', '1997-12-19', '-12'),
('Shutter Island', 'En 1954, le marshal Teddy Daniels enquête sur la disparition d''une patiente dans un hôpital psychiatrique sur une île.', 'https://m.media-amazon.com/images/M/MV5BYzhiNDkyNzktNTZmYS00ZTBkLTk2MDAtM2U0YjU1MzgxZjgzXkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg', 'https://www.youtube.com/embed/5iaYLCiq5RM', 138, 'Thriller', '2010-02-19', '-12'),
('Django Unchained', 'Dans le sud des États-Unis, un esclave affranchi part à la recherche de sa femme avec l''aide d''un chasseur de primes.', 'https://image.tmdb.org/t/p/w500/7oWY8VDWW7thTzWh3OKYRkWUlD5.jpg', 'https://www.youtube.com/embed/0fUCuvNlOCg', 165, 'Western', '2012-12-25', '-16'),
('Inside Out', 'Les émotions de Riley — Joie, Tristesse, Peur, Colère et Dégoût — guident sa vie quotidienne.', 'https://m.media-amazon.com/images/M/MV5BOTgxMDQwMDk0OF5BMl5BanBnXkFtZTgwNjU5OTg2NDE@._V1_SX300.jpg', 'https://www.youtube.com/embed/yRUAzGQ3nSY', 95, 'Animation', '2015-06-19', 'TP');

-- Halls (5 salles)
INSERT INTO halls (name, rows_count, cols_count, hall_type) VALUES
('Salle Atlas', 10, 14, 'Standard'),
('Salle IMAX Prestige', 8, 16, 'IMAX'),
('Salle VIP Lounge', 6, 8, 'VIP'),
('Salle Carthage', 12, 16, 'Standard'),
('Salle Médina', 8, 12, 'Standard');

-- Screenings (séances sur 7 jours)
INSERT INTO screenings (movie_id, hall_id, show_date, show_time, price) VALUES
-- Aujourd'hui
(1, 2, CURDATE(), '14:30:00', 80.00),
(1, 2, CURDATE(), '20:00:00', 90.00),
(2, 1, CURDATE(), '16:00:00', 60.00),
(3, 4, CURDATE(), '19:00:00', 45.00),
(7, 3, CURDATE(), '21:30:00', 120.00),
(8, 1, CURDATE(), '14:00:00', 50.00),
(11, 5, CURDATE(), '18:00:00', 40.00),
(14, 2, CURDATE(), '16:30:00', 85.00),
-- Demain
(2, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '15:00:00', 60.00),
(3, 3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '19:30:00', 110.00),
(4, 4, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '20:00:00', 45.00),
(5, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '21:00:00', 55.00),
(6, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '17:00:00', 85.00),
(9, 5, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '14:00:00', 40.00),
(10, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '16:00:00', 50.00),
(12, 3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '21:30:00', 120.00),
-- J+2
(1, 4, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '15:00:00', 45.00),
(6, 2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '20:30:00', 90.00),
(7, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:00:00', 65.00),
(7, 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '21:00:00', 130.00),
(13, 5, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '18:00:00', 40.00),
(15, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '16:00:00', 50.00),
(16, 2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '19:00:00', 80.00),
-- J+3
(3, 1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '15:00:00', 55.00),
(5, 1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '18:00:00', 55.00),
(8, 2, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '16:30:00', 80.00),
(10, 4, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '20:00:00', 45.00),
(17, 3, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '21:00:00', 110.00),
(18, 5, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '14:00:00', 35.00),
(19, 1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '19:00:00', 50.00),
(20, 4, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '16:00:00', 40.00),
-- J+4 à J+6
(1, 2, DATE_ADD(CURDATE(), INTERVAL 4 DAY), '20:00:00', 85.00),
(4, 1, DATE_ADD(CURDATE(), INTERVAL 4 DAY), '18:00:00', 50.00),
(9, 3, DATE_ADD(CURDATE(), INTERVAL 4 DAY), '21:30:00', 120.00),
(11, 5, DATE_ADD(CURDATE(), INTERVAL 5 DAY), '19:00:00', 40.00),
(14, 2, DATE_ADD(CURDATE(), INTERVAL 5 DAY), '17:00:00', 80.00),
(16, 4, DATE_ADD(CURDATE(), INTERVAL 5 DAY), '20:30:00', 45.00),
(2, 1, DATE_ADD(CURDATE(), INTERVAL 6 DAY), '16:00:00', 55.00),
(6, 2, DATE_ADD(CURDATE(), INTERVAL 6 DAY), '19:30:00', 85.00),
(19, 3, DATE_ADD(CURDATE(), INTERVAL 6 DAY), '21:00:00', 115.00);

-- Sample Bookings
INSERT INTO bookings (user_id, screening_id, total_seats, total_price, status) VALUES
(2, 1, 2, 160.00, 'confirme'),
(2, 3, 1, 60.00, 'confirme'),
(3, 2, 3, 270.00, 'confirme'),
(3, 5, 2, 240.00, 'en_attente'),
(4, 4, 1, 45.00, 'confirme'),
(5, 6, 2, 100.00, 'confirme');

INSERT INTO booking_seats (booking_id, seat_row, seat_number) VALUES
(1, 'E', 7), (1, 'E', 8),
(2, 'D', 5),
(3, 'C', 3), (3, 'C', 4), (3, 'C', 5),
(4, 'F', 3), (4, 'F', 4),
(5, 'B', 10),
(6, 'A', 6), (6, 'A', 7);

