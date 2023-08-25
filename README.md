
# Task API with JWT

This is a Laravel project that shows how to use JSON Web token for API Tokenization. Implemenation user authentication and authorization as well as Task CRUD functionality. 

NOTE: You must have PHP >=8.2, composer and Mysql before you can execute this project




## Documentation

Step 1: Clone git repo

```bash
  git clone git@github.com:Guzbyte-tech/Simple_jwt_auth_with_laravel.git
```
Step 2: cd into the project
```bash
  cd Simple_jwt_auth_with_laravel
```
Step 3: Run the composer install command
```bash
  composer install
```
Step 4: Copy and setup .env file
```bash
  cp .env.example .env
```
Step 5: Generate Application key
```bash
  php artisan key:generate
```

Step 6: Create Database and update database credentials in .env file
```bash
  DB_DATABASE=interview
  DB_USERNAME=root
  DB_PASSWORD=
```
Step 7: Run the following command to set JWT_SECRET in the .env file
```bash
  php artisan jwt:secret
```

Step 8: Update the .env file with your GOOGLE_CLIENT_ID and SECRET to enable OAuth2 sign and register with Google. you can do that https://console.cloud.google.com/. In your .env file set the following with the right credentials
```bash
  GOOGLE_CLIENT_ID="xxxxx"
  GOOGLE_CLIENT_SECRET="xxxx"
```
(Optional only on localhost)
Then goto terminal and serve Application using the command 
```bash
  php artisan serve
```


Step 9: Since this is on a local server set Google Callback uri to http://127.0.0.1:8000/api/v1/google/callback .
To test on live server replace http://127.0.0.1:8000 with e.g https://example.com

Step 9: Run the command below to generate swagger Documentation.
```bash
  php artisan l5-swagger:generate
``` 

Goto http://127.0.0.1:8000/api/v1/documentation to view API documentation with swagger.

THATS IT :)