<?php
//Librairie regroupant les fonctions pour le site

//Gestion du cache, pour économiser des requêtes.  On vérifie si le fichier existe et n'est pas expiré et après, on retourne un booléen
/*function check_cache($file,$delay)
{
	//On fait en sorte que si la valeur entrée est 0, on mets un délai d'un an pour que le cache ne soit rafraîchi qu'au besoin, via une fonction dans l'ACP
	$expire = time() - ($delay > 0)? $delay : 31536000;
	//Vérification si le fichier de cache est à jour
	if(file_exists($file) && filemtime($file) > $expire)
	{
		return true;
	}
	else return false;
}*/

function order_by_lang($lang,$param1,$param2)
{
	if($lang == 'fr')
		return $param1.' '.$param2;
	else
		return $param2.' '.$param1;
}

//Fonction pour la boîte de recherche et de pagination.
//La pagination utilise les variables GET tandis que la recherche utilise les variables POST
//Cette fonction sert donc à simplifier le traitement
function getpost($key)
{
	if(isset($_GET[$key]))
		return $_GET[$key];
	elseif(isset($_POST[$key]))
		return $_POST[$key];
	else
		return NULL;
}

function rename_file($type,$file,$mini=false)
{
	$type = str_replace(' ','-',strtolower($type)); //icones ou screenshots
	$file = str_replace(' ','-',strtolower($file)); //nom du fichier
	if($mini == true)
		$filepath = './img/'.$type.'/thumbs/'.$file;
	else
		$filepath = './img/'.$type.'/'.$file;
	return $filepath;
}


//Fonction pour la création des miniatures selon le type
function create_thumb($file)
{
	if(preg_match('#jpe?g$#',$file))
	{
		$create = imagecreatefromjpeg($file);
	}
	elseif(preg_match('#gif$#',$file))
	{
		$create = imagecreatefromgif($file);
	}
	elseif(preg_match('#png$#',$file))
	{
		$create = imagecreatefrompng($file);
	}
	return $create;
}

//Fonction pour récupérer l'URL des miniatures
function get_image_link($file,$mini=false)
{
	$parts = explode('/',$file); //coupe le chemin par des slashes.  Ex : ../img/gimp/fichier.png = 0/1/2/3

	if($parts[2] == 'res-cat-icons' || $parts[2] == 'tuts-cat-icons' || $parts[2] == 'tut')
		$imagelink = './img/'.$parts[2].'/'.$parts[3];
	elseif(preg_match('#res#',$parts[2]))
	{
		if(!empty($mini))
			$imagelink = './img/'.$parts[2].'/thumbs/'.$parts[3];
		else
			$imagelink = './img/'.$parts[2].'/'.$parts[3];
	}
	return $imagelink;
}

//Fonction pour enregistrer la miniature
function save_thumb($image,$file,$folder)
{
/*	if(preg_match('#png$#',$file)) //On ne veut appliquer l'alpha que sur les .png
	{
		$back = imagecolorallocatealpha($image, 255, 255, 255, 0);
//		$border = imagecolorallocate($image, 0, 0, 0);
		imagefilledrectangle($image, 0, 0, $size - 1, $size - 1, $back);
//		imagerectangle($image, 0, 0, $size - 1, $size - 1, $border);
	}*/
	$path = explode('/',$file); //Coupe le chemin complet à partir des slash.  Ex : ../mg/gimp/fichier.png = 0/1/2/3
/*	if($folder == 'res-cat-icons' || $folder == 'tuts-cat-icons')
		$link = './img/'.$path[2].'/'.$path[3];*/
/*	elseif*/
	if(preg_match('#res#',$folder))
		$link = './img/'.$path[2].'/thumbs/'.$path[3];
	elseif($folder == 'tut')
		$link = './img/'.$path[2].'/'.$path[3];
	if(preg_match('#jpe?g$#',$file))
		$save = imagejpeg($image,$link);
	elseif(preg_match('#gif$#',$file))
		$save = imagegif($image,$link);
	elseif(preg_match('#png$#',$file))
		$save = imagepng($image,$link);
//	imagedestroy($image);
	return $save;
}

