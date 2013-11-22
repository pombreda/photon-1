<?php

return array(

	/**
	 * Photon URI
	 *
	 * @type string
	 */
	'uri' => 'admin',

	/**
	 * Project Name
	 *
	 * @type string
	 */
	'title' => 'Admin',

	/**
	 * Any "falsey" response will send the user back to the 'login_uri' defined below.
	 *
	 * @type closure
	 */
	'permission'=> function()
	{
		return true;
	},

	/**
	 * The login URI is the path where Photon will send the user if they fail a permission check
	 *
	 * @type string
	 */
	'login_uri' => 'user/login',

	/**
	 * The logout URI is the path where Photon will send the user when they click the logout link
	 *
	 * @type string
	 */
	'logout_uri' => 'user/logout',

	/**
	 * The assets folder is where Photon will store all uploaded assets (images, files etc.)
	 *
	 * @type string
	 */
	'assets' => public_path() . '/packages/orangehill/photon/assets',

	/**
	 * The Package Assets URI is where Photon package assets will be publicly available
	 *
	 * @type string
	 */
	'package_assets_uri' => Request::root() . '/packages/orangehill/photon',
    'media_folder' => 'media',

    // `entry` or `blank`
    'row_creation_redirection' => 'entry'


);
