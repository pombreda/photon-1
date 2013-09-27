<?php

/**
 * Routes
 */
Route::group(array('before' => 'is_admin'), function() {
        // Route that handles Ajax requests made by jstree plugin
        Route::controller('admin/jstree/{table?}', '\Orangehill\Photon\JsTreeController');
        // Route that feeds jstree plugin with initial settings
        Route::controller('admin/jstree-settings', '\Orangehill\Photon\JsTreeSettingsController');
        // Route that feeds jstree plugin with initial settings
        Route::controller('admin/settings/{id?}', '\Orangehill\Photon\SettingsController');
        // Testing route
        Route::any('admin/test', '\Orangehill\Photon\TestController@index');
        // Main admin resource controller
        Route::controller('admin', '\Orangehill\Photon\AdminController');
    });

// View Composers
View::composer('photon::common.main-menu', function($view) {
        // Get main menu items
        $view->with('mainMenu', \Orangehill\Photon\AdminController::getMainMenu());
    });
