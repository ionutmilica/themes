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
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ionutmilica/themes');

		$this->app['themes']->registerThemes();
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
			$finder = new ThemeFinder($app['files'], $app['config']);

			return new Theme($finder, $app['view'], $app['config'], $app['translator']);
		});
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
