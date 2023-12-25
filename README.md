
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
- Livewire
- Alpine.js
- Vanilla JS for the flashcard Quiz and Learn Mode

## Installation
- Create and set up BDD :
    - `sudo mysql -u root -p`
    - `CREATE DATABASE yourDBName;`
    - `CREATE USER 'yourDBUser'@'localhost' IDENTIFIED BY 'YourPassword';`
    - `GRANT ALL PRIVILEGES ON yourDBName.* TO 'yourDBUser'@'localhost';`
- Install Imagick and Ghostscript
    - `apt install ghostscript`
    - `apt install php8.2-imagick imagick`
- In order to allow ImageMagick to process PDF files, you must SSH into your server as root and edit the following file: `/etc/ImageMagick-6/policy.xml`
    - Locate `<policy domain="coder" rights="none" pattern="PDF" />`
    - and comment it : `<!--<policy domain="coder" rights="none" pattern="PDF" />-->`
- Clone the app and move it to where you want
- run `composer install`
- run `npm install` and `npm run build`
- edit .env file
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan db:seed`
- `npm run build`

An admin user is created, so you can login with : Mail : `admin@system.localhost` / Password : `d4d5ehdpdepd81 `

Don't forget to change the password !

## Update
- Unzip the archive in release
- cd into it and `composer install` then `npm run build`
- edit `.env` file
- `php artisan storage:link`

## Security
If you discover a security vulnerability within RSCMS, please send an e-mail to Paulhenry via [paulhenry@paulhenry.eu](mailto:paulhenry@paulhenry.eu). All security vulnerabilities will be promptly addressed.

## License

RSCMR is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
