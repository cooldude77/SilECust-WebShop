## Use this for routing in the app

In `routes.yaml`

```
silecust_web_shop:
    resource:
        path: '@SilecustWebShopBundle/src/Controller/'
        namespace: Silecust\WebShop\Controller
    type: attribute
```

## for twig use this setting

In `twig.yaml`

```
twig:
    form_themes: [ 'bootstrap_5_layout.html.twig' ]
```

## Run these to include javascript

`composer require symfony/stimulus-bundle`
`composer require symfony/ux-autocomplete`

## Copy .htaccess_dev to .htaccess

## Copy contents of `security.yaml.dist` to `security.yaml`