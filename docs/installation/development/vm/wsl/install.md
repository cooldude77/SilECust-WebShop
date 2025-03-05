# Install Silecust in Windows using wsl
WSL may be faster than oracle virtual box in windows environment

## Install WSL first
Enable WSL first

Open a powershell terminal and type
``wsl --install``

## Install Ubuntu 22 
### Install from marketplace
[Windows marketplace](https://www.microsoft.com/store/productId/9PN20MSR04DW?ocid=pdpshare)

### Install using comand line  
First download using powershell

`curl (("https://cloud-images.ubuntu.com", "releases/22.04/release-20231130" "ubuntu-22.04-server-cloudimg-amd64-root.tar.xz") -join "/") --output ubuntu-22.04-wsl-root-tar.xz`  

Then install  

`wsl --import <your distro name> "<Your windows folder path>" ubuntu-22.04-wsl-root-tar.xz`  

example: 

`` wsl --import webshop-staging "c:\wsl" ubuntu-22.04-wsl-root-tar.xz ``
## Run script
At the powershell , type  

`` wsl -d  <your distro name> ``

## Create a new user
At the Linux prompt  , type or copy-paste
```
useradd -m symfony -s /bin/bash
passwd symfony
usermod -a -G sudo symfony
su - symfony
```

## Run this script

[script](script.sh)

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