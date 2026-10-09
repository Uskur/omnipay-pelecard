# Omnipay: pelecard

**Pelecard gateway for the Omnipay PHP payment processing library**

[![Latest Version on Packagist](https://img.shields.io/packagist/v/uskur/omnipay-pelecard.svg?style=flat-square)](https://packagist.org/packages/uskur/omnipay-pelecard)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Tests](https://github.com/uskur/omnipay-pelecard/actions/workflows/tests.yml/badge.svg)](https://github.com/uskur/omnipay-pelecard/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/uskur/omnipay-pelecard.svg?style=flat-square)](https://packagist.org/packages/uskur/omnipay-pelecard)


[Omnipay](https://github.com/thephpleague/omnipay) is a framework-agnostic,
multi-gateway payment processing library. This package provides Pelecard's
hosted iframe payment flow for Omnipay 3 on PHP 8.1 and later.

## Version compatibility

| Package | PHP | Omnipay |
| --- | --- | --- |
| `2.x` | `^8.1` | `^3.0` |
| `1.x` | `>=5.3` | `~2.0` |

## Install

Via Composer

```bash
composer require uskur/omnipay-pelecard
```

## Usage

The package provides the `Pelecard_Iframe` gateway. Pelecard's iframe flow
authorizes and captures in one operation, so both `purchase()` and
`authorize()` create the same hosted payment request.

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Pelecard_Iframe');
$gateway->setUser(getenv('PELECARD_USER'));
$gateway->setPassword(getenv('PELECARD_PASSWORD'));
$gateway->setTerminal(getenv('PELECARD_TERMINAL'));

$response = $gateway->purchase([
    'transactionId' => 'order-123',
    'amount' => '100.00',
    'currency' => 'ILS',
    'card' => $card,
    'returnUrl' => 'https://merchant.example/payments/return',
    'cancelUrl' => 'https://merchant.example/payments/cancel',
])->send();

if ($response->isRedirect()) {
    $response->redirect();
}
```

Supported currencies are `ILS`, `USD`, and `EUR`. The legacy `NIS` alias is
accepted and normalized to `ILS`.

### Test mode

Pelecard uses the production API endpoints with credentials assigned for
testing. Supply those credentials explicitly; the package does not contain
shared or hard-coded test credentials.

```php
$gateway->setTestMode(true);
$gateway->setTestUser(getenv('PELECARD_TEST_USER'));
$gateway->setTestPassword(getenv('PELECARD_TEST_PASSWORD'));
$gateway->setTestTerminal(getenv('PELECARD_TEST_TERMINAL'));
```

Keep credentials in environment variables or a secret manager. Do not commit
them to the repository.

For Pelecard QA accounts, `QAResultStatus` can be passed on an individual
payment request when a specific simulated outcome is needed. Status requests
report Pelecard's actual result and do not override it.

### Status and callbacks

Use the transaction reference returned by the initialization response when
requesting status:

```php
$status = $gateway->status([
    'transactionReference' => $transactionReference,
])->send();
```

Transaction references are recognized in Pelecard result data, callback
parameters (`PelecardTransactionId` or `TransactionId`), hosted-payment URLs,
and the originating request as a final fallback.

For general usage instructions, see the main
[Omnipay](https://github.com/thephpleague/omnipay) repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release announcements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/uskur/omnipay-pelecard/issues),
or better yet, fork the library and submit a pull request.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email burak@uskur.com.tr instead of using the issue tracker.

## Credits

- [Burak USGURLU](https://github.com/busgurlu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
