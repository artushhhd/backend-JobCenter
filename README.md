# JobCenter Backend

Laravel REST API for JobCenter, a job marketplace with authentication, recruiter job management, likes, comments, staff management, and private CV storage.

## Stack

- PHP 8.3+
- Laravel 13
- Laravel Sanctum
- MySQL or SQLite
- Eloquent ORM
- PHPUnit

## Features

- Registration and login with Sanctum personal access tokens
- Job seeker and recruiter account types
- Role-based staff access: moderator, admin, super admin
- Job CRUD with validation and policies
- Draft and published job listings
- Search, location filtering, and sorting
- Likes with persistent counters
- Paginated comments with ownership authorization
- Private CV upload, download, replacement, and deletion
- Staff job and user management
- API throttling for sensitive write operations

## Project structure

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
├── Models/
└── Policies/

database/
├── migrations/
└── seeders/

routes/
└── api.php

tests/
├── Feature/
└── Unit/
```

## Requirements

- PHP 8.3+
- Composer
- Node.js/npm if you need Laravel's frontend tooling
- SQLite or MySQL

## Installation

Clone the repository and install PHP dependencies:

```bash
git clone https://github.com/artushhhd/backend-JobCenter.git
cd backend-JobCenter
composer install
```

Create the environment file and application key:

```bash
copy .env.example .env
php artisan key:generate
```

Configure the database in `.env`.

For SQLite:

```text
DB_CONNECTION=sqlite
```

Then run migrations:

```bash
php artisan migrate
```

Start the API:

```bash
php artisan serve
```

The default local API URL is:

```text
http://127.0.0.1:8000
```

## CORS

Set `FRONTEND_URL` in `.env` to the frontend origin. Multiple origins can be separated by commas.

Example:

```text
FRONTEND_URL=http://localhost:3000,http://127.0.0.1:3000
```

## Authentication

Authentication uses Laravel Sanctum bearer tokens.

After registration or login, send:

```http
Authorization: Bearer <token>
Accept: application/json
```

## Main API endpoints

| Method | Endpoint | Purpose |
|---|---|---|
| POST | `/api/register` | Register |
| POST | `/api/login` | Login |
| GET | `/api/profile` | Current user |
| POST | `/api/logout` | Revoke current token |
| GET | `/api/jobs` | Published jobs |
| POST | `/api/jobs` | Create a job |
| GET | `/api/jobs/{job}` | Job details |
| PUT/PATCH | `/api/jobs/{job}` | Update a job |
| DELETE | `/api/jobs/{job}` | Delete a job |
| POST | `/api/jobs/{job}/like` | Like a job |
| DELETE | `/api/jobs/{job}/like` | Remove a like |
| GET | `/api/likes` | Current user's liked jobs |
| GET | `/api/jobs/{job}/comments` | Job comments |
| POST | `/api/jobs/{job}/comments` | Add a comment |
| PUT/PATCH | `/api/comments/{comment}` | Update own comment |
| DELETE | `/api/comments/{comment}` | Delete own comment |
| GET | `/api/settings/jobs` | Staff job management |
| GET | `/api/settings/users` | Staff user management |
| DELETE | `/api/settings/users/{user}` | Delete an allowed user |
| POST | `/api/profile/cv` | Upload/replace private CV |
| GET | `/api/profile/cv` | Download own CV |
| DELETE | `/api/profile/cv` | Delete own CV |

## Job filtering

`GET /api/jobs` supports:

- `q` — title, company, or skills search
- `location` — location search; remote jobs remain included
- `sort=recent`
- `sort=salary`
- default sorting by featured status and publication date
- `page` — Laravel pagination

The API returns 10 jobs per page.

## Authorization

Authorization is enforced server-side with Laravel policies and Form Requests.

Examples:

- Only recruiters can create jobs.
- Recruiters can update their own jobs.
- Admin-level staff can edit jobs according to policy.
- Users can delete their own comments.
- Staff can manage users according to role hierarchy.
- CV files are stored on the private local disk and are never exposed through a public storage URL.

## Validation and security

- Request validation is handled by Form Requests.
- Passwords use Laravel's hashed cast.
- API authentication uses Sanctum.
- Sensitive write endpoints use rate limiting.
- CV downloads require authentication and ownership.
- User input is not trusted for authorization decisions.

## Testing

Run the test suite with:

```bash
php artisan test
```

Run code formatting with:

```bash
vendor/bin/pint
```

## Related repository

Frontend: https://github.com/artushhhd/frontend-JobCenter

## License

This project is licensed under the MIT License.
