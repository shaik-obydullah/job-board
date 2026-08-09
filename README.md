# Job Board

A full-stack, multi-role job board platform built on the **Laravel TALL stack** (Tailwind CSS, Alpine.js, Laravel, Livewire). Job seekers can build rich public profiles and apply in one click; employers get branded company pages, job posting, and an applicant tracking pipeline; admins get a custom Livewire admin panel for moderating everything.

![PHP](https://img.shields.io/badge/PHP-8.4-%23777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13.x-%23FF2D20?logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-4.x-%234E56A6?logo=livewire&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-%2306B6D4?logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-%238BC0D0?logo=alpine.js&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-8.x-%23646CFF?logo=vite&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8-%234479A1?logo=mysql&logoColor=white)
![Redis](https://img.shields.io/badge/Redis-7-%23DC382D?logo=redis&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-%232496ED?logo=docker&logoColor=white)
![Tests](https://img.shields.io/badge/tests-79%20passing-%232ea44f)
![Code Style](https://img.shields.io/badge/code%20style-Laravel%20Pint-%23ffcb2d)
![License](https://img.shields.io/badge/license-MIT-blue)

## Tech Stack

| Technology     | Version                                                |
| -------------- | ------------------------------------------------------ |
| PHP            | [8.4](https://www.php.net/) (runtime, requires `^8.3`) |
| Laravel        | [13.x](https://laravel.com/)                           |
| Livewire       | [4.x](https://livewire.laravel.com/)                   |
| Tailwind CSS   | [4.x](https://tailwindcss.com/)                        |
| Alpine.js      | [3.x](https://alpinejs.dev/)                           |
| Vite           | [8.x](https://vite.dev/)                               |
| MySQL          | 8                                                      |
| Redis          | 7                                                      |
| PHPUnit / Dusk | 12 / 8                                                 |
| Docker         | Compose                                                |

## Architecture Overview

```
┌─────────────────────────────────────────────┐
│              Docker Containers              │
│  ┌───────────┐  ┌─────────┐  ┌───────────┐  │
│  │  Nginx    │  │ Laravel │  │  MySQL    │  │
│  │  :80     │→  │ PHP-FPM │→ │  :3306    │  │
│  └───────────┘  └─────────┘  └───────────┘  │
│                     │                       │
│               ┌─────┴─────┐                 │
│               │  Redis     │                │
│               │  :6379     │                │
│               └───────────┘                 │
└─────────────────────────────────────────────┘

TALL Stack: Tailwind CSS + Alpine.js + Laravel + Livewire
```

## Features

### Docker & TALL stack setup

- Multi-container Docker setup — **Nginx** reverse proxy, **PHP-FPM**, **MySQL** (persistent volume), **Redis** for sessions/queues/cache.
- TALL stack wired up: **Tailwind CSS** via Vite, **Alpine.js**, **Livewire**, plus a Makefile for common commands.

### Database design

- Full job-board schema: users, companies, jobs, job categories, skills (pivot), saved jobs, applications, company followers, activity logs.
- Migrations with proper foreign keys, indexes, and soft deletes; Eloquent models with relationships; factories and seeders with realistic sample data.

### Auth & roles

- Multi-role registration with role selection (job seeker / employer), custom login, email verification, password reset, and logout.
- Role-based middleware: `auth`, `role:job_seeker`, `role:employer`, `role:admin`, `guest`.
- Banned-account enforcement, rate-limited auth, reCAPTCHA on signup.

### Job seeker profile & CV builder

- 12-section profile builder: basic info, summary, education, employment, certifications, skills (tag autocomplete), languages, portfolio, memberships, awards, hobbies, references.
- Profile photo upload with resize + thumbnail (Intervention Image).
- Live completion progress bar, PDF CV download (DOMPDF), public profile at `/u/{username}`, profile visibility settings.

### Company profiles & job posting (employer)

- Company profile CRUD — logo, banner, description, website, location, industry, size, contact info.
- Job posting form with rich text, required skills tag selector, job type/experience/salary, location, deadline, and preview.
- Job lifecycle management — draft/published/closed/expired, edit, close, repost, applicant counts.
- Applicant tracking pipeline: `pending → reviewed → shortlisted → rejected → hired`, view applicant profile, download applicant CV.
- Company analytics — job views, total applications, follower count.

### Job search & discovery (job seeker)

- Homepage — hero search, featured jobs carousel, popular categories, recent jobs, stats.
- Live job search with filters: category, job type, experience level, salary slider, location, date posted; sort by relevance/date/salary; Livewire pagination.
- Job detail pages with company sidebar, "Apply Now", related jobs.
- Company directory with search, follow/unfollow, and company pages.
- Save/bookmark jobs and an "Applied Jobs" page.

### Applications & notifications

- One-click apply with cover letter; auto-attaches the user's profile CV; confirmation notification.
- Notification system — in-app (database) plus queued email: new application to employer, status changes to job seeker, new jobs matching saved searches, new jobs from followed companies.
- Notification center with bell icon + dropdown; markdown email templates; Redis-backed queue.

### UI/UX polish

- Consistent design system (colors, typography, button/card/form/modal/badge components).
- Mobile-first responsive design with hamburger menu and slide-out filters.
- Dark mode toggle, skeleton loaders, `wire:loading` states, empty states, toasts, breadcrumbs.

### Admin panel & analytics

- Dashboard with totals/charts, user management (ban/unban, roles), company verification, job moderation, category & skill management, system settings.

### Testing, optimization & security

- Feature tests for Livewire components, unit tests for models, Dusk browser tests.
- Database indexing, eager loading, Redis caching, image optimization, queued heavy operations.
- CSRF, XSS/SQL-injection protection, bcrypt hashing, rate limiting, reCAPTCHA.
- SEO — per-page meta/Open Graph tags, sitemap, clean slug URLs.

## Screenshots

### Public pages

| Home                                                        | Job listings                            | Job details                                 | Companies                                               |
| ----------------------------------------------------------- | --------------------------------------- | ------------------------------------------- | ------------------------------------------------------- |
| ![Home — featured jobs](screenshots/Home-Featured-Jobs.png) | ![Job board](screenshots/Job-Board.png) | ![Job details](screenshots/Job-Details.png) | ![Explore companies](screenshots/Explore-Companies.png) |

- ![Home — top companies](screenshots/Home-Top-Companies.png) — home page top companies section
- ![Home — website overview](screenshots/Job-Board-Website.png) — home page overview
- ![Job board listing](screenshots/Job-Board-2.png) — jobs listing
- ![Job details](screenshots/Job-Details-2.png) — job details page

### Dashboards

| Job seeker                                                    |
| ------------------------------------------------------------- |
| ![Job seeker dashboard](screenshots/Job-Seeker-Dashboard.png) |

### Admin panel

| Dashboard                                           | Users                                               | Companies                                                 | Employers                                                   |
| --------------------------------------------------- | --------------------------------------------------- | --------------------------------------------------------- | ----------------------------------------------------------- |
| ![Admin dashboard](screenshots/Admin-Dashboard.png) | ![User management](screenshots/User-Management.png) | ![Company management](screenshots/Company-Management.png) | ![Employer management](screenshots/Employer-Management.png) |

| Jobs                                              | Categories                                        | Skills                                                  |
| ------------------------------------------------- | ------------------------------------------------- | ------------------------------------------------------- |
| ![Job management](screenshots/Job-Management.png) | ![Job categories](screenshots/Job-Categories.png) | ![Skills management](screenshots/Skills-Management.png) |

## Getting Started

Development runs entirely in Docker — you never need PHP, Composer, or Node on your host.

```bash
docker compose up -d
open http://localhost:8080
```

- App: `http://localhost:8080`
- MySQL: `localhost:3307`
- Redis: `localhost:6380`

### Common commands

```bash
docker compose up -d                                  # start dev stack
docker exec job-board-php sh -lc 'cd /var/www/html && php artisan test'      # run tests
docker exec job-board-php sh -lc 'cd /var/www/html && php artisan migrate --force'  # migrate
docker exec job-board-php sh -lc 'cd /var/www/html && php artisan db:seed'   # seed demo data
docker exec job-board-php sh -lc 'cd /var/www/html && php vendor/bin/pint'   # fix code style
```

Or use the included Makefile shortcuts: `make test`, `make artisan COMMAND='migrate'`, `make npm-build`.

## Project Layout

```
job-board/
├── docker/                  # nginx + PHP-FPM images (dev)
├── src/                     # Laravel application (all app code)
│   ├── app/Livewire/        # components by domain (Auth, Job, Company, Profile, Admin, …)
│   ├── app/Models/          # Eloquent models
│   ├── app/Support/         # CacheKeys, JobMeta, ThrottlesLivewireActions
│   ├── app/Rules/           # Recaptcha rule
│   ├── routes/web.php       # all routes in one file
│   ├── resources/views/     # layouts, Blade components, Livewire views
│   └── tests/               # Unit, Feature, and Dusk suites
├── PLAN.md                  # phase-by-phase plan
├── TRACKER.md               # live progress tracker
└── AGENTS.md                # developer/agent guide
```

## Testing

```bash
docker exec job-board-php sh -lc 'cd /var/www/html && php artisan test'
```

The suite runs against SQLite (`:memory:`) locally. See `AGENTS.md` for details.

## License

[MIT](https://spdx.org/licenses/MIT.html) (see `src/composer.json`)
