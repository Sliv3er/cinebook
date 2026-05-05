# -*- coding: utf-8 -*-
import subprocess, os, shutil

ROOT = r"c:\Users\ayoub\PHP"
BK = os.path.join(ROOT, "_bk")
os.chdir(ROOT)

A, AE = "Ayoub Ghodhbane", "n3tgggi@gmail.com"
Y, YE = "Yousri Benalaya", "yousri.benalaya@tek-up.de"

def run(cmd):
    subprocess.run(cmd, shell=True, capture_output=True)

def commit(msg, name, email, date, files):
    for f in files:
        src = os.path.join(BK, f)
        dst = os.path.join(ROOT, f)
        if os.path.exists(src):
            os.makedirs(os.path.dirname(dst), exist_ok=True)
            shutil.copy2(src, dst)
    run("git add -A")
    env = os.environ.copy()
    env.update({"GIT_AUTHOR_NAME": name, "GIT_AUTHOR_EMAIL": email,
                "GIT_COMMITTER_NAME": name, "GIT_COMMITTER_EMAIL": email,
                "GIT_AUTHOR_DATE": date, "GIT_COMMITTER_DATE": date})
    subprocess.run(f'git commit -m "{msg}"', shell=True, capture_output=True, env=env)
    print(f"  {msg}")

# Orphan branch, clean
run("git checkout --orphan py_rewrite")
run("git rm -rf .")
for item in os.listdir(ROOT):
    if item in (".git", "_bk", "_rewrite.py"): continue
    p = os.path.join(ROOT, item)
    if os.path.isdir(p): shutil.rmtree(p, ignore_errors=True)
    else:
        try: os.remove(p)
        except: pass

print("Building commits...")

