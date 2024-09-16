## Laravel + Vue.js CRUD ERP Application

### Description
A CRUD application consisting of an API backend built with Laravel and a web client made using Vue.js. The intention is to mimic an ERP system, which typically includes user management alongside business-specific models.

---

### Features
- Create, read, update and delete users, roles and permissions
- Dashboards (Chart.js)
- API authentication with JWT token
- CORS handling
- Request throtling

---

### Technologies Used
- Laravel 10
- PHP 8.2
- MySQL
- Vue 3
- Typescript
- Chart.js
- TailwindCSS
- JWT

---

### Installation
#### Laravel Backend
1. Install dependencies: `composer install`
2. Set your environment file based on the `.env.example` file
3. Generate app key: `php artisan key:generate`
4. Go to `app/Providers/AuthServiceProvider.php` and comment permissions definition
5. Setup a MySQL 8 database named as you wish.
6. Run migrations: `php artisan migrate`
7. Uncomment the permissions from before
8. Start the server: `php artisan serve`

#### Vue.js Client
1. Install dependencies: `npm install`
7. Start the server: `php artisan serve`

---
### Credits
* [Thiago Olivier](https://github.com/thiagoolivier) - Software Developer & UX Designer.

#### Screenshots
![2024-09-15_21-32](https://github.com/user-attachments/assets/86ac3de7-2eab-466a-8f06-013ce4dd847e)

![2024-09-15_21-37](https://github.com/user-attachments/assets/679a5a9c-de0c-4f00-8179-4b6a9fdfc32e)
