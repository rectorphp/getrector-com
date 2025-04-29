# [getrector.com](https://getrector.com) Website

### Customization

- Use `.env.local` to customize environment variables.

## First Run

Install npm and dependencies:

```bash
npm install
npm run dev
```

Install composer dependencies:

```bash
cp .env.dist .env
composer install
```

Setup database:

```bash
touch database/database.sqlite
php artisan migrate
```

Run website in local browser:

```bash
php artisan serve
```
