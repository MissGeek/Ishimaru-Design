<?php
//define('PUN_ROOT', './fluxbb14/');
//define('BASE_URL', 'http://localhost/sites/ishimaru-v6/');
//require PUN_ROOT.'include/common.php';

// Load site config file
// If it doesn't exists or if it's empty, redirect to site install page
if(file_exists('./site_config.php'))
	require('./site_config.php');
if(!defined('PUN_SITE'))
{
	header('Location: install_site.php');
	exit;
}
require PUN_ROOT.'include/common.php';
// Load site language file
require './includes/lang-fr.php';
$lang = $lang_site['Lang'];

//Load functions library
require './includes/lib.php';
require './includes/lib_lang.php';

// Load cached config
if (file_exists('cache/cache_config.php'))
	include 'cache/cache_config.php';

if (!defined('SITE_CONFIG_LOADED'))
{
	if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_site_config_cache();
	require 'cache/cache_config.php';
}
