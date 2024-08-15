# Common steps
After installation is complete and  database has been created , you can follow these steps to start working on your development environment

Go to installation directory

`cd /var/www/html/silecust`

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

# Set parameters ( required)
 In order for application to work , especially pricing 
 in `.env.local`( for dev ) and in `.env.test.local` (for test)
 
set these parameters
````
SILECUST_SIGN_UP_EMAIL_FROM_ADDRESS="replace with your value"  
SILECUST_SIGN_UP_EMAIL_HEADLINE="replace with your value"
SILECUST_DEFAULT_COUNTRY=IN or "IN" ( any one of it for now)
````