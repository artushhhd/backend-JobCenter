# JobCenter API

Laravel 13 REST API for a job board, focused on authentication, authorization, validation, private CV storage, search/filtering, pagination, and testable API workflows.

**Frontend:** https://github.com/artushhhd/frontend-JobCenter

## Highlights

- REST API with Laravel 13
- Sanctum bearer-token authentication
- Policy-based server-side authorization
- Form Request validation
- Job seeker / job poster domain model
- Staff roles
- Search, filtering, sorting, pagination
- Likes and paginated comments
- Private CV storage with ownership checks
- Feature tests
- Structured API errors

## Stack

PHP 8.3 · Laravel 13 · Sanctum · Eloquent · MySQL / SQLite · PHPUnit · Laravel Pint

## Domain Model

- **Status:** `job_seeker` / `job_poster`
- **Role:** `user` / `moderator` / `admin` / `super_admin`

Authorization is enforced server-side. Frontend visibility is never treated as a security boundary.

## Architecture

```text
HTTP Request
 -> Middleware / Sanctum
 -> Controller
 -> Form Request
 -> Policy
 -> API Resource
 -> Eloquent
 -> Database
```

## API

```http
POST /api/register
POST /api/login
POST /api/logout
GET  /api/profile
GET /api/jobs
POST /api/jobs
GET /api/jobs/{job}
PUT /api/jobs/{job}
DELETE /api/jobs/{job}
POST /api/jobs/{job}/like
DELETE /api/jobs/{job}/like
GET /api/likes
GET /api/jobs/{job}/comments
POST /api/jobs/{job}/comments
POST /api/profile/cv
GET /api/profile/cv
DELETE /api/profile/cv
```

## Quality Checks

```bash
php artisan test
vendor/bin/pint
```

## Run Locally

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
