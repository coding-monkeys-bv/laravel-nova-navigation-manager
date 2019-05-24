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

## Database migrations

This will create a navigations and navigation_items table.

``` bash
php artisan vendor:publish --provider="Voicecode\NavigationManager\NavigationManagerServiceProvider"
```

``` bash
php artisan migrate
```

