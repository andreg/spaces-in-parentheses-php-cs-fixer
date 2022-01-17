<?php declare(strict_types=1);

/* *
 * (c) 2018 Kuba Werłos
 *
 * For the full copyright and license information, please view https://github.com/kubawerlos/php-cs-fixer-custom-fixers
 */

namespace SuperDJ\SpacesInParenthesesFixer\Tests;

/**
 * @internal
 */
trait AssertRegExpTrait
{
    public static function assertRegExp(string $pattern, string $string, string $message = ''): void
    {
        if (\method_exists(self::class, 'assertMatchesRegularExpression')) {
            self::assertMatchesRegularExpression($pattern, $string, $message);
        } else {
            parent::assertRegExp($pattern, $string, $message);
        }
    }

    public static function assertNotRegExp(string $pattern, string $string, string $message = ''): void
    {
        if (\method_exists(self::class, 'assertDoesNotMatchRegularExpression')) {
            self::assertDoesNotMatchRegularExpression($pattern, $string, $message);
        } else {
            parent::assertNotRegExp($pattern, $string, $message);
        }
    }
}