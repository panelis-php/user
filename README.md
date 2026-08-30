# Panelis User

Manage application users directly from the Panelis admin panel.

## Features

* User management
* Create, update, and delete users
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

## Permission synchronization

Permissions are defined by permission enums registered by each Panelis module. They are not created, edited, or deleted manually from the permission list.

### Sync from the admin panel

Open **Users > Permissions** in the Panelis admin panel and click **Sync permissions**. The sync creates or updates permissions from the registered enums and removes permissions that are no longer registered.

### Sync from the command line

Run:

```bash
php artisan panelis:sync-permissions
```

The application must have an available database connection before running the command.

### Automatic sync after Composer update

The main Panelis application registers the sync command in its `post-update-cmd` Composer hook:

```json
"post-update-cmd": [
  "@php artisan vendor:publish --tag=laravel-assets --ansi --force",
  "@php artisan panelis:sync-permissions"
]
```

Therefore, running `composer update` from the main application repository automatically synchronizes permissions. This hook does not run when Composer is executed with `--no-scripts`.

### Register permissions in a Panelis module

Define permissions in a backed enum that implements Filament's `HasLabel` contract. The label should use the module's translation namespace:

```php
use Filament\Support\Contracts\HasLabel;

enum Permission: string implements HasLabel
{
    case Browse = 'BrowseExample';

    public function getLabel(): string
    {
        return __('example::permission.name_browse_example');
    }
}
```

Register the enum in the module's `composer.json` under `extra.panelis.permissions`:

```json
"extra": {
  "panelis": {
    "permissions": {
      "example": "Panelis\\Example\\Panel\\Resources\\ExampleResource\\Enums\\Permission"
    }
  }
}
```

Add the corresponding translation key in the module's language files, then run the sync command from the application repository.

## Integration

The User module integrates with Laravel's authentication system and can be extended by other Panelis modules.

## License

The MIT License (MIT).
