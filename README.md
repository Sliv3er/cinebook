# 🎬 CinéBook

Plateforme de réservation de billets de cinéma développée en PHP natif (MVC).

**Projet universitaire** — TEK-UP University, 3ème année Ingénierie  
**Encadrant** : Prof. Jasser Jammeli  
**Année** : 2025-2026

## Équipe

- **Ayoub Ghodhbane** — Architecture, BDD, Modèles, Réservation, Admin CRUD
- **Yousri Benalaya** — Authentification, Profil, Dashboard, Front-end, Design

---

## Prérequis

- [XAMPP](https://www.apachefriends.org/) (PHP 8+, MySQL/MariaDB, Apache)
- Git

## Installation

### 1. Cloner le projet

```bash
cd C:\xampp\htdocs
git clone https://github.com/Sliv3er/cinebook.git
```

### 2. Lancer XAMPP

- Ouvrir **XAMPP Control Panel**
- Démarrer **Apache** et **MySQL**

### 3. Créer la base de données

- Ouvrir **phpMyAdmin** : [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
- Cliquer sur **"Nouvelle base de données"**
- Nom : `cinebook` — Interclassement : `utf8mb4_general_ci`
- Cliquer **Créer**
- Aller dans l'onglet **"Importer"**
- Choisir le fichier `database/cinebook.sql`
- Cliquer **Exécuter**

### 4. Accéder à l'application

Ouvrir dans le navigateur :

```
http://localhost/cinebook
```

## Comptes par défaut

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| Admin | admin@cinebook.tn | admin123 |
| Client | client@cinebook.tn | client123 |

> ⚠️ Si les mots de passe ne fonctionnent pas, exécuter cette requête SQL dans phpMyAdmin :
> ```sql
> UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE role = 'admin';
> UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE role = 'client';
> ```
> (Ce hash correspond au mot de passe `password`)

## Structure du projet

```
cinebook/
├── index.php                  # Front controller (routeur)
├── .htaccess                  # Réécriture URL Apache
├── config/
│   ├── constants.php          # Configuration application
│   └── database.php           # Connexion PDO (singleton)
├── controllers/
│   ├── AuthController.php     # Login / Register / Logout
│   ├── BookingController.php  # Réservation de billets
│   ├── HomeController.php     # Page d'accueil
│   ├── MovieController.php    # Affichage films
│   ├── ProfileController.php  # Profil utilisateur
│   ├── ScreeningController.php
│   └── admin/                 # Contrôleurs administration
├── models/
│   ├── Booking.php            # Modèle réservation
│   ├── Hall.php               # Modèle salle
│   ├── Movie.php              # Modèle film
│   ├── Screening.php          # Modèle séance
│   └── User.php               # Modèle utilisateur
├── views/
│   ├── admin/                 # Vues administration
│   ├── auth/                  # Login, Register
│   ├── layouts/               # Header, Footer
│   └── user/                  # Vues client
├── helpers/
│   ├── auth.php               # Fonctions authentification
│   ├── upload.php             # Upload sécurisé
│   └── validation.php         # Validation serveur
├── assets/
│   ├── css/                   # Stylesheets
│   └── js/                    # JavaScript
└── database/
    └── cinebook.sql           # Schéma BDD
```

## Technologies

- **Backend** : PHP 8 natif (MVC, sans framework)
- **Base de données** : MySQL / MariaDB
- **Frontend** : HTML5, CSS3, JavaScript
- **Serveur** : Apache (XAMPP)
- **Sécurité** : PDO préparé, bcrypt, sessions PHP
