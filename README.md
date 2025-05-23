## Descriere

Platformă web pentru gestionarea lucrărilor de licență/disertație, cu roluri pentru student, profesor și administrator. Include funcționalități de upload PDF, mesagerie, feedback, management utilizatori și lucrări, notificări și dashboard-uri dedicate.

## Funcționalități principale
- **Student:**
  - Upload și previzualizare lucrare PDF
  - Vizualizare feedback primit (notă și mesaj de la profesor)
  - Mesagerie cu profesorul (thread, cu atașamente PDF opționale)
  - Notificări pentru mesaje necitite
- **Profesor:**
  - Vizualizare lucrări studenți asociați
  - Acordare feedback (notă și mesaj) pentru fiecare lucrare
  - Editare/ștergere feedback
  - Ștergere lucrări studenți
  - Mesagerie cu studenții
  - Notificări pentru mesaje necitite
- **Administrator:**
  - Management utilizatori (creare, editare, ștergere profesori/studenți)
  - Management lucrări (vizualizare, redenumire, ștergere)
  - Asignare profesori la studenți

## Tehnologii folosite
- **Backend:** Laravel 10+
- **Frontend:** Blade, Tailwind CSS, Material Icons, Alpine.js
- **DB:** MySQL/MariaDB
- **Altele:** Composer, npm, Vite, Docker/Sail (opțional)

## Instalare și rulare

### 1. Clonare proiect
```bash
git clone <repo-url> licenta
cd licenta
```

### 2. Instalare dependențe
```bash
composer install
npm install
```

### 3. Configurare mediu
- Copiază `.env.example` în `.env`:
  ```bash
  cp .env.example .env
  ```
- Setează datele de conectare la baza de date în `.env`:
  ```env
  DB_DATABASE=licenta
  DB_USERNAME=utilizator
  DB_PASSWORD=parola
  ```

### 4. Generează cheia aplicației
```bash
php artisan key:generate
```

### 5. Migrare și seed (dacă ai seedere)
```bash
php artisan migrate
# sau
php artisan migrate --seed
```

### 6. Compilează assets frontend
```bash
npm run build
# sau pentru development:
npm run dev
```

### 7. Pornește serverul
```bash
php artisan serve
```
- Accesează aplicația la http://localhost:8000

### 8. (Opțional) Folosește Docker/Sail
```bash
./vendor/bin/sail up
```

## Alte note
- Pentru funcționalitatea de upload/descărcare PDF, asigură-te că folderul `storage/` are permisiuni de scriere.
- Dacă muți proiectul, copiază și fișierele din `storage/app/private` pentru a păstra lucrările încărcate.
- Pentru notificări live, aplicația folosește AJAX polling (nu WebSockets).

## Structură foldere relevante
- `app/Http/Controllers/` - logica pentru dashboard-uri, mesagerie, feedback etc.
- `resources/views/` - Blade views pentru fiecare rol și funcționalitate
- `routes/web.php` - rutele aplicației
- `database/migrations/` - structura tabelelor (users, pdfs, messages, feedback etc.)
