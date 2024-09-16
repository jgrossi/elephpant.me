## ElePHPant.me

> Here is the right place for your elePHPants!


### About
- You can add your herd
- See ranking
    - global / per country
- Find people to trade
- See statistics about elephpants

### Stack: 

#### Frontend
- HTML5
- CSS3
- Bootstrap
- JavaScript
- JQuery
- Stimulus.js

#### Backend
- PHP 7.2^
- Laravel 6
- Composer
- PHPUnit

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

---

### Maintainers
Junior Grossi – [@junior_grossi](https://twitter.com/junior_grossi)  
Igor Santos – [@IgorSantoos17](https://twitter.com/IgorSantoos17)


This project is `Open Source` and contains [MIT License](LICENSE).
