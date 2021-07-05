# msm-az-sms
SMS library for msm.az

With [composer](https://getcomposer.org), require:

`composer require baxi/msm-az-sms`

```php
<?php
use baxi\MsmAzSms\MsmAzSms;

$loader = require __DIR__ . '/vendor/autoload.php';

$sms = new MsmAzSms();

$sms->setUsername('usernameapi');
$sms->setPassword('password');
$sms->setFrom('SENDERNAME');
$sms->setMessage("Message to send");
$sms->addTo("0501112233");

$sms->sendSms();
```
