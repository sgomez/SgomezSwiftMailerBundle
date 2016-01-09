# SgomezDebugSwiftMailerBundle

This bundles provides a new DataCollector to the Symfony Web Profiler and a Behat Context to debug the spooled
messages sent with SwiftMailer.

## Installation

### Step 1: Download the bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require --dev sgomez/swiftmailer-bundle dev-master
```

This command requires you to have Composer installed globally, as explained
in the [Composer documentation](https://getcomposer.org/doc/00-intro.md).


### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in the
`app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Sgomez\DebugSwiftMailerBundle\SgomezDebugSwiftMailerBundle(),
        );
    }

    // ...
}
```

### Step 3: Enable the Behat Extension


Load the extension of the bundle by adding this configuration in your `behat.yml` file:

```yaml
default:
    extensions:
        Behat\Symfony2Extension: ~
        Sgomez\DebugSwiftMailerBundle\ServiceContainer\Extension: ~
```

## Acknowledgement

Work based on the next bundles and articles:

- [Extra Contexts for Behat](https://github.com/Behat/CommonContexts)
- [MailCatcher for PHP](https://github.com/alexandresalome/mailcatcher)
- [Tester l'envoi de mail avec Behat dans une application Symfony2](https://www.elao.com/fr/blog/tester-lenvoi-de-mail-avec-behat-dans-une-application-symfony2)
- [Testing emails with checking spool directory in behat](http://www.inanzzz.com/index.php/post/nv3f/testing-emails-with-checking-spool-directory-in-behat)
