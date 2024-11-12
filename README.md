# Installation
To install the program click the green "Code" button you can either use the download ZIP method or cloning using the URL

### Donwloading ZIP

Click the "download ZIP option and extract it.
Open the folder using Visual Studio Code which contains all the files

### Install dependencies

Our program requires composer in order to work
Execute the following command using either the VSC terminal or Windows Power Shell
Note: This requires composer to be installed previously for more infomation see: https://getcomposer.org/
Also make sure to be executing the command at the folder which contains all the files
```sh
composer install
```

### Install doctrine

Doctrine is a library that allows connection with databases, which we used it to manage our CRUD and its installation is required in order to make our code functional
Execute the following command using either VSC terminal or Windows Power Shell
```sh
composer require symfony/orm-pack
```

### Configure .env file

.env files contains the link to the connection of your database, make sure to uncomment the database version that you are using and change to your own values

### Create the database
Execute the following command to create a database
```sh
php bin/console doctrine:database:create
```

### Execute the migration
Execute the following command to create the table and its fields
```sh
php bin/console doctrine:migrations:migrate
```
After this step you can add as many nurses as you like.

### Download Postman
Our project has not front-end, so we need a software which sends requests, in our case, we used Postman.
You have two ways to download Postman
<br>1: As a software, for this installation check https://www.postman.com/
<br>2: As a VSC extension search "Postman.postman-for-vscode" at VSC extensions and it will show you
After the installation you are asked to create an account





