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
//Check before loading language file
//Has the visitor changed language ?
if(isset($_GET['lang']) && $_GET['lang'] != '')
{
	if($_GET['lang'] == 'fr')
	{
		require './includes/lang-fr.php';
		setcookie('sitelang','fr',time()+60*60*24*365,'/','ishimaru.pingveno.net');	
	}
	elseif($_GET['lang'] == 'en')
	{
		require './includes/lang-en.php';
		setcookie('sitelang','en',time()+60*60*24*365,'/','ishimaru.pingveno.net');
	}
}
//Does the visitor already has a cookie ?
elseif(isset($_COOKIE['sitelang']) && $_COOKIE['sitelang'] != '')
{
	if($_COOKIE['sitelang'] == 'fr')
	{
		require './includes/lang-fr.php';
	}
	elseif($_COOKIE['sitelang'] == 'en')
	{
		require './includes/lang-en.php';
	}
}
//The visitor has just arrived for the first time, so we check his/her browser preferences in order to load the right language file and set a cookie for the user.
else
{
	$Language = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
	$navlang = strtolower(substr(chop($Language[0]),0,2));
	if($navlang == 'fr')
	{
		require './includes/lang-fr.php';
		setcookie('sitelang','fr',time()+60*60*24*365,'/','ishimaru.pingveno.net');
	}
	elseif($navlang == 'en')
	{
		require './includes/lang-en.php';
		setcookie('sitelang','en',time()+60*60*24*365,'/','ishimaru.pingveno.net');
	}
	else
	{
		require './includes/lang-en.php';
		setcookie('sitelang','en',time()+60*60*24*365,'/','ishimaru.pingveno.net');
	}
}
// require './includes/lang-fr.php';
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
