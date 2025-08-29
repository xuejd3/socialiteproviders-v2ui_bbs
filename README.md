# Socialite Providers

A Collection of Providers for Laravel Socialite

## Simplicity First

Minimal setup with a familiar structure helps you focus on developing your newest product.

## Seamless Integration

Following the same API as Laravel Socialite allows for a seamless integration.

## Powerful Support

The Socialite Manager grants you access to both custom and official providers.

## 1. Installation

```shell script
// This assumes that you have composer installed globally
composer require xuejd3/socialiteproviders-v2ui_bbs
```

## 2. Service Provider

- Remove if you have added it already.Laravel\Socialite\SocialiteServiceProvider

- Add .\SocialiteProviders\Manager\ServiceProvider::class

### For example in Laravel 11+
In ```.bootstrap/providers.php```

```php
return [
    // a whole bunch of providers
    // remove 'Laravel\Socialite\SocialiteServiceProvider',
    \SocialiteProviders\Manager\ServiceProvider::class, // add
];
```

### For example in Laravel 10 or below
In ```.config\app.php```

```php
'providers' => [
    // a whole bunch of providers
    // remove 'Laravel\Socialite\SocialiteServiceProvider',
    \SocialiteProviders\Manager\ServiceProvider::class, // add
];
```

- Note: If you would like to use the Socialite Facade, you need to [install the official package](https://github.com/laravel/socialite).

## 3. Event Listener

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

### Laravel 10 or below

- Add event to your array in ```SocialiteProviders\Manager\SocialiteWasCalledlisten[]app/Providers/EventServiceProvider```

- Add your listeners (i.e. the ones from the providers) to the that you just created.```SocialiteProviders\Manager\SocialiteWasCalled[]```

- Add the listener for your provider to the array. In our example, this is .```SocialiteProviders\\Zoho\\ZohoExtendSocialite@handle```,

- Note: You do not need to add anything for the built-in socialite providers unless you override them with your own providers.

For example with v2ui_bbs:

For example:

```php
/**
 * The event handler mappings for the application.
 *
 * @var array
 */
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // add your listeners (aka providers) here
        'SocialiteProviders\V2uiBbs\V2uiBbsExtendSocialite@handle',
    ],
];
```

#### Reference

- [Laravel docs about events](http://laravel.com/docs/master/events)
- [Laracasts video on events in Laravel 5](https://laracasts.com/lessons/laravel-5-events)

## 4. Configuration setup

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

## 5. Usage

- [Laravel docs on configuration](http://laravel.com/docs/master/configuration)

- You should now be able to use it like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('v2ui_bbs')->redirect();
```

```php
$user = Socialite::driver('v2ui_bbs')->user();

$user->getId();
$user->getName();
$user->getEmail();
$user->getAvatar();
```

### Lumen Support

You can use Socialite providers with Lumen. Just make sure that you have facade support turned on and that you follow the setup directions properly.

**Note:** If you are using this with Lumen, all providers will automatically be stateless since **Lumen** does not keep track of state.

Also, configs cannot be parsed from the `services[]` in Lumen. You can only set the values in the `.env` file as shown exactly in this document. If needed, you can also override a config (shown below).

### Stateless

- You can set whether or not you want to use the provider as stateless. Remember that the OAuth provider (Twitter, Tumblr, etc) must support whatever option you choose.

**Note:** If you are using this with Lumen, all providers will automatically be stateless since **Lumen** does not keep track of state.

```
// to turn off stateless
return Socialite::driver('v2ui_bbs')->stateless(false)->redirect();

// to use stateless
return Socialite::driver('v2ui_bbs')->stateless()->redirect();

```

### Overriding a config

If you need to override the providers environment or config variables dynamically anywhere in your application, you may use the following:

```php
$clientId = "secret";
$clientSecret = "secret";
$redirectUrl = "http://yourdomain.com/api/redirect";
$additionalProviderConfig = [
    // Add additional configuration values here.
];
$config = new \SocialiteProviders\Manager\Config(
    $clientId,
    $clientSecret,
    $redirectUrl,
    $additionalProviderConfig
);

return Socialite::with('v2ui_bbs')->setConfig($config)->redirect();
```

### Retrieving the Access Token Response Body

Laravel Socialite by default only allows access to the `access_token`. Which can be accessed via the `\Laravel\Socialite\User->token` public property. Sometimes you need access to the whole response body which may contain items such as a `refresh_token`.

You can get the access token response body, after you called the `user()` method in Socialite, by accessing the property `$user->accessTokenResponseBody`;

```php
$user = Socialite::driver('v2ui_bbs')->user();
$accessTokenResponseBody = $user->accessTokenResponseBody;
```

#### Reference

- [Laravel Socialite Docs](https://github.com/laravel/socialite)
