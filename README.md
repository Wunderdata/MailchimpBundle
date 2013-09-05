# MailchimpBundle

Symfony2 Bundle for Mailchimp 2.0 API and Export 1.0 API

[![Build Status](https://travis-ci.org/MikeRoetgers/MailchimpBundle.png?branch=master)](https://travis-ci.org/MikeRoetgers/MailchimpBundle)

## Installation

The bundle can be installed via [Composer](http://getcomposer.org). You can find it on [Packagist](https://packagist.org/packages/wunderdata/mailchimp-bundle).

## Configuration

Just add the following lines to your `app/config/config.yml`

```
wunderdata_mailchimp:
    apikey: verysecretkey-us1
    opts:
        debug: false
        timeout: 600
```

## Usage

The bundle uses the official PHP implementation of the Mailchimp 2.0 API. There is no wrapper class or anything around it. They have an [example project](https://github.com/mailchimp/mcapi2-php-examples) to get you started.

You can get a ready-to-use instance of the Mailchimp client from the container:

```php
// example action in a controller

public function exampleAction()
{
    $client = $this->get('wunderdata_mailchimp.client');
}
```

Unfortunately there is no official PHP implementation of the Export 1.0 API. Its implementation can be found in the class `\Wunderdata\MailchimpBundle\Client\ExportClient`. It is using [Buzz](https://github.com/kriswallsmith/Buzz) internally to perform the needed HTTP requests.

You can get a ready-to-use instance of the export client from the container:

```php
// example action in a controller

public function exampleAction()
{
    $client = $this->get('wunderdata_mailchimp.export_client');
}
```