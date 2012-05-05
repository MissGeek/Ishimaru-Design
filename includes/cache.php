<?php

// Ici écrire les foncions pour la mise en cache des données du site.

function generate_site_config_cache()
{
	global $db;

	// Get the forum config from the DB
	$result = $db->query('SELECT * FROM site_config', true) or error('Unable to fetch site config', __FILE__, __LINE__, $db->error());

	$output = array();
	while ($cur_config_item = $db->fetch_row($result))
		$output[$cur_config_item[0]] = $cur_config_item[1];

	// Output config as PHP code
	$fh = @fopen('./cache/cache_config.php', 'wb');
	if (!$fh)
		error('Unable to write configuration cache file to cache directory. Please make sure PHP has write access to the directory \'cache\'', __FILE__, __LINE__);

	fwrite($fh, '<?php'."\n\n".'define(\'SITE_CONFIG_LOADED\', 1);'."\n\n".'$site_config = '.var_export($output, true).';'."\n\n".'?>');

	fclose($fh);

	if (function_exists('apc_delete_file'))
		@apc_delete_file('./cache/cache_config.php');
}

function generate_footer_cache($lang)
{
	global $db, $lang_site, $site_config;

	if($site_config['o_enable_footer_links'] == '1')
	{
		$output = array();
		$fh = @fopen('./cache/cache_footer-'.$lang.'.php', 'wb');
		if (!$fh)
			error('Unable to write footer links file to cache directory. Please make sure PHP has write access to the directory \'cache\' (outside FluxBB directory)', __FILE__, __LINE__);
		
		if($site_config['o_footer_sitelinks'] != '')
			$output['sitelinks'] = '<div class="footer-block" id="block01">'."\n".'<h5>'.$lang_site['Footer site'].'</h5>'.footer_links(pun_htmlspecialchars($site_config['o_footer_sitelinks']),$lang).'</div>';
		else
			$output['sitelinks'] = '';
		if($site_config['o_footer_affiliates'] != '')
			$output['affiliates'] = '<div class="footer-block" id="block02">'."\n".'<h5>'.$lang_site['Footer affiliates'].'</h5>'.footer_links(pun_htmlspecialchars($site_config['o_footer_affiliates']),$lang).'</div>';
		else
			$output['affiliates'] = '';

		fwrite($fh, '<?php'."\n\n".'if (!defined(\'PUN\')) exit;'."\n".'define(\'PUN_FOOTER_LINKS_LOADED\', 1);'."\n\n".'$footer = '.var_export($output,true).';'."\n\n".'?>');
		fclose($fh);

		if (function_exists('apc_delete_file'))
			@apc_delete_file('./cache/cache_footer_'.$lang.'.php');
	}
}

function generate_res_submenu_cache($lang)
{
	global $db, $lang_site;

	$menu_query = $db->query('SELECT rcat_id, rcat_clearname, rcat_order, rcat_lang FROM res_cat WHERE rcat_lang LIKE (\'%'.$lang.'%\') ORDER BY rcat_order') or error('Unable to fetch categories', __FILE__, __LINE__, $db->error());
	$fh = @fopen('./cache/cache_sub_res-'.$lang.'.php', 'wb');
	if (!$fh)
		error('Unable to write resource submenu file to cache directory. Please make sure PHP has write access to the directory \'cache\' (outside FluxBB directory)', __FILE__, __LINE__);
	$output = '<?php'."\n\n".'if (!defined(\'PUN\')) exit;'."\n".'define(\'PUN_SUB_RES_LOADED\', 1);'."\n\n".'?>';
	while($cur_link = $db->fetch_assoc($menu_query))
		$output .= '<li><a href="resources.php?cat='.$cur_link['rcat_id'].'">'.pun_htmlspecialchars(shorttext_lang($cur_link['rcat_clearname'],$lang)).'</a></li>';
	fwrite($fh, $output);
	if (function_exists('apc_delete_file'))
		@apc_delete_file('./cache/cache_sub_res-'.$lang.'.php');
}
function generate_tuts_submenu_cache($lang)
{
	global $db, $lang_site;

	$menu_query = $db->query('SELECT tcat_id, tcat_clearname, tcat_order, tcat_lang FROM tuts_cat WHERE tcat_lang LIKE (\'%'.$lang.'%\') ORDER BY tcat_order') or error('Unable to fetch categories', __FILE__, __LINE__, $db->error());

	$fh = @fopen('./cache/cache_sub_tuts-'.$lang.'.php', 'wb');
	if (!$fh)
		error('Unable to write tutorial submenu file to cache directory. Please make sure PHP has write access to the directory \'cache\' (outside FluxBB directory)', __FILE__, __LINE__);
			while($cur_link = $db->fetch_assoc($menu_query))
				$output .= '<li><a href="tutorials.php?cat='.$cur_link['tcat_id'].'">'.pun_htmlspecialchars(shorttext_lang($cur_link['tcat_clearname'],$lang)).'</a></li>';
	fwrite($fh, $output);
	if (function_exists('apc_delete_file'))
		@apc_delete_file('./cache/cache_sub_tuts-'.$lang.'.php');
}

