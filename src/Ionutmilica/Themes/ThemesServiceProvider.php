<?php namespace Ionutmilica\Themes;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;

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
			return new Theme(new ThemeFinder($app['files']));
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
