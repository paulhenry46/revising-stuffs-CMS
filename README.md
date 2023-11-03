<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Revising Stuffs CMS (RSCMS)

RSCMS is a web app wich allow user to easily publish and share their revissing stuffs with their community. There are a lot of features, including

### Administration
- management of the avaible courses and level
- management of users
- moderation of content
### Publishing content
- Publishing different type of content (mindmap, worsheets and others)
- Create flashcards attached to a content
- pin contents
- create complementary documents attached to a content

## Tech Stack
#### Back-end
- Laravel 10
- Laravel JetStream for authentication
- User permissions and roles package by Spatie
- PDF to image package by Spatie
#### Front-end
- Tailwind CSS with DaisyUI components
- Laravel Livewire
- Alpine.js
- Vanilla JS for the flashcard Quiz and Learn Mode

## Installation
- Create BDD 
- Install Imagick and Ghostscript
- Clone the app and move it to whre you want
- run `composer install` and `npm install`
- edit .env file
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan seed`

An admin user is created, so you can login with : Mail : `admin@system.localhost` / Password : `d4d5ehdpdepd81 `

Don't forget to change the password !


## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [paulhenry@paulhenry.eu](mailto:paulhenry@paulhenry.eu). All security vulnerabilities will be promptly addressed.

## License

RSCMR is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
