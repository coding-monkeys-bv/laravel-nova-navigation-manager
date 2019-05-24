# Navigation Manager for Laravel Nova

Create a navigation structure using the Navigation Manager.

# Installation 

```bash
composer require voicecode/laravel-nova-navigation-manager
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

