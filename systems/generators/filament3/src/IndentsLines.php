<?php

namespace Generators\Filament3;

trait IndentsLines
{
    protected function indentString(string $string, int $level = 1): string
    {
        return implode(
            PHP_EOL,
            array_map(
                static fn (string $line) => ($line !== '') ? (str_repeat('    ', $level).$line) : '',
                explode(PHP_EOL, $string),
            ),
        );
    }
}
