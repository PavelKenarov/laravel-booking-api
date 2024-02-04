<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Hotel Api

- Clone the repository
- Install dependencies: composer install
- Configure database connection in .env
- Add database migration tables:  php artisan migrate

### Use Postman for tests

- Navigate to registration page http://localhost/PROJECT-FOLDER/public/api/register and make new User:
![alt text](public/hotel-api-pic/Rregistration-Step-1.JPG) 
![alt text](public/hotel-api-pic/Rregistration-Step-2.JPG) 

- Login in http://localhost/PROJECT-FOLDER/public/api/login to get your token

![alt text](public/hotel-api-pic/Login.JPG) 

- You must use this token inside all future API requests, following this example:

![alt text](public/hotel-api-pic/authorization.JPG) 

### Use x-www-form-urlencoded

![alt text](public/hotel-api-pic/WhenUsingPUT.JPG) 

### Routes

![alt text](public/hotel-api-pic/Routes.JPG) 

### Examples
![alt text](public/hotel-api-pic/Room-add.JPG)
 
![alt text](public/hotel-api-pic/Room-add-token.JPG) 

![alt text](public/hotel-api-pic/StoreBooking.JPG) 

![alt text](public/hotel-api-pic/ViewRoom.JPG) 

![alt text](public/hotel-api-pic/ViewBooking.JPG) 