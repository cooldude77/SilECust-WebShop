**How To Install Silecust webshop ( temporarily )**

On your webserver, open a terminal 
1. Install [composer](https://getcomposer.org/)
2. Clone [SilECust](https://github.com/cooldude77/SilECust-WebShop) in a directory which can be served as web page ( like `/var/www/html/web-app`)
3. Install all dependencies, php8+ , and mariadb
4. Add .env.local or env.test or both and create database parameter for e.g. `DATABASE_URL="mysql://dbAdmin:dbPassword@127.0.0.1:3306/dev_admin?serverVersion=10.6.16-MariaDB"`. Right now , only mariadb has been tested upon.
5. Run `composer install` or `composer install -vvv` for detailed process
6. Run `php bin/console doctrine:database:create` : Creates database based on .env file
8. Run `php bin/console doctrine:migrations:migrate` : creates tables in database
9. You should see the application working in http\(s\)://YourServer/web-app/public/index.php
   -- more steps pending --
   -- deployment steps pending -- 
10. To create a user with SUPER_ADMIN privilege ( super admin can create other employees ) . Type command `php bin/console silecust:user:super:create` and provide the details
11. Now you can log in as super admin_

<p>
   Please note that for now there is no release version of the repo and it is not fit for production. DO NOT USE IT ON PRODUCTION SERVER.
</p>

**Pending tasks**
<p>
<a href="https://docs.google.com/spreadsheets/d/1VdEItM5627GQX1xD8RuF6sroZU90rYMgpzv3eR0kHc4/edit?usp=sharing">Tasks to be done(Phew!!)</a>
</p>

**References:**
Files for testing taken from creations by
<a href="https://unsplash.com/photos/apples-and-bananas-in-brown-cardboard-box-8RaUEd8zD-U?utm_content=creditShareLink&utm_medium=referral&utm_source=unsplash">Grocery _1920</a>
