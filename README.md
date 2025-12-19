# PHP_Laravel12_Create_Custom_Middleware

A complete Laravel 12–compatible project demonstrating **custom middleware** with **role-based access control**, **user activity tracking**, and **inactivity management**. This repository is designed for learning, interviews, and real-world Laravel application architecture.

---

## Project Overview

This project explains how to create and use **custom middleware** in Laravel for:

* Controlling access based on user roles
* Tracking authenticated user activity
* Preventing inactive users from accessing the system
* Managing sessions using the database
* Structuring middleware cleanly for scalability

It is suitable for **Laravel 10+** and fully compatible with **Laravel 12**.

---

## Key Features

* Custom middleware implementation
* Role-based access control (Admin, Moderator, User)
* Automatic user activity tracking
* Auto logout for inactive users
* Database-backed sessions (optional)
* Authentication with sample dashboards
* Seeder-based demo users
* Clean and beginner-friendly project structure

---

## Middleware Included

### TrackUserActivity Middleware

* Updates `last_activity_at` on every authenticated request
* Logs user activity details in `storage/logs/laravel.log`
* Helps track real-time user behavior

### CheckUserRole Middleware

* Restricts route access based on user role
* Supports single or multiple roles
* Works for both web and API requests

### PreventInactiveUsers Middleware

* Automatically logs out users after inactivity
* Inactivity duration is configurable
* Redirects users to login with a message

---

## Technology Stack

* Backend: Laravel 12 (PHP 8.1+)
* Database: MySQL
* ORM: Eloquent
* Authentication: Laravel Auth
* Session Driver: File / Database

---

## Prerequisites

Ensure the following are installed:

* PHP 8.1 or higher
* Composer
* MySQL or compatible database
* Node.js (optional)
* Git

---

## Installation Steps

### Clone Repository

```bash
git clone https://github.com/yourusername/laravel-middleware-demo.git
cd laravel-middleware-demo
```

### Install Dependencies

```bash
composer install
```

### Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Update database credentials in `.env`:

```env
DB_DATABASE=laravel_middleware
DB_USERNAME=root
DB_PASSWORD=
```

### Create Database

```bash
mysql -u root -p -e "CREATE DATABASE laravel_middleware;"
```

### Run Migrations and Seeders

```bash
php artisan migrate
php artisan db:seed --class=UsersTableSeeder
```

### (Optional) Enable Database Sessions

```bash
php artisan session:table
php artisan migrate
```

Update `.env`:

```env
SESSION_DRIVER=database
```

### Start Server

```bash
php artisan serve
```

Application URL:

```
http://localhost:8000
```

---

## Test User Credentials

| Email                                                 | Password | Role      | Description          |
| ----------------------------------------------------- | -------- | --------- | -------------------- |
| [admin@example.com](mailto:admin@example.com)         | password | admin     | Full access          |
| [moderator@example.com](mailto:moderator@example.com) | password | moderator | Partial admin access |
| [user@example.com](mailto:user@example.com)           | password | user      | Regular user         |
| [inactive@example.com](mailto:inactive@example.com)   | password | user      | Inactivity test      |

---

## Project Structure

```
app/
  Http/
    Controllers/
    Middleware/
config/
database/
  migrations/
  seeders/
resources/views/
routes/web.php
```

---

## Middleware Registration

Register middleware aliases in `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    'role' => \App\Http\Middleware\CheckUserRole::class,
    'track.activity' => \App\Http\Middleware\TrackUserActivity::class,
    'prevent.inactive' => \App\Http\Middleware\PreventInactiveUsers::class,
];
```

---

## Example Routes

```php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth','track.activity']);

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware(['auth','role:admin','track.activity']);

Route::get('/moderator/panel', function () {
    return view('moderator.panel');
})->middleware(['auth','role:moderator,admin']);
```

---

## Database Schema

### Users Table

```php
$table->enum('role', ['user','admin','moderator'])->default('user');
$table->timestamp('last_activity_at')->nullable();
```

---

## Sample Unauthorized API Response

```json
{
  "message": "Unauthorized. Required role(s): admin",
  "your_role": "user"
}
```

---

## Activity Log Example

```
User activity {"user_id":1,"path":"/admin/dashboard","method":"GET"}
```

---

## Testing Guide

* Login using different roles
* Try accessing restricted routes
* Verify role-based blocking
* Check `last_activity_at` updates
* Reduce inactivity timeout to test auto logout

---

## Troubleshooting

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Session issues:

```bash
php artisan session:table
php artisan migrate
```

---

## Extending This Project

* Add more user roles
* Store activities in a separate table
* Add permission-based access
* Integrate real-time notifications

---

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to your branch
5. Open a pull request

---

## Screenshots

(See images above for dashboard and middleware behavior)
