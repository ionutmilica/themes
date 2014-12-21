<?php namespace Ionutmilica\Themes\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

class MakeCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'themes:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates a new theme';

	protected $dirs = array(
		'views',
		'lang',
		'config',
	);
	/**
	 * @var Filesystem
	 */
	private $filesystem;

	/**
	 * Create a new command instance.
	 * @param Filesystem $filesystem
	 */
	public function __construct(Filesystem $filesystem)
	{
		parent::__construct();

		$this->filesystem = $filesystem;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$name = $this->argument('name');

		$path = $this->getThemePath($name);

		if ($this->filesystem->isDirectory($path))
		{
			$this->error('There is already a theme named ' . $name.' !');
			return;
		}

		$this->filesystem->makeDirectory($path);

		foreach ($this->dirs as $dir)
		{
			$this->filesystem->makeDirectory($path.'/'.$dir);
		}

		$content = file_get_contents(__DIR__.'/stubs/theme.php');
		$content = str_replace('$$NAME$$', $name, $content);

		$this->filesystem->put($path.'/theme.php', $content);
		$this->info('Theme '. $name . ' created !');
	}

	protected function getThemePath($name)
	{
		return base_path('themes/'.$name);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'The name of the theme'),
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
		);
	}

}
