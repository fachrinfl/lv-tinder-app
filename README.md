# Tinder App API (MVP)

A Laravel-based API for a Tinder-like dating application MVP.

## Features

-   **Recommendations API**: Get personalized recommendations for people to swipe
-   **Like/Dislike System**: Record user interactions with like/dislike functionality
-   **Liked People List**: View all people you've liked
-   **Popularity Notifications**: Automatic email notifications when someone reaches 50+ likes
-   **Swagger Documentation**: Interactive API documentation with "Try it out" functionality
-   **Database Seeding**: Pre-populated with 80 sample profiles

## API Endpoints

### Base URL: `/api/v1`

| Method | Endpoint               | Description            | Auth Required |
| ------ | ---------------------- | ---------------------- | ------------- |
| GET    | `/health`              | Health check           | No            |
| GET    | `/recommendations`     | Get recommended people | Yes           |
| GET    | `/people/{id}`         | Get person details     | Yes           |
| POST   | `/people/{id}/like`    | Like a person          | Yes           |
| POST   | `/people/{id}/dislike` | Dislike a person       | Yes           |
| GET    | `/likes`               | Get liked people list  | Yes           |

### Authentication

For testing purposes, use the `X-User-Id` header with a valid UUID:

```
X-User-Id: 550e8400-e29b-41d4-a716-446655440000
```

## Setup Instructions

### Prerequisites

-   PHP 8.2 or higher
-   Composer
-   Node.js & npm (for frontend assets)
-   PostgreSQL (for production and development)

### 1. Clone and Install Dependencies

```bash
git clone <repository-url>
cd lv-tinder-app
composer install
npm install
```

### 2. Environment Configuration

Copy the environment file and configure it:

```bash
cp .env.example .env
php artisan key:generate
```

For **PostgreSQL** (Production):

```env
APP_NAME="Tinder App API"
APP_ENV="local"
APP_KEY="your-app-key-here"
APP_DEBUG="false"
APP_URL="https://your-app.up.railway.app"
DB_CONNECTION="pgsql"
DB_HOST="your-postgres-host"
DB_PORT="5432"
DB_DATABASE="your-database"
DB_USERNAME="your-username"
DB_PASSWORD="your-password"
MAIL_MAILER="smtp"
MAIL_HOST="smtp-relay.brevo.com"
MAIL_PORT="587"
MAIL_USERNAME="your-brevo-username"
MAIL_PASSWORD="your-brevo-password"
MAIL_ENCRYPTION="tls"
MAIL_FROM_ADDRESS="your-email@example.com"
MAIL_FROM_NAME="${APP_NAME}"
ADMIN_EMAIL="admin@example.com"
BREVO_API_KEY="your-brevo-api-key-here"
```

### 3. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed with sample data (80 people with pictures)
php artisan db:seed
```

### 4. Generate Swagger Documentation

```bash
php artisan l5-swagger:generate
```

### 5. Start Development Server

```bash
# Option A: Use the custom development script (recommended)
composer run dev

# Option B: Manual setup
php artisan serve          # Terminal 1
npm run dev               # Terminal 2
php artisan queue:work    # Terminal 3 (optional)
```

## 📖 API Documentation

Once the server is running, access the interactive Swagger documentation at:

-   **Swagger UI**: http://localhost:8000/api/documentation
-   **JSON Spec**: http://localhost:8000/docs?api-docs.json

## 📧 Email Notifications & Cronjobs

### Popularity Notifications

The app automatically sends email notifications to admin when someone gets more than 50 likes using **Brevo** (formerly Sendinblue) email service.

**Configuration:**

```bash
# Production environment variables
APP_NAME="Tinder App API"
APP_ENV="local"
APP_DEBUG="false"
APP_URL="https://your-app.up.railway.app"

# Database configuration
DB_CONNECTION="pgsql"
DB_HOST="your-postgres-host"
DB_PORT="5432"
DB_DATABASE="your-database"
DB_USERNAME="your-username"
DB_PASSWORD="your-password"

# Email configuration with Brevo
MAIL_MAILER="smtp"
MAIL_HOST="smtp-relay.brevo.com"
MAIL_PORT="587"
MAIL_USERNAME="your-brevo-username"
MAIL_PASSWORD="your-brevo-password"
MAIL_ENCRYPTION="tls"
MAIL_FROM_ADDRESS="your-email@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Admin email for notifications
ADMIN_EMAIL="admin@example.com"
BREVO_API_KEY="your-brevo-api-key-here"
```

**Manual Testing:**

```bash
# Test popularity check command
php artisan popularity:check

# Create test likes for a person
php artisan tinker
>>> $person = App\Models\Person::first();
>>> for ($i = 0; $i < 52; $i++) {
...     App\Models\Interaction::create([
...         'user_id' => 'test-user-' . $i,
...         'person_id' => $person->id,
...         'action' => 'like'
...     ]);
... }
```

**Production Cronjob Setup:**

```bash
# Add to crontab (runs every 5 minutes)
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

The command is already scheduled in `routes/console.php` to run every 5 minutes.

**Brevo Integration:**

-   Uses Brevo SMTP service for reliable email delivery
-   Configured with production-ready SMTP settings
-   Admin notifications sent to: `admin@example.com`
-   Email format includes emojis and structured content
-   Production URL: `https://your-app.up.railway.app`

**Troubleshooting Email Issues:**
If emails are not received, check:

1. **Brevo Account**: Ensure account is verified and not in sandbox mode
2. **Gmail Settings**: Check spam folder and Gmail filters
3. **SMTP Credentials**: Verify username/password are correct
4. **Domain Verification**: Ensure sender domain is verified in Brevo
5. **Brevo Limits**: Check if Brevo has rate limiting enabled

