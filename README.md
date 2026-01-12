# Gamification PHP + MongoDB Demo

## Requirements
- PHP 8.x
- PHP extension: `mongodb`
- MongoDB server running with database `finfrdm`

## Configuration
Update connection details in `config/config.php` if needed:
- `mongo_dsn`
- `mongo_db`
- admin credentials

## Running
You can use PHP's built-in server:

```bash
php -S localhost:8000 index.php
```

## Example URLs
- Frontend users list: `http://localhost:8000/`
- Frontend user view: `http://localhost:8000/users/view?id=<id>`
- Add action: `http://localhost:8000/actions/add?userId=<id>`
- Admin login: `http://localhost:8000/admin/login`
- Admin users: `http://localhost:8000/admin/users`
- Admin event types: `http://localhost:8000/admin/events/types`
- Seed data: `http://localhost:8000/admin/seed`
