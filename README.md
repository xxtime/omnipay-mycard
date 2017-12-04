# Omnipay: MyCard

**MyCard driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements MyCard support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "xxtime/omnipay-mycard": "~1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Support Gateways

The following gateways are provided by this package:

* MyCard (MyCard Web Checkout)

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Usage For Purchase


```php
// Initialize
$config = [
    'appId'  => 'MyCard_ServiceId',
    'appKey' => 'MyCard_Key'
];
$gateway = Omnipay::create('MyCard');
$gateway->initialize($config);

// Send purchase request
$response = $gateway->purchase(
    [
        'amount'        => '1.00',
        'currency'      => 'TWD',
        'description'   => 'product description',
        'transactionId' => mt_rand(100000, 999999),
    ]
)->send();

// Process response
if ($response->isRedirect()) {
    // doing something here
    // $token = $response->getToken();
    // $data = $response->getData();
    // $transactionReference = $response->getTransactionReference();
    $response->redirect();
}
elseif ($response->isSuccessful()) {
    // doing something here
    print_r($response);
}
else {
    echo $response->getMessage();
}

```


## Usage For Notify Or Return
```php
// Notify
$config = [
    'appId'  => 'MyCard_ServiceId',
    'appKey' => 'MyCard_Key'
];
$gateway = Omnipay::create('MyCard');
$gateway->initialize($config);
try {
    $response = $gateway->acceptNotification()->send();

    // set token (which saved when send a purchase @see Usage For Purchase)
    // $transactionId = $response->getTransactionId();
    $response->setToken('MyCard_AuthCode');

    // doing something here

    // confirm
    $response->confirm();
    
    // get more transaction
    // you should save the info for further compare
    // $response->getData();
} catch (\Exception $e) {
    // failed logs
}
```


## Usage For Query
```php
$gateway = Omnipay::create('MyCard');
$gateway->initialize($config);
$response = $gateway->fetchTransaction(['token' => 'MyCard_AuthCode'])->send();
// further functions below
$response->isSuccessful();
$response->getTransactionId();
$response->getAmount();
$response->getCurrency();
$response->getCard();           // card number
$response->getMessage();        // message response from MyCard query api
$response->getData();           // output RAW data
```


## Usage For Compare

```php
$compare = $gateway->compareTransaction();

// Get Params, Exp: ["card"=>"MC123456"] or ["startTime"=>1500000000,"endTime"=>1560000000];
$params = $compare->getParams();

// Get data from database with the $params above
$data = [
    [
        'type'                 => 'INGAME',         // INGAME, COSTPOINT Or Something Else
        'transactionId'        => '12345678',       // My Transaction Id
        'transactionReference' => 'MC973924',       // MyCard Transaction Id
        'card'                 => 'card number',    // Card Number Or Something Else
        'amount'               => '50.00',          // Amount
        'currency'             => 'TWD',            // Currency
        'account'              => 'user123',        // User Id
        'time'                 => 1500000000,       // Timestamp
    ],
    // ... more
];

// Output data
$compare->setData($data)->send();
```


## Related

[Project Home Page](https://github.com/xxtime/omnipay-mycard)  
[About Usage](https://www.ctolib.com/omnipay.html)  
[MyCard Official Website](https://www.mycard520.com.tw)  


## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/xxtime/omnipay-mycard/issues),
or better yet, fork the library and submit a pull request.
