<?php

namespace DuskCrawler\Console;

use Orchestra\Canvas\Core\Commands\Generator;

class PageCommand extends Generator
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dusk-crawler:page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Page';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Page';

    /**
     * Get the stub file for the generator.
     */
    public function getStubFile(): string
    {
        return __DIR__.'/stubs/page.stub';
    }

    /**
     * Get the default namespace for the class.
     */
    public function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace.'\Browser\Pages';
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
