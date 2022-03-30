# PHP CS Fixer: Spaces in parentheses fixer

[![Packagist Downloads](https://img.shields.io/packagist/dt/superdj/spaces-in-parentheses-php-cs-fixer?style=flat-square)](https://packagist.org/packages/superdj/spaces-in-parentheses-php-cs-fixer)

## Installation

Install it with the following command:

```php
composer require --dev superdj/spaces-in-parentheses-php-cs-fixer
```

## Usage

In `.php-cs-fixer.php` add the following:
```php
return ( new PhpCsFixer\Config )
    ->registerCustomFixers( [
        new \SuperDJ\SpacesInParenthesesFixer\SpacesInParenthesesFixer,
    ] )
    ->setRules( [
        'SuperDJ/spaces_in_parentheses'                    => [ 'space' => 'spaces' ],
    ]);
```
