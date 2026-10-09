# JobCenter API

Laravel REST API for a job-board application. The project demonstrates authentication, server-side authorization, request validation, job discovery, user interactions, and private CV management.

**Frontend:** [frontend-JobCenter](https://github.com/artushhhd/frontend-JobCenter)

## Features

- Laravel Sanctum bearer-token authentication
- Policy-based authorization and resource-ownership checks
- Form Request validation and structured API responses
- Job seeker / job poster account status
- Staff roles and protected moderation workflows
- Search, filtering, sorting, and pagination
- Job likes and paginated comments
- Private CV upload and retrieval workflows
- PHPUnit feature tests

## Technology

PHP 8.3 · Laravel 13 · Sanctum · Eloquent ORM · MySQL / SQLite · PHPUnit · Laravel Pint

## Domain and Security

- **Account status:** `job_seeker`, `job_poster`
- **Staff roles:** `user`, `moderator`, `admin`, `super_admin`

Authorization is enforced by the API. Hiding a button in the frontend is not considered a security control. Private CV files should remain behind authenticated, ownership-checked endpoints.

## Request Flow

```text
HTTP request
  -> route and middleware
  -> authentication
  -> Form Request validation
  -> policy / authorization
  -> controller and API resource
  -> Eloquent
  -> database or private storage
```

## Main API Routes

| Method | Endpoint | Purpose |
|---|---|---|
| POST | `/api/register` | Register |
| POST | `/api/login` | Authenticate |
| POST | `/api/logout` | Revoke current token |
| GET | `/api/profile` | Get current profile |
| GET | `/api/jobs` | Browse jobs |
| POST | `/api/jobs` | Create a job |
| GET | `/api/jobs/{job}` | View a job |
| PUT | `/api/jobs/{job}` | Update a job |
| DELETE | `/api/jobs/{job}` | Delete a job |
| POST | `/api/jobs/{job}/like` | Like a job |
| DELETE | `/api/jobs/{job}/like` | Remove a like |
| GET | `/api/likes` | List liked jobs |
| GET | `/api/jobs/{job}/comments` | List job comments |
| POST | `/api/jobs/{job}/comments` | Add a comment |
| POST | `/api/profile/cv` | Upload CV |
| GET | `/api/profile/cv` | Retrieve current CV |
| DELETE | `/api/profile/cv` | Delete current CV |

## Requirements

- PHP version supported by the project's Laravel dependencies
- Composer
- MySQL or SQLite
- Node.js and npm are needed for the separate frontend

## Run Locally

Run these commands from the backend repository root:

```bash
composer install
```

Create your local environment file by copying `.env.example` to `.env` (use `cp` on macOS/Linux or `copy` in Windows Command Prompt), then configure your database connection.

```bash
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

The API is served at `http://127.0.0.1:8000` by default. Keep local credentials in `.env`; never commit secrets.

## Quality Checks

```bash
php artisan test
vendor/bin/pint --test
```

See the [frontend README](https://github.com/artushhhd/frontend-JobCenter) for client setup.
