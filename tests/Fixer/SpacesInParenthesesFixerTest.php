<?php

declare(strict_types=1);

namespace SuperDJ\SpacesInParenthesesFixer\Tests\Fixer;

use PhpCsFixer\ConfigurationException\InvalidFixerConfigurationException;
use SuperDJ\SpacesInParenthesesFixer\Tests\AbstractFixerTestCase;

final class SpacesInParenthesesFixerTest extends AbstractFixerTestCase
{
    public function testNameIsValid(): void
    {
        $this->assertRegExp('/^[A-Z][a-zA-Z0-9]*\/[a-z][a-z0-9_]*$/', $this->fixer->getName());
    }

    public function testInvalidConfigMissingKey(): void
    {
        $this->expectException(InvalidFixerConfigurationException::class);
        $this->expectExceptionMessageMatches('#^\[SuperDJ/spaces_in_parentheses\] Invalid configuration: The option "a" does not exist\. Defined options are: "space"\.$#');

        $this->fixer->configure(['a' => 1]);
    }

    public function testInvalidConfigValue(): void
    {
        $this->expectException(InvalidFixerConfigurationException::class);
        $this->expectExceptionMessageMatches('#^\[SuperDJ/spaces_in_parentheses\] Invalid configuration: The option "space" with value "double" is invalid\. Accepted values are: "none", "spaces"\.$#');

        $this->fixer->configure(['space' => 'double']);
    }

    /**
     * @dataProvider provideFixCases
     */
    public function testDefaultFix(string $expected, string|null $input = null): void
    {
        $this->doTest($expected, $input);
    }

    /**
     * @dataProvider provideSpacesFixCases
     */
    public function testSpacesFix(string $expected, string|null $input = null): void
    {
        $this->doTest($expected, $input, ['space' => 'spaces']);
    }

    public function provideFixCases(): \Generator
    {
        yield 'It leaves new lines alone' => [
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
        ];

        yield 'It will remove spaces from empty parenthesis' => [
            '<?php foo();',
            '<?php foo( );',
        ];

        yield 'It will remove spaces in if statements' => [
            '<?php
if (true) {
    // if body
}',
            '<?php
if ( true ) {
    // if body
}',
        ];

        yield 'It will remove multiple spaces' => [
            '<?php
if (true) {
    // if body
}',
            '<?php
if (     true   ) {
    // if body
}',
        ];

        yield 'It will remove spaces in function declarations' => [
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
        ];

        yield 'It will remove spaces in chained methods' => [
            '<?php
$foo->bar($arg1, $arg2);',
            '<?php
$foo->bar(  $arg1, $arg2   );',
        ];

        yield 'It will leave spaces in array methods' => [
            '<?php
$var = array( 1, 2, 3 );
'
        ];

        yield 'It will leave square brackets alone' => [
            '<?php
$var = [ 1, 2, 3 ];
',
        ];

        yield 'It should leave list call with trailing comma alone 1' => [
            '<?php list($path, $mode, ) = foo();',
        ];

        yield 'It should leave list call with trailing comma alone 2' => [
            '<?php list($path, $mode,) = foo();',
        ];

        yield 'It leaves comments alone' => [
            '<?php
$a = $b->test(  // do not remove space
    $e          // between `(` and `)`
                // and this comment
);',
        ];

        yield 'It will remove spaces from use of functions' => [
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
        ];

        yield 'It will remove spaces from for-loop' => [
            '<?php
for ($i = 0; $i < 42; $i++) {
    // code...
}
',
            '<?php
for (   $i = 0; $i < 42; $i++ ) {
    // code...
}
'
        ];

        yield 'It will remove spaces from default core method calls' => [
            '<?php
explode($a, $b);
',
            '<?php
explode( $a, $b );
'
        ];

        yield 'It will remove spaces in nested parenthesis' => [
            '<?php
multiply((2 + 3) * 4);
',
            '<?php
multiply( (    2 + 3  ) * 4    );
'
        ];

        yield 'It should leave casting alone' => [
            '<?php
$bool = "true";
$bool = ( bool )$bool;
'
        ];
    }

    public function provideSpacesFixCases(): \Generator
    {
        yield 'It leaves new lines alone' => [
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
}"
        ];

        yield 'It will remove spaces from empty parenthesis' => [
            '<?php foo();',
            '<?php foo( );',
        ];

        yield 'It will add spaces in if-statements' => [
            '<?php
if ( true ) {
    // if body
}',
            '<?php
if (true) {
    // if body
}'
            ];

        yield 'It will remove multiple spaces' => [
            '<?php
if ( true ) {
    // if body
}',
            '<?php
if (     true   ) {
    // if body
}'
        ];

        yield 'It will add spaces in method definition' => [
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
        ];

        yield 'It will add spaces in chained method' => [
            '<?php
$foo->bar( $arg1, $arg2 );',
            '<?php
$foo->bar(  $arg1, $arg2   );',
        ];

        yield 'It will add spaces in array method' => [
            '<?php
$var = array( 1, 2, 3 );
',
            '<?php
$var = array(1, 2, 3);
',
        ];

        yield 'It will leave square brackets alone' => [
            '<?php
$var = [1, 2, 3];
'
        ];

        yield 'It will add spaces in list method' => [
            '<?php list( $path, $mode, ) = foo();',
            '<?php list($path, $mode,) = foo();',
        ];

        yield 'It will leave comments alone' => [
            '<?php
$a = $b->test(  // do not remove space
    $e          // between `(` and `)`
                // and this comment
 );'
        ];

        yield 'It will add spaces in use of method' => [
            '<?php
$code = function ( $hello, $there ) use ( $ami, $tumi ) {
    // code
};
',
            '<?php
$code = function ($hello, $there) use ($ami, $tumi) {
    // code
};
'
        ];

        yield 'It will add spaces in for-loops' => [
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
            ];

        yield 'It will add spaces in core methods' => [
                '<?php
explode( $a, $b );
',
                '<?php
explode($a, $b);
',
            ];

        yield 'It will add spaces in nested parentheses' => [
                '<?php
multiply( ( 2 + 3 ) * 4 );
',
                '<?php
multiply((2 + 3) * 4);
',
            ];

        yield 'It will leave casting alone' => [
                '<?php
$bool = "true";
$bool = (bool)$bool;
'
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