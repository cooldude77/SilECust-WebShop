Install Silecust on a virtual machine.
======================================

This is tested on Windows 11 and it creates an Unbuntu 22.04 machine

* Install [Oracle VirtualBox](https://www.virtualbox.org/wiki/Downloads)
* Install [Vagrant](https://www.vagrantup.com/)
* Create a folder in a directory ( `c:\silecust` for example)
* Extract the [Vagrant Zip File](https://cooldude77.github.io/SilECust-WebShop/docs/installation/vm/virtualbox/vagrant.zip) into that directory
* Open command prompt in the folder "vagrant" ( better, an admin command prompt ) and type > `vagrant up`

Wait for the process to complete

* In the same command prompt type > `vagrant ssh` You will see the linux command prompt

Enter these codes

**Go to installation directory**

`cd /var/www/html/silecust`

**To create an employee with superuser privilege**

`php bin/console silecust:user:super:create`
_use this login email /password for logging in as employee_

**Optional: creates a customer**

`php bin/console silecust:customer:sample:create`
_use this login email /password for logging in as customer_

You can navigate the website at [Url](http://192.168.200.100/silecust/public/index.php)

Troubleshooting
---------------

In case you cannot reach the site because of firewall or any other reason, install a GUI on your newly created server

1. On the same command prompt ( if not already inside the server in which case you will see a $ command prompt. Skip this step and proceed to next)

> `vagrant ssh`

This will open a prompt terminal

2. Copy these lines and press enter

`sudo sudo apt install slim`  
`sudo apt install ubuntu-desktop`  
`sudo service slim start`  


Now go make a coffee for yourself . It will be sometimes before GUI gets installed  

3. Once gui gets installed you will see a user / password screen  

Enter username as `vagrant`Enter password as `vagrant`

4. Once inside , open the firefox browser and copy this address to the address bar

`http://localhost/silecust/public/index.php`

You should be able to see the site
