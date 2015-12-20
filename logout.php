<?php

/**
 * Copyright (C) 2008-2011 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

if (isset($_GET['action']))
	define('PUN_QUIET_VISIT', 1);

require 'site_config.php';
require PUN_ROOT.'include/common.php';
require './includes/lib.php';
define('SITE_PAGE', 'index.php'); //Page where you want your users to be redirected.  Use $_SERVER['PHP_SELF'] to find it


// Load the login.php language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/login.php';
require './includes/lang-fr.php';

$action = isset($_GET['action']) ? $_GET['action'] : null;

if ($action == 'out')
{
	if ($pun_user['is_guest'] || !isset($_GET['id']) || $_GET['id'] != $pun_user['id'] || !isset($_GET['csrf_token']) || $_GET['csrf_token'] != pun_hash($pun_user['id'].pun_hash(get_remote_address())))
	{
		header('Location: index.php');
		exit;
	}

	// Remove user from "users online" list
	$db->query('DELETE FROM '.$db->prefix.'online WHERE user_id='.$pun_user['id']) or error('Unable to delete from online list', __FILE__, __LINE__, $db->error());

	// Update last_visit (make sure there's something to update it with)
	if (isset($pun_user['logged']))
		$db->query('UPDATE '.$db->prefix.'users SET last_visit='.$pun_user['logged'].' WHERE id='.$pun_user['id']) or error('Unable to update user visit data', __FILE__, __LINE__, $db->error());

	pun_setcookie(1, pun_hash(uniqid(rand(), true)), time() + 31536000);

	site_redirect(BASE_URL.SITE_PAGE, $lang_login['Logout redirect']);
}
else
	header('Location: index.php');
	exit;
