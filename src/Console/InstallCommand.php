<?php

namespace DuskCrawler\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\CodeGenerator;
use Orchestra\Canvas\Core\CommandsProvider;
use Orchestra\Canvas\Core\Contracts\GeneratesCodeListener;
use Orchestra\Canvas\Core\Presets\Preset;

class InstallCommand extends Command implements GeneratesCodeListener
{
    use CodeGenerator,
        CommandsProvider;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dusk-crawler:install
                {--proxy= : The proxy to download the binary through (example: "tcp://127.0.0.1:9000")}
                {--ssl-no-verify : Bypass SSL certificate verification when installing through a proxy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Dusk Crawler into the application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $files, Preset $preset)
    {
        if (! $files->isDirectory($this->laravel->path('Browser/Components'))) {
            $files->makeDirectory($this->laravel->path('Browser/Components'), 0755, true, true);
        }

        $this->setPreset($preset)->generateCode();
    }

    /**
     * Code already exists.
     */
    public function codeAlreadyExists(string $className)
    {
        $this->updateChromeDrivers();

        return 0;
    }

    /**
     * Code successfully generated.
     */
    public function codeHasBeenGenerated(string $className)
    {
        $this->info('Dusk Crawler scaffolding installed successfully.');

        return 0;
    }

    /**
     * Get the stub file for the generator.
     */
    public function getStubFile(): string
    {
        return __DIR__.'/stubs/page.install.stub';
    }

    /**
     * Get the default namespace for the class.
     */
    public function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace.'\Browser\Pages';
    }

    /**
     * Get the desired generator name.
     */
    public function generatorName(): string
    {
        return 'Page';
    }
}
