# upgrade/update
sudo apt update -y
sudo apt upgrade -y


#apache
sudo apt-get install -y apache2

sudo sed -i 's/export APACHE_RUN_USER=www-data/#export APACHE_RUN_USER=www-data\nexport APACHE_RUN_USER=symfony/' /etc/apache2/envvars
sudo sed -i 's/export APACHE_RUN_GROUP=www-data/#export APACHE_RUN_GROUP=www-data\nexport APACHE_RUN_GROUP=symfony/' /etc/apache2/envvars
sudo service apache2 restart

#php
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update -y

sudo apt install php8.3 -y

sudo apt install php8.3-common php8.3-mysql php8.3-xml php8.3-xmlrpc php8.3-curl php8.3-gd php8.3-imagick php8.3-cli php8.3-dev php8.3-imap php8.3-mbstring php8.3-opcache php8.3-soap php8.3-zip php8.3-intl -y

sudo a2enmod php8.3 -y
  
sudo service apache2 restart 

# mariadb
sudo apt install mariadb-server -y
sudo mysql -e "SET PASSWORD FOR root@localhost = PASSWORD('ABC');FLUSH PRIVILEGES;"; 
printf "ABC\n n\n n\n n\n y\n y\n y\n" | sudo mysql_secure_installation  
sudo systemctl start mariadb.service

sudo mysql -u root  -e"CREATE USER 'dbAdmin'@'localhost' IDENTIFIED BY 'dbPassword'";
sudo mysql -u root -e"GRANT ALL PRIVILEGES ON *.* TO 'dbAdmin'@localhost IDENTIFIED BY 'dbPassword'";
sudo mysql -u root  -e"FLUSH PRIVILEGES";

sudo apt install adminer -y
sudo sudo a2enconf adminer -y
sudo systemctl reload apache2

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

# Silecust
sudo mkdir /var/www/html/silecust
sudo chown -R symfony:symfony /var/www/html/silecust