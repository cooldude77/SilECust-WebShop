# Install Silecust in Windows using wsl
WSL may be faster than oracle virtual box in windows environment

## Install WSL first
Enable WSL first

Open a terminal and type
wsl --install

## Install Ubuntu 22 
[Windows marketplace](https://www.microsoft.com/store/productId/9PN20MSR04DW?ocid=pdpshare)

One way to install is   
First download using powershell

`curl (("https://cloud-images.ubuntu.com", "releases/22.04/release-20231130" "ubuntu-22.04-server-cloudimg-amd64-root.tar.xz") -join "/") --output ubuntu-22.04-wsl-root-tar.xz`  

Then install  

`wsl --import <your distro name> "<Your windows folder path>" ubuntu-22.04-wsl-root-tar.xz`

## Run script
Open a prompt using WSL

## create a new user
```
useradd -m symfony -s /bin/bash
passwd symfony
usermod -a -G sudo symfony
su - symfony
```
and copy or run this [script](script.sh)

**Your installation is ready for further configuration**

## For Further testing help
Follow these steps
[Common configuration](../../../development/common.md)

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