function generate_lastnews_cache($lang)
{
	global $db, $lang_site, $site_config;

	//Nécessaire pour vérifier si le cache est à jour ou non. Le résultat est stocké comme constante
	//pour être ensuite utilisé pour comparer avec le résultat d'une requête faite avant l'inclusion.
	//Si les ids diffèrent, on n'affiche pas la liste, afin d'éviter un doublon.
	$result = $db->query('SELECT MAX(id) FROM '.$db->prefix.'topics WHERE forum_id='.forum_news($site_config['o_forum_news'],$lang));
	$last_id = $db->result($result);
	$query1 = $db->query('SELECT id, subject FROM '.$db->prefix.'topics WHERE forum_id='.forum_news($site_config['o_forum_news'],$lang).' ORDER BY id DESC LIMIT 0,'.$site_config['o_nb_news_home']);
	$fh = @fopen('./cache/cache_lastnews-'.$lang.'.php', 'wb');
	if (!$fh)
		error('Unable to write last news cache file to cache directory. Please make sure PHP has write access to the directory \'cache\' (outside FluxBB directory)', __FILE__, __LINE__);
	$output = '<?php'."\n\n".'if (!defined(\'PUN\')) exit;'."\n".'define(\'PUN_LASTNEWS_LOADED\', 1);'."\n\n".'define(\'LAST_NEWS_ID\', '.$last_id.');'."\n\n".'if($last_news_id == LAST_NEWS_ID)'."\n".'{'."\n".'?>';
	if($db->num_rows($query1) > 0)
	{
		while($news = $db->fetch_assoc($query1))
			$output .= "\n\t\t\t".'<li><a href="<?php echo PUN_ROOT; ?>viewtopic.php?id='.$news['id'].'">'.pun_htmlspecialchars($news['subject']).'</a></li>';
	}
	else
		$output .= '<li>'.$lang_site['No news'].'</li>';
	$output .= "\n".'<?php'."\n".'}'."\n".'?>';
	fwrite($fh, $output);
	if (function_exists('apc_delete_file'))
		@apc_delete_file('./cache/cache_lastnews-'.$lang.'.php');
}

function generate_lasttuts_cache($lang)
{
	global $db, $lang_site, $site_config;

	$query2 = $db->query('SELECT tentry_id, tentry_name FROM tuts_entries WHERE tentry_lang=\''.$lang.'\' AND tentry_publish=1 ORDER BY tentry_lastupdate DESC LIMIT 0,'.$site_config['o_nb_tuts_home']);
	$fh = @fopen('./cache/cache_lasttuts-'.$lang.'.php', 'wb');
	if (!$fh)
		error('Unable to write last tutorial cache file to cache directory. Please make sure PHP has write access to the directory \'cache\' (outside FluxBB directory)', __FILE__, __LINE__);
	$output = '<?php'."\n\n".'if (!defined(\'PUN\')) exit;'."\n".'define(\'PUN_LASTTUTS_LOADED\', 1);'."\n\n".'?>';
	if($db->num_rows($query2) > 0)
	{
		while($tut = $db->fetch_assoc($query2))
			$output .= "\n\t\t\t".'<li><a href="tutorials.php?id='.$tut['tentry_id'].'">'.pun_htmlspecialchars($tut['tentry_name']).'</a></li>';
	}
	else
		$output .= "\n\t\t\t".'<li>'.$lang_site['No tutorial'].'</li>';
	fwrite($fh, $output);
	if (function_exists('apc_delete_file'))
		@apc_delete_file('./cache/cache_lasttuts-'.$lang.'.php');
}

