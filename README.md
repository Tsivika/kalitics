Test Kalitics
========================== 
 
Install project
--------------------- 
Needs:
- php 7.4.3
- mysql 5.7
- node.js V10.19.0
- npm 6.14.4
- yarn 1.22.10

Installation of the various components:
```bash 
$ composer install
$ yarn install
``` 
Configuration
```bash 
The configuration is in env.dist to copy in your .env file
``` 
 
Create database:
```bash 
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force
``` 
 
Launch server symfony :
```bash 
$ php -S localhost:8000 -t public
``` 

Launch Webpack encore :
```bash 
$ yarn encore dev --watch
``` 
 
Different pages :
```bash 
localhost:8000/user/list => Users list
localhost:8000/chantier/list => Chantiers list
localhost:8000/chantier/detail => Chantier detail
localhost:8000/pointing/list => Chantiers list
``` 