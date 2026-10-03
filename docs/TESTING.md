# Testing

Tests are written with [Pest](https://pestphp.com) and live in `tests/Feature` and `tests/Unit`.

## Running tests

```bash
./vendor/bin/pest -p                         # full suite, in parallel (what CI runs)
php artisan test --compact                   # full suite
php artisan test --compact --filter="profile update"   # a subset, matched by test name
./vendor/bin/pint --test                     # code style check (also runs in CI)
```

## Test environment

`.env.testing` sets `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:`, so tests run against a throwaway in-memory database and never touch your local development database. CI copies it into place with `cp .env.testing .env`.

## Creating users: `UserObserver` and `UserFactory`

`App\Observers\UserObserver` runs on the `creating` event and always replaces the username:

```php
$user->username = User::generateUsername($user);
```

`User::generateUsername()` uses `x_handle`, falling back to the slugified name, and appends `1`, `2`, ... if the name is taken. `UserFactory` fills `x_handle` with a random value, so a username passed to the factory is ignored:

```php
$user = User::factory()->create(['username' => 'custom_username']);
$user->username; // the random x_handle, not 'custom_username'
```

To get a predictable username, use one of these:

```php
// 1. Set x_handle, which the observer uses
$user = User::factory()->create(['x_handle' => 'custom_username']);

// 2. Update after creation (the observer does not listen to `updating`)
$user = User::factory()->create();
$user->update(['username' => 'custom_username']);

// 3. Or read $user->username in your assertions instead of hardcoding it
```

Two users created with the same `x_handle` get `same` and `same1`.

## Validation failures vs database errors

A request that fails validation redirects back with errors, which you assert with:

```php
$response->assertSessionHasErrors(['email']);
```

If a rule is missing, such as `Rule::unique('users')`, bad input is not rejected by validation. It reaches the database, and the test fails with an exception like `SQLSTATE[23000]: Integrity constraint violation: UNIQUE constraint failed: users.email` instead of an assertion failure. When writing a negative test, temporarily remove the rule and confirm the test fails to be sure it checks what you think it does.
