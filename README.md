# Hilly Chilly CMS — Backend & Admin Panel

Nepal's premier gamified adventure platform. A unified Laravel 11 application serving:

- **Website** — Premium marketing homepage at `/`
- **Admin Panel** — Filament v3 at `/manage` (admins only — no public link)
- **REST API** — Full JSON API at `/api/v1/` for the Flutter mobile app

## Tech Stack
- **Laravel 11** + PHP 8.3
- **Filament v3** admin panel (Emerald theme, dark mode)
- **Laravel Sanctum** — API token authentication
- **Kimi by Moonshot AI** — Chilly AI chatbot (`moonshot-v1-8k`)
- **MySQL 8** (production) / SQLite (local dev)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed        # Creates admin user + sample data
php artisan storage:link
php artisan serve
```

### Environment Variables
```
DB_CONNECTION=mysql
DB_DATABASE=hillychilly
DB_USERNAME=root
DB_PASSWORD=

MOONSHOT_API_KEY=sk-...           # Kimi API key
MOONSHOT_MODEL=moonshot-v1-8k
```

## Admin Panel

Access at: `https://yourdomain.com/manage`

Default admin credentials (change after first login):
- Email: `admin@hillychilly.com`
- Password: `password`

**Resources managed:**
| Resource | Group | Description |
|---|---|---|
| Quest Packages | Catalogue | Adventure packages shown in app |
| Users | Commerce | App user accounts |
| Bookings | Commerce | All booking records |
| Badges | Rewards | Achievement badges |
| Blogs | Content | Travel blog posts |
| Testimonials | Content | Website reviews |
| FAQs | Content | App & website FAQs |
| Settings | System | App-wide config (store URLs, contact, social) |

## API Endpoints

All endpoints prefixed with `/api/v1/`

### Public
| Method | Route | Description |
|---|---|---|
| POST | `/auth/register` | Register new user |
| POST | `/auth/login` | Login, returns token |
| GET | `/packages` | List active packages |
| GET | `/packages/{id}` | Package detail |

### Protected (Bearer token required)
| Method | Route | Description |
|---|---|---|
| GET | `/auth/me` | Current user |
| POST | `/auth/logout` | Logout |
| GET | `/bookings` | My bookings |
| POST | `/bookings` | Create booking |
| GET | `/bookings/{id}` | Booking detail |
| PATCH | `/bookings/{id}/cancel` | Cancel booking |
| GET | `/rewards` | Points + badges |
| GET | `/rewards/transactions` | Points history |
| GET | `/profile` | Profile |
| PUT | `/profile` | Update profile |
| POST | `/ai/chat` | Chilly AI chat |

## Deployment

```bash
# On server after git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
php artisan storage:link
```

## License
Proprietary — Baakhapaa Org
