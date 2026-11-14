# Mini-Shop

A Persian RTL storefront and admin dashboard demo built with PHP 8.3, SQLite, vanilla JavaScript and custom responsive CSS.

## Features

- Persian RTL landing page and responsive product catalog
- Category filters, sorting, favorites and interactive cart
- Demo checkout that validates product prices on the server and stores orders in SQLite
- Admin dashboard with demo login, order list and product overview
- Automatic database creation and sample product seeding on first request
- Docker configuration and Render Blueprint

## Run locally

Requirements: PHP 8.1+ with PDO SQLite enabled.

```bash
php -S localhost:8000
```

Open http://localhost:8000

Admin: http://localhost:8000/admin.php

Demo credentials:
- Email: `admin@minishop.test`
- Password: `MiniShop@2026`

## Deploy on Render

1. Push this repository to GitHub.
2. In Render, choose **New → Blueprint** and connect this repository.
3. Render reads `render.yaml` and builds the Docker image.
4. Open the generated `onrender.com` URL.

**Demo hosting note:** the free Render service may spin down and its local filesystem is ephemeral. SQLite data can be reset when the service restarts or redeploys. This is a showcase/demo configuration, not production commerce hosting. For durable production data, attach a persistent disk or switch to managed MySQL/PostgreSQL.

## Important

This is a portfolio demonstration. Checkout is simulated; no payment provider is connected. The admin credentials are public demo credentials and must be replaced before any real deployment with private customer data. The included dashboard is intentionally read-only for products; orders created through checkout are persisted and visible there.
