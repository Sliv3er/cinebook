# Diagramme de Classes — CinéBook

## Plateforme de Réservation Cinéma

> Projet PHP/JS — Diagramme UML des entités du système

```mermaid
classDiagram
    direction LR

    class User {
        -int id
        -string name
        -string email
        -string password
        -string phone
        -enum role : user | admin
        -bool is_active
        -datetime created_at
        -datetime updated_at
        +findById(int id) array
        +findByEmail(string email) array
        +create(array data) int
        +update(int id, array data) bool
        +getAll(string search, int page) array
        +toggleStatus(int id) bool
        +countAll() int
    }

    class Movie {
        -int id
        -string title
        -text description
        -string poster
        -string trailer_url
        -int duration_min
        -string genre
        -date release_date
        -string rating
        -bool is_active
        -datetime created_at
        -datetime updated_at
        +findById(int id) array
        +getActive(string genre, string search, int page) array
        +getAll(string search, int page) array
        +create(array data) int
        +update(int id, array data) bool
        +delete(int id) bool
        +toggleStatus(int id) bool
        +getFeatured(int limit) array
        +getTopByBookings(int limit) array
        +getBookingsByGenre() array
    }

    class Hall {
        -int id
        -string name
        -int rows_count
        -int cols_count
        -enum hall_type : Standard | IMAX | VIP
        -bool is_active
        -datetime created_at
        +findById(int id) array
        +getAll(int page) array
        +getActive() array
        +create(array data) int
        +update(int id, array data) bool
        +delete(int id) bool
        +toggleStatus(int id) bool
        +getCapacity(int id) int
        +getOccupancyRates() array
    }

    class Screening {
        -int id
        -int movie_id
        -int hall_id
        -date show_date
        -time show_time
        -decimal price
        -bool is_active
        -datetime created_at
        +findById(int id) array
        +getByMovie(int movieId) array
        +getAll(string search, int page) array
        +create(array data) int
        +update(int id, array data) bool
        +delete(int id) bool
        +toggleStatus(int id) bool
        +getBookedSeats(int screeningId) array
        +getAvailableSeats(int screeningId) int
        +countToday() int
    }

    class Booking {
        -int id
        -int user_id
        -int screening_id
        -int total_seats
        -decimal total_price
        -enum status : en_attente | confirme | annule
        -datetime booked_at
        +findById(int id) array
        +create(int userId, int screeningId, array seats, float totalPrice) int
        +getByUser(int userId, int page) array
        +getAll(string status, string search, int page) array
        +getSeats(int bookingId) array
        +updateStatus(int id, string status) bool
        +cancel(int id, int userId) bool
        +getTotalRevenue() float
        +getRevenueByDay(int days) array
        +getBookingsByDay(int days) array
    }

    class BookingSeat {
        -int id
        -int booking_id
        -char seat_row
        -int seat_number
    }

    User "1" --> "*" Booking : effectue
    Movie "1" --> "*" Screening : programmé dans
    Hall "1" --> "*" Screening : accueille
    Screening "1" --> "*" Booking : réservation pour
    Booking "1" --> "*" BookingSeat : contient
```

## Description des relations

| Relation | Cardinalité | Description |
|----------|-------------|-------------|
| User → Booking | 1..* | Un utilisateur peut effectuer plusieurs réservations |
| Movie → Screening | 1..* | Un film peut avoir plusieurs séances |
| Hall → Screening | 1..* | Une salle accueille plusieurs séances |
| Screening → Booking | 1..* | Une séance peut avoir plusieurs réservations |
| Booking → BookingSeat | 1..* | Une réservation contient un ou plusieurs sièges |

## Entités gérées (hors User)

1. **Movie** (Film) — CRUD complet avec upload d'affiche
2. **Hall** (Salle) — CRUD complet avec types (Standard, IMAX, VIP)
3. **Screening** (Séance) — CRUD complet avec lien film/salle
4. **Booking** (Réservation) — Création, consultation, confirmation, annulation
