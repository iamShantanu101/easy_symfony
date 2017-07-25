## EasyEngine CRUD operations using symfony/console

This repository contains the code for implementing EasyEngine like CRUD operations using symfony/console.

# Note : Kindly make sure to set environment variable `WEBROOT_PATH` before trying these commands. This has been done due to the fact that this directory might vary from system to system and environment to environment. Also kindly note that these commands currently does not create actual files, they just create a text file containing all the details.

## Examples:

1. Create a simple html site:
```
php bin/console ee-site:create example.com --html 
```

2. Create a php site:
```
php bin/console ee-site:create example.com --php
php bin/console ee-site:create example.com --php7
```

3. Create a php + mysql site
```
php bin/console ee-site:create example.com --mysql
```
4. Create various Wordpress sites:
```
php bin/console ee-site:create example.com --wp // creates WP site with php5.6, mysql5.6.
php bin/console ee-site:create example.com --wpredis // creates WP site with redis cache
php bin/console ee-site:create example.com --wpfc //creates WP site with FasCGI cache
```
5. Delete a website:
```
php bin/console ee-site:delete example.com
php bin/console ee-site:delete example.com --files // deletes wesite webrot only
php bin/console ee-site:delete example.com --db // deletes DB of website only
php bin/console ee-site:delete example.com --no-prompt // deletes complete site without any prompt
```

6. List all files:
```
php bin/console ee-site:list // lists all the files present in the sites directory
```
7. Show website:
```
php bin/console ee-site:show example.com // shows the text file of website present in website webroot
```

## TODO:

1. Update the websites.
