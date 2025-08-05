# Rick and Morty API Demo

A simple Dockerized Symfony + Stimulus + Tailwind CSS application for retrieval of characters.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Symfony Setup](#symfony-setup)
- [Database or API mode](#database-or-api-mode)
- [Build Frontend Assets](#build-frontend-assets)
- [Access the Application](#access-the-application)

---
## Prerequisites

- Docker and Docker Compose installed
- Access to modify the system `hosts` file
- Composer installed (inside the container)
- Yarn or npm for frontend builds (inside the container)

---
## Installation

### 1. Configure Local Domain

Edit your `hosts` file to point `rickandmorty.local` to `127.0.0.1`.

- **Windows:**  
  `C:\Windows\System32\drivers\etc\hosts`

- **macOS / Linux:**  
  `/etc/hosts`

**Add the following line:**

```bash
127.0.0.1 rickandmorty.local
```

> **Note:** Editing this file may require administrator/root privileges.

### 2. Start Docker Containers

From the project root directory, run:

```bash
docker-compose up -d
```

---
## Symfony Setup

### 3. Access App Container

```bash
docker exec -it app bash
```

### 4. Navigate to Symfony App Directory

```bash
cd /var/www/html/app
```

### 5. Install Symfony Dependencies

```bash
composer install
```

---
## Database or API mode

### 6. Setup Database or API caching
Edit environment file
```bash
vi .env.prod
```
Change DATA_PROVIDER_TYPE to either db (for Database) or api (for API)
If you are going to use Database, skip the next step and jump to 10.

### 7. Create Database

```bash
bin/console doctrine:database:create
```

### 8. Run Database Migrations

```bash
bin/console doctrine:migrations:migrate
```

### 9. Load Data Fixtures

```bash
php bin/console doctrine:fixtures:load --group=group1
php bin/console doctrine:fixtures:load --group=group2 --append
php bin/console doctrine:fixtures:load --group=group3 --append
```

### 10. Populate data to cache file
If you switch to API mode, you may need to run these commands or go straight to the website after installation complete.
```bash
bin/console app:cache:characters
bin/console app:cache:dimensions
bin/console app:cache:locations
bin/console app:cache:episodes
```

---
## Build Frontend Assets

### 11. Install JS Modules

```bash
npm install
```

### 12. Run Webpack

```bash
npm run build
```

---
## Access the Application

Once setup is complete, open your browser and navigate to:

```arduino
https://rickandmorty.local
```

---
Feel free to open an issue or submit a PR for improvements or bug fixes.


Let me know if you'd like a version that includes `make` commands, `.env` configuration, or Docker volume setup for persistence.