# JobCenter API

Laravel 13 REST API for a job board with authentication, role-based authorization, job listings, comments, likes, filtering, pagination, and private CV storage.

**Frontend:** https://github.com/artushhhd/frontend-JobCenter

## Overview

JobCenter is a full-stack job board built as two independent applications. This repository contains the backend API responsible for business rules, authentication, authorization, validation, persistence, and file handling.

The API uses Laravel Sanctum for token authentication and Laravel Policies/Form Requests to keep authorization and validation close to the HTTP boundary.

## Tech Stack

- PHP 8.3
- Laravel 13
- Laravel Sanctum
- Eloquent ORM
- MySQL / SQLite
- PHPUnit
- Laravel Pint
- REST API

## Core Features

### Authentication

- Registration, login, logout
- Sanctum bearer-token authentication
- Configurable token expiration
- Authenticated profile endpoint
- Token-specific logout
- Rate limiting on write-heavy endpoints

### Authorization

Users have two independent access attributes:

- `status`: `job_seeker` or `job_poster`
- `role`: `user`, `moderator`, `admin`, or `super_admin`

Resource authorization is enforced by the API. Job and comment ownership is checked through Laravel Policies rather than relying on frontend visibility.

### Job Listings

- Create, read, update, and delete jobs
- Owner/staff authorization
- Search by title, company, and skills
- Location filtering with remote-job support
- Sorting by recent or salary
- Featured-first ordering
- Paginated results

### Social Features

- Like and unlike jobs
- View liked jobs
- Paginated comments
- Comment ownership and staff authorization

### CV Storage

- Upload one CV per user
- PDF, DOC, and DOCX support
- Maximum file size of 5 MB
- Private storage outside the public web root
- Ownership checks before download
- Replace or delete the current CV

### screenshot
<img width="1919" height="1079" alt="register" src="https://github.com/user-attachments/assets/c96eb5cf-242c-4f0f-abdb-24c06e43531d" />

<img width="1919" height="1079" alt="login" src="https://github.com/user-attachments/assets/c5a0cead-70af-4247-955a-0a8c6e49f665" />

<img width="1900" height="909" alt="profile" src="https://github.com/user-attachments/assets/0d9f5dc8-e200-4c77-b178-83beb9976c79" />

<img width="1901" height="1079" alt="job" src="https://github.com/user-attachments/assets/cb65d2e9-4fe7-4950-a14c-c6f56ee5e9e0" />


### Staff Endpoints

Staff users can access:

- All job listings, including drafts
- Paginated user management
- User filtering by search and role
- User deletion

## API Surface

### Authentication

```http
POST /api/register
POST /api/login
POST /api/logout
GET  /api/profile
```

### Jobs

```http
GET    /api/jobs
POST   /api/jobs
GET    /api/jobs/{job}
PUT    /api/jobs/{job}
PATCH  /api/jobs/{job}
DELETE /api/jobs/{job}

POST   /api/jobs/{job}/like
DELETE /api/jobs/{job}/like
GET    /api/likes
```

### Comments

```http
GET    /api/jobs/{job}/comments
POST   /api/jobs/{job}/comments
PUT    /api/comments/{comment}
PATCH  /api/comments/{comment}
DELETE /api/comments/{comment}
```

### CV

```http
POST   /api/profile/cv
GET    /api/profile/cv
DELETE /api/profile/cv
```

## Validation & Error Handling

The API uses Laravel Form Requests for input validation.

- `401 Unauthorized` — authentication is missing or invalid
- `403 Forbidden` — authenticated user is not authorized
- `422 Unprocessable Entity` — validation failed

Validation errors are returned in a structured `errors` object consumed by the frontend.

Write operations are protected by route-specific rate limits.

## Architecture

The application separates HTTP concerns, validation, authorization, persistence, and routing.

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
└── Policies/

database/
├── factories/
├── migrations/
└── seeders/

routes/
└── api.php

tests/
└── Feature/
```

## Local Development

### Requirements

- PHP 8.3+
- Composer
- MySQL or SQLite

### Installation

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Configure the database in `.env`.

For SQLite:

```text
DB_CONNECTION=sqlite
```

Create an empty `database/database.sqlite` file before running migrations.

For MySQL, configure `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.

Run migrations and seed development data:

```bash
php artisan migrate --seed
php artisan serve
```

The API runs at:

```text
http://127.0.0.1:8000
```

All API routes are prefixed with `/api`.

### CORS

Configure allowed frontend origins with:

```env
FRONTEND_URL=http://localhost:3000,http://127.0.0.1:3000
```

### Testing

```bash
php artisan test
vendor/bin/pint
```

## Project Structure

The project follows Laravel conventions with dedicated controllers, Form Requests, Policies, Eloquent models, migrations, seeders, and feature tests.

The frontend is maintained separately so the API and client can be developed and deployed independently.
