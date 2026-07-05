# URL Shortener

A Laravel-based multi-tenant URL Shortener with role-based authentication.

## Features

- Authentication
- Role Based Access (Super Admin, Admin, Member)
- Company Management
- Invite Users via Email
- Short URL Generation
- Public URL Redirection
- Dashboard Analytics
- Excel Export
- Filter by:
  - Today
  - Last Week
  - Last Month
  - This Month

## Requirements

- PHP 8.2+
- Composer
- MySQL
- Node.js & npm

## Installation

Clone the repository

```bash
[git clone https://github.com/your-username/url-shortener.git](https://github.com/rahulroot7/url-shortener.git)
```

Go to project

```bash
cd url-shortener
```

Install dependencies

```bash
composer install
```

Install frontend packages

```bash
npm install
```

Create environment file

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Configure your database in the `.env` file.

Run migrations

```bash
php artisan migrate
```

Seed database

```bash
php artisan db:seed
```

Link storage

```bash
php artisan storage:link
```

Start the application

```bash
php artisan serve
```

Compile frontend assets

```bash
npm run dev
```

The application will be available at:

```
http://127.0.0.1:8000
```

## Default Roles

- Super Admin
- Admin
- Member

## Invitation Flow

### Super Admin

- Invite Admin
- Admin creates Company during registration.

### Admin

- Invite Admin
- Invite Member
- Users join the existing company.

## Short URL

Short URLs are publicly accessible and redirect to the original URL.

## Excel Export

Excel export is available for:

- Super Admin
- Admin
- Member

based on selected date filters.

## Technologies

- Laravel 12
- MySQL
- Tailwind CSS
- Alpine.js
- Laravel Excel
