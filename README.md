# Northlight Optical (concept project)

Northlight Optical is a concept website for a **fictional** local eyewear store. It is a portfolio piece, not a client site.

**Live demo:** https://northlight-optical.onrender.com/
(Free hosting: the first visit after about 15 minutes idle takes up to a minute to wake. Data resets on restart.)

### Who built what
- **The shop** (catalog, search and filters, cart, checkout, accounts, orders) is the open-source **Sunray** project by [Bhupinder Singh](https://github.com/bhupindersingh007/sunray). It is reused as-is.
- **The Northlight layer** is this repo's work: the brand, five marketing pages (About, Services, Promise, Testimonials, Contact), the homepage narrative, the scroll and hover motion layer, the 3D hero, and the Docker/Render deployment setup.

### Run it free on Render
`render.yaml` deploys a free Docker web service using SQLite, so no database is needed. In Render choose New, then Blueprint, pick this repo and branch, and apply. See `docker/start.sh` for the boot steps (migrate, seed, serve). To use MySQL instead, set `DB_CONNECTION=mysql` and the `DB_*` variables.

---

## Introduction (upstream Sunray README)
**SUNRAY** - An eyewear ecommerce application built with PHP, Laravel, Vite, Bootstrap, and MySQL.

**SUNRAY Link** - http://sunray.thsite.top
<br/>
<br/>
![SUNRAY - Showcase](https://github.com/bhupindersingh007/sunray/assets/63149405/3d3c4ee6-c8ed-4792-808e-42f59174a0d4)

## Features
- **User Authentication:** Laravel built-in login, sign-up, and logout for user accounts.
- **Product Search & Filters:** Easily find products with search and custom filters.
- **Cart System:** Add, remove, and manage items in your cart easily.
- **Checkout:** Easy way to pay for your items and provide your address for delivery.
- **User Settings:** Update account info, password, or delete account.
- **Orders History:** View past orders for tracking and reordering.
- **Dashboard:** Manage orders, preferences, and account settings from one place.
- **Responsive UI:** Browse and shop on any device with a mobile-friendly design.
- **Flash Messages:** Quick notifications for important actions like successful login, item added to cart, or order confirmation.

## Demo

https://github.com/bhupindersingh007/sunray/assets/63149405/886c6865-05fc-4baa-a408-a6dea0752dd5


## Showcase

![Home](https://github.com/bhupindersingh007/sunray/assets/63149405/96753e27-93f8-490b-b20c-89ed4761cf9e)

### Register & Login
![Register](https://github.com/bhupindersingh007/sunray/assets/63149405/1673d258-3d10-4e78-b1a7-050843356076)
![Login](https://github.com/bhupindersingh007/sunray/assets/63149405/fb9f1f6b-5715-4532-989e-ac966b597f8f)

### Products Details, Search & Filters
![Products with Search & Filters](https://github.com/bhupindersingh007/sunray/assets/63149405/7d48694d-7686-487a-8ea4-5244f3cdf851)
![Product Details](https://github.com/bhupindersingh007/sunray/assets/63149405/8dd3536e-51ca-4d7b-9712-25f09f973dea)

### Shopping Cart
![Cart](https://github.com/bhupindersingh007/sunray/assets/63149405/c029717c-fc8f-4c3e-b527-292e6cfa5680)

### Customer Dashboard  
![My Account](https://github.com/bhupindersingh007/sunray/assets/63149405/11d90d0f-a072-4c06-aa23-ee1361239901)

### Customer Orders History
![My Orders](https://github.com/bhupindersingh007/sunray/assets/63149405/835995f5-c968-4911-a148-98f117e6ac04)
![Order Detailed Information](https://github.com/bhupindersingh007/sunray/assets/63149405/475feb2f-b9fe-40f6-8675-36ba6290d245)

### Update or Delete Account & Update Password 
![Update & Delete Account](https://github.com/bhupindersingh007/sunray/assets/63149405/505e48dd-d2e8-41d4-95ba-e260fa15474e)
![Update Password](https://github.com/bhupindersingh007/sunray/assets/63149405/726a5441-cccd-43c9-b87d-381e6089b568)

## Installation

**Requirements**: PHP >= 8.1, Composer, RDBMS (such as MySQL, MariaDB, PostgreSQL, etc.)

**Installation Steps:**

1. Clone the repository ```git clone https://github.com/bhupindersingh007/sunray.git``` or download zip.
2. Open the directory ```sunray``` in the terminal.
3. Install composer dependencies ```composer install```.
4. Make a new ```.env``` file and copy ```.env.example``` file to ```.env```.
5. Set the database configuration in the ``.env`` like ```DB_DATABASE, DB_USERNAME and DB_PASSWORD```etc.
7. Generate key: ```php artisan key:generate```.
8. Run ```php artisan migrate:refresh --seed```
9. Run ```npm i``` and ```npm build```.
10. Run the application: ```php artisan serve```.
    
## Technology Stack 

- [PHP 8.1](https://www.php.net/) - A popular general-purpose scripting language for web development.
- [Laravel 10](https://laravel.com/docs/10.x) - PHP fullstack web application framework.
- [MySQL 8.0](https://dev.mysql.com/doc/relnotes/mysql/8.0/en/) - The world's most popular open source relational database.
- [Feather Icons](https://feathericons.com) - Simply beautiful open source SVG icons.
- [Vite Plugin](https://github.com/laravel/vite-plugin) - Laravel plugin for Vite.
- [Bootstrap 5](https://github.com/twbs/bootstrap) - The most popular HTML, CSS, and JavaScript framework for developing responsive, mobile first projects on the web.
