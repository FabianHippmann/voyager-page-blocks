<?php

namespace Pvtl\VoyagerPageBlocks\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageServiceProviderLaravel5;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use Pvtl\VoyagerPageBlocks\PageBlocksServiceProvider;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'voyager-page-blocks:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Voyager Page Blocks package';

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd() . '/composer.phar')) {
            return '"' . PHP_BINARY . '" ' . getcwd() . '/composer.phar';
        }

        return 'composer';
    }

    public function fire(Filesystem $filesystem)
    {
        return $this->handle($filesystem);
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {

        $this->info('Publishing Page Blocks assets, database, and config files');
        $this->call('vendor:publish', ['--provider' => PageBlocksServiceProvider::class]);

        $this->info('Dumping the autoloaded files and reloading all new files');
        $composer = $this->findComposer();
        $process = new Process($composer . ' dump-autoload');
        $process->setWorkingDirectory(base_path())->mustRun();

        $this->info('Migrating the database tables into your application');
        $this->call('migrate');

        $this->info('Seeding data into the database');
        $this->call('db:seed', ['--class' => 'PageBlocksTableSeeder']);

        $this->info('Successfully installed Voyager Page Blocks! Enjoy');
    }
}