**Alternative Email Testing:**

```bash
# Test with different email address
ADMIN_EMAIL=test@example.com php artisan popularity:check

# Check email logs
tail -f storage/logs/laravel.log | grep -i mail
```

## 🧪 Testing the API

### Health Check

```bash
# Local development
curl -X GET "http://localhost:8000/api/v1/health"

# Production
curl -X GET "https://your-app.up.railway.app/api/v1/health"
```

### Get Recommendations

```bash
# Local development
curl -X GET "http://localhost:8000/api/v1/recommendations?page=1&per_page=5" \
  -H "X-User-Id: 550e8400-e29b-41d4-a716-446655440000"

# Production
curl -X GET "https://your-app.up.railway.app/api/v1/recommendations?page=1&per_page=5" \
  -H "X-User-Id: 550e8400-e29b-41d4-a716-446655440000"
```

### Like a Person

```bash
# Local development
curl -X POST "http://localhost:8000/api/v1/people/1/like" \
  -H "X-User-Id: 550e8400-e29b-41d4-a716-446655440000"

# Production
curl -X POST "https://your-app.up.railway.app/api/v1/people/1/like" \
  -H "X-User-Id: 550e8400-e29b-41d4-a716-446655440000"
```

### Get Liked People

```bash
# Local development
curl -X GET "http://localhost:8000/api/v1/likes" \
  -H "X-User-Id: 550e8400-e29b-41d4-a716-446655440000"

# Production
curl -X GET "https://your-app.up.railway.app/api/v1/likes" \
  -H "X-User-Id: 550e8400-e29b-41d4-a716-446655440000"
```

## Database Schema

### People Table

-   `id` (integer, primary): Auto-increment ID
-   `name` (string): Person's name
-   `age` (integer): Person's age
-   `location` (string): City/location
-   `bio` (text): Person's bio
-   `total_likes` (integer): Total like count
-   `popularity_notified_at` (timestamp): When popularity notification was sent
-   `last_notified_like_count` (integer): Last like count when notified

### Pictures Table

-   `id` (integer, primary)
-   `person_id` (integer, foreign key): References people.id
-   `url` (string): Image URL
-   `is_primary` (boolean): Primary photo flag
-   `order` (integer): Display order

### Interactions Table

-   `id` (integer, primary)
-   `user_id` (string): X-User-Id header value
-   `person_id` (integer, foreign key): References people.id
-   `action` (enum): 'like' or 'dislike'
-   `created_at` (timestamp)
-   `updated_at` (timestamp)
-   Unique constraint on (user_id, person_id)

## Cron Jobs

The popularity notification system runs automatically:

```bash
# Manual execution
php artisan popularity:check

# Scheduled (every 5 minutes)
# Configured in routes/console.php
```

## Deployment (Railway)

### Environment Variables

Railway akan otomatis menyediakan environment variables PostgreSQL. Gunakan file `railway.env.example` sebagai referensi:

```env
APP_NAME="Tinder App API"
APP_ENV="local"
APP_KEY="your-app-key-here"
APP_DEBUG="false"
APP_URL="https://your-app.up.railway.app"

# PostgreSQL Configuration for Railway
DB_CONNECTION="pgsql"
DB_HOST="your-postgres-host"
DB_PORT="5432"
DB_DATABASE="your-database"
DB_USERNAME="your-username"
DB_PASSWORD="your-password"

# Email Configuration
MAIL_MAILER="smtp"
MAIL_HOST="smtp-relay.brevo.com"
MAIL_PORT="587"
MAIL_USERNAME="your-brevo-username"
MAIL_PASSWORD="your-brevo-password"
MAIL_ENCRYPTION="tls"
MAIL_FROM_ADDRESS="your-email@example.com"
MAIL_FROM_NAME="${APP_NAME}"
ADMIN_EMAIL="admin@example.com"
BREVO_API_KEY="your-brevo-api-key-here"
```

### Railway Services

1. **Web Service**: Runs Laravel application
2. **Worker Service**: Runs `php artisan schedule:work` for cron jobs

### Deployment Steps

1. **Connect to Railway**: Link repository ke Railway
2. **Add PostgreSQL Service**: Tambahkan PostgreSQL database service
3. **Set Environment Variables**: Gunakan environment variables dari PostgreSQL service
4. **Deploy**: Railway akan otomatis build dan deploy menggunakan `nixpacks.toml`

### Production URLs

-   **API Base URL**: `https://your-app.up.railway.app/api/v1`
-   **Swagger Documentation**: `https://your-app.up.railway.app/api/documentation`
-   **Health Check**: `https://your-app.up.railway.app/api/v1/health`

## API Response Format

All API responses follow this standardized format:

```json
{
    "code": "SUCCESS | ERROR_CODE",
    "message": "Human readable message",
    "data": {
        // Response data
    }
}
```

## Security Features

-   UUID validation for X-User-Id header
-   SQL injection protection via Eloquent ORM
-   CSRF protection for web routes
-   Input validation and sanitization

## Product Rules Implemented

-   **Deduplication**: Users cannot like the same person twice
-   **No Re-surfacing**: Liked/disliked people don't appear in recommendations
-   **Location Priority**: Recommendations prioritize same/nearest locations
-   **Popularity Threshold**: Email notifications when someone reaches 50+ likes
-   **Pagination**: All list endpoints support pagination (max 50 items per page)

## Troubleshooting

### Database Connection Issues

-   Ensure PostgreSQL is running
-   Check database credentials in `.env`
-   Verify PostgreSQL service is accessible

### Swagger Not Loading

```bash
php artisan l5-swagger:generate
php artisan config:clear
```

### Migration Errors

```bash
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```
