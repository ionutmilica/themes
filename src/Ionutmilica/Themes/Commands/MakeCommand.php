<?php namespace Ionutmilica\Themes\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

class MakeCommand extends AbstractMakeCommand {

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
		'Controllers',
		'Resources',
		'Resources/views',
		'Resources/lang',
		'Resources/config',
	);

	protected $files = array(
		'routes.php',
		'filters.php'
	);

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

		$this->filesystem->makeDirectory(public_path('themes/'.$name), 0777, true);
		$this->filesystem->makeDirectory($path);

		$this->makeDirs($this->dirs, function (&$dir) use ($path) {
			$dir = $path .'/'. $dir;
		});

		$this->makeFiles($this->files, '<?php'.PHP_EOL, function (&$file) use ($path) {
			$file = $path .'/'. $file;
		});

		$this->moveStub('theme', $path, array(
			'NAME' => $name
		));

		$this->moveStub('ThemeServiceProvider', $path, array(
			'NAME' => ucfirst($name)
		));

		$this->info('Theme '. $name . ' created !');
	}



	/**
	 * Get the path of the theme
	 *
	 * @param $name
	 * @return string
	 */
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
