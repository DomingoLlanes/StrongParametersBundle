Strong Parameters Bundle
============

This bundle allow to simulate Strong Parameters from Rails, inside Symfony Controllers.

You can simply allow and deny items from parameters.

This bundle is really useful for APIs, but can be used in any application.

**ALERT:** this is an early version.

Tested only in Symfony 3.4.

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require domingollanes/strong-parameters-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

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
        $bundles = array(
            // ...
            new DomingoLlanes\StrongParametersBundle\StrongParametersBundle(),
        );

        // ...
    }

    // ...
}
```

Configuration
============

The bundle accepts two configurations, you can override them in config.yml. But it have defaults values.

```yaml
// config.yml

strong_parameters:
  resource: '%kernel.project_dir%/app/Resources/parameters/'
  exceptions: false
```

Configuring the permitted parameters
------------

You can easily configure namespaces of permitted parameters, for example:

```yaml
// app/Resources/parameters/user.yml

allow:
  test:
  test2:
    test1:
      t3:
      t2:
deny:
  - test4
```

That yaml accepts this parameters in Controller action:

```
array:2 [
  "test" => "testValue"
  "test2" => array:1 [
    "test1" => array:2 [
      "t2" => "t2Value",
      "t3" => "t3Value"
    ]
  ]
]
```

How to use
============

```php

// src/AppBundle/Controller/DefaultController.php

// ...

    public function testAction(Request $request, ParametersService $parametersService)
    {
        // ...

        // For $_GET parameters
        $params = $parametersService->permitParameters('user', $request->query->all());

        // For $_POST parameters
        $params = $parametersService->permitParameters('user', $request->request->all());

        // For Content/json parameters
        $params = $parametersService->permitParameters('user', json_decode($request->getContent(), true));
        // ...
    }
    
// ...

```
