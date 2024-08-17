<?php

namespace SuperDJ\SpacesInParenthesesFixer;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class SpaceAroundIfFixer extends AbstractFixer
{
	public function getDefinition(): FixerDefinitionInterface
	{
		return new \PhpCsFixer\FixerDefinition\FixerDefinition(
			'Ensure there are spaces around the "if" token.',
			[]
		);
	}

	public function getName(): string
	{
		return 'Andreg/space_around_if';
	}

	public function isCandidate(Tokens $tokens):bool
	{
		return $tokens->isTokenKindFound(T_IF) || $tokens->isTokenKindFound(T_CATCH);
	}

	public function applyFix(\SplFileInfo $file, Tokens $tokens):void
	{
		foreach ($tokens as $index => $token) {
			if ($token->isGivenKind(T_IF) || $token->isGivenKind(T_CATCH)) {
				// Add a space before 'if' if not present
				if (!$tokens[$index - 1]->isWhitespace()) {
					$tokens->insertAt($index, new Token([T_WHITESPACE, ' ']));
				}

				// Add a space after 'if' if not present
				if (!$tokens[$index + 1]->isWhitespace()) {
					$tokens->insertAt($index + 1, new Token([T_WHITESPACE, ' ']));
				}
			}
		}
	}
}