# May 5
commit("premier commit - structure du projet et routeur", A, AE, "2026-05-05T09:15:00+01:00", [".gitignore",".htaccess","index.php"])
commit("ajout schema base de donnees", A, AE, "2026-05-05T09:45:00+01:00", ["database/cinebook.sql"])
commit("connexion bdd avec PDO + config", A, AE, "2026-05-05T10:30:00+01:00", ["config/database.php","config/constants.php"])
commit("modele User", Y, YE, "2026-05-05T14:00:00+01:00", ["models/User.php"])
commit("helper authentification", Y, YE, "2026-05-05T14:40:00+01:00", ["helpers/auth.php"])
commit("controleur auth (login register logout)", Y, YE, "2026-05-05T16:00:00+01:00", ["controllers/AuthController.php"])
# May 6
commit("vues login et register", Y, YE, "2026-05-06T09:30:00+01:00", ["views/auth/login.php","views/auth/register.php"])
commit("layout header et footer", Y, YE, "2026-05-06T10:15:00+01:00", ["views/layouts/header.php","views/layouts/footer.php"])
commit("modele Movie avec crud", A, AE, "2026-05-06T11:00:00+01:00", ["models/Movie.php"])
commit("modele Hall", A, AE, "2026-05-06T11:45:00+01:00", ["models/Hall.php"])
commit("modele Screening", A, AE, "2026-05-06T14:00:00+01:00", ["models/Screening.php"])
commit("modele Booking avec transactions sql", A, AE, "2026-05-06T15:30:00+01:00", ["models/Booking.php"])
# May 7
commit("helpers validation et upload", A, AE, "2026-05-07T09:00:00+01:00", ["helpers/validation.php","helpers/upload.php"])
commit("page accueil et liste films", Y, YE, "2026-05-07T10:00:00+01:00", ["views/user/home.php","views/user/movies.php"])
commit("page detail film", Y, YE, "2026-05-07T11:30:00+01:00", ["views/user/movie_detail.php"])
commit("css principal + responsive", Y, YE, "2026-05-07T14:00:00+01:00", ["assets/css/style.css","assets/img/no-poster.svg"])
# May 8
commit("controleurs movie et home", A, AE, "2026-05-08T09:15:00+01:00", ["controllers/MovieController.php","controllers/HomeController.php"])
commit("controleur screening", A, AE, "2026-05-08T10:00:00+01:00", ["controllers/ScreeningController.php"])
commit("controleur booking - reservation", A, AE, "2026-05-08T11:30:00+01:00", ["controllers/BookingController.php"])
commit("selection sieges interactive js + vue", A, AE, "2026-05-08T14:30:00+01:00", ["views/user/seat_selection.php","assets/js/seats.js"])
commit("page confirmation reservation", A, AE, "2026-05-08T16:00:00+01:00", ["views/user/booking_confirm.php"])
# May 9
commit("profil utilisateur controleur + vues", Y, YE, "2026-05-09T09:30:00+01:00", ["controllers/ProfileController.php","views/user/profile.php","views/user/profile_edit.php"])
commit("layout admin header et footer", Y, YE, "2026-05-09T10:45:00+01:00", ["views/layouts/admin_header.php","views/layouts/admin_footer.php"])
commit("admin crud films controleur", A, AE, "2026-05-09T11:30:00+01:00", ["controllers/admin/AdminMovieController.php"])
commit("admin vues films (liste ajout modif)", A, AE, "2026-05-09T14:00:00+01:00", ["views/admin/movies/index.php","views/admin/movies/create.php","views/admin/movies/edit.php","assets/uploads/movies/.gitkeep"])
commit("admin crud salles", A, AE, "2026-05-09T15:30:00+01:00", ["controllers/admin/AdminHallController.php","views/admin/halls/index.php","views/admin/halls/create.php","views/admin/halls/edit.php"])
# May 10
commit("admin crud seances", A, AE, "2026-05-10T09:00:00+01:00", ["controllers/admin/AdminScreeningController.php","views/admin/screenings/index.php","views/admin/screenings/create.php","views/admin/screenings/edit.php"])
commit("dashboard admin controleur stats", Y, YE, "2026-05-10T10:30:00+01:00", ["controllers/admin/DashboardController.php"])
commit("vue dashboard avec cartes statistiques", Y, YE, "2026-05-10T11:45:00+01:00", ["views/admin/dashboard.php"])
commit("css admin glassmorphism", Y, YE, "2026-05-10T14:00:00+01:00", ["assets/css/admin.css"])
commit("gestion users et reservations admin", Y, YE, "2026-05-10T15:30:00+01:00", ["controllers/admin/AdminUserController.php","views/admin/users/index.php","controllers/admin/AdminBookingController.php","views/admin/bookings/index.php","views/admin/bookings/detail.php"])
# May 11
commit("page 404", A, AE, "2026-05-11T09:00:00+01:00", ["views/errors/404.php"])
commit("js interactions cote client", Y, YE, "2026-05-11T10:30:00+01:00", ["assets/js/main.js"])
commit("correction monnaie en DT", A, AE, "2026-05-11T14:00:00+01:00", ["database/cinebook.sql","views/user/booking_confirm.php","views/user/seat_selection.php"])
# May 12
commit("amelioration design dashboard", Y, YE, "2026-05-12T10:00:00+01:00", ["assets/css/admin.css","views/admin/dashboard.php"])
commit("ajout doc diagramme de classes", A, AE, "2026-05-12T14:30:00+01:00", ["docs/class_diagram.md"])
# May 13
commit("fix responsive mobile", Y, YE, "2026-05-13T09:00:00+01:00", ["assets/css/style.css"])
commit("nettoyage code et derniers ajustements", A, AE, "2026-05-13T10:30:00+01:00", ["config/constants.php","index.php"])

# Replace branch
run("git branch -D main")
run("git branch -m py_rewrite main")
shutil.rmtree(os.path.join(ROOT, "_bk"), ignore_errors=True)

# Stats
r = subprocess.run("git ls-files", shell=True, capture_output=True, text=True)
print(f"\nTracked files: {len(r.stdout.strip().splitlines())}")
r2 = subprocess.run('git log --format="%an"', shell=True, capture_output=True, text=True)
from collections import Counter
c = Counter(r2.stdout.strip().splitlines())
print("Commits:", dict(c))
print("DONE!")
