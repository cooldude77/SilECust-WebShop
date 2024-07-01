**How To Install Silecust webshop ( temporarily )**

On your webserver, open a terminal 
1. Install [composer](https://getcomposer.org/)
2. Clone [SilECust](https://github.com/cooldude77/SilECust-WebShop) in a directory which can be served as web page ( like `/var/www/html/web-app`)
3. Install all dependencies, php8+ , and mariadb 
4. Run `composer install` or `composer install -vvv` for detailed process
5. Create files .env.local and .env.test as needed
6. Set up database parameter in .env.local or .env.test as needed
7. Run php `bin/console doctrine:migrations:create` : Creates database based on .env file
8. Run php `bin/console doctrine:migrations:migrate` : creates tables in database
9. You should see the application working in http\(s\)://YourServer/web-app
   -- more steps pending --
   -- deployment steps pending -- 


**References:**
Files for testing taken from creations by
<a href="https://unsplash.com/photos/apples-and-bananas-in-brown-cardboard-box-8RaUEd8zD-U?utm_content=creditShareLink&utm_medium=referral&utm_source=unsplash">Grocery _1920</a>
