# Install Silecust on vagrant vm.
======================================

This is tested on Windows 11 and it creates an Unbuntu 22.04 machine

* Install [Oracle VirtualBox](https://www.virtualbox.org/wiki/Downloads)
* Install [Vagrant](https://www.vagrantup.com/)
* Create a folder in a directory ( `c:\silecust` for example)
* Extract the [Vagrant Zip File](https://cooldude77.github.io/SilECust-WebShop/docs/installation/vm/virtualbox/vagrant.zip) into that directory
* Open command prompt in the folder "vagrant" ( better, an admin command prompt ) and type  
`vagrant up`

Wait for the process to complete

* In the same command prompt type   
`vagrant ssh`  
You will see the linux command prompt

You can navigate the website at [Url](http://192.168.200.100/silecust/public/index.php)

## For Further testing help
Follow these steps
[Common configuration](../../../development/common.md)

## Troubleshooting

In case you cannot reach the site because of firewall or any other reason, install a GUI on your newly created server

1. On the same command prompt ( if not already inside the server in which case you will see a $ command prompt. Skip this step and proceed to next)

`vagrant ssh`

This will open a linux prompt terminal($)

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
