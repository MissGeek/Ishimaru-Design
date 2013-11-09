<?php
//Sous-menu
$lang = $lang_site['Lang'];
if($module == 'resources')
{
	if (file_exists('./cache/cache_sub_res-'.$lang.'.php'))
		include './cache/cache_sub_res-'.$lang.'.php';

	if (!defined('PUN_SUB_RES_LOADED'))
	{
		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_res_submenu_cache($lang);
		require './cache/cache_sub_res-'.$lang.'.php';
	}
}
elseif($module == 'tutorials')
{
	if (file_exists('./cache/cache_sub_tuts-'.$lang.'.php'))
		include './cache/cache_sub_tuts-'.$lang.'.php';

	if (!defined('PUN_SUB_TUTS_LOADED'))
	{
		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_tuts_submenu_cache($lang);
		require './cache/cache_sub_tuts-'.$lang.'.php';
	}
}
elseif($module == 'admin')
	echo '<li><a href="admin.php">'.$lang_site['Home'].'</a></li><li><a href="admin.php?adm=resources">'.$lang_site['Resources'].'</a></li><li><a href="admin.php?adm=tutorials">'.$lang_site['Tutorials'].'</a></li><li><a href="admin_pages.php">'.$lang_site['Pages'].'</a></li>';
else
	echo '<li><a href="index.php">'.$lang_site['Home'].'</a></li><li><a href="index.php?module=news">'.$lang_site['News'].'</a></li>';

echo '<li><a href="index.php?lang='.$lang_site['Changelang short'].'">'.$lang_site['Changelang long'].'</a></li>';
?>
