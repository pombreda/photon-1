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
