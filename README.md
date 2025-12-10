# PHP_Laravel12_Create_Custome_Middleware

A complete Laravel project demonstrating custom middleware with role-based access control and user activity tracking.

## Overview

This project showcases:

* Custom middleware implementations
* Role-based access control (Admin, Moderator, User)
* User activity tracking (last activity timestamp + logs)
* Inactive user prevention (auto-logout after configurable inactivity)
* Database-backed session management
* Authentication system and example dashboards
* Clean file structure and sample seeders for test users

---

## Features

* `TrackUserActivity` middleware — updates `last_activity_at` and logs actions.
* `CheckUserRole` middleware — restricts access by role(s), supports multiple roles.
* `PreventInactiveUsers` middleware — logs out users inactive for a configurable period.
* Multiple sample roles: `admin`, `moderator`, `user`.
* Database session driver support (optional but recommended for real-time session queries).
* Example routes and controllers for admin, moderator, management, and regular users.

---

## Prerequisites

* PHP 8.1 or higher
* Composer
* MySQL (or compatible DB)
* Laravel 10 or higher (this repo targets Laravel 12 compatibility but works with 10+)

---

## Quick Installation

```bash
# Clone repository
git clone https://github.com/yourusername/laravel-middleware-demo.git
cd laravel-middleware-demo

# Install composer dependencies
composer install

# Copy env and generate app key
cp .env.example .env
php artisan key:generate

# Configure database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel_middleware
# DB_USERNAME=root
# DB_PASSWORD=

# Create database (example with MySQL)
mysql -u root -p -e "CREATE DATABASE laravel_middleware;"

# Run migrations and seeders
php artisan migrate
php artisan db:seed --class=UsersTableSeeder

# (Optional) Create sessions table and switch SESSION_DRIVER=database
php artisan session:table
php artisan migrate

# Start server
php artisan serve
```

Visit: `http://localhost:8000`

---

## Test User Credentials

| Email                                                 | Password | Role      | Notes                 |
| ----------------------------------------------------- | -------- | --------- | --------------------- |
| [admin@example.com](mailto:admin@example.com)         | password | admin     | Full system access    |
| [moderator@example.com](mailto:moderator@example.com) | password | moderator | Limited admin access  |
| [user@example.com](mailto:user@example.com)           | password | user      | Regular user          |
| [inactive@example.com](mailto:inactive@example.com)   | password | user      | Example inactive user |

---

## Project Structure (high level)

```
app/
  Http/
    Controllers/
      AdminController.php
      DashboardController.php
      AuthController.php
    Middleware/
      CheckUserRole.php
      PreventInactiveUsers.php
      TrackUserActivity.php
config/
database/
  migrations/
  seeders/
resources/views/
  auth/login.blade.php
  dashboard.blade.php
  profile.blade.php
  admin/dashboard.blade.php
  moderator/panel.blade.php
routes/web.php
```

---

## Middleware Details

### TrackUserActivity

* Purpose: Update `last_activity_at` on the authenticated user and write an activity entry to `storage/logs/laravel.log`.
* Typical location: `app/Http/Middleware/TrackUserActivity.php`.
* Behavior: Runs on each request for authenticated routes. Optionally can be limited to specific routes.

### CheckUserRole

* Purpose: Protect routes or controllers by required role(s). Accepts comma-separated roles.
* Example usage: `->middleware(['auth', 'role:admin,moderator'])`
* API behavior: Returns JSON `{ message, your_role }` with a `403` or `401` HTTP status for API requests.

### PreventInactiveUsers

* Purpose: Automatically log out users who have been inactive for a configured duration (default: 30 days).
* Configuration: `$inactiveDays` constant or `config('auth.inactive_days')`.
* Behavior: Redirects to login with a message like "Your account was logged out due to inactivity".

Register middleware aliases in `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // other middleware
    'role' => \App\Http\Middleware\CheckUserRole::class,
    'track.activity' => \App\Http\Middleware\TrackUserActivity::class,
    'prevent.inactive' => \App\Http\Middleware\PreventInactiveUsers::class,
];
```

---

## Example Routes

```php
Route::get('/', function () { return view('welcome'); });

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth','track.activity']);
Route::get('/profile', [DashboardController::class, 'profile'])->middleware(['auth','track.activity']);

// Admin-only
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->middleware(['auth','role:admin','track.activity']);
Route::get('/admin/statistics', [AdminController::class, 'statistics'])->middleware(['auth','role:admin']);

// Moderator (or admin) access
Route::get('/moderator/panel', function(){ return view('moderator.panel'); })->middleware(['auth','role:moderator,admin']);

// Management: admin or moderator
Route::get('/management/dashboard', function(){ return view('management.dashboard'); })->middleware(['auth','role:admin,moderator']);
```

---

## Database Schema (example)

### `users` table (migration snippet)

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('role', ['user','admin','moderator'])->default('user');
    $table->timestamp('last_activity_at')->nullable();
    $table->timestamps();
});
```

### `sessions` table (if using DB session driver)

Run `php artisan session:table` then migrate. This creates the `sessions` table used by Laravel.

---

## API / JSON Response Example

When a user without required role attempts access to a role-protected route and the request expects JSON:

```json
{
  "message": "Unauthorized. Required role(s): admin",
  "your_role": "user"
}
```

---

## Activity Log Example

```
[2024-01-15 10:30:45] local.INFO: User activity {"user_id":1,"user_name":"Admin User","path":"/admin/dashboard","method":"GET","ip":"127.0.0.1","time":"2024-01-15 10:30:45"}
```

---

## Testing Guide

* Login as each seeded user and attempt access to the routes listed in the `Testing Middleware` section.
* Verify `last_activity_at` updates on navigation.
* To test inactivity auto-logout, set `PreventInactiveUsers` to a short timeframe (e.g. 1 minute) and simulate inactivity.

---

## Troubleshooting

Common commands:

```bash
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan optimize
```

Session troubleshooting:

```bash
php artisan session:table
php artisan migrate
# set SESSION_DRIVER=database in .env
```

---

## Extending the Project

* Add more roles: modify the `role` enum in migration and update middleware checks.
* Store activities in a dedicated `activities` table for feeds and analytics.
* Add real-time notifications via WebSockets (Laravel Echo + Pusher or Socket.IO).
* Integrate a permissions package (e.g., Spatie) for fine-grained permission control.

---

## Contributing

1. Fork the repository
2. Create a feature branch `git checkout -b feature-name`
3. Commit changes `git commit -m 'Add feature'`
4. Push to your branch `git push origin feature-name`
5. Submit a Pull Request

Please include reproduction steps and screenshots for UI changes.

---
<img width="1829" height="972" alt="image" src="https://github.com/user-attachments/assets/27c9e67f-c8fc-456d-b0dd-54e08a2a1b37" />

<img width="1713" height="810" alt="image" src="https://github.com/user-attachments/assets/6fc842f3-6089-4e0a-b116-87a9acd099a2" />

<img width="1731" height="737" alt="image" src="https://github.com/user-attachments/assets/fa6593c5-25ff-456f-832b-c34fd657e546" />

<img width="1746" height="724" alt="image" src="https://github.com/user-attachments/assets/90fac283-7317-4885-bda2-9111fae33829" />






