# PHP CS Fixer: Spaces in parentheses fixer

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