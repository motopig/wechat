<?php
namespace Ecdo\Atlas;

use Illuminate\Console\Command;
use Ecdo\Atlas\AtlasAssetPublisher;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AtlasAssetPublishCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'atlas:asset';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Publish a star's assets to the public directory";

	/**
	 * The asset publisher instance.
	 *
	 * @var \Ecdo\Atlas\AtlasAssetPublisher
	 */
	protected $assets;

	/**
	 * Create a new asset publish command instance.
	 *
	 * @param  \Ecdo\Atlas\AtlasAssetPublisher  $assets
	 * @return void
	 */
	public function __construct(AtlasAssetPublisher $assets)
	{
		parent::__construct();

		$this->assets = $assets;
	}

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
	    
		$this->publishAssets($star);
	}

    /**
     * Publish the assets for a given star name.
     *
     * @param string $star            
     * @return void
     */
    protected function publishAssets($star)
    {
        $path = $this->laravel['path.base'].'/atlas';
        $destination = $this->laravel['path.base'].'/public/atlas';
        $this->assets->publish($star, $path, $destination);
        $this->output->writeln('<info>Assets published for star:</info> ' . $star);
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('star', InputArgument::REQUIRED, 'The name (vendor/name) of the star.'),
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