<?php namespace Ecdo\Atlas;

use Illuminate\Console\Command;
use Ecdo\Atlas\Star;
use Ecdo\Atlas\StarCreator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AtlasCreateCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'atlas:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new star atlas';

	/**
	 * The atlas creator instance.
	 *
	 * @var \Ecdo\Atlas\StarCreator
	 */
	protected $creator;

	/**
	 * Create a new make atlas command instance.
	 *
	 * @param  \Ecdo\Atlas\StarCreator  $creator
	 * @return void
	 */
	public function __construct(StarCreator $creator)
	{
		parent::__construct();

		$this->creator = $creator;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$star = $this->runCreator($this->buildStar());

		$this->info('Star atlas created!');
	}

	/**
	 * Run the atlas creator class for a given Star.
	 *
	 * @param  \Ecdo\Atlas\Star $star
	 * @return string
	 */
	protected function runCreator($star)
	{
		$path = $this->laravel['path.base'].'/atlas';
		$plain = false;

		return $this->creator->create($star, $path, $plain);
	}

	/**
	 * Call the composer update routine on the path.
	 *
	 * @param  string  $path
	 * @return void
	 */
	protected function callComposerUpdate($path)
	{
		chdir($path);

		passthru('composer install --dev');
	}

	/**
	 * Build the atlas details from user input.
	 *
	 * @return \Ecdo\Atlas\Star
	 *
	 * @throws \UnexpectedValueException
	 */
	protected function buildStar()
	{
		list($vendor, $name) = $this->getStarSegments();

		$config = $this->laravel['config']['atlas'];

		if (empty($config)) {
			$config = ['name' => '', 'email' => ''];
		}

		if (is_null($config['email']))
		{
			throw new \UnexpectedValueException("Please set the author's email in the atlas configuration file.");
		}

		return new Star($vendor, $name, $config['name'], $config['email']);
	}

	/**
	 * Get the atlas vendor and name segments from the input.
	 *
	 * @return array
	 */
	protected function getStarSegments()
	{
		$star = $this->argument('star');

		return array_map('studly_case', explode('/', $star, 2));
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
