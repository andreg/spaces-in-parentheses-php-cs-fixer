<?php

declare(strict_types=1);

namespace SuperDJ\SpacesInParenthesesFixer;

use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;

final class NoValidateFixerConfigurationResolver implements FixerConfigurationResolverInterface
{
    public function getOptions(): array
    {
        return [];
    }

    public function resolve(array $configuration): array
    {
        return [];
    }
}