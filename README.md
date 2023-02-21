# Waba notifications channel for Laravel 5.4+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/waba/notification-channel-waba.svg?style=flat-square)](https://packagist.org/packages/waba/notification-channel-waba)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/grupodkt/laravel-notification-channel-waba.svg?style=flat-square)](https://packagist.org/packages/waba/notification-channel-waba)

This package makes it easy to send [Waba notifications] with Laravel 5.4.

## Contents
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

``` bash
composer require grupodkt/notification-channel-waba
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Waba\WabaProvider::class,
],
```

### Setting up your Waba account

Add your Waba Account SID, Auth Token, and phoneNumberId to your `config/services.php`:

```php
// config/services.php
...
'waba' => [
    'url'   => env('WABA_URL'),
    'token' => env('WABA_TOKEN'),
    'phoneNumberId' => env('WABA_PHONE_NUMBER_ID'),
],
...
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\Waba\WabaChannel;
use NotificationChannels\Waba\WabaMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [WabaChannel::class];
    }

    public function toWaba($notifiable)
    {
        return (new WabaMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```

In order to let your Notification know which phone are you sending/calling to, the channel will look for the `celular` attribute of the Notifiable model. If you want to override this behaviour, add the `routeNotificationForWaba` method to your Notifiable model.

```php
public function routeNotificationForWaba()
{
    return $this->mobile;
}
```

### Available Message methods

#### WabaSmsMessage

- `from('')`: Accepts a phone to use as the notification sender.
- `content('')`: Accepts a string value for the notification body.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email lecheverria@grupodkt.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [GRUPODKT](https://github.com/grupodkt)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
