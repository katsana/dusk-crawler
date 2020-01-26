<?php

namespace Laravie\DuskCrawler\Console;

use Orchestra\Canvas\Core\Commands\Generator;

class ComponentCommand extends Generator
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dusk-crawler:component';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Component';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Component';

    /**
     * Get the stub file for the generator.
     */
    public function getStubFile(): string
    {
        return __DIR__.'/stubs/component.stub';
    }

    /**
     * Get the default namespace for the class.
     */
    public function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace.'\Browser\Components';
    }

    /**
     * Generator options.
     */
    public function generatorOptions(): array
    {
        return [
            'name' => $this->generatorName(),
        ];
    }
}
