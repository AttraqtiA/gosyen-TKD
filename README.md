# Gosyen Assessment — Portal Psikologi IST & TKD

Web-based candidate assessment platform for **PT. Gosyen Polinator Indonesia** and partner companies, hosted at **[test.gosyenpolinator.com](https://test.gosyenpolinator.com)**.

The application combines standardized **Intelligenz-Struktur-Test (IST)** psychological subtests with position-specific **Technical Essays & Case Studies**, paired with an automated scoring engine, anti-cheat monitoring, and role-based Admin & Psychologist dashboards.

---

## Key Features

### Candidate Exam Portal (`/`)
* **Structured 5-Session Assessment Flow:**
  1. **Sesi 1 (WA — Wortauswahl):** 20 randomized word-selection questions with shuffled answer options per candidate.
  2. **Sesi 2 (AN — Analogi):** 20 verbal analogy questions.
  3. **Sesi 3 (ZR — Zahlenreihen):** 20 number series questions.
  4. **Sesi 4 (FA — Figurenauswahl):** 20 visual/spatial reasoning questions.
  5. **Sesi 5 (Esai & Studi Kasus):** Position-specific technical essays and case studies (automatically skipped for custom/unlisted positions without Sesi 5 banks).
* **Company & Position Filtering:** Candidates select their target company (`perusahaan`) and position (`posisi`), dynamically filtering company-specific and general roles.
* **Bilingual & Theme Support:** Indonesian (`ID`) / English (`EN`) flag switcher and Light/Dark mode toggle (`localStorage` persisted, respects OS preference).
* **PWA Ready:** Installable Progressive Web App (`public/manifest.json`, `public/sw.js`) with Screen Wake Lock support on mobile devices.

### Security & Anti-Cheat System
* **Tab/Window Switch Detection:** Monitors `visibilitychange`, `window.blur`, and periodic `document.hasFocus()` checks.
* **Server-Persisted Violations & Freeze:** Violations are immediately logged via `POST /ujian/pelanggaran` with a 30-second freeze penalty (web-audio siren, vibration, and visual strobe) that persists across page reloads (`F5`). Reaching 3 violations automatically disqualifies and submits the exam with a score of 0.
* **Sequential Flow & Server-Side Timer:** Enforces strict session order (`1 → 2 → 3 → 4 → 5`) and tracks elapsed time on the server so refreshing the page cannot reset timers or skip sessions.
* **Exam Lockdowns:** Blocks copy/cut/paste, right-click context menus, and DevTools shortcuts during active timed sessions, and rate-limits dashboard PIN login (`3 attempts/min`).

### Admin & Psychologist Dashboard (`/login` → `/dashboard`)
* **Admin Role:**
  * Full CRUD for **Perusahaan (Companies/Clients)** and **Posisi (Roles)** with safe `ON DELETE SET NULL` relationships so candidate history is never erased.
  * Full CRUD for **Bank Soal Sesi 1–5**, **Rubrik Penilaian**, and per-session **Timer Durations**.
  * Psychologist access PIN management.
* **Psychologist Role:**
  * Filterable candidate results table (date range, name, company, position) with automatic IST raw-to-SW (*Standard Wert*) normalization and recommendation categories (`AnalisisUjianHelper`).
  * Individual answer inspection/correction, **PDF**, **Word (.doc)**, and filtered **Excel (.xls)** report exports.

---

## Tech Stack

* **Backend:** Laravel (PHP), Eloquent ORM & Query Builder, DomPDF (`barryvdh/laravel-dompdf`)
* **Database:** MySQL / MariaDB
* **Frontend:** Blade Templates, Bootstrap 5, Vanilla JavaScript, Custom Light/Dark CSS Design Tokens (`Plus Jakarta Sans`)
* **Deployment:** GitHub Actions SSH CI/CD (`.github/workflows/deploy.yml`) on Hostinger

---

## Local Development Setup

```bash
# 1. Install PHP dependencies
composer install

# 2. Configure environment
cp .env.example .env
php artisan key:generate
# Update DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env

# 3. Run migrations & Sesi 5 seeder
php artisan migrate
php artisan db:seed --class=BankSoalSesi5Seeder

# 4. Start local development server
php artisan serve
```

### Running Tests

```bash
php artisan test
```

---

## Deployment (Hostinger CI/CD)

Pushes to `main` (or manual runs via `workflow_dispatch`) trigger `.github/workflows/deploy.yml`, which connects via SSH to Hostinger (`~/domains/test.gosyenpolinator.com/public_html`) and executes:
1. `git pull origin main`
2. `composer install --no-dev --optimize-autoloader`
3. `php artisan migrate --force`
4. `php artisan db:seed --class=BankSoalSesi5Seeder --force`
5. `php artisan view:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache`

Required GitHub Repository Secrets: `SERVER_HOST`, `SERVER_USERNAME`, `SERVER_PASSWORD`, `SERVER_PORT`.
