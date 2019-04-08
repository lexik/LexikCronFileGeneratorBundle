LexikCronFileGeneratorBundle
============================

[![Build Status](https://travis-ci.org/lexik/LexikCronFileGeneratorBundle.svg?branch=master)](https://travis-ci.org/lexik/LexikCronFileGeneratorBundle)

Installation
============

Documentation
-------------

The bulk of the documentation is stored in the [`Resources/doc`](Resources/doc/index.md) directory of this bundle:

* [Getting started](Resources/doc/index.md#getting-started)
  * [Configuration](Resources/doc/index.md#configuration)
  * [Command](Resources/doc/index.md#command)
  * [Bonus](Resources/doc/index.md#bonus)

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require lexik/cron-file-generator-bundle
```

Applications that don't use Symfony Flex
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
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Lexik\Bundle\LexikCronFileGeneratorBundle\LexikCronFileGeneratorBundle(),
        ];

        // ...
    }

    // ...
}
```
