Absolutely! Here's the **updated full README** with your name and email replaced as requested, and using `BASE_URL` for URLs.

---

# Laravel Classified Ads API

This is a RESTful API built with Laravel to manage a dynamic classified ads platform. It supports category-specific fields, user authentication, and allows users to create, update, view, and delete their ads.

## Features

* User registration, login, and token-based authentication with Laravel Sanctum
* Dynamic categories with flexible fields fetched from an external API
* CRUD operations for Ads
* Filter, sort, and paginate ads
* API Resource responses for consistent JSON output
* Form Request validation including dynamic fields per category

---

## Setup

1. Clone the repository

```bash
git clone <repo-url>
cd <repo-folder>
```

2. Install dependencies

```bash
composer install
```

3. Copy `.env.example` to `.env` and set your environment variables

```bash
cp .env.example .env
php artisan key:generate
```

4. Run migrations and seed the database

```bash
php artisan migrate --seed
```

5. Start the development server

```bash
php artisan serve
```

---

## API Endpoints and Example Requests

Replace `BASE_URL` with your server URL. Use the token returned from login or registration for authenticated routes.

### 1. Register a user

```bash
curl -X POST BASE_URL/api/v1/register \
  -H "Accept: application/json" \
  -d '{
        "name": "Batoul Amcha",
        "email": "batoul@example.com",
        "password": "password",
        "password_confirmation": "password"
      }'
```

### 2. Login a user

```bash
curl -X POST BASE_URL/api/v1/login \
  -H "Accept: application/json" \
  -d '{
        "email": "batoul@example.com",
        "password": "password"
      }'
```

### 3. Logout

```bash
curl -X POST BASE_URL/api/v1/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 4. Create an Ad with dynamic fields

```bash
curl -X POST BASE_URL/api/v1/ads \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
        "title": "Brand new bike",
        "description": "A mountain bike in perfect condition",
        "price": 499.99,
        "category_id": 1,
        "fields": {
            "color": "Red",
            "brand": "Trek"
        }
      }'
```

### 5. List My Ads (paginated)

```bash
curl -X GET "BASE_URL/api/v1/my-ads?per_page=5&page=1" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 6. View Specific Ad

```bash
curl -X GET BASE_URL/api/v1/ads/1 \
  -H "Accept: application/json"
```

### 7. Update an Ad

```bash
curl -X PUT BASE_URL/api/v1/ads/1 \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
        "title": "Updated Bike Title",
        "fields": {
            "color": "Blue"
        }
      }'
```

### 8. Delete an Ad

```bash
curl -X DELETE BASE_URL/api/v1/ads/1 \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Notes

* The `fields` object in the POST and PUT Ad endpoints should match the dynamic fields of the selected category.
* All responses are in JSON and follow the API Resource format.
* Use the `Authorization: Bearer YOUR_TOKEN_HERE` header for all protected routes.