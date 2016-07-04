## Laravel SEO package

## How to install

It is recommended that you use Composer to install PHP PhantomJS. First, add the following to your project’s `composer.json` file:
```php
    "scripts": {
        "post-install-cmd": [
            "PhantomInstaller\\Installer::installPhantomJS"
        ],
        "post-update-cmd": [
            "PhantomInstaller\\Installer::installPhantomJS"
        ]
    }
```

This will ensure the latest version of PhantomJS is installed for your system, in your bin folder. If you haven’t defined your bin folder in your `composer.json`, add the path:

```php
    "config": {
        "bin-dir": "bin"
    }
```

Install composer package

```shell
	composer require qsoft/seo:dev-master
```

### Provider
```php
	Qsoft\Seo\SeoServiceProvider::class,
```

### Publish config

```shell
	php artisan vendor:publish --provider="Qsoft\Seo\SeoServiceProvider"
```