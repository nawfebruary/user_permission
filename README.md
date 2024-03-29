## Requirements:
- Laravel `7.x` | `9.7`
- Spatie role permission package  `3.1.3`


## Versions:
- Laravel `9.7` & PHP - `8.x`


## Project Setup
Install Laravel Dependencies -
```console
composer install
```

Create database called - `user_management`

Create `.env` file by copying `.env.example` file

Generate Artisan Key (If needed) -
```console
php artisan key:generate
```

Migrate Database with seeder -
```console
php artisan migrate --seed
```

Run Project -
```php
php artisan serve
```

So, You've got the project of Laravel Role & Permission Management on your http://localhost:8000

## How it works
1. Login using Super Admin Credential -
     Username - `superadmin`
     Password - `12345678`
2. Create Admin
3. Create Role
4. Assign Permission to Roles