//Fonction qui retourne les dimensions de l'image vide à créer pour les miniatures
function new_thumb($width,$height,$thumb)
{
	if($thumb == 'tut')
	{
		if($height >= 150 && $width >= 150)
		{
			$new_width = 150;
			$new_height = 150;
		}
		elseif($height >= 150 && $width < 150)
		{
			$new_height = 150;
			$new_width = $width;
		}
		elseif($height < 150 && $width >= 150)
		{
			$new_height = $height;
			$new_width = 150;
		}
		else
		{
			$new_height = $height;
			$new_width = $width;
		}
	}
	elseif($thumb == 'res')
	{
		$new_width = 200;
		$new_height = round(($height*200)/$width);
	}
	return imagecreatetruecolor($new_width,$new_height);
}
//Fonction pour retirer des captures lors d'une suppression ou d'une modification
function remove_file($file,$folder)
{
	if($folder == 'res-cat-icons' || $folder == 'tuts-cat-icons' || $folder == 'tut')
	{
		$delete = @unlink($file);
	}
	elseif(preg_match('#res#',$folder))
	{
		$pieces = explode('/',$file); //Découpe le chemin avec les slashes.  Ex : ../img/gimp/fichier.png = 0/1/2/3
//		$parts = explode('.',$pieces[3]); //Découpe le nom du fichier pour séparer le nom de l'extension.  Ex : fichier.png = 0.1

		$delete1 = @unlink('./img/'.$folder.'/'.$pieces[3]);
		$delete2 = @unlink('./img/'.$folder.'/thumbs/'.$pieces[3]);
		$delete = $delete1 . $delete2;
	}
	return $delete;
}

// Fonction supprimant récursivement un dossier et tout son contenu
// Source : http://www.wikistuce.info/doku.php/php/supprimer_un_dossier_et_son_contenu
function clear_dir($dossier)
{
	$ouverture=@opendir($dossier);
	if (!$ouverture) return;
	while($fichier=readdir($ouverture))
	{
		if ($fichier == '.' || $fichier == '..') continue;

		if (is_dir($dossier."/".$fichier))
		{
			$r=clear_dir($dossier."/".$fichier);
			if (!$r) return false;
		}
		else
		{
			$r=@unlink($dossier."/".$fichier);
			if (!$r) return false;
		}
	}
	closedir($ouverture);
	$r=@rmdir($dossier);
	if (!$r) return false;
	return true;
}


/*Functions for checking tutorial cat and versior */
function get_cat_version($id)
{
	global $db;
	if(preg_match('#^c[1-9]+[0-9]{0,2}#',$id))
	{
		$cat_id = substr($id,1);
		return array('cat' => $cat_id,'ver' => 0);
	}
	elseif(preg_match('#^v[1-9]+[0-9]{0,2}#',$id))
	{
		$id_to_search = substr($id,1);
		$c = $db->query('SELECT version_cat FROM tuts_versions WHERE version_id='.$id_to_search) or error('Unable to fetch cat data', __FILE__, __LINE__, $db->error());
		$cat_id = $db->result($c);
		return array('cat' => $cat_id,'ver' => $id_to_search);
	}
	else
		return 'null';
}



/*
 Function managing the language use on the website part
 We must check if a cookie is present in the visitor's computer and if the visitor tried to change language
 In case of language change, the $_COOKIE must have priority, so we can change it
 In case of non-existent or erroneous variable in both cases, we use the visitor's browser preferences
*/

function site_lang()
{
	$lang_choices = array('en','fr');
	$cookie = (isset($_COOKIE['lang'])) ? $_COOKIE['lang'] : NULL;
	$get = (isset($_GET['lang'])) ? $_GET['lang'] : NULL;
	if(in_array($get,$lang_choices) && in_array($cookie,$lang_choices))
		require('./includes/lang-'.(($get == $cookie) ? $cookie : $get).'.php');
	elseif(!in_array($get,$lang_choices) && in_array($cookie,$lang_choices))
		require('./includes/lang-'.$cookie.'.php');
	elseif(!in_array($cookie,$lang_choices) && in_array($get,$lang_choices))
		require('./includes/lang-'.$get.'.php');
	else
	{
		if(!isset($langue)) {
			$langue = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$navlang = (strtolower(substr(chop($langue[0]),0,2)) == 'fr') ? 'fr' : 'en';
			require('./includes/lang-'.$navlang.'.php');
			setcookie('lang',$navlang,time() + (365*24*3600));
		}
		else
		{
			require('lang-en.php');
			setcookie('lang','en',time() + (365*24*3600));
		}
	}
}

