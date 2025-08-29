# v2ui_bbs

```shell script
    composer require xuejd3/socialiteproviders-v2ui_bbs
```

## Installation & Basic Usage

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific instructions below.

## Service Provider

- Remove if you have added it already. ```Laravel\Socialite\SocialiteServiceProvider```

- Add ```\SocialiteProviders\Manager\ServiceProvider::class```.

### For example in Laravel 11+
In ```.bootstrap/providers.php```

```php
return [
    // a whole bunch of providers
    // remove 'Laravel\Socialite\SocialiteServiceProvider',
    \SocialiteProviders\Manager\ServiceProvider::class, // add
];
```

## Event Listener

### Laravel 11+
In Laravel 11, the default provider was removed. Instead, add the listener using the method on the facade, in your EventServiceProviderlistenEventAppServiceProvider

Note: You do not need to add anything for the built-in socialite providers unless you override them with your own providers.

```php
namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('v2ui_bbs', \SocialiteProviders\V2uiBbs\Provider::class);
        });
    }
}
```

#### Reference

- [Laravel 11 docs about events(opens new window)
  #](http://laravel.com/docs/11.0/events)

## Configuration setup

You will need to add an entry to the services configuration file so that after config files are cached for usage in production environment (Laravel command ) all config is still available.```artisan config:cache```

See your provider README for more infomation on the required config.

#### Add configuration to `config/services.php`.

```php
'v2ui_bbs' => [
    'client_id'     => env('V2UI_BBS_KEY'),
    'client_secret' => env('V2UI_BBS_SECRET'),
    'redirect'      => env('V2UI_BBS_REDIRECT_URI'),
    'guzzle'        => [
        'headers' => ['User-Agent' => 'xuejd3/socialiteproviders-v2ui_bbs'],
    ]
]
```

## Usage

- You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('v2ui_bbs')->redirect();
```

## Returned User fields

```php
$user = Socialite::driver('v2ui_bbs')->user();

$user->getId();
$user->getName();
$user->getEmail();
$user->getAvatar();
```
