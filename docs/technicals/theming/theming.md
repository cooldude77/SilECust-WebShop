When you install Silecust , you will see basic white vanilla version of the app. The app is functional however, the looks may not be of your liking or you would like to modify it . 

## Create a new theme ,
Create a composer theme in your desired folder 
and then 
`composer init`  

Then use the example of composer.json in base theme , copy all the required packages
Install the packages using 
`composer install`

Add some templates under the template folder. If you don't have any business logic you can just prefix your bundle name in front of the view path as show in the Silecust Base Template app. 

Put this code in event subscriber  


```
if ($this->environment->getLoader()->exists("@SilecustBaseTheme/{$event->getView()}"))  

$event->setView("@SilecustBaseTheme/{$event->getView()}");```
```

To install a theme into the app:

If your theme is not in packagist
First add repo to the composer.json in the silecust app
```
composer config repositories.<your-theme-name> '{"type": "path", "url": "<path to theme root folder>"}' --file composer.json

# load it into your app
composer require <your name space>/<your package>
```
If you have added your theme to packagist just use "require"