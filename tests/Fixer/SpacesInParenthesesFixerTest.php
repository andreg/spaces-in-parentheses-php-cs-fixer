<?php

declare(strict_types=1);

namespace SuperDJ\SpacesInParenthesesFixer\Tests\Fixer;

use PhpCsFixer\ConfigurationException\InvalidFixerConfigurationException;
use SuperDJ\SpacesInParenthesesFixer\Tests\AbstractFixerTestCase;

final class SpacesInParenthesesFixerTest extends AbstractFixerTestCase
{
    public function testInvalidConfigMissingKey(): void
    {
        $this->expectException(InvalidFixerConfigurationException::class);
        $this->expectExceptionMessageMatches('#^\[spaces_in_parentheses\] Invalid configuration: The option "a" does not exist\. Defined options are: "space"\.$#');

        $this->fixer->configure(['a' => 1]);
    }

    public function testInvalidConfigValue(): void
    {
        $this->expectException(InvalidFixerConfigurationException::class);
        $this->expectExceptionMessageMatches('#^\[spaces_in_parentheses\] Invalid configuration: The option "space" with value "double" is invalid\. Accepted values are: "none", "spaces"\.$#');

        $this->fixer->configure(['space' => 'double']);
    }

    /**
     * @dataProvider provideFixCases
     */
    public function testDefaultFix(string $expected, ?string $input = null): void
    {
        $this->doTest($expected, $input);
    }

    /**
     * @dataProvider provideSpacesFixCases
     */
    public function testSpacesFix(string $expected, ?string $input = null): void
    {
        $this->doTest($expected, $input, ['space' => 'spaces']);
    }

    public function provideFixCases(): array
    {
        return [
            // default leaves new lines alone
            [
                "<?php
class Foo
{
    private function bar()
    {
        if (foo(
            'foo' ,
            'bar'    ,
            [1, 2, 3],
            'baz' // a comment just to mix things up
        )) {
            return 1;
        };
    }
}
",
            ],
            [
                '<?php foo();',
                '<?php foo( );',
            ],
            [
                '<?php
if (true) {
    // if body
}',
                '<?php
if ( true ) {
    // if body
}',
            ],
            [
                '<?php
if (true) {
    // if body
}',
                '<?php
if (     true   ) {
    // if body
}',
            ],
            [
                '<?php
function foo($bar, $baz)
{
    // function body
}',
                '<?php
function foo( $bar, $baz )
{
    // function body
}',
            ],
            [
                '<?php
$foo->bar($arg1, $arg2);',
                '<?php
$foo->bar(  $arg1, $arg2   );',
            ],
            [
                '<?php
$var = array( 1, 2, 3 );
',
            ],
            [
                '<?php
$var = [ 1, 2, 3 ];
',
            ],
            // list call with trailing comma - need to leave alone
            [
                '<?php list($path, $mode, ) = foo();',
            ],
            [
                '<?php list($path, $mode,) = foo();',
            ],
            [
                '<?php
$a = $b->test(  // do not remove space
    $e          // between `(` and `)`
                // and this comment
);',
            ],
            [
                '<?php
function foo($bar, $baz)
{
    // function body
}',
                '<?php
function foo( $bar, $baz )
{
    // function body
}',
            ],
            [
                '<?php
function hello($value) {
    // code...
}',
                '<?php
function hello( $value ) {
    // code...
}',
            ],
            [
                '<?php
$code = function ($hello, $there) use ($ami, $tumi) {
    // code
};
',
                '<?php
$code = function ( $hello, $there   ) use ( $ami, $tumi ) {
    // code
};
',
            ],
            [
                '<?php
for ($i = 0; $i < 42; $i++) {
    // code...
}
',
                '<?php
for (   $i = 0; $i < 42; $i++ ) {
    // code...
}
',
            ],
            [
                '<?php
explode($a, $b);
',
                '<?php
explode( $a, $b );
',
            ],
            [
                '<?php
if ($something) {
    // code
}
',
                '<?php
if (  $something      ) {
    // code
}
',
            ],
            [
                '<?php
multiply((2 + 3) * 4);
',
                '<?php
multiply( (    2 + 3  ) * 4    );
',
            ],
        ];
    }

    public function provideSpacesFixCases(): array
    {
        return [
            // Leaves new lines alone
            [
                "<?php
class Foo
{
    private function bar()
    {
        if ( foo(
            'foo' ,
            'bar'    ,
            [1, 2, 3],
            'baz' // a comment just to mix things up
        ) ) {
            return 1;
        };
    }
}",
            ],
            [
                '<?php foo();',
                '<?php foo( );',
            ],
            [
                '<?php
if ( true ) {
    // if body
}',
                '<?php
if (true) {
    // if body
}',
            ],
            [
                '<?php
if ( true ) {
    // if body
}',
                '<?php
if (     true   ) {
    // if body
}',
            ],
            [
                '<?php
function foo( $bar, $baz )
{
    // function body
}',
                '<?php
function foo($bar, $baz)
{
    // function body
}',
            ],
            [
                '<?php
$foo->bar( $arg1, $arg2 );',
                '<?php
$foo->bar(  $arg1, $arg2   );',
            ],
            [
                '<?php
$var = array( 1, 2, 3 );
',
                '<?php
$var = array(1, 2, 3);
',
            ],
            [
                '<?php
$var = [ 1, 2, 3 ];
',
            ],
            [
                '<?php list( $path, $mode, ) = foo();',
                '<?php list($path, $mode,) = foo();',
            ],
            [
                '<?php
$a = $b->test(  // do not remove space
    $e          // between `(` and `)`
                // and this comment
 );',
            ],
            [
                '<?php
function foo( $bar, $baz )
{
    // function body
}',
                '<?php
function foo($bar, $baz)
{
    // function body
}',
            ],
            [
                '<?php
function hello( $value ) {
    // code...
}',
                '<?php
function hello($value) {
    // code...
}',
            ],
            [
                '<?php
$code = function ( $hello, $there ) use ( $ami, $tumi ) {
    // code
};
',
                '<?php
$code = function ($hello, $there) use ($ami, $tumi) {
    // code
};
',
            ],
            [
                '<?php
for ( $i = 0; $i < 42; $i++ ) {
    // code...
}
',
                '<?php
for ($i = 0; $i < 42; $i++) {
    // code...
}
',
            ],
            [
                '<?php
explode( $a, $b );
',
                '<?php
explode($a, $b);
',
            ],
            [
                '<?php
if ( $something ) {
    // code
}
',
                '<?php
if (    $something    ) {
    // code
}
',
            ],
            [
                '<?php
multiply( ( 2 + 3 ) * 4 );
',
                '<?php
multiply((2 + 3) * 4);
',
            ],
        ];
    }

    /**
     * @dataProvider provideFix80Cases
     * @requires PHP 8.0
     */
    public function testDefaultFix80(string $expected, string $input): void
    {
        $this->doTest($expected, $input);
    }

    public function provideFix80Cases(): \Generator
    {
        yield [
            '<?php function foo(mixed $a){}',
            '<?php function foo( mixed $a ){}',
        ];
    }

    /**
     * @dataProvider provideSpacesFix80Cases
     * @requires PHP 8.0
     */
    public function testSpacesFix80(string $expected, string $input): void
    {
        $this->doTest($expected, $input, ['space' => 'spaces']);
    }

    public function provideSpacesFix80Cases(): \Generator
    {
        yield [
            '<?php function foo( mixed $a ){}',
            '<?php function foo(mixed $a){}',
        ];
    }
}