Photon
======
Dev Instructions:

* Checkout to /workbench/orangehill/photon
* Add these lines to list of providers in app.php config file:

        'Orangehill\Iseed\IseedServiceProvider',
        'Way\Generators\GeneratorsServiceProvider',
        'Orangehill\Photon\PhotonServiceProvider',
        
* run `composer update` from /workbench/orangehill/photon to update dependencies
* run `php artisan asset:publish --bench="orangehill/photon"` from project root to publish the assets

## Installing a package

1) Edit your project's `composer.json` file to require `orangehill/photon`.

    "require": {
		"laravel/framework": "4.0.*",
		"orangehill/photon": "dev-master"
	}

2) Update Composer from the CLI:

    composer update

3) Run `php artisan asset:publish orangehill/photon` in CLI from project root

4) Run `php artisan config:publish orangehill/photon` in CLI from project root

5) Add the service provider by opening a `app/config/app.php` file, and adding these items to the `providers` array:

	'Orangehill\Iseed\IseedServiceProvider',
	'Way\Generators\GeneratorsServiceProvider',
	'Orangehill\Photon\PhotonServiceProvider',


