# JobCenter API

Laravel 13 REST API for a job board. The backend is the source of truth for authentication, authorization, validation, job data, comments, likes, and private CV files.

**Frontend:** https://github.com/artushhhd/frontend-JobCenter

## What this project demonstrates

- REST API design with Laravel 13
- Sanctum bearer-token authentication
- Server-side authorization with Policies
- Form Request validation
- Separate job-seeker / job-poster status and staff roles
- Search, filtering, sorting, and pagination
- Ownership rules for jobs, comments, and CV files
- Private document storage
- Feature testing and API error handling

## Tech Stack

- PHP 8.3
- Laravel 13
- Laravel Sanctum
- Eloquent ORM
- MySQL / SQLite
- PHPUnit
- Laravel Pint

## Domain Model

Users have two independent access attributes:

- status: job_seeker or job_poster
- role: user, moderator, admin, or super_admin

The API does not trust frontend visibility for security. Resource access is checked server-side with authentication middleware and Policies.

## Features

### Authentication & Access

- Registration, login, logout
- Sanctum bearer tokens
- Configurable token expiration
- Authenticated profile
- Token-specific logout
- Rate limiting on write-heavy endpoints

### Jobs

- Create, read, update, delete
- Owner/staff authorization
- Search by title, company, and skills
- Location and remote-job filtering
- Sort by recent or salary
- Featured-first ordering
- Pagination

### Comments & Likes

- Like / unlike jobs
- List liked jobs
- Paginated comments
- Comment ownership checks
- Staff authorization

### CV Storage

- One active CV per user
- PDF, DOC, and DOCX
- Maximum size: 5 MB
- Private storage outside the public web root
- Ownership checks before access
- Replace and delete operations

## API Surface

### Authentication

~~~http
POST /api/register
POST /api/login
POST /api/logout
GET  /api/profile
~~~

### Jobs

~~~http
GET    /api/jobs
POST   /api/jobs
GET    /api/jobs/{job}
PUT    /api/jobs/{job}
PATCH  /api/jobs/{job}
DELETE /api/jobs/{job}

POST   /api/jobs/{job}/like
DELETE /api/jobs/{job}/like
GET    /api/likes
~~~

### Comments

~~~http
GET    /api/jobs/{job}/comments
POST   /api/jobs/{job}/comments
PUT    /api/comments/{comment}
PATCH  /api/comments/{comment}
DELETE /api/comments/{comment}
~~~

### CV

~~~http
POST   /api/profile/cv
GET    /api/profile/cv
DELETE /api/profile/cv
~~~

## Error Handling

| Status | Meaning |
|---|---|
| 401 | Missing or invalid authentication |
| 403 | Authenticated but not authorized |
| 422 | Validation failed |

Validation failures are returned in a structured errors object for frontend consumption.

## Architecture

~~~text
HTTP Request
    |
    v
Routes / Middleware
    |
    +-- Sanctum Authentication
    +-- Rate Limiting
    |
    v
Controller
    |
    +-- Form Request -> Validation
    +-- Policy -> Authorization
    +-- API Resource -> Response
    |
    v
Eloquent Models
    |
    v
MySQL / SQLite
~~~

Repository structure follows Laravel conventions:

~~~text
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
~~~

## Screenshots

The UI is implemented in the separate Next.js frontend.

<img width="1919" height="1079" alt="Register" src="https://github.com/user-attachments/assets/c96eb5cf-242c-4f0f-abdb-24c06e43531d" />

<img width="1919" height="1079" alt="Login" src="https://github.com/user-attachments/assets/c5a0cead-70af-4247-955a-0a8c6e49f665" />

<img width="1900" height="909" alt="Profile" src="https://github.com/user-attachments/assets/0d9f5dc8-e200-4c77-b178-83beb9976c79" />

<img width="1901" height="1079" alt="Job details" src="https://github.com/user-attachments/assets/cb65d2e9-4fe7-4950-a14c-c6f56ee5e9e0" />

## Local Development

### Requirements

- PHP 8.3+
- Composer
- MySQL or SQLite

### Installation

~~~bash
composer install
copy .env.example .env
php artisan key:generate
~~~

Configure the database in .env.

For SQLite:

~~~text
DB_CONNECTION=sqlite
~~~

Create an empty database/database.sqlite file, then run:

~~~bash
php artisan migrate --seed
php artisan serve
~~~

The API runs at http://127.0.0.1:8000.

For the Next.js client, configure CORS with:

~~~env
FRONTEND_URL=http://localhost:3000,http://127.0.0.1:3000
~~~

### Quality Checks

~~~bash
php artisan test
vendor/bin/pint
~~~

## Related Repository

**Next.js frontend:** https://github.com/artushhhd/frontend-JobCenter
