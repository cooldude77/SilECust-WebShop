# upgrade/update
sudo apt update -y
sudo apt upgrade -y

#apache
sudo apt-get install -y apache2
sudo service apache2 start

## add user to apache group
sudo usermod -a -G www-data symfony

# website working
time curl -I http://yourpage.com | grep HTTP

#sudo sed -i 's/export APACHE_RUN_USER=www-data/#export APACHE_RUN_USER=www-data\nexport APACHE_RUN_USER=symfony/' /etc/apache2/envvars
#sudo sed -i 's/export APACHE_RUN_GROUP=www-data/#export APACHE_RUN_GROUP=www-data\nexport APACHE_RUN_GROUP=symfony/' /etc/apache2/envvars
#sudo service apache2 restart

#php
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update -y

sudo apt install php8.3 -y

sudo apt install php8.3-common php8.3-mysql php8.3-xml php8.3-xmlrpc php8.3-curl php8.3-gd php8.3-imagick php8.3-cli php8.3-dev php8.3-imap php8.3-mbstring php8.3-opcache php8.3-soap php8.3-zip php8.3-intl php8.3-raphf -y

sudo a2enmod php8.3 -y
  
sudo service apache2 restart 

# mariadb
sudo apt install mariadb-server -y
sudo service mariadb start

## set root password
echo "Please enter root password to be used for mariadb "
read -r -p rootPassword
sudo mysql -e "SET PASSWORD FOR root@localhost = PASSWORD(\"$rootPassword\");FLUSH PRIVILEGES;";
printf "ABC\n n\n n\n n\n y\n y\n y\n" | sudo mysql_secure_installation  

sudo mysql -u root  -e"CREATE USER 'dbAdmin'@'localhost' IDENTIFIED BY 'dbPassword'";
sudo mysql -u root -e"GRANT ALL PRIVILEGES ON *.* TO 'dbAdmin'@localhost IDENTIFIED BY 'dbPassword'";
sudo mysql -u root  -e"FLUSH PRIVILEGES";

sudo apt install adminer -y
sudo sudo a2enconf adminer -y
sudo service apache2 restart

# composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

sudo mv composer.phar /usr/bin/composer

# symfony 
curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash

sudo apt install symfony-cli
symfony check:requirements

#acl
sudo apt install acl

# Silecust


## Create directory, set permission
sudo mkdir /var/www/html/silecust
# shellcheck disable=SC2164
cd /var/www/html/silecust
sudo chown -R symfony:www-data /var/www/html/silecust
sudo setfacl -dRm u:symfony:rwX,g:www-data:rwX /var/www/html/silecust

# shellcheck disable=SC2164
cd /var/www/html/silecust
# clone repo
git clone https://github.com/cooldude77/SilECust-WebShop .


# copy environment file
cp -r .env .env.local
echo 'DATABASE_URL="mysql://dbAdmin:dbPassword@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"'>>.env.local

# add a user symfony
# Doctrine will complain if you use root user to create database
composer install -vvv

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
# the site is ready for work @
# http://localhost/silecust/public/index.php