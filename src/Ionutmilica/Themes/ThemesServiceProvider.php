<?php namespace Ionutmilica\Themes;

use Illuminate\Support\ServiceProvider;

class ThemesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * @var array
	 */
	protected $commands = array(
		'Make'
	);

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ionutmilica/themes');

		$this->app['themes']->registerTheme();
		$this->app['themes']->registerNamespace($this->app);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['themes'] = $this->app->share(function ($app)
		{
			$finder = new ThemeFinder($app['files']);

			return new Theme($finder, $app['view'], $app['config'], $app['translator']);
		});

		$this->registerCommands();
	}

	/**
	 * Register theme package commands
	 */
	public function registerCommands()
	{
		$this->commands([
			'Ionutmilica\Themes\Commands\MakeCommand'
		]);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('themes');
	}

}
