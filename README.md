## ElePHPant.me

> Here is the right place for your elePHPants!


### About
- You can add your herd
- See ranking
    - global / per country
- Find people to trade
- See statistics about elephpants

### Stack

#### Frontend
- HTML5, CSS3, Bootstrap 5
- Vite, Livewire 3, Flux UI
- JavaScript, jQuery (popovers)

#### Backend
- PHP 8.2+ (target 8.5)
- Laravel 10
- Livewire 3, FakerPHP
- Composer, PHPUnit

#### Database
- MySQL 5.8^

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

### Upgrade notes

- **Laravel 11/12:** Upgrading past Laravel 10 is currently blocked by `pragmarx/countries` (via `pragmarx/coollection` → abandoned `tightenco/collect`), which conflicts with Symfony 7. Replacing `pragmarx/countries` with another package would allow moving to Laravel 11+ and Livewire 4.
- **PHP 8.5:** The app supports PHP 8.2+. When PHP 8.5 is available, set `"php": "^8.5"` in `composer.json` if you want to require it.

---

### Maintainers
Junior Grossi – [@junior_grossi](https://twitter.com/junior_grossi)  
Igor Santos – [@IgorSantoos17](https://twitter.com/IgorSantoos17)


This project is `Open Source` and contains [MIT License](LICENSE).
