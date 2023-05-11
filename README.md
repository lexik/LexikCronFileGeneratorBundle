LexikCronFileGeneratorBundle
============================

[![Build Status](https://travis-ci.org/lexik/LexikCronFileGeneratorBundle.svg?branch=master)](https://travis-ci.org/lexik/LexikCronFileGeneratorBundle)
[![Coverage Status](https://coveralls.io/repos/github/lexik/LexikCronFileGeneratorBundle/badge.svg?branch=master)](https://coveralls.io/github/lexik/LexikCronFileGeneratorBundle?branch=master)

This symfony bundle provides cron file generation utilities.

Installation
============

Documentation
-------------

The bulk of the documentation is stored in the [`Resources/doc`](Resources/doc/index.md) directory of this bundle:

* [Getting started](Resources/doc/index.md#getting-started)
  * [Configuration](Resources/doc/index.md#configuration)
  * [Command](Resources/doc/index.md#command)
  * [Bonus](Resources/doc/index.md#bonus)

Applications using Symfony Flex
----------------------------------

Open a command console at your project directory and execute :

```console
$ composer require lexik/cron-file-generator-bundle
```

Applications not using Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require lexik/cron-file-generator-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
<?php
// config/bundles.php

return [
// ...
    Lexik\Bundle\CronFileGeneratorBundle\LexikCronFileGeneratorBundle::class => ['all' => true],
// ...
];
```
