<?php

namespace Generators\Laravel11\Generators;

class MigrationLineGenerator
{
    private string $type = 'string';

    private ?string $key = null;

    private bool $constrained = false;

    private ?string $constrainedTable = null;

    private ?string $constrainedColumn = null;

    private bool $nullable = false;

    private bool $index = false;

    private ?bool $default = null;

    public function toString(): string
    {
        $properties = $this->getMigrationOptionsOrder();

        return '$table'.implode('', $properties).';';
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function constrained(?string $table = null, ?string $column = null): self
    {
        $this->constrained = true;
        $this->constrainedTable = $table;
        $this->constrainedColumn = $column;

        return $this;
    }

    public function nullable(): self
    {
        $this->nullable = true;

        return $this;
    }

    public function index(): self
    {
        $this->index = true;

        return $this;
    }

    public function setDefault(bool $default): self
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return string[]
     */
    protected function getMigrationOptionsOrder(): array
    {
        return [
            $this->buildKeyAndMethod(),
            $this->checkForDefault(),
            $this->checkForNullable(),
            $this->checkForConstrained(),
            $this->checkForIndex(),
        ];
    }

    private function buildKeyAndMethod(): string
    {
        $output = '->'.$this->type;

        if ($this->key) {
            $output .= '(\''.$this->key.'\')';
        } else {
            $output .= '()';
        }

        return $output;
    }

    private function checkForConstrained(): string
    {
        if ($this->constrained) {
            $output = '->constrained';

            if ($this->constrainedTable) {
                $output .= '(\''.$this->constrainedTable.'\'';

                if ($this->constrainedColumn) {
                    $output .= ', \''.$this->constrainedColumn.'\'';
                }

                $output .= ')';

                return $output;
            }

            return $output.'()';
        }

        return '';
    }

    private function checkForNullable(): string
    {
        if ($this->nullable) {
            return '->nullable()';
        }

        return '';
    }

    private function checkForIndex(): string
    {
        if ($this->index) {
            return '->index()';
        }

        return '';
    }

    private function checkForDefault(): string
    {
        if (! is_null($this->default)) {
            return '->default('.($this->default ? 'true' : 'false').')';
        }

        return '';
    }
}
