<?php namespace Ecdo\Atlas;

use Illuminate\Foundation\Console\ConfigPublishCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class AtlasConfigPublishCommand extends ConfigPublishCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'atlas:config';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a star's configuration to the application";
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $star = $this->input->getArgument('star');
        list ($vendor, $package) = explode('/', $star);
        $vendor = snake_case($vendor, '-');
        $package = snake_case($package, '-');
        $star = $vendor . '/' . $package;
        $proceed = $this->confirmToProceed('Config Already Published!', function () use($star) {
            return $this->config->alreadyPublished($star);
        });
        
        if (! $proceed) {
            return;
        }
        
        $this->config->publishPackage($star);
    
        $this->output->writeln('<info>Configuration published for star:</info> '.$star);
    }
    
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('star', InputArgument::REQUIRED, 'The name of the star being published.'),
        );
    }
    
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('force', null, InputOption::VALUE_NONE, 'Force the operation to run when the file already exists.'),
        );
    }
}