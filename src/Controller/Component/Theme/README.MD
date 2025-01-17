**How to add your theme to Silecust project**

Create a theme as a composer project
In your theme folder do not forget to run   
`composer require silecust/framework`  

Run this command on the command line after setting proper values  
```
composer config repositories.alpha-theme '{"type": "path", "url": "/var/www/html/project/alpha-theme"}' --file composer.json
composer require silecust/alpha-theme
```
Your theme is added to the project

