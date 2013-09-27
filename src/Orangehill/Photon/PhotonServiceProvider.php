<?php namespace Orangehill\Photon;

use Illuminate\Support\ServiceProvider;

class PhotonServiceProvider extends ServiceProvider {

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
		$this->package('orangehill/photon');
		include __DIR__.'/../../filters.php';
		include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
	      'Orangehill\Photon\ModuleRepository',
	      'Orangehill\Photon\ModuleEloquentRepository'
	    );
		$this->app->bind(
	      'Orangehill\Photon\FieldRepository',
	      'Orangehill\Photon\FieldEloquentRepository'
	    );
		$this->app['photon'] = $this->app->share(function($app)
		{
			return new Photon($app->make('photon.repository'));
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('photon');
	}

}
