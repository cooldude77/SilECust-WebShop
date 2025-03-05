# Common steps

#

Go to installation directory

```
cd /var/www/html/silecust
cp vendor/silecust/web-shop/src/Resources/misc/security.yaml.dist config/packages/security.yaml
cp vendor/silecust/web-shop/src/Resources/misc/twig.yaml.dist config/packages/twig.yaml
cp vendor/silecust/web-shop/src/Resources/misc/services.yaml.dist config/services.yaml
cp vendor/silecust/web-shop/src/Resources/misc/routes.yaml.dist config/routes.yaml
cp vendor/silecust/web-shop/src/Resources/misc/.htaccess_dev .htaccess
```

# Set parameters ( required)

In order for application to work
in `.env.dev.local`( for dev ) and in `.env.test.local` (for test)set your database like this ( choose your own
database)

````
DATABASE_URL="mysql://dbAdmin:dbPassword@127.0.0.1:3306/app?charset=utf8mb4"
````

set these parameters

````
SILECUST_SIGN_UP_EMAIL_FROM_ADDRESS="replace with your value"  
SILECUST_SIGN_UP_EMAIL_HEADLINE="replace with your value"
SILECUST_DEFAULT_COUNTRY=IN or "IN" ( any one of it for now)
````

# install silecust package

In your composer.json
set
`
"minimum-stability": "dev",
`

```
composer require symfony/web-shop
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console importmap:require tom-select/dist/css/tom-select.default.css
``` 

# How to see your website working

On the prompt, use

```
symfony server:start -d --no-tls

```

Your website should be working at
```http://localhost:8000```

## Create an employee with superuser privilege

`php bin/console silecust:user:super:create`
_use this login email /password for logging in as employee_

## Optional: create a customer

`php bin/console silecust:customer:sample:create`
_use this login email /password for logging in as customer_

## Optional: To create sample product, price and test data

`php bin/console silecust:dev:data-fixture:create`

or install [adminer](https://www.adminer.org/) to add your own data

````
sudo apt install adminer
sudo a2enconf adminer.conf
sudo service apache2 restart
````

## htaccess file

In dev environment to load css and js files,
copy included .htaccess_dev to .htaccess in your project and follow the instructions in the file (TBD in the script)
