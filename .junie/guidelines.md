# Development Guidelines for TFN Artist Project

This document provides guidelines and instructions for developing and maintaining the TFN Artist project.

## Build/Configuration Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm
- SQLite (for testing)

### Initial Setup
1. Clone the repository
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install JavaScript dependencies:
   ```bash
   npm install
   ```
4. Create environment file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Set up the database:
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

### Development Environment
The project includes a convenient development script that starts all necessary services:

```bash
composer dev
```

This command runs:
- Laravel development server
- Queue worker
- Log watcher
- Vite development server

For server-side rendering, use:

```bash
composer dev:ssr
```

## Testing Information

### Testing Framework
The project uses Pest PHP, a testing framework built on top of PHPUnit, with a more expressive syntax.

### Running Tests
To run all tests:

```bash
composer test
```

To run specific tests:

```bash
php artisan test tests/Feature/DashboardTest.php
```

### Test Structure
Tests are organized into two main directories:
- `tests/Unit/`: For unit tests that test individual components in isolation
- `tests/Feature/`: For feature tests that test the application as a whole

### Writing Tests
Tests are written using Pest PHP's functional syntax. Here's an example of a unit test:

```php
<?php

test('basic arithmetic operations work correctly', function () {
    // Test addition
    expect(1 + 1)->toBe(2);
    
    // Test multiplication
    expect(2 * 3)->toBe(6);
});
```

Feature tests can use Laravel's testing helpers:

```php
<?php

use App\Models\User;

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});
```

### Custom Expectations
You can add custom expectations in `tests/Pest.php`:

```php
expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});
```

## Code Style and Development Practices

### PHP Code Style
- The project follows PSR-12 coding standards
- Laravel Pint is used for PHP code formatting:
  ```bash
  ./vendor/bin/pint
  ```

### JavaScript/TypeScript Code Style
- Uses Prettier for formatting with the following configuration:
  - 4 spaces for indentation
  - Single quotes
  - Semicolons
  - 150 character line length
- Format code with:
  ```bash
  npm run format
  ```
- Check formatting with:
  ```bash
  npm run format:check
  ```
- Lint code with:
  ```bash
  npm run lint
  ```

### Editor Configuration
The project includes an `.editorconfig` file with the following settings:
- UTF-8 encoding
- LF line endings
- 4 spaces for indentation (2 spaces for YAML files)
- Trim trailing whitespace
- Insert final newline

### Frontend Development
- Vue.js 3 with TypeScript
- Inertia.js for frontend-backend integration
- Tailwind CSS for styling
- Vite for building assets

### Backend Development
- Laravel 12.0
- Uses Laravel's Eloquent ORM for database interactions
- Follows Laravel's conventions for controllers, models, and routes