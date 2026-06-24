# Panelis User

Manage application users directly from the Panelis admin panel.

## Features

* User management
* Create, update, and delete users
* User profile management
* Email verification status
* Password management
* Search and filtering
* Automatic Panelis plugin discovery

## Requirements

* PHP 8.3+
* Laravel 13+
* Filament 5+

## Installation

Install the package via Composer:

```bash
composer require panelis-php/user
```

Run migrations:

```bash
php artisan migrate
```

## Usage

After installation, a **Users** menu will be available in the Panelis admin panel.

The User module provides user administration features for managing access to your application.

Available actions include:

* Create users
* Edit user information
* Reset passwords
* Verify email addresses
* Delete users

User information may include:

* Name
* Email address
* Password
* Email verification status

## Integration

The User module integrates with Laravel's authentication system and can be extended by other Panelis modules.

## License

The MIT License (MIT).
