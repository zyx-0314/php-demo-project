# PHP + Database using Action Controller MVC
- PHP
- Docker
- Postgresql
- MongoDB

> Always update your work book

### Problems in hosts:
- localhost
- 127.0.0.1
- 127.0.0.0
- host.docker.internal

## 1. Modifying Documentation: Update Readme
- [ ] Check all the TODO Tasks and update according to relevance to the topic
- [ ] Delete `TODO` mark when done modifying

## 2. Modifying Composer: Update `composer.json`
Change the following:
- [ ] your-username-here
- [ ] project-name-here
- [ ] add author/s
> note: don't use upercase or whitespace in the `"name"`

> you can add multiple authors
```json
"authors": [
    {
        "name": "your-username-here",
        "email": "your-email-here@gmail.com"
    },
    {
        "name": "your-username-here",
        "email": "your-email-here@gmail.com"
    }
],
```
- [ ] after modifications use the command `composer install` in cmd to check if it works
    - to verify if its correct it will show after the command green and no red another is to see if there is `vendor` folder in your files

## 3. Modifying Docker: Update `compose.yml`
Change the following:
- [ ] Change all `web-app-php` to the name of your project. don't use upercase or whitespace.
> Using `ctrl` + `D`, each press in `D` will select another similar text and its not case sensetive.
- [ ] Update Database names of : `MONGO_INITDB_DATABASE` & `POSTGRES_DB`
- [ ] (Optional) Can Change External ports <External Port>:<Internal Port> ex.: "27017:27017" -> "23567:27017"
- [ ] Run the docker if it works by using command `docker compose up` in cmd. just wait to complete the process.
    - if you have docker desktop you will see there the name of your project and if you click it you will see 3 containers.
    - it should all be green
- [ ] back in cmd if you saw a `w Enable Watch` press w while in the cmd to use it or use the following command on new cmd `docker compose watch`

## 4. Update the Checker
- [ ] create new path
    - [ ] inside the `bootstrap.php` similar to base path create new path depends on the folder your refering. example in this part of checker we will be using `handlers folder` to create path follow this format: `define('<path name>', realpath(BASE_PATH . "<folder name>"));`.
        - change the following: `<path name>` with the path name and `<folder name>` with folder name
        - example for the handlers folder; `define('HANDLERS_PATH', realpath(BASE_PATH . "/handlers"));`
- [ ] in your `index.php` in the root call the 2 checkers: `postgreChecker.handler.php` and `mongodbChecker.handler.php`
- [ ] `mongodbChecker.handler.php`
    - [ ] change the `27017` with your updated port with internal/external port
    > $mongo = `new MongoDB\Driver\Manager("mongodb://host.docker.internal:27017");` -> `$mongo = new MongoDB\Driver\Manager("mongodb://host.docker.internal:23567");`
    - [ ] make sure data from `compose.yml` matches data in the `mongodbChecker.handler.php`
- [ ] `postgreChecker.handler.php`
    - [ ] change the `5112` with your updated port with internal/external port
    > `$port = "5112";` -> `$port = "5555";`
    - [ ] make sure data from `compose.yml` matches data in the `postgreChecker.handler.php`
- [ ] Spin up the project: in terminal use the command: `docker compose up` and in new cmd is `docker compose watch`
- [ ] Add the checker in any pages and wait for either of the 2:
    All working: 
    ```html
    ✅ Connected to MongoDB successfully.
    ✅ PostgreSQL Connection
    ```

    Need Debugging:
    ```html
    ❌ MongoDB connection failed: ...
    ❌ Connection Failed: ...
    ```
> restart `docker compose up` and `docker compose watch` if you modify the docker after you spin up

## 5. Installing Dependencies
In this demo we will install a environment setter dependency.
- `vlucas/phpdotenv`

format: `composer require <name of the dependencies>`

sample:
```ps
composer require vlucas/phpdotenv
```
- [ ] install `vlucas/phpdotenv`

