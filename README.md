## ElePHPant.me

> Here is the right place for your elePHPants!


### About
- You can add your herd
- See ranking
    - global / per country
- Find people to trade
- See statistics about elephpants
- API, documented at https://www.elephpant.me/docs (spec: https://www.elephpant.me/docs.openapi)

### Stack

#### Frontend
- HTML5, CSS3, Bootstrap 5
- Vite, Livewire 3, Flux UI
- JavaScript, jQuery (popovers)

#### Backend
- PHP 8.5
- Laravel 10
- Livewire 4, FakerPHP
- Composer, PHPUnit

#### Database
- MySQL 8.0^

---

### Installation

### Using ddev

Clone this repo.

```bash
ddev start
ddev project-setup
```

Access the site on https://elephpantme.ddev.site

#### Prerequisite
- config file `.env`
- create local database  

#### Database

```bash
$ php artisan migrate
$ php artisan db:seed # only for generating fake data locally
```

#### Backend

```bash
$ composer install
$ php artisan key:generate
$ php artisan elephpants:read
$ php artisan storage:link
```

#### Frontend (Vite)

```bash
$ npm install
$ npm run build   # or npm run dev
```

---

### API documentation

API docs are **generated from the code** with [Scribe](https://scribe.knuckles.wtf/laravel), not
written by hand. Deploy runs `php artisan scribe:generate` in `update.sh`, so generated files are
**not** committed.

Public URLs (served by Scribe):

- HTML docs: https://www.elephpant.me/docs
- OpenAPI spec: https://www.elephpant.me/docs.openapi

Never edit generated OpenAPI or HTML by hand. To change the docs, update the API controllers (or
`config/scribe.php`) and regenerate.

#### Regenerating locally

```bash
$ composer docs   # php artisan scribe:generate
```

Scribe calls every endpoint for real while generating, and uses the actual responses as the
examples in the docs. That means **the database you generate against becomes the examples**, so
seed it first:

```bash
$ php artisan migrate:fresh
$ php artisan db:seed   # real species catalogue + fake collectors and herds
$ composer docs
```

What belongs in git:

| Path | What it is |
|---|---|
| `config/scribe.php` | Scribe configuration |
| Controller attributes | Endpoint descriptions / groups |
| `.scribe/intro.md`, `.scribe/auth.md`, `.scribe/endpoints/custom.*.yaml` | Optional hand-edited Scribe sources |

What is generated (gitignored; produced on deploy / via `composer docs`):

| Path | What it is |
|---|---|
| `storage/app/scribe/openapi.yaml` | Generated OpenAPI spec (served at `/docs.openapi`) |
| `resources/views/scribe/` | The `/docs` Blade page |
| `public/vendor/scribe/` | CSS and JS for that page |
| `.scribe/endpoints*` | Extracted endpoint cache |

#### Describing an endpoint

Scribe reads PHP attributes on the controller. Field types and example values are inferred from the
real response, so you only describe what the JSON cannot tell it:

```php
#[Group('Herds', "A single collector's herd of elePHPants.")]
class HerdController extends Controller
{
    #[Endpoint(title: "Get a collector's herd", description: '...')]
    #[UrlParam('username', 'string', 'Collector username.', example: 'john')]
    #[ResponseField('stats.spare', 'integer', 'Extra copies beyond one of each species held.')]
    public function show(string $username): JsonResponse
```

The `example` on a `UrlParam` is the value Scribe puts in the URL when it calls the endpoint, so it
has to exist in the seeded database or the captured example response will be a 404.

CI generates the spec with Scribe, then validates it with [Redocly](https://redocly.com/docs/cli).

---

### Maintainers
Junior Grossi – [@junior_grossi](https://x.com/junior_grossi)  
Igor Duarte – [@Igor Duarte](https://www.linkedin.com/in/igorduartedev/)  
Jon Purvis - [@jonpurvis_](https://x.com/jonpurvis_)  
Thomas Eiling - [@TEiling88](https://x.com/TEiling88)

### Sponsors
Hosting: [Creoline](https://creoline.com/en?utm_source=elephpant.me)

This project is `Open Source` and contains [MIT License](LICENSE).
