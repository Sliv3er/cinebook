-- ============================================
-- CinéBook - Migration : Ajout données démo
-- Exécuter dans phpMyAdmin après la création initiale
-- ============================================

USE cinebook;

-- ============================================
-- Fix broken poster + Add new movies
-- ============================================

-- Fix Dark Knight poster (broken URL)
UPDATE movies SET poster = 'https://m.media-amazon.com/images/M/MV5BMTMxNTMwODM0NF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_SX300.jpg' WHERE title = 'The Dark Knight';

-- New Movies
INSERT INTO movies (title, description, poster, trailer_url, duration_min, genre, release_date, rating) VALUES

('Avatar', 'Un marine paraplégique envoyé sur la lune Pandora se retrouve déchiré entre obéir à ses ordres et protéger le monde qu''il considère désormais comme le sien.', 'https://m.media-amazon.com/images/M/MV5BMjEyOTYyMzUxNl5BMl5BanBnXkFtZTcwNTg0MTUzNA@@._V1_SX300.jpg', 'https://www.youtube.com/embed/5PSNL1qE6VY', 162, 'Science-Fiction', '2009-12-16', 'TP'),

('Joker', 'Arthur Fleck, comédien raté, sombre dans la folie et se transforme en criminel psychopathe dans les rues de Gotham City.', 'https://m.media-amazon.com/images/M/MV5BNGVjNWI4ZGUtNzE0MS00YTJmLWE0ZDctN2ZiYTk2YmI3NTYyXkEyXkFqcGdeQXVyMTkxNjUyNQ@@._V1_SX300.jpg', 'https://www.youtube.com/embed/zAGVQLHvwOY', 122, 'Drame', '2019-10-04', '-16'),

('Avengers: Endgame', 'Après les événements dévastateurs d''Infinity War, les Avengers restants tentent de renverser les actions de Thanos et de restaurer l''équilibre dans l''univers.', 'https://m.media-amazon.com/images/M/MV5BMTc5MDE2ODcwNV5BMl5BanBnXkFtZTgwMzI2NzQ2NzM@._V1_SX300.jpg', 'https://www.youtube.com/embed/TcMBFSGVi1c', 181, 'Action', '2019-04-24', 'TP'),

('Gladiator', 'Un général romain trahi par l''empereur est réduit en esclavage et devient gladiateur pour venger le meurtre de sa famille.', 'https://m.media-amazon.com/images/M/MV5BMDliMmNhNDEtODUyOS00MjNlLTgxODEtN2U3NzIxMGVkZGQ4XkEyXkFqcGdeQXVyNjU0OTQ0OTY@._V1_SX300.jpg', 'https://www.youtube.com/embed/owK1qxDselE', 155, 'Action', '2000-05-05', '-12'),

('John Wick', 'Un ancien tueur à gages sort de sa retraite pour traquer les gangsters qui ont tué son chien et volé sa voiture.', 'https://m.media-amazon.com/images/M/MV5BMTU2NjA1ODgzMF5BMl5BanBnXkFtZTgwMTM2MTI4MjE@._V1_SX300.jpg', 'https://www.youtube.com/embed/C0BMx-qxsP4', 101, 'Action', '2014-10-24', '-16'),

('La La Land', 'À Los Angeles, un musicien de jazz et une aspirante actrice tombent amoureux tout en poursuivant leurs rêves dans une ville connue pour briser les coeurs.', 'https://m.media-amazon.com/images/M/MV5BMzUzNDM2NzM2MV5BMl5BanBnXkFtZTgwNTM3NTg4OTE@._V1_SX300.jpg', 'https://www.youtube.com/embed/0pdqf4P9MB8', 128, 'Romance', '2016-12-09', 'TP'),

('Le Roi Lion', 'Un jeune lion d''Afrique doit accepter son destin de roi après la mort tragique de son père aux mains de son oncle.', 'https://m.media-amazon.com/images/M/MV5BMjIwMjE1Nzc4NV5BMl5BanBnXkFtZTgwNDg4OTA1NzM@._V1_SX300.jpg', 'https://www.youtube.com/embed/7TavVZMewpY', 118, 'Animation', '2019-07-19', 'TP'),