function site_paginate($num_pages, $cur_page, $link)
{
	global $lang_common, $lang_site, $site_config;

	$pages = array();
	$link_to_all = false;

	// If $cur_page == -1, we link to all pages (used in viewforum.php)
	if ($cur_page == -1)
	{
		$cur_page = 1;
		$link_to_all = true;
	}

	if ($num_pages <= 1)
		$pages = array('<strong class="item1">1</strong>');
	else
	{
		// Add a previous page link
		if ($num_pages > 1 && $cur_page > 1)
			$pages[] = '<a'.(empty($pages) ? ' class="item1"' : '').' href="'.$link.'&amp;p='.($cur_page - 1).'">'.$lang_site['Previous'].'</a>';

		if ($cur_page > 3)
		{
			$pages[] = '<a'.(empty($pages) ? ' class="item1"' : '').' href="'.$link.'&amp;p=1">1</a>';

			if ($cur_page > 5)
				$pages[] = '<span class="spacer">'.$lang_common['Spacer'].'</span>';
		}

		// Don't ask me how the following works. It just does, OK? :-)
		for ($current = ($cur_page == 5) ? $cur_page - 3 : $cur_page - 2, $stop = ($cur_page + 4 == $num_pages) ? $cur_page + 4 : $cur_page + 3; $current < $stop; ++$current)
		{
			if ($current < 1 || $current > $num_pages)
				continue;
			else if ($current != $cur_page || $link_to_all)
				$pages[] = '<a'.(empty($pages) ? ' class="item1"' : '').' href="'.$link.'&amp;p='.$current.'">'.forum_number_format($current).'</a>';
			else
				$pages[] = '<strong'.(empty($pages) ? ' class="item1"' : '').'>'.forum_number_format($current).'</strong>';
		}

		if ($cur_page <= ($num_pages-3))
		{
			if ($cur_page != ($num_pages-3) && $cur_page != ($num_pages-4))
				$pages[] = '<span class="spacer">'.$lang_common['Spacer'].'</span>';

			$pages[] = '<a'.(empty($pages) ? ' class="item1"' : '').' href="'.$link.'&amp;p='.$num_pages.'">'.forum_number_format($num_pages).'</a>';
		}

		// Add a next page link
		if ($num_pages > 1 && !$link_to_all && $cur_page < $num_pages)
			$pages[] = '<a'.(empty($pages) ? ' class="item1"' : '').' href="'.$link.'&amp;p='.($cur_page +1).'">'.$lang_site['Next'].'</a>';
	}

	return implode(' ', $pages);
}


//
// Make sure that HTTP_REFERER matches base_url/script - Adaptation for Ishimaru Design
//
function site_confirm_referrer($script, $error_msg = false)
{
	global $pun_config, $lang_site, $site_config;

	// There is no referrer
	if (empty($_SERVER['HTTP_REFERER']))
		site_msg($error_msg ? $error_msg : $lang_site['Bad referrer']);

	$referrer = parse_url(strtolower($_SERVER['HTTP_REFERER']));
	// Remove www subdomain if it exists
	if (strpos($referrer['host'], 'www.') === 0)
		$referrer['host'] = substr($referrer['host'], 4);

	$valid = parse_url(strtolower('http://localhost/sites/ishimaru-v6/'.$script));
	// Remove www subdomain if it exists
	if (strpos($valid['host'], 'www.') === 0)
		$valid['host'] = substr($valid['host'], 4);

	// Check the host and path match. Ignore the scheme, port, etc.
	if ($referrer['host'] != $valid['host'] || $referrer['path'] != $valid['path'])
		site_msg($error_msg ? $error_msg : $lang_site['Bad referrer']);
}

//Pour les commentaires - pour permettre la modération depuis la page publique ET la page admin
/*function mod_comments_referrer($url,$page)
{
	if($page == 'admin')
		return site_confirm_referrer(
}*/

//Adaptation of FluxBB's message() function for ishimaru-design
function site_msg($message)
{
	global $db, $lang_site, $pun_config, $pun_user, $site_config;

	$titre_page = $lang_site['Info'];
	require './includes/top.php';
?>
	<h3><?php echo $lang_site['Info'] ?></h3>
	<div class="error">
			<p><?php echo $message ?></p>
			<p><a href="javascript: history.go(-1)"><?php echo $lang_site['Go back'] ?></a></p>
	</div>
<?php require './includes/bottom.php';
}

