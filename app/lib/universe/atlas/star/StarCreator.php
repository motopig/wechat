<?php namespace Ecdo\Atlas;

use Illuminate\Filesystem\Filesystem;

class StarCreator {

	/**
	 * The filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * The basic building blocks of the atlas.
	 *
	 * @param  array
	 */
	protected $basicBlocks = array(
		'ServiceProvider',
	);

	/**
	 * The building blocks of the atlas.
	 *
	 * @param  array
	 */
	protected $blocks = array(
		'SupportDirectories',
		'PublicDirectory',
		'ServiceProvider',
	);

	/**
	 * Create a new atlas creator instance.
	 *
	 * @param  \Illuminate\Filesystem\Filesystem  $files
	 * @return void
	 */
	public function __construct(Filesystem $files)
	{
		$this->files = $files;
	}

	/**
	 * Create a new atlas stub.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  string  $path
	 * @param  bool    $plain
	 * @return string
	 */
	public function create(Star $star, $path, $plain = true)
	{
		$directory = $this->createDirectory($star, $path);

		// To create the atlas, we will spin through a list of building blocks that
		// make up each atlas. We'll then call the method to build that block on
		// the class, which keeps the actual building of stuff nice and cleaned.
		foreach ($this->getBlocks($plain) as $block)
		{
			$this->{"write{$block}"}($star, $directory, $plain);
		}

		return $directory;
	}

	/**
	 * Create a atlas with all resource directories.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  string  $path
	 * @return void
	 */
	public function createWithFull(Star $star, $path)
	{
		return $this->create($star, $path, false);
	}

	/**
	 * Get the blocks for a given atlas.
	 *
	 * @param  bool  $plain
	 * @return array
	 */
	protected function getBlocks($plain)
	{
		return $plain ? $this->basicBlocks : $this->blocks;
	}

	/**
	 * Create the support directories for a atlas.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  string  $directory
	 * @return void
	 */
	public function writeSupportDirectories(Star $star, $directory)
	{
		foreach (array('config', 'controllers', 'lang', 'lib', 'migrations', 'models', 'views') as $support)
		{
			$this->writeSupportDirectory($star, $support, $directory);
		}
	}

	/**
	 * Write a specific support directory for the atlas.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  string  $support
	 * @param  string  $directory
	 * @return void
	 */
	protected function writeSupportDirectory(Star $star, $support, $directory)
	{
		// Once we create the source directory, we will write an empty file to the
		// directory so that it will be kept in source control allowing the dev
		// to go ahead and push these components to GitHub right on creation.
		$path = $directory.'/src/'.$support;

		$this->files->makeDirectory($path, 0777, true);

		$this->files->put($path.'/.gitkeep', '');
	}

	/**
	 * Create the public directory for the atlas.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  string  $directory
	 * @param  bool    $plain
	 * @return void
	 */
	public function writePublicDirectory(Star $star, $directory, $plain)
	{
		if ($plain) return;

		$this->files->makeDirectory($directory.'/public');

		$this->files->put($directory.'/public/.gitkeep', '');
	}

	/**
	 * Write the stub ServiceProvider for the atlas.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  string  $directory
	 * @param  bool    $plain
	 * @return void
	 */
	public function writeServiceProvider(Star $star, $directory, $plain)
	{
		// Once we have the service provider stub, we will need to format it and make
		// the necessary replacements to the class, namespaces, etc. Then we'll be
		// able to write it out into the atlas's atlas directory for them.
		$stub = $this->getProviderStub($star, $plain);

		$this->writeProviderStub($star, $directory, $stub);

		if (! $plain) {
			$stub = $this->getRouteStub($star);
			$this->writeRouteStub($star, $directory, $stub);
		}
	}

	/**
	 * Write the service provider stub for the atlas.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  string  $directory
	 * @param  string  $stub
	 * @return void
	 */
	protected function writeProviderStub(Star $star, $directory, $stub)
	{
		$path = $this->createClassDirectory($star, $directory);

		// The primary source directory where the atlas's classes will live may not
		// exist yet, so we will need to create it before we write these providers
		// out to that location. We'll go ahead and create now here before then.
		$file = $path.'/'.$star->name.'ServiceProvider.php';

		$this->files->put($file, $stub);
	}

	/**
	 * Get the stub for a ServiceProvider.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  bool  $plain
	 * @return string
	 */
	protected function getProviderStub(Star $star, $plain)
	{
		return $this->formatStarStub($star, $this->getProviderFile($plain));
	}

	/**
	 * Load the raw service provider file.
	 *
	 * @param  bool  $plain
	 * @return string
	 */
	protected function getProviderFile($plain)
	{
		if ($plain)
		{
			return $this->files->get(__DIR__.'/stubs/plain.provider.stub');
		}

		return $this->files->get(__DIR__.'/stubs/provider.stub');
	}

	/**
	 * Write the service route stub for the atlas.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  string  $directory
	 * @param  string  $stub
	 * @return void
	 */
	protected function writeRouteStub(Star $star, $directory, $stub)
	{
		$path = $directory.'/src/routes';
	    $this->files->makeDirectory($path);

		// The primary source directory where the atlas's classes will live may not
		// exist yet, so we will need to create it before we write these routes
		// out to that location. We'll go ahead and create now here before then.
		$file = $path.'/'.$star->lowerName.'.php';

		$this->files->put($file, $stub);
	}

	/**
	 * Get the stub for a Route.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  bool  $plain
	 * @return string
	 */
	protected function getRouteStub(Star $star)
	{
		return $this->formatStarStub($star, $this->getRouteFile());
	}

	/**
	 * Load the raw service route file.
	 *
	 * @param  bool  $plain
	 * @return string
	 */
	protected function getRouteFile()
	{
		return $this->files->get(__DIR__.'/stubs/route.stub');
	}
	
	/**
	 * Create the main source directory for the atlas.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  string  $directory
	 * @return string
	 */
	protected function createClassDirectory(Star $star, $directory)
	{
		$path = $directory.'/src/provider';

		if ( ! $this->files->isDirectory($path))
		{
			$this->files->makeDirectory($path, 0777, true);
		}

		return $path;
	}

	/**
	 * Format a generic atlas stub file.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  string  $stub
	 * @return string
	 */
	protected function formatStarStub(Star $star, $stub)
	{
		foreach (get_object_vars($star) as $key => $value)
		{
			$stub = str_replace('{{'.snake_case($key).'}}', $value, $stub);
		}

		return $stub;
	}

	/**
	 * Create a atlas directory for the atlas.
	 *
	 * @param  \Ecdo\Atlas\Star  $star
	 * @param  string  $path
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function createDirectory(Star $star, $path)
	{
	    
		$fullPath = $path. '/' . $star->getFullName();

		// If the directory doesn't exist, we will go ahead and create the atlas
		// directory in the atlas location. We will use this entire atlas
		// name when creating the directory to avoid any potential conflicts.
		if ( ! $this->files->isDirectory($fullPath))
		{
			$this->files->makeDirectory($fullPath, 0777, true);

			return $fullPath;
		}

		throw new \InvalidArgumentException("Star exists.");
	}

}
