## ElePHPant.me

> Here is the right place for your elePHPants!


### About
- You can add your herd
- See ranking
    - global / per country
- Find people to trade
- See statistics about elephpants
- API, documented at https://www.elephpant.me/docs (spec: https://www.elephpant.me/openapi.yaml)

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
written by hand. Two things come out of one command:

- `openapi.yaml` at the repo root, served at https://www.elephpant.me/openapi.yaml
- a browsable HTML page at https://www.elephpant.me/docs

Never edit `openapi.yaml` by hand: the next regeneration overwrites it. To change the docs, change
the controller (or `config/scribe.php`) and regenerate.

#### Regenerating

```bash
$ composer docs   # php artisan scribe:generate, then copies the spec to openapi.yaml
```

Scribe calls every endpoint for real while generating, and uses the actual responses as the
examples in the docs. That means **the database you generate against becomes the examples**, so
seed it first:

```bash
$ php artisan migrate:fresh
$ php artisan db:seed   # real species catalogue + fake collectors and herds
$ composer docs
```

Scribe writes into several places, and the deploy does not run Scribe, so whatever is committed is
what production serves. Commit all of it along with your change:

| Path | What it is |
|---|---|
| `openapi.yaml` | The spec, at its public URL. Copied here by `composer docs` |
| `storage/app/scribe/openapi.yaml` | Same spec, read by the `/docs.openapi` route |
| `resources/views/scribe/` | The `/docs` page itself |
| `public/vendor/scribe/` | CSS and JS for that page |
| `.scribe/` | Scribe's extracted source. `intro.md` here is editable by hand |

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

CI validates the committed `openapi.yaml` with [Redocly](https://redocly.com/docs/cli).

Scribe is a dev dependency, and the `/docs` route comes from the package, so the deploy has to run
`composer install` **with** dev dependencies (as it does today). `openapi.yaml` is a plain file and
is served either way.

---

### Maintainers
Junior Grossi – [@junior_grossi](https://x.com/junior_grossi)  
Igor Duarte – [@Igor Duarte](https://www.linkedin.com/in/igorduartedev/)  
Jon Purvis - [@jonpurvis_](https://x.com/jonpurvis_)  
Thomas Eiling - [@TEiling88](https://x.com/TEiling88)

### Sponsors
Hosting: [Creoline](https://creoline.com/en?utm_source=elephpant.me)

This project is `Open Source` and contains [MIT License](LICENSE).
