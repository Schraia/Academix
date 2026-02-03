# Academix – Local Development Setup Guide

This guide explains **exactly what to do after cloning the repository** so everyone on the team can run the project locally without issues.

> **Tech stack**: Laravel (PHP), Blade, Tailwind CSS, MySQL, Vite

> **Dev server**: Laragon (Windows)

---

## 1. Prerequisites (Install Once)

Make sure these are installed **before cloning**:

- **Laragon (Full version)** [Download Laragon](https://laragon.org/download)
  - PHP
  - MySQL
  - Composer
  - Git
  - These 4 are included when you download the full version
- **Node.js (LTS)** [Download Node.js](https://nodejs.org/en/download)
- **VS Code** (recommended) or preferred IDE

After installing Laragon:
- Open Laragon
- Click **Start All**

---

## 2. Clone the Repository

Clone the project into Laragon’s `www` directory:

```bash
cd C:\laragon\www
git clone https://github.com/Schraia/Academix academix
```

> The folder name **must be `academix`** so Laragon maps it to:
>
> `http://academix.test`

---

## 3. Open the Project Folder

```bash
cd academix
```

Or open the folder directly in VS Code.

---

## 4. Install PHP Dependencies (IMPORTANT)

Laravel does **not** include the `vendor/` folder in Git.

Run:

```bash
composer install
```

This will:
- Download Laravel dependencies
- Create the `vendor/` folder

**Note:** If running:
```bash
composer install
```
and this warning shows:
```bash
Failed to download symfony/http-foundation from dist: The zip extension and unzip/7z commands are both missing, skipping. 
The php.ini used by your command-line PHP is: D:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.ini Now trying to download from source
```
**Step 1**: Do this:
**Enable PHP Zip Extension in Laragon**

Open Laragon → Menu → PHP → php.ini
(This opens the CLI PHP `php.ini` — check your error: `D:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.ini`)

Search for:
```bash
;extension=zip
```
Remove the semicolon `;` to enable:
```bash
extension=zip
```
Save after configuring.

**Step 2**: Restart Laragon

Stop all services → Start all again

Open a new Laragon Terminal to refresh environment variables.

**Step 3**: (If Step 2 is not enough) — Install Unzip / 7-Zip

If enabling zip doesn’t work, Composer also checks for external extractors:

Install 7-Zip → Add C:\Program Files\7-Zip to your Windows PATH

OR install unzip for Windows (less common)

**Step 4**: Re-run Composer Install

Back in your project root:

```bash
composer install
```

---

## 5. Create Environment File

Copy the example environment file:

```bash
cp .env.example .env
```

Then generate the application key:

```bash
php artisan key:generate
```

---

## 6. Configure Database

### 6.1 Create Database

Using **HeidiSQL / DBeaver / phpMyAdmin**, create a database:

```
academix_db
```

---

### 6.2 Update `.env`

Open `.env` and update:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=academix_db
DB_USERNAME=root
DB_PASSWORD=
```

(Default Laragon MySQL credentials)

---

## 7. Run Database Migrations

```bash
php artisan migrate
```

If this succeeds → database connection is working.

---

## 8. Install Frontend Dependencies (Tailwind + Vite)

```bash
npm install
```

Then start Vite:

```bash
npm run dev
```

Keep this terminal running while developing.

---

## 9. Access the Application

Restart Laragon, then open:

```
http://academix.test
```

You should see the homepage.

---


## 10. Common Issues & Fixes

### ❌ `vendor/autoload.php` missing
Run:
```bash
composer install
```

---

### ❌ CSS not loading
Make sure Vite is running:
```bash
npm run dev
```

---

### ❌ `composer` not recognized
- Use **Laragon Terminal**
- Ensure Composer is added to PATH

---

### ❌ Database connection error
- Check `.env` values
- Make sure MySQL is running in Laragon

## 11. Summary (Quick Start)

```bash
git clone <repo>
cd academix
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev
```

---
