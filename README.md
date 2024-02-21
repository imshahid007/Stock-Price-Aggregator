# Stock Price Aggregator, A project in PHP Laravel

This project fetch's the real-time stock price using Alpha Vantage API and saved them in Database.

## Prerequiste

-   Docker
-   Docker Compose
-   Composer
-   Apache HTTP Server (Optional, already bundled in docker-compose)
-   PHP 8.2 (Optional, already bundled in docker-compose)
-   MySQL (Optional, already bundled in docker-compose)

## Installation

After cloning the repository, you may need to install the dependencies using composer.

```
composer install
```

Copy (Clone) the .env.example file and update it with your values

```
cp .env.example .env
```

Add Database and Alpha Vantage configuration in the .env file

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_task_v1
DB_USERNAME=root
DB_PASSWORD=

ALPHA_VANTAGE_API_KEY="demo"
ALPHA_VANTAGE_CACHE_DURATION=60
```

The ALPHA_VANTAGE_CACHE_DURATION is responsible for cache duration and currently files driver is being used for cache. You can update accordingly

Generate Application key

```
php artisan key:generate
```

Run the Laravel Migrations

```
php artisan migrate
```

Seed the Stock Seeder (Predefined values)

```
php artisan db:seed
```

Run the application

```
php artisan serve
```

Fetch the Pricing History of Stocks by schedule or you can also set the cron jobs every minute

For once only:

```
php artisan app:fetch-stock-price
```

For recurring:

```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Home Page

Designed and implemented a user interface that shows the latest stock price with visual indicators to display positive or negative changes.

```
http://baseUrl/public/
```

# REST API

## Get /api/stocks

Returns all the defined stocks in the database based on pagination (10)

```
{
"symbol": "IBM",
"name": "International Business Machines Corporation"
}
```

## Get /api/price/IBM

Returns all the pricing history of a stocks in the database based on pagination (10)

```
{
    "datetime": "2024-02-20 19:55:00",
    "open": "183.3500",
    "high": "183.4600",
    "low": "183.3500",
    "close": "183.4600",
    "volume": "40"
}
```

## Get /api/report/IBM
Returns reports of the current close price - previous close price 
```
{
    "current": {
        "datetime": "2024-02-20 19:55:00",
        "open": "183.3500",
        "high": "183.4600",
        "low": "183.3500",
        "close": "183.4600",
        "volume": "40"
    },
    "previous": {
        "datetime": "2024-02-20 19:50:00",
        "open": "183.3500",
        "high": "183.3500",
        "low": "183.3500",
        "close": "183.3500",
        "volume": "200"
    },
    "change": "0.11",
    "change_percent": "0.06",
    "symbol": "IBM"
}
```


# Docker Setup
## Running
Normally, to run PHP application we just need to access it from browser by entering the address and the browser will render the page according to the instructions coded. 

**First, the DockerFile has been coded in a way to update all the dependencies and composer files but still if that doesn't work, follow the below ...**

**Start with docker (recommended)**
```
docker-compose up
```

By running the command docker-compose up, Docker Compose reads the docker-compose.yml file and starts the containers defined within it. It automatically creates the necessary networks, attaches volumes, and manages the dependencies between containers.



# Pest Testing Framework (Unit and Feature)
This application is using Pest PHP framework for Unit and integration testing

Run to view the tests
```
./vendor/bin/pest
```


# About Project and It's working

This project is built to utilise the Alpha Vantage API in Laravel PHP framework and cache the Data

## Services
A Unique class inside App\Services\AlphaVantageService was used to request HTTP calls to the api

## Resources
App\Http\Resources are used to modify any output response or behaviour towards the REST API request

## Cache
Alpha Vantage Cache Duration can be changed in the .env file or in the config/alphavantage.php

## Pint
Laravel Pint was used for code style so it stays clean and consistent.

