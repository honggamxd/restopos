# Restaurant Ordering System

## Built With
* [Laravel 5.4](https://laravel.com/)
* [AngularJS](https://angularjs.org/)
* [Bootstrap 3](https://getbootstrap.com/)
* [Semantic UI](https://semantic-ui.com/)


## Prerequisites
* [composer](https://getcomposer.org/) - PHP Dependency Management
* [Node.js](https://nodejs.org/en/)

## Installation
1. Clone this GIT Repository
2. Run these commands:
```
composer install
```
```
cp .env.example .env
```
or
```
copy .env.example .env
```
```
php artisan key:generate
```

3. Configure your database settings in .env
4. run database migration
```
php artisan migrate:refresh --seed
```

## User Privileges and Features
### Admin
* Full Control of the users module
* Can add table, menu and waiters in the restaurant
* Can Generate All kinds Reports by Date Range
* Can modify restaurant order slip.
* Cannot make an Order Slip and other transactions in the restaurant

### Restaurant Admin
* Can accept cancellation of orders
* Can Generate Reports by Date Range
* Can add table, menu and waiters in the restaurant
* Can modify restaurant order slip.
* Cannot make an Order Slip and other transactions in the restaurant

### Restaurant Cashier
* Can Generate his report during the day. 
* Can make an Order Slip and other transactions in the restaurant

## Demo
* [Restaurant Ordering System Demo](http://restaurant-ordering-system.systemph.com/)

## Default Admin Account
username: admin<br>
password: admin

## Adding Restaurants
* Insert new row in restaurant table
* Define the name of the restaurant in the name column

## Must Add in the system before making Order Slips
* User Accounts (Restaurant Admin and Cashier)
* Restaurant Menu,Tables,Servers or Waiters using Admin or Restaurant Admin account
