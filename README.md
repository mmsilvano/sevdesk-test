<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# SevDesk Integration

This Laravel application integrates with the SevDesk API to fetch and display transactions and invoices in read-only Backpack CRUD interfaces.

## Features

- **Transaction Model**: Laravel model with migration for storing transaction data
- **Invoice Model**: Laravel model with migration for storing invoice data
- **SevDesk Service**: Service class for fetching transactions and invoices from SevDesk API
- **Import Commands**: Artisan commands to import transactions and invoices from SevDesk
- **Backpack CRUD**: Read-only admin interfaces for viewing transactions and invoices

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure your database
4. Add SevDesk API token to `.env`: `SEVDESK_API_TOKEN=your_token_here`
5. Run migrations: `php artisan migrate`
6. Seed sample data: `php artisan db:seed`
7. Start the development server: `php artisan serve`

## Usage

### Importing Transactions from SevDesk

To import transactions from the SevDesk API:

```bash
php artisan import:sevdesk-transactions
```

This command will:
- Fetch transactions from `https://my.sevdesk.de/api/v1/Transaction`
- Use the configured bearer token for authentication
- Import new transactions (skip existing ones)
- Display import statistics

### Importing Invoices from SevDesk

To import invoices from the SevDesk API:

```bash
php artisan import:sevdesk-invoices
```

This command will:
- Fetch invoices from `https://my.sevdesk.de/api/v1/Invoice`
- Use the configured bearer token for authentication
- Import new invoices or update existing ones
- Display import statistics

### Accessing the Admin Panel

1. Visit `http://localhost:8000/admin`
2. Login with your admin credentials
3. Navigate to "Transactions" or "Invoices" in the menu
4. View the list of imported data

## Database Schema

### Transactions Table

The `transactions` table contains the following columns:

- `id` - Primary key
- `sevdesk_id` - SevDesk transaction ID
- `amount` - Transaction amount
- `currency` - Currency code (nullable)
- `purpose` - Transaction purpose/description (nullable)
- `created_at` - Record creation timestamp
- `updated_at` - Record update timestamp

### Invoices Table

The `invoices` table contains the following columns:

- `id` - Primary key
- `sevdesk_id` - SevDesk invoice ID (unique)
- `invoice_number` - Invoice number (nullable)
- `invoice_date` - Invoice date (nullable)
- `customer_name` - Customer name (nullable)
- `currency` - Currency code (nullable)
- `total_amount` - Total invoice amount (decimal 15,2, nullable)
- `paid_amount` - Paid amount (decimal 15,2, nullable)
- `status` - Invoice status (nullable)
- `created_at` - Record creation timestamp
- `updated_at` - Record update timestamp

## Configuration

The SevDesk API configuration is stored in environment variables:

- Base URL: `https://my.sevdesk.de/api/v1`
- Bearer Token: Set in `.env` as `SEVDESK_API_TOKEN`
- Config access: `config('services.sevdesk.token')`

## API Integration

The `SevDeskService` class handles:
- HTTP requests to the SevDesk API
- Authentication using bearer token from environment
- Error handling and logging
- Response parsing for both transactions and invoices

## CRUD Interfaces

Both Backpack CRUD interfaces are configured as read-only:
- List view shows all records with pagination
- Show view displays detailed information
- Create, update, and delete operations are disabled
- Columns are properly labeled and formatted

### Transaction Fields Mapping

- `id` → `sevdesk_id`
- `amount` → `amount`
- `currency` → `currency`
- `purpose` → `purpose`

### Invoice Fields Mapping

- `id` → `sevdesk_id`
- `invoiceNumber` → `invoice_number`
- `invoiceDate` → `invoice_date`
- `addressName` → `customer_name`
- `currency` → `currency`
- `sumGross` → `total_amount`
- `paidAmount` → `paid_amount`
- `status` → `status`

## Sample Data

The application includes seeders with sample data for testing purposes:

```bash
# Seed transactions only
php artisan db:seed --class=TransactionSeeder

# Seed invoices only
php artisan db:seed --class=InvoiceSeeder

# Seed all data
php artisan db:seed
```

## Troubleshooting

- Check Laravel logs for API errors: `tail -f storage/logs/laravel.log`
- Verify database connection and migrations
- Ensure Backpack is properly installed and configured
- Check API token validity if import fails
- Verify environment variable `SEVDESK_API_TOKEN` is set correctly