function generate_lastres_cache($lang)
{
	global $db, $lang_site, $site_config;

	$query3 = $db->query('SELECT rentry_id, rentry_name, rentry_catid, rentry_subcatid, rsub_id, rsub_type, rcat_id, rcat_name, rcat_clearname, rcat_display, rentry_lastupdate FROM res_entries LEFT JOIN res_subcat ON rentry_subcatid=rsub_id LEFT JOIN res_cat ON rentry_catid=rcat_id WHERE rentry_publish=1 AND rentry_lang LIKE (\'%'.$lang.'%\') AND rentry_publish=1 ORDER BY rentry_lastupdate DESC LIMIT 0,'.$site_config['o_nb_res_home']);
	$fh = @fopen('./cache/cache_lastres-'.$lang.'.php', 'wb');
	if (!$fh)
		error('Unable to write last resource cache file to cache directory. Please make sure PHP has write access to the directory \'cache\' (outside FluxBB directory)', __FILE__, __LINE__);
	$output = '<?php'."\n\n".'if (!defined(\'PUN\')) exit;'."\n".'define(\'PUN_LASTRES_LOADED\', 1);'."\n\n".'?>';
	if($db->num_rows($query2) > 0)
	{
		while($res = $db->fetch_assoc($query3))
			$output .= "\n\t\t\t".'<li><a href="resources.php?id='.$res['rentry_id'].'>'.pun_htmlspecialchars(shorttext_lang($res['rentry_name'])).'</a> &raquo; <a href="resources.php?cat='.$res['rcat_id'].'">'.pun_htmlspecialchars(shorttext_lang($res['rcat_clearname'])).'</a></li>';
	}
	else
		$output .= "\n\t\t\t".'<li>'.$lang_site['No style'].'</li>';
	fwrite($fh, $output);
	if (function_exists('apc_delete_file'))
		@apc_delete_file('./cache/cache_lastres-'.$lang.'.php');
}

function generate_admin_home_cache()
{
	global $db, $lang_site;

	$stat1 = $db->query('SELECT COUNT(rentry_id) FROM res_entries');
	$nb_res = $db->result($stat1);
	$stat2 = $db->query('SELECT COUNT(tentry_id) FROM tuts_entries');
	$nb_tuts = $db->result($stat2);
	$stat3 = $db->query('SELECT COUNT(comment_id) FROM tuts_comments');
	$nb_comments = $db->result($stat3);
	$stat4 = $db->query('SELECT COUNT(page_id) FROM site_pages');
	$nb_pages = $db->result($stat4);

	$fh = @fopen('./cache/cache_adm_home.php', 'wb');
	if (!$fh)
		error('Unable to write admin home cache file to cache directory. Please make sure PHP has write access to the directory \''.pun_htmlspecialchast('cache').'\' (outside FluxBB directory)', __FILE__, __LINE__);
	$output = '<?php'."\n\n".'if (!defined(\'PUN\')) exit;'."\n".'define(\'PUN_ADM_HOME_LOADED\', 1);'."\n\n".'?>';

	$output .= '<div class="adm-block block-right" id="adm-stats">'."\n\t\t".'<h4><?php echo $lang_site[\'Admin stats\']; ?></h4>'."\n\t\t".'<dl class="stats">'."\n\t\t\t".'<dt><?php echo $lang_site[\'Admin nb res\']; ?>:</dt>'."\n\t\t\t".'<dd>'.$nb_res.'</dd>'."\n\t\t\t".'<dt><?php echo $lang_site[\'Admin nb tutorials\']; ?>:</dt>'."\n\t\t\t".'<dd>'.$nb_tuts.'</dd>'."\n\t\t\t".'<dt><?php echo $lang_site[\'Admin nb comments\']; ?>:</dt>'."\n\t\t\t".'<dd>'.$nb_comments.'</dd>'."\n\t\t\t".'<dt><?php echo $lang_site[\'Admin nb pages\']; ?>:</dt>'."\n\t\t\t".'<dd>'.$nb_pages.'</dd>'."\n\t\t".'</dl>'."\n\t".'</div>';
	fwrite($fh, $output);
	if (function_exists('apc_delete_file'))
		@apc_delete_file('./cache/cache_adm_home.php');
}

