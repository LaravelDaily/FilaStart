<?php

namespace Generators\Filament3\Generators\Files;

class ResourceFile implements FileBase
{
    /**
     * @var array<string, mixed>
     */
    private array $replacements;

    public function generate(): string
    {
        $view = view('filament3::resource', $this->replacements)->render();

        return '<?php'.PHP_EOL.PHP_EOL.$view;
    }

    public function setReplacements(array $replacements): void
    {
        $this->replacements = $replacements;
    }
}
