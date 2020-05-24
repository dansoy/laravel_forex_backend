<p align="center">Laravel Forex Backend</p>

## Installation

- Clone this repository

  ```
   git clone https://github.com/dansoy/laravel_forex_backend projectName
  ```

- cd into your project

  ```
   cd projectName
  ```

- Install Composer Dependencies

  ```
   composer install
  ```

- Create a copy of your .env file

  ```
   cp .env.example .env
  ```

- Generate APP_KEY

  ```
   php artisan key:generate
  ```

- Create an empty database and add your database details to the .env file

  ```
   DB_HOST=
   DB_PORT=
   DB_DATABASE=
   DB_USERNAME=
   DB_PASSWORD=
  ```

- Migrate the database

  ```
   php artisan migrate
  ```

- Run the server (Default port is 8000)

  ```
   php artisan serve

   or

   php artisan serve --port=3000
  ```

- Run the queue worker to allow the automated cache deletion

  ```
   php artisan queue:work
  ```

- Update cache time in config/forex.php

  ```
   //Cache Time in seconds
   'cache_time' => 7200,
  ```

