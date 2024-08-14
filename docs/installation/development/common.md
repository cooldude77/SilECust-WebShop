# Common steps
After installation is complete and  database has been created , you can follow these steps to start working on your development environment

**Go to installation directory**

`cd /var/www/html/silecust`

**To create an employee with superuser privilege**

`php bin/console silecust:user:super:create`
_use this login email /password for logging in as employee_

**Optional: creates a customer**

`php bin/console silecust:customer:sample:create`
_use this login email /password for logging in as customer_

**Optional: To create sample product, price and test data**

`php bin/console silecust:dev:data-fixture:create`

