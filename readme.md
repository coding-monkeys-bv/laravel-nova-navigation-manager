# Navigation Manager for Laravel Nova

Create a navigation structure using the Navigation Manager. When creating a CMS using Laravel Nova, this package can be used for creating multiple navigations.

# Installation 

```bash
composer require voicecode/navigation-manager
```

Activating the tool via NovaServiceProvider

```php
public function tools()
{
    return [
        new \Voicecode\NavigationManager\NavigationManager()
    ];
}
```

Publish the migration

``` bash
php artisan vendor:publish --provider="Voicecode\NavigationManager\NavigationManagerServiceProvider"
```

Then run the migration

``` bash
php artisan migrate
```

## Localization

You can add these lines to your localization json files.

```
"Go Back": "Ga terug",
"Delete Navigation": "Navigatie Verwijderen",
"Navigation Manager": "Navigatiebeheer",
"Select A Navigation": "Selecteer Een Navigatie",
"Create A Navigation": "Navigatie Aanmaken",
"Navigation Name": "Naam",
"Maximum Levels": "Maximum niveau's",
"Add An Item": "Item Toevoegen",
"Open in": "Openen In",
"New Window": "Nieuw Venster",
"Current Window": "Huidig Venster",
"Item Name": "Item Naam"
```

## Caching

This package uses caching, so you don't have to fetch data from your database on every pageload. You can access the cached values for every single navigation using:

```
cache('navigation_'.$id);
```


## Known issues

We're familiar with the issue which occurs when setting a max level higher than 2 not to work correctly. 
If you'd like, we're happy to accept PR's ;-)

## About Voicecode B.V.

Voicecode B.V.
Faradaystraat 11
2014 EN Haarlem
The Netherlands