## 6. Modifying `.env`: Update `.env`
Make sure important informations are hidden and tucked . as in testing of for the checker they should be changed from hard codded to env based
- [ ] remove the `**/.env` in the `.dockerignore`
    - [ ] rebuild the docker by `docker compose restart`
- [ ] Fill all the following data
    - referencing from `postgreChecker.handler.php` and `mongodbChecker.handler.php`, hide important datas
- [ ] Change the hard coded of checkers to env based
    - in the `postgreChecker.handler.php` and `mongodbChecker.handler.php` change hard coded important datas
- [ ] Create a `envSetter.util.php` code under `utils` distributing all the env
> add the following code before distributing it to a variable
```php
<?php

require_once BASE_PATH . '/bootstrap.php';
require_once BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Distribute the data using array key
$typeConfig = [
    'key' => $_ENV['ENV_NAME'],
];
```
- [ ] add keys which refer to the keys from the `.env`
    - ex.: `PG_HOST=host.docker.internal` this will be something like this:
```php
//... other code above
$typeConfig = [
    'pgHost' => $_ENV['PG_HOST'],
];
```
- [ ] Update `mongodbChecker.handler.php` and `postgreChecker.handler.php`
    - [ ] call the setter
    - [ ] use the variable created to call the values
    All working:
    ```html
    ✅ Connected to MongoDB successfully.
    ✅ PostgreSQL Connection
    ```

    Need Debugging:
    ```html
    ❌ MongoDB connection failed: ...
    ❌ Connection Failed: ...
    ```

## 7. Using Tools: Connecting Database to UI Database Manager
Using `Database` a tool at the tool tab manage and view your database

**Postgresql**
- [ ] Make Sure the Database is working. Go to Docker Desktop and make sure the `image` of `postgre` is green.
- [ ] In `Database` click `Create Connection`
- [ ] Select `PostgreSQL`
- [ ] Setup connection: Port, Username, Password and Database
> can be view the data in `compose.yaml`
- [ ] Click Connect and should show: `Connection Success!` then `Save`

**Mongodb**
- [ ] Make Sure the Database is working. Go to Docker Desktop and make sure the `image` of `mongodb` is green.
- [ ] In `Database` click `Create Connection`
- [ ] Select `MongoDB`
- [ ] Setup connection: Port
> can be view the data in `compose.yaml`
- [ ] Click Connect and should show: `Connection Success!` then `Save`

## 8. Design Database: Creating Database formula preparation for automation
Using the GUI of database you need to formulate your data structure on how you will handle datas of your system.
in this demo we need to have a design for our users
Task: Users can be divided into group, they can login, basic information and role.

- [ ] Design a structure
- [ ] Create Base Pattern using the tool by simple selecting the database from `Database`
    - [ ] Select your <database name> ex.: `mydatabase`
    - [ ] Select `Tables` and look for the `+` sign then click it
    - [ ] Create Sample code then copy

    ```sql
    CREATE TABLE IF NOT EXISTS public."users" (
        id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),
        first_name varchar(225) NOT NULL,
        middle_name varchar(225),
        last_name varchar(225) NOT NULL,
        password varchar(225) NOT NULL,
        username varchar(225) NOT NULL,
        role varchar(225) NOT NULL
    );
    ```
    - [ ] Goto your `Explorer`
    - [ ] Create new file for that specific model ex.: `users.model.sql`
    - [ ] Add conditional command on your SQL code
        - [ ] between `CREATE TABLE` and `<table name>` add the following code `IF NOT EXISTS`

Task:
Create more tables for the following
- [ ] Projects
- [ ] Project ↔ User assignments (project_user)
- [ ] Tasks

Just Copy the following for the `project_users.model.sql`
```sql
CREATE TABLE IF NOT EXISTS project_users (
    project_id INTEGER NOT NULL REFERENCES projects (id),
    user_id INTEGER NOT NULL REFERENCES users (id),
    PRIMARY KEY (project_id, user_id)
);
```

- [ ] for all id copy this: `id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),`

## 9. Automation: Creating Resetter
Creating automation needs first a logic on what is the process and what should be expected output.
ex.: Processing `Giniling`, learn what is its `IPO`
- Input: Meat
- Process: Grind
- Output: Giniling