//
// Display $message and redirect user to $destination_url
//
function site_redirect($destination_url, $message)
{
	global $db, $pun_config, $lang_common, $lang_login, $lang_site, $pun_user, $site_config;

	// Prefix with base_url (unless there's already a valid URI)
	if (strpos($destination_url, 'http://') !== 0 && strpos($destination_url, 'https://') !== 0 && strpos($destination_url, '/') !== 0)
		$destination_url = BASE_URL.$destination_url;

	// Do a little spring cleaning
	$destination_url = preg_replace('/([\r\n])|(%0[ad])|(;\s*data\s*:)/i', '', $destination_url);

	// If the delay is 0 seconds, we might as well skip the redirect all together
	if ($pun_config['o_redirect_delay'] == '0')
		header('Location: '.str_replace('&amp;', '&', $destination_url));

	// Send no-cache headers
	header('Expires: Thu, 21 Jul 1977 07:30:00 GMT'); // When yours truly first set eyes on this world! :)
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('Pragma: no-cache'); // For HTTP/1.0 compatibility

	// Send the Content-type header in case the web server is setup to send something else
	header('Content-type: text/html; charset=utf-8');

	if (file_exists(PUN_ROOT.'style/'.$pun_user['style'].'/redirect.tpl'))
	{
		$tpl_file = PUN_ROOT.'style/'.$pun_user['style'].'/redirect.tpl';
		$tpl_inc_dir = PUN_ROOT.'style/'.$pun_user['style'].'/';
	}
	else
	{
		$tpl_file = PUN_ROOT.'include/template/redirect.tpl';
		$tpl_inc_dir = PUN_ROOT.'include/user/';
	}

	$tpl_redir = file_get_contents($tpl_file);

	// START SUBST - <pun_include "*">
	preg_match_all('#<pun_include "([^/\\\\]*?)\.(php[45]?|inc|html?|txt)">#', $tpl_redir, $pun_includes, PREG_SET_ORDER);

	foreach ($pun_includes as $cur_include)
	{
		ob_start();

		// Allow for overriding user includes, too.
		if (file_exists($tpl_inc_dir.$cur_include[1].'.'.$cur_include[2]))
			require $tpl_inc_dir.$cur_include[1].'.'.$cur_include[2];
		else if (file_exists(PUN_ROOT.'include/user/'.$cur_include[1].'.'.$cur_include[2]))
			require PUN_ROOT.'include/user/'.$cur_include[1].'.'.$cur_include[2];
		else
			error(sprintf($lang_common['Pun include error'], htmlspecialchars($cur_include[0]), basename($tpl_file)));

		$tpl_temp = ob_get_contents();
		$tpl_redir = str_replace($cur_include[0], $tpl_temp, $tpl_redir);
		ob_end_clean();
	}
	// END SUBST - <pun_include "*">


	// START SUBST - <pun_language>
	$tpl_redir = str_replace('<pun_language>', $lang_common['lang_identifier'], $tpl_redir);
	// END SUBST - <pun_language>


	// START SUBST - <pun_content_direction>
	$tpl_redir = str_replace('<pun_content_direction>', $lang_common['lang_direction'], $tpl_redir);
	// END SUBST - <pun_content_direction>


	// START SUBST - <pun_head>
	ob_start();

	$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), $lang_site['Redirecting']);

?>
<meta http-equiv="refresh" content="<?php echo $pun_config['o_redirect_delay'] ?>;URL=<?php echo str_replace(array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $destination_url) ?>" />
<title><?php echo generate_page_title($page_title) ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo PUN_ROOT; ?>style/<?php echo $pun_user['style'].'.css' ?>" />
<?php

	$tpl_temp = trim(ob_get_contents());
	$tpl_redir = str_replace('<pun_head>', $tpl_temp, $tpl_redir);
	ob_end_clean();
	// END SUBST - <pun_head>


	// START SUBST - <pun_redir_main>
	ob_start();

?>
<div class="block">
	<h2><?php echo $lang_site['Redirecting'] ?></h2>
	<div class="box">
		<div class="inbox">
			<p><?php echo $message.'<br /><br /><a href="'.$destination_url.'">'.$lang_site['Click redirect'].'</a>' ?></p>
		</div>
	</div>
</div>
<?php

	$tpl_temp = trim(ob_get_contents());
	$tpl_redir = str_replace('<pun_redir_main>', $tpl_temp, $tpl_redir);
	ob_end_clean();
	// END SUBST - <pun_redir_main>


	// START SUBST - <pun_footer>
	ob_start();

	// End the transaction
	$db->end_transaction();

	// Display executed queries (if enabled)
	if (defined('PUN_SHOW_QUERIES'))
		display_saved_queries();

	$tpl_temp = trim(ob_get_contents());
	$tpl_redir = str_replace('<pun_footer>', $tpl_temp, $tpl_redir);
	ob_end_clean();
	// END SUBST - <pun_footer>


	// Close the db connection (and free up any result data)
	$db->close();

	exit($tpl_redir);
}

/*
 Some resources are available only in one language, so if it isn't available in the current language, we select the other languege
 For example, if it isn't available in French, it will be displayed in English, and vice-versa
*/
function lang_priority($fr,$en,$lang)
{
	if($lang == 'fr')
		return (isset($fr)) ? $fr : $en;
	elseif($lang == 'en')
		return (isset($en)) ? $en : $fr;
}

function block_side($count)
{
	$result = $count % 2;
	if($result == '1')
		return 'block-right';
	else
		return 'block-left';
}





