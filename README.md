# JobCenter API

Laravel 13 REST API for the JobCenter job board. The Next.js client lives in a separate
repository: https://github.com/artushhhd/frontend-JobCenter

Every user carries two flags:

- `status` — `job_seeker` or `job_poster`. Only job posters can publish listings.
- `role` — `user`, `moderator`, `admin` or `super_admin`. Anything above `user` opens the
  `/api/settings` routes.

## Requirements

PHP 8.3, Composer, MySQL or SQLite.

## Setup

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Pick the database in `.env`. For SQLite set `DB_CONNECTION=sqlite` and create an empty
`database/database.sqlite`. For MySQL create the schema and fill in `DB_DATABASE`,
`DB_USERNAME`, `DB_PASSWORD`.

```bash
php artisan migrate --seed
php artisan serve
```

The API is then on `http://127.0.0.1:8000` and every route is prefixed with `/api`.

`UserSeeder` creates demo accounts that all share the password `password123`
(alex.morgan@example.com is a job seeker, olivia.park@example.com posts jobs), and
`JobSeeder` fills the feed with sample listings.

## CORS

`FRONTEND_URL` lists the allowed origins, comma separated:

```
FRONTEND_URL=http://localhost:3000,http://127.0.0.1:3000
```

## Tokens

`POST /api/register` and `POST /api/login` return a plaintext Sanctum token. Send it back as
`Authorization: Bearer <token>` — everything below `auth:sanctum` needs it. Lifetime comes
from `SANCTUM_TOKEN_EXPIRATION_MINUTES` (a week by default) and `sanctum:prune-expired`
runs once a day. `POST /api/logout` deletes only the token that made the request, so the
other devices of the same user stay signed in.

## Routes

Auth

- `POST /api/register` — name, email, password, status
- `POST /api/login`
- `POST /api/logout`
- `GET /api/profile` — the current user plus his resume metadata

Jobs

- `GET /api/jobs` — published listings, 10 per page
- `POST /api/jobs` — job posters only
- `GET|PUT|PATCH|DELETE /api/jobs/{job}` — owner or staff, decided in `JobPolicy`
- `POST|DELETE /api/jobs/{job}/like`, `GET /api/likes`

Comments

- `GET|POST /api/jobs/{job}/comments` — 20 per page
- `PUT|PATCH|DELETE /api/comments/{comment}` — author or staff

Resume

- `POST /api/profile/cv` — one PDF/DOC/DOCX up to 5 MB, replaces the previous file
- `GET /api/profile/cv` — download
- `DELETE /api/profile/cv`

The file goes to the private local disk (`storage/app/private`), never under `/public`, and
the download route checks ownership before streaming it.

Staff

- `GET /api/settings/jobs` — every listing, drafts included
- `GET /api/settings/users` — 15 per page, `q` and `role` filters
- `DELETE /api/settings/users/{user}`

## Listing filters

`GET /api/jobs` takes `q` (matches title, company and the skills json), `location`
(remote jobs always stay in the result), `sort` (`recent` or `salary`, otherwise featured
first and then newest) and `page`.

## Errors

Form Requests do the validation, so a bad payload answers 422 with an `errors` object and
the client prints those strings next to the fields. A wrong token answers 401, a denied
action 403. Writes are throttled: 5/min on login, 10/min on register, 30/min on job,
comment, like, cv and user-delete routes.

## Tests and formatting

```bash
php artisan test
vendor/bin/pint
```