In this step we will design an automation that resets the database when needed and remodeling it.
- Input: Database Code
- Process: 
    - Check Database Connection
    - Check SQL Code
    - Apply SQL Code
- Output: Create the Table/s Ready for Use

- [ ] Creating a new util code `dbResetPostgresql.util.php`

- [ ] Setting up requirements
> Just copy this
```php
declare(strict_types=1);

// 1) Composer autoload
require_once 'vendor/autoload.php';

// 2) Composer bootstrap
require_once 'bootstrap.php';

// 3) envSetter
require_once UTILS_PATH . '/envSetter.util.php';
```

- [ ] Adding the database host and connecting
```php
$host = $databases['pgHost'];
$port = $databases['pgPort'];
$username = $databases['pgUser'];
$password = $databases['pgPassword'];
$dbname = $databases['pgDB'];

// ——— Connect to PostgreSQL ———
$dsn = "pgsql:host={$databases['pgHost']};port={$port};dbname={$dbname}";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
```

- [ ] Using specific commands to use to automatically generate the database tables
```php
// Just indicator it was working
echo "Applying schema from database/users.model.sql…\n";

$sql = file_get_contents('database/users.model.sql');

// Another indicator but for failed creation
if ($sql === false) {
  throw new RuntimeException("Could not read database/users.model.sql");
} else {
    echo "Creation Success from the database/users.model.sql";
}

// If your model.sql contains a working command it will be executed
$pdo->exec($sql);
```
> repeat this code times the number of tables

- [ ] Make sure it clean the tables
```php
echo "Truncating tables…\n";
foreach (['users'] as $table) {
  $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
}
```

- [ ] Add the command in the `composer.json`
    - below `scripts` add a new library key set
    - `"postgresql:reset": "php utils/dbResetPostgresql.util.php"`

- [ ] Test it if working
    - in terminal use command `composer postgresql:reset`
    Partial Complete: ✅ PostgreSQL reset complete!
    Issue Arise from SQL Code: ❌ Could not read database/modelName.model.sql

- [ ] visit GUI extension for database for checking and if each table exist congrats it works!!! 🎉

## 10. Adding Seeder: Creating Automation for viewing Data
Seeding is terminology used refering to inputing data in database upon creation, making sure it is connected and can view data

- [ ] duplicate the `dbResetPostgresql.util.php` and rename it `dbSeederPostgresql.util.php`
- [ ] add the following logic for
- Input: Database Code
- Process: 
    - Check Database Connection
    - Check SQL Code
    - Apply SQL Code
    - Add Seed Data(Dummy Data)
- Output: Create the Table/s Ready for Use and can view data

- [ ] before logic prepare the data a head
    - [ ] create in `staticData/dummies` a file for the specific model
    - [ ] (in this demo we will use the `users model` with `users dummies`) create file named `users.staticData.php`
    - [ ] add simple dummy data using array of key arrays
```php
<?php
// the table of users are compose of following columns: id, username, first_name, last_name, password, role
return [
    ['username' => 'john.smith', 'first_name' => 'John', 'last_name' => 'Smith', 'password' => 'p@ssW0rd1234', 'role' => 'designer'],
]
```
    - [ ] call the dummy data to the seeder code
```php
// after settings requirements

$users = require_once DUMMIES_PATH . '/users.staticData.php';

// before logic
// connect to postgresql
```
    - [ ] add seeding logic
```php
// simple indicator command seeding started
echo "Seeding users…\n";

// query preparations. NOTE: make sure they matches order and number
$stmt = $pdo->prepare("
    INSERT INTO users (username, role, first_name, last_name, password)
    VALUES (:username, :role, :fn, :ln, :pw)
");

// plug-in datas from the staticData and add to the database
foreach ($users as $u) {
  $stmt->execute([
    ':username' => $u['username'],
    ':role' => $u['role'],
    ':fn' => $u['first_name'],
    ':ln' => $u['last_name'],
    ':pw' => password_hash($u['password'], PASSWORD_DEFAULT),
  ]);
}
```

