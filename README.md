## ElePHPant.me

> Here is the right place for your elePHPants!


### About
- You can add your herd
- See ranking
    - global / per country
- Find people to trade
- See statistics about elephpants
- API, documented at https://www.elephpant.me/openapi.yaml

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

### Maintainers
Junior Grossi – [@junior_grossi](https://x.com/junior_grossi)  
Igor Duarte – [@Igor Duarte](https://www.linkedin.com/in/igorduartedev/)  
Jon Purvis - [@jonpurvis_](https://x.com/jonpurvis_)  
Thomas Eiling - [@TEiling88](https://x.com/TEiling88)

### Sponsors
Hosting: [Creoline](https://creoline.com/en?utm_source=elephpant.me)

This project is `Open Source` and contains [MIT License](LICENSE).
