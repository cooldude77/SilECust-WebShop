# Install Silecust in Windows using wsl
WSL may be faster than oracle virtual box in windows environment

## Install WSL first
Enable WSL first

Open a terminal and type
wsl --install

## Install Ubuntu 22 
[Windows marketplace](https://www.microsoft.com/store/productId/9PN20MSR04DW?ocid=pdpshare)

## Run script
Open a prompt using WSL
and copy or run this [script](script.sh)

Your installation should be running at the WSL address and silecust/public directory
## Install debugger quickly 

```
sudo apt-get install php-xdebug   
sudo cat >> /etc/php/8.3/apache2/php.ini
[XDebug]
zend_extension=xdebug
xdebug.mode=debug
^D
sudo service apache2 restart
```