function generate_admin_tuts_home_cache($lang)
{
	global $db, $lang_site;

	$fh = @fopen('./cache/cache_adm_tuts_home-'.$lang.'.php', 'wb');
	if (!$fh)
		error('Unable to write admin home cache file to cache directory. Please make sure PHP has write access to the directory \'cache\' (outside FluxBB directory)', __FILE__, __LINE__);
	$output = '<?php'."\n\n".'if (!defined(\'PUN\')) exit;'."\n".'define(\'PUN_ADM_TUTS_HOME_LOADED\', 1);'."\n\n".'?>';
	$output .= '<table class="adm-table tbl-left" id="tbl-tuts-overview">'."\n\t\t".'<tr>'."\n\t\t\t".'<th class="cat"><?php echo $lang_site[\'Catégorie\']; ?></th>'."\n\t\t\t".'<th class="tut"><?php echo $lang_site[\'Tutorials\']; ?></th>'."\n\t\t".'</tr>'."\n\t\t";
	$query1 = $db->query('SELECT tcat_id, tcat_name FROM tuts_cat');
	if($db->num_rows($query1) > 0)
	{
		while($cur_cat = $db->fetch_assoc($query1))
		{
			$output .= '<tr>'."\n\t\t\t".'<td class="cat">'.pun_htmlspecialchars(shorttext_lang($cur_cat['tcat_name'],$lang)).'</td>'."\n\t\t\t";
			$query2 = $db->query('SELECT COUNT(tentry_id) FROM tuts_entries WHERE tentry_catid='.$cur_cat['tcat_id']);
			$nb_tuts = $db->result($query2);
			$output .= '<td class="tut center">'.$nb_tuts.'</td>'."\n\t\t".'</tr>'."\n\t";
		}
	}
	else
		$output .= '<tr><td colspan="2" class="center"><?php echo $lang_site[\'No cat\']; ?></td></tr>'."\n\t";
	$output .= '</table><table class="adm-table tbl-right" id="tbl-tuts-latest">'."\n\t\t".'<tr>'."\n\t\t\t".'<th class="tut-name"><?php echo $lang_site[\'Tutorial\']; ?></th>'."\n\t\t\t".'<th class="res-cat"><?php echo $lang_site[\'Category\']; ?></th>'."\n\t\t".'</tr>'."\n\t\t";
	define('NB_TUTS',10);
	$query4 = $db->query('SELECT tentry_id, tentry_name, tcat_id, tcat_name FROM tuts_entries LEFT JOIN tuts_cat ON tcat_id=tentry_catid ORDER BY tentry_id DESC LIMIT 0,'.NB_TUTS);
	if($db->num_rows($query4) > 0)
	{
		while($cur_tut = $db->fetch_assoc($query4))
			$output .= '<tr>'."\n\t\t\t".'<td class="tut-name nowrap">'.pun_htmlspecialchars($cur_tut['tentry_name']).'</td>'."\n\t\t\t".'<td class="tut-cat center">'.pun_htmlspecialchars(shorttext_lang($cur_tut['tcat_name'],$lang)).'</td>'."\n\t\t".'</tr>'."\n\t";
	}
	else
		$output .= '<tr><td colspan="2" class="center"><?php echo $lang_site[\'No tutorial\']; ?></td></tr>'."\n\t";
	$output .= '</table>';
	fwrite($fh, $output);
	if (function_exists('apc_delete_file'))
		@apc_delete_file('./cache/cache_adm_tuts_home-'.$lang.'.php');
}

