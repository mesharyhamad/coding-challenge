# Coding Challenge
This API using Laravel 9 .

#### Following are the Models
* Product
* Ingredient
* Order

#### Usage
Clone the project via git clone or download the zip file.

##### .env
Copy contents of .env.example file to .env file. Create a database and connect your database in .env file.
##### Composer Install
cd into the project directory via terminal and run the following  command to install composer packages.
###### `composer install`
##### Generate Key
then run the following command to generate fresh key.
###### `php artisan key:generate`
##### Run Migration
then run the following command to create migrations in the databbase.
###### `php artisan migrate`
##### Database Seeding
finally run the following command to seed the database with dummy content.
###### `php artisan db:seed`

### API EndPoints
##### Create order
* URL `http://localhost:8000/api/v1/order`
* Method `POST`
##### payload example
###### Request 
```
{
"products": [
{
"product_id": 1,
"quantity": 2
}
]
}
```
###### success response 
```
{
"success":true,
"message": "order has been created successfully",
"data": {
"order_id":1
}
```

###### error response 
```
{
"success":false,
"message": "Validation errors",
"data": {
"products":["The products field is required."
],

}

}
```

##### Run test 
###### `php artisan test`