- [ ] Add the command in the `composer.json`
    - below `scripts` add a new library key set
    - `"postgresql:seed": "php utils/dbSeederPostgresql.util.php"`
    Partial Complete: ✅ PostgreSQL seeding complete!
    Issue Arise from SQL Code: ❌ Could not read database/modelName.model.sql

- [ ] visit GUI extension for database for checking and if each contents exist in the tables congrats it works!!! 🎉

## 11. Adding Migration: Creating Automation for Migrating New Table Data
Resets and add/update database

- [ ] duplicate the `dbResetPostgresql.util.php` and rename it `dbMigratePostgresql.util.php`
- [ ] delete all codes below `$pdo`

 we will change the logic here:
 - select all tables and drop them: means deleting them
 - then create newly updated tables

- [ ] add this deleting part, add all tables inside the array:
```php
echo "Dropping old tables…\n";
foreach ([
  'projects',
  'users',
] as $table) {
  // Use IF EXISTS so it won’t error if the table is already gone
  $pdo->exec("DROP TABLE IF EXISTS {$table} CASCADE;");
}
```

- [ ] then add again the old setter code
```php
echo "Applying schema from database/users.model.sql…\n";

$sql = file_get_contents('database/users.model.sql');

if ($sql === false) {
    throw new RuntimeException("Could not read database/users.model.sql");
} else {
    echo "Creation Success from the database/users.model.sql";
}

$pdo->exec($sql);
```

- [ ] Add the command in the `composer.json`
    - below `scripts` add a new library key set
    - `"postgresql:migrate": "php utils/dbMigratePostgresql.util.php"`
    Partial Complete: ✅ PostgreSQL seeding complete!
    Issue Arise from SQL Code: ❌ Could not read database/modelName.model.sql

- [ ] visit GUI extension for database for checking and if each contents exist in the tables congrats it works!!! 🎉

## 12. Creating Utility Function Codes
in this part you will be creating class which has specific function codes, this is similar on how you create OOP codes.

- [ ] Strategies what functions you should have
    - in this we are creating login where data are being retrieved and then checked out based on input of user if it matches
    ```md
    Input: username & password
    Process:
        - Connection Check on DB
        - Check if username exist
        - Compare Hashed Password
    Output: If Success then move to specific page else move to specfic page with error
    ```
- [ ] Create a file in util following th format `nameOfFunction.util.php`
    - in this demo we use `auth.util.php`
- [ ] Create a class and define `public` and `private` functions
```php
class ClassName
{
    public static function publicFunction() {
        // Code here
    }

    private static function privateFunction() {
        // Code here
    }

}
```
- [ ] create `init` which must check if a `session` has started
```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```
- [ ] create `login` which must hold login logic
- [ ] create a `user` which return the value of user hold inside the session
- [ ] create a `check` which just return if in session a `user` has been set, indicates someone is logged in
- [ ] create `logout` which clear out session, cookies logged

## 13. Creating Handler/Controller Codes
in this part the code controls the flow of interaction depends on their logic using handlers functionality to attain specific goals

- [ ] strategies what functionality must be done
    - [ ] login
        - [ ] receives data from front end
        - [ ] use handlers function (`Auth`) to login
        - [ ] respond accordingly based on the return value of util
        - [ ] must receive a feedback in form of page or messages
    - [ ] logout
        - [ ] trigger from front end
        - [ ] execute the command of logout from `Auth`

## 14. Connecting Backend and Frontend Codes
after preparing the utility and handlers and after the front end designed the page you can now fuse the 2 together. in this demo we will use the login with form and button as triggers.

Login:
- [ ] look for the form and indicate the `/handlers/auth.handler.php` will be used as the controller in method of `POST`.
- [ ] double check that the `name` of inputs matches your receiver in the handlers key in `$_POST[]`
```php
// ex.:
// index.php
<input id="username" name="username" type="text" required class="input">

// _.handler.php
$_POST['username']
```