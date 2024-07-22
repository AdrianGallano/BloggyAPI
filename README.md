## Requirements
```
   php v7+
   composer v5.6.0+
   xampp
```

## Installation
```
   composer install
```

## Database Initialization
```
   Open migrations folder in project
   Download the latest version of anuwrap.sql

   Open xampp
   import database to xampp

   Open test folder in project
   execute InsertRecords.sql in xampp
```
## Project Setup
```
   create .env file in root folder
```
   Define your environment variables
```
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=databasename
   DB_DRIVER=mysql
   DB_USERNAME=yourusername
   DB_PASSWORD=yourpassword
   SECRET_API_KEY=yourapikey
```

## More information
```
   The project is highly opinionated by the author ( Adrian cris Gallano )

   i am not picking a fight for any php devs out there, 
   i simply feel comfortable in the way i structured my project

   Model -> Handles data
   Controller -> Orchestration
   Routes -> Url Dispatcher
   Services -> Business Logic
   Migrations -> Database Version Control
   public -> Entry point

```

## Accessing the API
### Authorizations
```
Level 1 Authorization:
User must login before having the ability to request for authorized resources  

Level 2 Authorization:
User can only modify their own information 
( so requesting a different id of a user is not permissible ).

```
### Routes 
```
User Routes:
   No Authorization
      [POST] /users -> create User => (username: str, firstname: str, lastname: str ,email: str, password: str, confirm_password: str)
      [GET] /users/{id:\d+} -> retrieve a single User
      [GET] /users -> get all users

   Authorized:
      [PUT] /users/{id:\d+} -> update a single User => ( first_name: str, last_name: str)
      [DELETE] /users/{id:\d+} -> delete a single User

Authenticated Routes:
   [POST] /token -> creates a Token (login) => ( email: str, password: str)

```
