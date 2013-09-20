<?php

Route::filter('is_admin', function ()
{
	//get the admin check closure that should be supplied in the config
	$permission = Config::get('photon::photon.permission');
	$response = $permission();

	//if this is a simple false value, send the user to the login redirect
	if (!$response)
	{
		return Redirect::to(Config::get('photon::photon.login_uri', 'user/login'));
	}
});