function generate_admin_res_home_cache($lang)
{
	global $db, $lang_site;

	$fh = @fopen('./cache/cache_adm_res_home-'.$lang.'.php', 'wb');
	if (!$fh)
		error('Unable to write admin home cache file to cache directory. Please make sure PHP has write access to the directory \'cache\' (outside FluxBB directory)', __FILE__, __LINE__);
	$output = '<?php'."\n\n".'if (!defined(\'PUN\')) exit;'."\n".'define(\'PUN_ADM_RES_HOME_LOADED\', 1);'."\n\n".'?>';
	$output .= '<table class="adm-table tbl-left" id="tbl-res-overview">'."\n\t\t".'<tr>'."\n\t\t\t".'<th class="cat"><?php echo $lang_site[\'Category\']; ?></th>'."\n\t\t\t".'<th class="hack"><?php echo $lang_site[\'Hacks\']; ?></th>'."\n\t\t\t".'<th class="style"><?php echo $lang_site[\'Styles\']; ?></th>'."\n\t\t".'</tr>'."\n\t\t";
	$result = $db->query('SELECT rcat_id, rcat_name FROM res_cat');
	if($db->num_rows($result) > 0)
	{
		while($cur_cat = $db->fetch_assoc($result))
		{
			$output .= '<tr>'."\n\t\t\t".'<td class="cat">'.pun_htmlspecialchars(shorttext_lang($cur_cat['rcat_name'],$lang)).'</td>';
			$h = $db->query('SELECT COUNT(rentry_id) FROM res_entries LEFT JOIN res_subcat ON rsub_id=rentry_subcatid WHERE rsub_type=\'hack\' AND rsub_catid='.$cur_cat['rcat_id']) or error('Unable to fetch resource info', __FILE__, __LINE__, $db->error());
			$nb_hacks = $db->result($h);
			$output .= '<td class="hack center">'.$nb_hacks.'</td>';
			$s = $db->query('SELECT COUNT(rentry_id) FROM res_entries LEFT JOIN res_subcat ON rsub_id=rentry_subcatid WHERE rsub_type=\'style\' AND rsub_catid='.$cur_cat['rcat_id']) or error('Unable to fetch resource info', __FILE__, __LINE__, $db->error());
			$nb_styles = $db->result($s);
			$output .= '<td class="style center">'.$nb_styles.'</td>'."\n\t\t".'</tr>'."\n\t";
		}
	}
	else
		$output .= '<tr><td colspan="3" class="center"><?php echo $lang_site[\'No cat\']; ?></td></tr>'."\n\t";
	$output .= '</table><table class="adm-table tbl-right" id="tbl-res-latest">'."\n\t\t".'<tr>'."\n\t\t\t".'<th class="res-name"><?php echo $lang_site[\'Resource\']; ?></th>'."\n\t\t\t".'<th class="res-type"><?php echo $lang_site[\'Type\']; ?></th>'."\n\t\t\t".'<th class="res-cat"><?php echo $lang_site[\'Category\']; ?></th>'."\n\t\t".'</tr>'."\n\t\t";
	define('NB_RES',10);
	$query4 = $db->query('SELECT rentry_id, rentry_name, rsub_id, rsub_type, rcat_id, rcat_name FROM res_entries LEFT JOIN res_cat ON rcat_id=rentry_catid LEFT JOIN res_subcat ON rsub_id=rentry_subcatid ORDER BY rentry_id DESC LIMIT 0,'.NB_RES) or error('Unable to fetch resource info', __FILE__, __LINE__, $db->error());
	if($db->num_rows($query4) > 0)
	{
		while($cur_res = $db->fetch_assoc($query4))
			$output .= '<tr>'."\n\t\t\t".'<td class="res-name nowrap">'.pun_htmlspecialchars(shorttext_lang($cur_res['rentry_name'],$lang)).'</td>'."\n\t\t\t".'<td class="res-type center">'.pun_htmlspecialchars(ucfirst($cur_res['rsub_type'])).'</td>'."\n\t\t\t".'<td class="res-cat center">'.pun_htmlspecialchars(shorttext_lang($cur_res['rcat_name'],$lang)).'</td>'."\n\t\t".'</tr>'."\n\t";
	}
	else
		$output .= '<tr><td colspan="3" class="center"><?php echo $lang_site[\'No resource\']; ?></td></tr>'."\n\t";
	$output .= '</table>';
	fwrite($fh, $output);
	if (function_exists('apc_delete_file'))
		@apc_delete_file('./cache/cache_adm_res_home-'.$lang.'.php');
}

define('SITE_CACHE_FUNCTIONS_LOADED', true);
