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
- Laravel Excel(MaatExel)

## Mail Configuration (Mailtrap)

This project uses **Mailtrap** for sending invitation emails during local development.

### 1. Create a Mailtrap Account

Sign up at: https://mailtrap.io/

### 2. Create a Sandbox Inbox

Create a new Sandbox Inbox and copy the SMTP credentials.

### 3. Update `.env`

Replace the mail configuration with your Mailtrap SMTP credentials.

```env
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=YOUR_MAILTRAP_USERNAME
MAIL_PASSWORD=YOUR_MAILTRAP_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Test Invitation Email

1. Log in as Super Admin or Admin.
2. Send an invitation to any email address.
3. Open your Mailtrap Sandbox Inbox.
4. Verify that the invitation email is received.
5. Click the invitation link to complete the registration process.

## AI Usage

The following AI tool was used during development:

- **ChatGPT**
  - Assisted with Laravel best practices.
  - Helped understand Eloquent relationships.
  - Assisted with validation, routing, invitation workflow, and Excel export implementation.

All application logic, architecture decisions, implementation, testing, debugging, and integration were completed and verified manually.
