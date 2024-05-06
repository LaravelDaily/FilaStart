<?php

namespace Generators\Filament3\Generators\Files;

interface FileBase
{
    public function generate(): string;

    /**
     * @param  array<string, mixed>  $replacements
     */
    public function setReplacements(array $replacements): void;
}
