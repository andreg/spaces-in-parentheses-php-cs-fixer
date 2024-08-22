<?php

namespace SuperDJ\SpacesInParenthesesFixer;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class SpaceInsideSquareBracketsFixer extends AbstractFixer
{
	public function getDefinition(): FixerDefinitionInterface
	{
		return new \PhpCsFixer\FixerDefinition\FixerDefinition(
			'Ensure there are spaces inside square brackets token.',
			[]
		);
	}

	public function getName(): string
	{
		return 'Andreg/space_inside_square_brackets';
	}

	public function isCandidate(Tokens $tokens):bool
	{
		return $tokens->isTokenKindFound( CT::T_ARRAY_SQUARE_BRACE_OPEN ) || $tokens->isTokenKindFound( CT::T_ARRAY_SQUARE_BRACE_CLOSE );
	}

	public function applyFix(\SplFileInfo $file, Tokens $tokens):void
	{
		foreach ($tokens as $index => $token) {
			if ( ! $token->isGivenKind( CT::T_ARRAY_SQUARE_BRACE_OPEN ) && ! $token->isGivenKind( CT::T_ARRAY_SQUARE_BRACE_CLOSE ) ) {
				continue;
			}

			if ( $token->isGivenKind( CT::T_ARRAY_SQUARE_BRACE_OPEN ) ) {
				if (!$tokens[$index + 1]->isWhitespace() && !$tokens[$index + 1]->isGivenKind( CT::T_ARRAY_SQUARE_BRACE_CLOSE )) {
					$tokens->insertAt($index + 1, new Token([ T_WHITESPACE, ' ' ]));
				}
			}

			if ( $token->isGivenKind( CT::T_ARRAY_SQUARE_BRACE_CLOSE ) ) {
				if (!$tokens[$index - 1]->isWhitespace() && !$tokens[$index - 1]->isGivenKind( CT::T_ARRAY_SQUARE_BRACE_OPEN )) {
					$tokens->insertAt($index, new Token([ T_WHITESPACE, ' ' ]));
				}
			}
		}
	}
}
