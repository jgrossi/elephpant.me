# Contributing

Thank you for contributing to ElePHPant.me! This guide outlines how to contribute code, run the test suite, and follow repository conventions.

## Getting started

1. **Fork the repository** on GitHub to your personal account.
2. **Clone your fork** locally:
   ```bash
   git clone git@github.com:<your-username>/elephpant.me.git
   cd elephpant.me
   ```
3. **Install dependencies**: run `composer install` (and `npm install && npm run build` if you're touching frontend assets) before running tests.
4. **Create a focused branch** from the latest `master` branch:
   ```bash
   git checkout -b feat/your-feature-name
   ```
5. **Make your changes** with clear, focused commits.
6. **Run validation** locally (tests, code style, and static analysis).
7. **Push your branch** to your fork:
   ```bash
   git push -u origin feat/your-feature-name
   ```
8. **Open a pull request** against `master` describing the change and linking any relevant issues.

## Running tests

Tests are written using [Pest](https://pestphp.com) and PHPUnit.

Run the full test suite with either Artisan or Pest:

```bash
# Run all tests using Artisan
php artisan test

# Run all tests using Pest directly
./vendor/bin/pest
```

To run a targeted test file or filter by test name:

```bash
# Run a specific test file
php artisan test tests/Feature/ProfileUpdateTest.php
./vendor/bin/pest tests/Feature/ProfileUpdateTest.php

# Filter tests by name
php artisan test --filter="profile update"
./vendor/bin/pest --filter="profile update"
```

## Code style and quality

CI runs several automated quality gates on every pull request: **Pint** (formatting), **Larastan** (static analysis), **Rector** (code quality and modernization), and **Pest** (tests). Running `./vendor/bin/pint --test` and tests locally are great, but they are not the only gates.

### Code style (Pint)

This project uses [Laravel Pint](https://laravel.com/docs/pint) for code style formatting:

```bash
# Check code formatting without modifying files
./vendor/bin/pint --test

# Automatically fix code style issues
./vendor/bin/pint
```

### Static analysis (Larastan)

Run [Larastan](https://github.com/larastan/larastan) (PHPStan) to verify type safety:

```bash
./vendor/bin/phpstan analyse
```

### Rector

Run [Rector](https://getrector.com) to check code quality:

```bash
# Check for refactoring suggestions without modifying files
./vendor/bin/rector process --dry-run

# Automatically apply changes
./vendor/bin/rector process
```

## Test database

Automated tests run against an isolated in-memory SQLite database configured in `.env.testing`:

```ini
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

Because tests use in-memory SQLite, the test suite executes safely in memory without creating tables or modifying data in your local MySQL or DDEV development database. CI sets this up by copying `.env.testing` to `.env` before running tests.

## Testing validation

When writing tests for forms or request validation, assert that the application validation layer catches errors as expected:

```php
// For standard web form POSTs (the default for existing forms)
$response->assertSessionHasErrors('email');

// For JSON / API endpoints
$response->assertJsonValidationErrors('email');
```

Session assertions (`assertSessionHasErrors`) are the right default for web form POST requests like those in the application today. If you are writing tests for JSON/API endpoints, use `assertJsonValidationErrors` instead.

Tests should assert that validation errors are returned in the session or response, rather than allowing invalid data to reach the database and relying on an unhandled database `UNIQUE` constraint exception (such as `SQLSTATE[23000]`) to fail the request.
