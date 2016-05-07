<?php
namespace Ecdo\Atlas;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Composer\Autoload\AutoloadGenerator;

class AtlasDumpCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'atlas:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate atlas autoload files.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->runDump();
    }

    /**
     * Command logic.
     */
    protected function runDump()
    {
        $star = $this->argument('star');
        list ($vendor, $package) = explode('/', $star);
        $vendor = snake_case($vendor, '-');
        $package = snake_case($package, '-');
        
        $generator = new AutoloadGenerator();
        $generator->dump($vendor, $package);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array(
                'star',
                InputArgument::REQUIRED,
                'The name (vendor/name) of the star.'
            )
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }
}
