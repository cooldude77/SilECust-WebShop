# Panels

## Concepts

The idea of making everything go through a panel controller is to unify the core block of application.
Now every module can just set the methods and classes and call panels which will display the home page.

## Types of Panels

The panels are

- Header -> Anything that goes into the toolbar
- Content -> The actual content of the process
- Head -> All things useful for browser like title and hrefs
- Sidebar -> For specific sidebars
- Footer -> For adding footer.

## Example of things that can be done using panels

- One possible benefit that sidebar can be swapped based on role and Url. So customer could see different sidebar than an
  employee.
- Also, different sidebars are needed for front and backends.
- An employee can have his or her own framework
  which will call the main panel framework and thus reduce the effort needed to render the data.
- You can even hide panels conditionally. If you don't provide a class and method to display panels, it won't be rendered

You can open `PanelMainController` class to see that flow from your business area to components happen through forward method. The base template is also set by you and is used by controller in its twig template to just render the output of various forwards.

The individual url's are also callable seprately but use of Panel Controller gives it context ( like an admin url vs 'my' url ) .

* There will be more improvements in future.*

The responses are collected and sent to the template

```
   $response = $this->render(
            '@SilecustWebShop/common/ui/panel/panel_main.html.twig', [
                'headResponse' => $headResponse->getContent(),
                'headerResponse' => $headerResponse->getContent(),
                'contentResponse' => $contentResponse->getContent(),
                'sideBarResponse' => $sideBarResponse->getContent(),
                'footerResponse' => $footerResponse->getContent(),
                'request' => $request]
        );
```


The downside is that some level of inheritence is lost in the base template overall but inheritence still works in individual panels.

Also one downside is that `request` parameter has to be passed to all called controllers
