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

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

# url-shortener
url-shortener

## URL Shortener Project Setup

This is a Laravel-based URL shortener project with role-based access. It allows users to shorten URLs, and depending on their role (Super Admin, Admin, User), they can manage and view different URLs. This project uses Laravel Breeze for user authentication and provides functionality for sending email invitations.

## Prerequisites

Before setting up the project, make sure you have the following installed:

- PHP >= 8.1
- Composer
- Node.js and npm
- MySQL or SQLite (or any database configured for your Laravel project)
- Laravel >= 10.0

## Step 1: Clone the repository

Start by cloning the repository to your local machine:

```bash
git clone https://github.com/saini31/url-shortener.git
cd url-shortener
```

## Step 2: Install dependencies

Run the following commands to install the required dependencies for your project:

1. Install PHP dependencies:

    ```bash
    composer install
    ```

2. Install JavaScript dependencies:

    ```bash
    npm install
    ```

## Step 3: Set up the environment

1. Copy the `.env.example` file to `.env`:

    ```bash
    cp .env.example .env
    ```

2. Generate the application key:

    ```bash
    php artisan key:generate
    ```

3. Configure the database settings and smtp credentials for send mail in your `.env` file. Make sure the database settings match your local environment:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=url_shortener
    DB_USERNAME=root
    DB_PASSWORD=
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=587
    MAIL_USERNAME=mail
    MAIL_PASSWORD=password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS="mail"
    MAIL_FROM_NAME="${APP_NAME}"
    ```

## Step 4: Run migrations and seed the database

Run the following commands to set up your database schema and seed the database:

1. Run migrations to create the necessary tables:

    ```bash
    php artisan migrate
    ```

2. Run the database seeder to populate the necessary tables with default data (including admin credentials):

    ```bash
    php artisan db:seed
    ```

    The `DatabaseSeeder.php` file will contain the initial data, including login credentials for testing. Check the `database/seeders/DatabaseSeeder.php` file for the credentials set for testing login.

## Step 5: Run the application

1. Compile assets (CSS, JavaScript, etc.) using npm:

    ```bash
    npm run dev
    ```

2. Start the Laravel development server:

    ```bash
    php artisan serve
    ```

    The app will be accessible at `http://127.0.0.1:8000`.

## Step 6: Super Admin, Admin, and User Roles

### Super Admin
The super admin has full access to the system and can:
- View all users.
- View all shortened URLs.
- Invite new admins and members.
- Manage clients for the team.

### Admin
An admin can:
- View their own users and URLs.
- Send email invitations to users for sign-up.

### User
A regular user can:
- Shorten their own URLs.
- View only their own shortened URLs.
- Download CSV files of their shortened URLs with filters.

### Role-Based Functionality:
- **Super Admin** can invite users by email (admin, member roles) using the invitation link.
- **Admin** can send invitations to users and manage their own clients and URLs.
- **User** can only shorten URLs and view their own shortened URLs.

## Step 7: Authentication (Laravel Breeze)

This project uses **Laravel Breeze** for user authentication. The following pages are available:

- **Login**: Users can log in using their email and password.
- **Register**: Users can register using an email invitation sent by the super admin or admin.
- 

## Step 8: Sending Invitations

1. The **Super Admin** can invite new users (admins, members) by email.
2. The **Admin** can send invitations to new users.

Once the invitation is received, users can sign up and gain access to their respective functionalities.

## Step 9: CSV Download and URL Filtering

- Users can download their shortened URLs in CSV format.
- Filters are available to view specific shortened URLs.

## Step 10: Update Vendor and Run NPM Dev

After pulling the latest changes, make sure to update your vendor and run npm development:

```bash
composer update
npm run dev
```

## Troubleshooting

If you encounter any issues, consider clearing cache and config:

```bash
php artisan config:clear
php artisan cache:clear
php artisan migrate:refresh --seed
```

## Conclusion

This project is a URL shortener with full role-based authentication and URL management functionality. Super Admins, Admins, and Users all have different levels of access to manage the system. Feel free to extend it as needed.

