# Slim Framework Plates View

This is a Slim Framework view helper built on top of the Plates templating component. You can use this component to create and render templates in your Slim Framework application.

## Install

Via [Composer](https://getcomposer.org/)

```bash
$ composer require blat/slim-plates
```

Requires Slim Framework 4, Plates 3 and PHP 7.4 or newer.

## Usage

```php
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Plates;
use Slim\Views\PlatesExtension;

require __DIR__ . '/vendor/autoload.php';

// Create Container
$container = new Container();
AppFactory::setContainer($container);

// Set Plates View in Container
$container->set('view', function () {
    return new Plates(__DIR__ . '/../templates');
});

// Create App
$app = AppFactory::create();

// Add Plates Extension Middleware
$app->add(new PlatesExtension($app));

// Define named route
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $this->get('view')->render($response, 'profile', [
        'name' => $args['name']
    ]);
})->setName('profile');

// Run app
$app->run();
```

## Custom template functions

`PlatesExtension` provides these functions to your Plates templates:

* `urlFor()` - returns the URL for a given route. e.g.: /hello/world
* `fullUrFor()` - returns the URL for a given route. e.g.: http://www.example.com/hello/world
* `isCurrentUrl()` - returns true is the provided route name and parameters are valid for the current path.
* `currentUrl()` - returns the current path, with or without the query string.
* `basePath()` - returns the base path.

You can use `urlFor` to generate complete URLs to any Slim application named route and use `isCurrentUrl` to determine if you need to mark a link as active as shown in this example Plates template:

```php
<?php $this->layout('template') ?>

<h1>User List</h1>
<ul>
    <li><a href="<?= $this->urlFor('profile', ['name' => 'josh']) ?>" <?php if ($this->isCurrentUrl('profile', ['name' => 'josh']): ?>}class="active"<?php endif ?>}>Josh</a></li>
    <li><a href="<?= $this->urlFor('profile', ['name' => 'andrew']) ?>">Andrew</a></li>
</ul>
```

## The MIT License (MIT)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

