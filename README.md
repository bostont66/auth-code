# ACSI Auth Code

Token-based (email code) authentication for Laravel. No passwords — users receive a 6-digit code via email. Includes registration with email verification, role-based access (admin/client), and admin gating middleware.

## Installation

```bash
composer require acsi/auth-code
```

Publish migrations and run them:

```bash
php artisan vendor:publish --tag=auth-code-migrations
php artisan migrate
```

Optionally publish views (to customize branding) and config:

```bash
php artisan vendor:publish --tag=auth-code-views
php artisan vendor:publish --tag=auth-code-config
```

## Setup

1. Add the `HasRoles` trait to your `User` model:
   ```php
   use Acsi\AuthCode\Traits\HasRoles;

   class User extends Authenticatable
   {
       use HasRoles;
       // ...
   }
   ```

2. Register the middleware in `bootstrap/app.php`:
   ```php
   $middleware->alias(['admin' => \Acsi\AuthCode\Http\Middleware\AdminOnly::class]);
   ```

3. Set `redirectGuestsTo` to the login route:
   ```php
   $middleware->redirectGuestsTo(fn () => route('auth.login'));
   ```

4. Ensure your `User` model has `role` and `is_active` columns (added by the migration).

## Usage

- `/login` — request a login code
- `/auth/verify` — enter the code
- `/register` — create an account (email verification required)
- `/auth/logout` — log out

First registered user becomes admin (configurable via `auth-code.first_user_is_admin`).

## Configuration

See `config/auth-code.php` for rate limits, code expiry, registration expiry, and role names.

## Routes

Routes are auto-loaded from the package. They use standard names: `auth.login`, `auth.login.send`, `auth.verify`, `auth.verify.post`, `auth.register`, `auth.register.post`, `auth.register.verify`, `auth.register.verify.post`, `auth.register.verify.token`, `auth.logout`.

## License

MIT
```