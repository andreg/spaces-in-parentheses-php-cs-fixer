<?php

namespace SuperDJ\SpacesInParenthesesFixer;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class SpaceInsideRoundBracketsFixer extends AbstractFixer
{
	public function getPriority(): int
    {
        return 20;
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new \PhpCsFixer\FixerDefinition\FixerDefinition(
            "Ensure there are spaces inside round brackets token.",
            []
        );
    }

    public function getName(): string
    {
        return "Andreg/space_inside_round_brackets";
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound("(") || $tokens->isTokenKindFound(")");
    }

    public function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        foreach ($tokens as $index => $token) {
            if (!$token->equals("(") && !$token->equals(")")) {
                continue;
            }

            if ($token->equals("(")) {
                if (
                    !$tokens[$index + 1]->isWhitespace() &&
                    !$tokens[$index + 1]->equals(")")
                ) {
                    $tokens->insertAt(
                        $index + 1,
                        new Token([T_WHITESPACE, " "])
                    );
                }
            }

            if ($token->equals(")")) {
                if (
                    !$tokens[$index - 1]->isWhitespace() &&
                    !$tokens[$index - 1]->equals("(")
                ) {
                    $tokens->insertAt($index, new Token([T_WHITESPACE, " "]));
                }
            }
        }
    }
}