('Matrix', 'Un programmeur informatique découvre que la réalité telle qu''il la connaît est une simulation créée par des machines intelligentes.', 'https://m.media-amazon.com/images/M/MV5BN2NmN2VhMTQtMDNiOS00NDlhLTliMjgtODE2ZDYxZjQxZjFhXkEyXkFqcGc@._V1_SX300.jpg', 'https://www.youtube.com/embed/vKQi3bBA1y8', 136, 'Science-Fiction', '1999-03-31', '-12'),

('Titanic', 'Une jeune aristocrate tombe amoureuse d''un artiste pauvre à bord du navire de luxe maudit.', 'https://m.media-amazon.com/images/M/MV5BMDdmZGU3NDQtY2E5My00ZTliLWIzOTUtMTY4ZGI1YjdiNjk3XkEyXkFqcGdeQXVyNTA4NzY1MzY@._V1_SX300.jpg', 'https://www.youtube.com/embed/kVrqfYjkTdQ', 194, 'Romance', '1997-12-19', 'TP'),

('Pulp Fiction', 'Les vies de deux tueurs à gages, d''un boxeur, d''un gangster et de sa femme s''entrelacent dans des histoires de violence et de rédemption.', 'https://m.media-amazon.com/images/M/MV5BNGNhMDIzZTUtNTBlZi00MTRlLWFjMDYtZGViZjFhNGY1ZjE1XkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_SX300.jpg', 'https://www.youtube.com/embed/s7EdQ4FqbhY', 154, 'Thriller', '1994-10-14', '-16'),

('Shutter Island', 'Un marshal américain enquête sur la disparition d''une patiente d''un hôpital psychiatrique situé sur une île isolée.', 'https://m.media-amazon.com/images/M/MV5BYzhiNDkyNzktNTZmYS00ZTBkLTk2MDAtM2U0YjU1MzgxZjgzXkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg', 'https://www.youtube.com/embed/v8yrZSkKxTA', 138, 'Thriller', '2010-02-19', '-12'),

('Django Unchained', 'Un ancien esclave s''associe à un chasseur de primes allemand pour sauver sa femme des griffes d''un propriétaire de plantation cruel.', 'https://m.media-amazon.com/images/M/MV5BMjIyNTQ5NjQ1OV5BMl5BanBnXkFtZTcwODg1MDU4OA@@._V1_SX300.jpg', 'https://www.youtube.com/embed/0fUCuvNlOCg', 165, 'Western', '2012-12-25', '-16');

-- Also update existing posters to use reliable Amazon CDN
UPDATE movies SET poster = 'https://m.media-amazon.com/images/M/MV5BZjdkOTU3MDktN2IxOS00OGEyLWFmMjktY2FiMmZkNWIyODZiXkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg' WHERE title = 'Interstellar';
UPDATE movies SET poster = 'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_SX300.jpg' WHERE title = 'Inception';
UPDATE movies SET poster = 'https://m.media-amazon.com/images/M/MV5BM2MyNjYxNmUtYTAwNi00MTYxLWJmNWYtYzZlODY3ZTk3OTFlXkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_SX300.jpg' WHERE title LIKE '%Parrain%';
UPDATE movies SET poster = 'https://m.media-amazon.com/images/M/MV5BYWZjMjk3ZTItODQ2ZC00NTY5LWE0ZDYtZTI3MjcwN2Q5NTVkXkEyXkFqcGdeQXVyODk4OTc3MTY@._V1_SX300.jpg' WHERE title = 'Parasite';
UPDATE movies SET poster = 'https://m.media-amazon.com/images/M/MV5BMDQ0NjgyN2YtNWViNS00YjA3LTkxNDktYzFkZTExZGMxZDkxXkEyXkFqcGdeQXVyODE5NzE3OTE@._V1_SX300.jpg' WHERE title = 'Dune';
UPDATE movies SET poster = 'https://m.media-amazon.com/images/M/MV5BN2JkMDc5MGQtZjg3YS00NmFiLWIyZmQtZjZjMjc3MjY1YWRhXkEyXkFqcGc@._V1_SX300.jpg' WHERE title = 'Oppenheimer';
UPDATE movies SET poster = 'https://m.media-amazon.com/images/M/MV5BZWMyYzFjYTYtNTRjYi00OGExLWE2YzgtOGRmYjFkZTcwNjg3XkEyXkFqcGc@._V1_SX300.jpg' WHERE title LIKE '%Spider-Man%';

