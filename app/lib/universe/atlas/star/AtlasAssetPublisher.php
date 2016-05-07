<?php namespace Ecdo\Atlas;

use Illuminate\Filesystem\Filesystem;

class AtlasAssetPublisher {

	/**
	 * The filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * The path where assets should be published.
	 *
	 * @var string
	 */
	protected $publishPath;

	/**
	 * The path where stars are located.
	 *
	 * @var string
	 */
	protected $starPath;

	/**
	 * Create a new asset publisher instance.
	 *
	 * @param  \Illuminate\Filesystem\Filesystem  $files
	 * @return void
	 */
	public function __construct(Filesystem $files)
	{
		$this->files = $files;
	}

	/**
	 * Copy all assets from a given path to the publish path.
	 *
	 * @param  string  $star
	 * @param  string  $source
	 * @param  string  $destination
	 * @return bool
	 *
	 * @throws \RuntimeException
	 */
	public function publish($star, $source, $destination)
	{
	    list($vendor, $name) = array_map('snake_case', explode('/', $star));
	    $star = $vendor . '/' . $name;
	    $source = $source . '/' . $star . '/public';
	    $destination = $destination . '/' . $star;
	    
		$success = $this->files->copyDirectory($source, $destination);

		if ( ! $success)
		{
			throw new \RuntimeException("Unable to publish star assets.");
		}

		return $success;
	}

}