-- ============================================
-- More Halls
-- ============================================
INSERT INTO halls (name, rows_count, cols_count, hall_type) VALUES
('Salle Carthage', 12, 16, 'Standard'),
('Salle Premium Gold', 5, 10, 'VIP'),
('Salle IMAX Ultra', 10, 18, 'IMAX'),
('Salle Médina', 8, 12, 'Standard'),
('Salle 4DX Expérience', 7, 14, 'IMAX');

-- ============================================
-- Lots of Screenings (today + next 5 days)
-- ============================================
INSERT INTO screenings (movie_id, hall_id, show_date, show_time, price) VALUES
-- Today
(1, 1, CURDATE(), '10:00:00', 55.00),
(1, 2, CURDATE(), '14:00:00', 80.00),
(1, 5, CURDATE(), '17:30:00', 95.00),
(2, 1, CURDATE(), '11:00:00', 55.00),
(2, 3, CURDATE(), '16:00:00', 65.00),
(3, 2, CURDATE(), '13:30:00', 80.00),
(3, 4, CURDATE(), '19:00:00', 70.00),
(4, 1, CURDATE(), '20:30:00', 60.00),
(5, 3, CURDATE(), '15:00:00', 65.00),
(6, 5, CURDATE(), '18:00:00', 90.00),
(7, 1, CURDATE(), '22:00:00', 60.00),
(8, 2, CURDATE(), '21:00:00', 85.00),
(9, 4, CURDATE(), '14:30:00', 50.00),
(10, 1, CURDATE(), '16:30:00', 55.00),
(11, 3, CURDATE(), '20:00:00', 65.00),
(12, 5, CURDATE(), '19:30:00', 90.00),
(13, 1, CURDATE(), '15:00:00', 55.00),
(14, 2, CURDATE(), '17:00:00', 80.00),
(15, 4, CURDATE(), '21:30:00', 50.00),
(16, 1, CURDATE(), '13:00:00', 55.00),

-- Tomorrow
(1, 3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '14:30:00', 70.00),
(2, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '16:00:00', 55.00),
(3, 5, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '18:00:00', 95.00),
(4, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '20:00:00', 80.00),
(5, 4, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '11:00:00', 50.00),
(6, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '13:30:00', 55.00),
(7, 3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '19:30:00', 70.00),
(8, 5, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '21:00:00', 90.00),
(9, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:00:00', 50.00),
(10, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '15:00:00', 80.00),
(11, 4, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '17:30:00', 50.00),
(12, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '22:00:00', 60.00),
(13, 3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '16:30:00', 65.00),
(14, 5, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '14:00:00', 90.00),
(15, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '20:30:00', 85.00),
(16, 4, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '12:00:00', 45.00),

-- Day +2
(1, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '11:00:00', 55.00),
(3, 2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:30:00', 80.00),
(5, 5, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '17:00:00', 95.00),
(7, 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '20:00:00', 65.00),
(9, 4, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '16:00:00', 50.00),
(11, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '19:30:00', 55.00),
(13, 2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '21:00:00', 85.00),
(15, 5, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '18:30:00', 90.00),
(16, 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '15:00:00', 65.00),
(17, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '22:00:00', 60.00),
(18, 4, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '13:00:00', 50.00),
(19, 2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '20:30:00', 80.00),
(20, 5, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '19:00:00', 95.00),

-- Day +3
(2, 1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '14:00:00', 55.00),
(4, 3, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '16:30:00', 70.00),
(6, 5, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '19:00:00', 95.00),
(8, 2, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '21:00:00', 80.00),
(10, 4, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '11:30:00', 50.00),
(12, 1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '17:00:00', 60.00),
(14, 3, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '20:30:00', 65.00),
(16, 5, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '15:00:00', 90.00),
(18, 2, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '13:30:00', 80.00),
(20, 4, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '22:00:00', 55.00);
