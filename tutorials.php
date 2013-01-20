<?php require './includes/init-main.php';

if($site_config['o_enable_tuts'] == '0')
	site_msg($lang_site['Module disabled']);

//Pour l'inclusion des modules appropriés
if(isset($_GET['cat']))
{
	$cat_id = intval($_GET['cat']);
	if($cat_id < 1)
		site_msg($lang_site['Bad request']);

	$lang = $lang_site['Lang'];
	$cat_result = $db->query('SELECT tcat_id, tcat_name, tcat_clearname, tcat_lang, tcat_desc FROM tuts_cat WHERE tcat_id='.$cat_id.' AND tcat_lang LIKE(\'%'.$lang.'%\')') or error('Unable to fetch category data', __FILE__, __LINE__, $db->error());

	if(!$db->num_rows($cat_result))
		site_msg($lang_site['Category not found']);

	$lang = $lang_site['Lang'];
	$cur_cat = $db->fetch_assoc($cat_result);

	require PUN_ROOT.'include/parser.php';
	$catname = pun_htmlspecialchars(shorttext_lang($cur_cat['tcat_name'],$lang));
	$catclear = pun_htmlspecialchars(shorttext_lang($cur_cat['tcat_clearname'],$lang));
	$catdesc = parse_message(longtext_lang($cur_cat['tcat_desc'],$lang),0);

	$filter = !$pun_user['is_admmod'] ? ' AND tentry_publish=1' : '';

	$sql = 'SELECT tentry_id, tentry_name, tentry_icon, tentry_lang, tentry_desc, tentry_type, tentry_level, tentry_author, tentry_publishdate, tentry_lastupdate, tentry_comments, tentry_catid, tentry_version, id, username, tcat_id, tcat_name, tcat_clearname, type_id, type_name, version_id, version_name FROM tuts_entries LEFT JOIN '.$db->prefix.'users ON tentry_author=id LEFT JOIN tuts_cat ON tentry_catid=tcat_id LEFT JOIN tuts_type ON tentry_type=type_id LEFT JOIN tuts_versions ON tentry_version=version_id WHERE tentry_catid='.$cat_id.' AND tentry_lang=\''.$lang.'\''.$filter;

	//For the searchbox
	$url = '';
	$sql_to_add = '';
	if(isset($_POST['sort']) || isset($_POST['page']))
	{
		$level = intval(getpost('level'));
		if($level > 0 && $level <= 3)
		{
			$sql_to_add .= ' AND tentry_level='.$level;
			$url .= '&amp;level='.$level;
		}
		$version = intval(getpost('version'));
		if($version > 0)
		{
			$sql_to_add .= ' AND tentry_version='.$version;
			$url .= '&amp;version='.$version;
		}
		$type = intval(getpost('type'));
		if($type > 0)
		{
			$sql_to_add .= ' AND tentry_type='.$type;
			$url .= '&amp;type='.$type;
		}
		$sort = array('id' => 'tentry_id', 'name' => 'tentry_name', 'type' => 'type_name', 'level' => 'tentry_level', 'lastupdate' => 'tentry_lastupdate', 'version' => 'tentry_version');
		$sortby = pun_trim(getpost('sortby'));
		if(array_key_exists($sortby,$sort))
		{
			$sql_to_add .= ' ORDER BY '.$sort[$sortby];
			$url .= '&amp;sortby='.$sort[$sortby];
		}
		$order = pun_trim(getpost('order'));
		if($order == 'desc')
		{
			$sql_to_add .= ' DESC';
			$url .= '&amp;order=DESC';
		}
	}
	// To avoid 10km-long pages, we'll paginate them
	define('NB_TUTS',$site_config['o_tuts_per_page']);
	if(isset($_GET['p']))
	{
		$page = intval($_GET['p']);
		if($page > 0)
			$to_start = ($page - 1)*NB_TUTS;
		else
			$to_start = 0;
	}
	else
	{
		$to_start = 0;
		$page = 0;
	}
	$sql_limit = ' LIMIT '.$to_start.','.NB_TUTS;


	$tut_result = $db->query($sql . $sql_to_add . $sql_limit) or error('Unable to get tutorial data', __FILE__, __LINE__, $db->error());
	if($db->num_rows($tut_result) == 0)
		site_msg($lang_site['No tutorial']);

	//For searchbox
	$ver_result = $db->query('SELECT version_id, version_name FROM tuts_versions WHERE version_cat='.$cat_id.' ORDER BY version_name') or error('Unable to fetch version info', __FILE__, __LINE__, $db->error());
	$type_result = $db->query('SELECT type_id, type_name FROM tuts_type ORDER BY type_name') or error('Unable to fetch type info', __FILE__, __LINE__, $db->error());

// Pagination
if($pun_user['is_admmod'])
	$count_sql = 'SELECT COUNT(tentry_id) FROM tuts_entries WHERE tentry_catid='.$cat_id.' AND tentry_lang=\''.$lang.'\' '.$sql_to_add;
else
	$count_sql = 'SELECT COUNT(tentry_id) FROM tuts_entries WHERE tentry_catid='.$cat_id.' AND tentry_lang=\''.$lang.'\' AND tentry_publish=1 '.$sql_to_add;
$count = $db->query($count_sql) or error('Unable to fetch tutorial data', __FILE__, __LINE__, $db->error());
$nb_tuts = $db->result($count);
$nb_pages = ceil(($nb_tuts - 1) / NB_TUTS);
$paginate = '<p class="paginate">'.$lang_site['Pages'].':'.site_paginate($nb_pages, $page, 'tutorials.php?cat='.$cat_id);;

	$titre_page = order_by_lang($lang,$lang_site['Tutorials'],$catname);
	$module = 'tutorials';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="tutorials.php"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="tutorials.php?cat=<?php echo $cat_id; ?>"><?php echo $catname; ?></a></p>
<h3><?php echo order_by_lang($lang,$lang_site['Tutorials'],$catname); ?></h3>
<?php echo '<p>'.$cur_cat['tcat_desc_'.$lang].'</p>';
?>
<form method="post" action="">
<fieldset>
	<legend><?php echo $lang_site['Sort tutorials']; ?></legend>
	<p>
		<label for="level"><?php echo $lang_site['Level']; ?>
			<select id="level" name="level">
				<option value="0"><?php echo $lang_site['All']; ?></option>
				<option value="1"><?php echo $lang_site['Newbie']; ?></option>
				<option value="2"><?php echo $lang_site['Intermediate']; ?></option>
				<option value="3"><?php echo $lang_site['Advanced']; ?></option>
			</select>
		</label> - <?php if(!empty($ver_result)): ?><label for="version"><?php echo $lang_site['Version']; ?>
		<select id="version" name="version">
			<option value="0"><?php echo $lang_site['All']; ?></option>
<?php
		while($ver = $db->fetch_assoc($ver_result))
			echo "\t\t\t\t".'<option value="'.$ver['version_id'].'">'.pun_htmlspecialchars($ver['version_name']).'</option>';
?>
		</select>
		</label> - <?php endif; ?><?php if(!empty($type_result)): ?><label for="type"><?php echo $lang_site['Type']; ?>
			<select id="type" name="type">
				<option value="0"><?php echo $lang_site['All']; ?></option>
<?php
		while($type = $db->fetch_assoc($type_result))
			echo "\t\t\t\t".'<option value="'.$type['type_id'].'">'.pun_htmlspecialchars(shorttext_lang($type['type_name'],$lang)).'</option>';
?>
			</select>
		</label> - <?php endif; ?><label for="sortby"><?php echo $lang_site['Sort by']; ?>
			<select id="sortby" name="sortby">
				<option value="id"><?php echo $lang_site['Tutorial ID']; ?></option>
				<option value="name"><?php echo $lang_site['Name']; ?></option>
				<?php if(!empty($ver_result)): ?><option value="version"><?php echo $lang_site['Version']; ?></option><?php endif; ?>
				<?php if(!empty($type_result)): ?><option value="type"><?php echo $lang_site['Type']; ?></option><?php endif; ?>
				<option value="level"><?php echo $lang_site['Level']; ?></option>
				<option value="lastupdate"><?php echo $lang_site['Last update']; ?></option>
			</select>
		</label> - <label for="order"><?php echo $lang_site['Order']; ?>
			<select id="order" name="order">
				<option value="asc"><?php echo $lang_site['Ascending']; ?></option>
				<option value="desc"><?php echo $lang_site['Descending']; ?></option>
			</select>
		</label>
	</p>
</fieldset>
<p class="submit"><input type="submit" value="<?php echo $lang_site['Sort']; ?>" name="sort" /></p>
</orm>
<?php echo $paginate;
echo '<hr class="sep" />';
$level = array(1 => $lang_site['Newbie'], 2 => $lang_site['Intermediate'], 3 => $lang_site['Advanced']);

	while($cur_tut = $db->fetch_assoc($tut_result))
	{
		?>
		<h4 class="subtitle"><?php echo pun_htmlspecialchars($cur_tut['tentry_name']); ?></h4>
		<p class="tut-preview"><img src="<?php echo $cur_tut['tentry_icon']; ?>" alt="<?php echo $lang_site['Tutorial icon']; ?>" /><br /></p>
		<ul class="tuto-desc">
			<li><strong><?php echo $lang_site['Author']; ?> :</strong> <?php echo pun_htmlspecialchars($cur_tut['username']); ?></li>
			<li><strong><?php echo $lang_site['Type']; ?> :</strong> <?php echo pun_htmlspecialchars(shorttext_lang($cur_tut['type_name'],$lang)); ?></li>
			<li><strong><?php echo $lang_site['Level']; ?> :</strong> <?php echo $level[$cur_tut['tentry_level']]; ?></li>
			<?php if($cur_tut['tentry_version'] > 0): ?><li><strong><?php echo $lang_site['Version']; ?> :</strong> <?php echo pun_htmlspecialchars($cur_tut['version_name']); ?></li><?php endif; ?>
		</ul>
		<?php echo parse_message($cur_tut['tentry_desc'],0); ?>
		<p class="read"><a href="tutorials.php?tut=<?php echo $cur_tut['tentry_id']; ?>"><?php echo $lang_site['Read tutorial']; ?></a></p>
		</ul>
		<hr class="sep" />
	<?php
	}
	echo $paginate;
	require './includes/bottom.php';
}
elseif(isset($_GET['tut']))
{
	$tut_id = intval($_GET['tut']);
	if($tut_id < 1)
		site_msg($lang_site['Bad request']);

	$print = 'true';
	$lang = $lang_site['Lang'];
	$filter = !$pun_user['is_admmod'] ? ' AND tentry_publish=1' : '';
	$sql = 'SELECT tentry_id, tentry_name, tentry_lang, tentry_desc, tentry_author, tentry_publishdate, tentry_lastupdate, tentry_comments, tentry_catid, tentry_version, tentry_level, tentry_type, id, username, tcat_id, tcat_name, tcat_clearname, version_id, version_name, type_id, type_name FROM tuts_entries LEFT JOIN tuts_cat ON tentry_catid=tcat_id LEFT JOIN '.$db->prefix.'users ON tentry_author=id LEFT JOIN tuts_versions ON tentry_version=version_id LEFT JOIN tuts_type ON tentry_type=type_id WHERE tentry_id='.$tut_id.' AND tentry_lang=\''.$lang.'\''.$filter;

	$query = $db->query($sql) or error('Unable to get tutorial data', __FILE__, __LINE__, $db->error());
	if(!$db->num_rows($query))
		site_msg($lang_site['Tutorial not found']);
	$tut = $db->fetch_assoc($query);
	$query2 = $db->query('SELECT text_id, text_name, text_order, text_entryid FROM tuts_texts WHERE text_entryid='.$tut['tentry_id'].' ORDER BY text_order') or error('Unable to get tutorial part data', __FILE__, __LINE__, $db->error());

	if($db->num_rows($query2) == 0)
		site_msg($lang_site['No part']);

	if ($pun_config['o_avatars'] == '1' && $pun_user['show_avatars'] != '0')
	{
		if (isset($user_avatar_cache[$tut['tentry_author']]))
			$user_avatar = $user_avatar_cache[$tut['tentry_author']];
		else
			$user_avatar = $user_avatar_cache[$tut['tentry_author']] = generate_avatar_markup($tut['tentry_author']);
	}
	require PUN_ROOT.'include/parser.php';

	$catname = pun_htmlspecialchars(shorttext_lang($tut['tcat_name'],$lang));
	$catclear = pun_htmlspecialchars(shorttext_lang($tut['tcat_clearname'],$lang));
	$typename = pun_htmlspecialchars(shorttext_lang($tut['type_name'],$lang));

	$level = array(1 => $lang_site['Newbie'], 2 => $lang_site['Intermediate'], 3 => $lang_site['Advanced']);
	$titre_page = '['.$catname.'] '.pun_htmlspecialchars($tut['tentry_name']).' - '.$lang_site['Pagename view tutorial'];
	$module = 'tutorials';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="tutorials.php"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="tutorials.php?cat=<?php echo $tut['tcat_id']; ?>"><?php echo $catclear; ?></a> &gt; <a href="tutorials.php?id=<?php echo $tut['tentry_id']; ?>"><?php echo pun_htmlspecialchars($tut['tentry_name']); ?></a></p>
<h3>[<?php echo $catname; ?>] <?php echo pun_htmlspecialchars($tut['tentry_name']); ?></h3>
<div id="tuto-infos">
	<p class="avatar">
		<?php echo $user_avatar; ?>
	</p>
	<ul class="tuto-info list1">
		<li><strong><?php echo $lang_site['Author']; ?> :</strong> <?php echo pun_htmlspecialchars($tut['username']); ?></li>
		<li><strong><?php echo $lang_site['Published']; ?> :</strong> <?php echo format_time($tut['tentry_publishdate']); ?></li>
		<li><strong><?php echo $lang_site['Updated']; ?> :</strong> <?php echo format_time($tut['tentry_lastupdate']); ?></li>
		<li><strong><?php echo $lang_site['Comments']; ?> :</strong> <a href="tutorials.php?id=<?php echo $tut['tentry_id']; ?>&amp;viewcomment=<?php echo $tut['tentry_id']; ?>"><?php echo $tut['tentry_comments']; ?></a></li>
	</ul>
	<ul class="tuto-info list2">
		<li><strong><?php echo $lang_site['Type']; ?> :</strong> <?php echo $typename; ?></li>
		<li><strong><?php echo $lang_site['Level']; ?> :</strong> <?php echo $level[$tut['tentry_level']]; ?></li>
		<?php if($tut['tentry_version'] > 0): ?><li><strong><?php echo $lang_site['Version']; ?> :</strong> <?php echo $tut['version_name']; ?></li><?php endif; ?>
	</ul>
</div>
<hr class="sep" />
<p><?php echo parse_message($tut['tentry_desc'],0); ?></p>
<h4 class="subtitle"><?php echo $lang_site['Summary']; ?></h4>
<?php
	//Deuxième requête, pour les pages
	$ids = array();
?>
<ol class="summary">
<?php
		while($subpart = $db->fetch_assoc($query2))
		{
			$ids[] = $subpart['text_id'];
			echo '<li><a href="tutorials.php?tut='.$tut['tentry_id'].'&amp;sub='.$subpart['text_id'].'">'.pun_htmlspecialchars($subpart['text_name']).'</a></li>';
		}
?>
</ol>
<hr class="sep" />
<?php
		//On utilise une variable GET pour la sous-partie à afficher
		if(isset($_GET['sub']) && intval($_GET['sub']) > 0 && in_array($_GET['sub'],$ids))
			$subpart_id = $_GET['sub'];
		else
			$subpart_id = $ids[0]; //Récupère le premier élément du tableau
		$next_key = array_search($subpart_id,$ids) + 1;
		$next = (array_key_exists($next_key,$ids)) ? true : false;
		$previous_key = array_search($subpart_id,$ids) - 1;
		$previous = (array_search($subpart_id,$ids) > 0) ? true : false;

		//Sous-partie correspondante
		$query3 = $db->query('SELECT text_id, text_name, text_text, text_order FROM tuts_texts WHERE text_id='.$subpart_id);
		if($db->num_rows($query3) > 0)
		{
			$text = $db->fetch_assoc($query3);
?>
<h4 class="subpart"><?php echo pun_htmlspecialchars($text['text_name']); ?></h4>
<div class="subpart-text"><?php echo parse_message($text['text_text'],0); ?></div>
<p class="next-previous"><?php echo (($previous == true)?'<a href="tutorials.php?tut='.$tut['tentry_id'].'&amp;sub='.$ids[$previous_key].'">'.$lang_site['Previous'].'</a> - ':''); ?> <a href="tutorials.php?viewcomment=<?php echo $tut['tentry_id']; ?>"><?php echo $tut['tentry_comments'].' '.$lang_site['comments']; ?></a> <?php echo (($next == true)?' - <a href="tutorials.php?tut='.$tut['tentry_id'].'&amp;sub='.$ids[$next_key].'">'.$lang_site['Next'].'</a>':''); ?></p>
<?php
		}
		else
			echo '<p>'.$lang_site['Subpart not found'].'</p>';
	require './includes/bottom.php';
}
/* Ajout d'un commentaire */
elseif(isset($_GET['add_comment']))
{
	$tut_id = intval($_GET['add_comment']);
	if($tut_id < 1)
		site_msg($lang['Bad request']);

	if(isset($_POST['form-sent']))
	{
		$poster = intval($_POST['poster_id']);
		if($poster != $pun_user['id'])
			site_msg($lang_site['Bad request']);

		if(!$pun_user['is_admmod'])
		{
			$query = $db->query('SELECT tentry_publish FROM tuts_entries WHERE tentry_id='.$tut_id) or error('Unable to get tutorial data', __FILE__, __LINE__, $db->error());
			$publish = $db->result($query);
			if($publish != 1)
				site_msg($lang_site['No permission']);
		}
		$orig_message = pun_linebreaks(pun_trim($_POST['content']));
		$errors = array();
		if ($pun_config['p_message_all_caps'] == '0' && is_all_uppercase($orig_message) && !$pun_user['is_admmod'])
			$errors[] = $lang_site['All caps message'];
		if ($pun_config['p_message_bbcode'] == '1')
		{
			require PUN_ROOT.'include/parser.php';
			$parsed_msg = preparse_bbcode($orig_message, $errors);
		}
		else
			$parsed_msg = $orig_message;
		if (empty($errors))
		{
			if ($parsed_msg == '')
				$errors[] = $lang_post['Empty comment'];
			else if ($pun_config['o_censoring'] == '1')
			{
				// Censor message to see if that causes problems
				$censored_msg = pun_trim(censor_words($parsed_msg));

				if ($censored_msg == '')
					$errors[] = $lang_post['No message after censoring'];
			}
			else
				$censored_msg = pun_trim($parsed_msg);
		}
		if(empty($errors) && !isset($_POST['preview']))
		{
			$res = $db->query('SELECT tentry_comments FROM tuts_entries WHERE tentry_id='.$tut_id) or error('Unable to get tutorial data', __FILE__, __LINE__, $db->error());
			if(!$res)
				site_msg($lang_site['Bad request']);
			$nb_comm = $db->result($res);
			$nb_comm = (int)$nb_comm;
			$db->query('UPDATE tuts_entries SET tentry_comments='.++$nb_comm.' WHERE tentry_id='.$tut_id) or error('Unable to update tutorial data', __FILE__, __LINE__, $db->error());

			$now = time();
			$db->query('INSERT INTO tuts_comments (comment_id,comment_entryid,comment_content,comment_author,comment_ip,comment_publishdate,comment_lastupdate) VALUES(\'\','.$tut_id.',\''.$db->escape($censored_msg).'\','.$poster.',\''.get_remote_address().'\','.$now.','.$now.')') or error('Unable to add comment', __FILE__, __LINE__, $db->error());

			if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
				require './includes/cache.php';

			//On confirme l'ajout
			generate_admin_home_cache();
			site_redirect('tutorials.php?viewcomment='.$tut_id, $lang_site['Comment added redirect']);
		}
	}
	//Les admins et modérateurs ont le droit d'écrire et éditer des commentaires, que le tuto soit publié ou non.
	$lang = $lang_site['Lang'];
	if($pun_user['is_admmod'])
		$sql = 'SELECT tentry_id, tentry_name, tentry_catid, tcat_id, tcat_name, tcat_clearname FROM tuts_entries LEFT JOIN tuts_cat ON tentry_catid=tcat_id WHERE tentry_id='.$tut_id;
	else
		$sql = 'SELECT tentry_id, tentry_name, tentry_catid, tcat_id, tcat_name, tcat_clearname FROM tuts_entries LEFT JOIN tuts_cat ON tentry_catid=tcat_id WHERE tentry_id='.$tut_id.' AND tentry_publish=1';

	$query = $db->query($sql) or error('Unable to get tutorial data', __FILE__, __LINE__, $db->error());
	if($db->num_rows($query) == 0)
		site_msg($lang_site['Tutorial not found']);
	$tut = $db->fetch_assoc($query);

	$catname = pun_htmlspecialchars(shorttext_lang($tut['tcat_name'],$lang));
	$catclear = pun_htmlspecialchars(shorttext_lang($tut['tcat_clearname'],$lang));

	if(isset($_GET['quote']) && intval($_GET['quote'] > 0))
	{
		//On cite quelqu'un
		$q = $db->query('SELECT comment_id, comment_entryid, comment_content, comment_author, id, username FROM tuts_comments LEFT JOIN '.$db->prefix.'users ON comment_author=id WHERE comment_id='.$_GET['quote'].' AND comment_entryid='.$tut_id) or error('Unable to get comment data', __FILE__, __LINE__, $db->error());
		if($db->num_rows($q) > 0)
		{
			$comm = $db->fetch_assoc($q);
			$comm['username'] = pun_htmlspecialchars($comm['username']);
			//Vient directement du fichier post.php de FluxBB
			// If the message contains a code tag we have to split it up (text within [code][/code] shouldn't be touched)
			if (strpos($comm['comment_content'], '[code]') !== false && strpos($comm['comment_content'], '[/code]') !== false)
			{
				$errors = array();
				list($inside, $outside) = split_text($comm['comment_content'], '[code]', '[/code]', $errors);
				if (!empty($errors)) // Technically this shouldn't happen, since $q_message is an existing post it should only exist if it previously passed validation
					message($errors[0]);

				$comm['comment_content'] = implode("\1", $outside);
			}

			// Remove [img] tags from quoted message
			$comm['comment_content'] = preg_replace('%\[img(?:=(?:[^\[]*?))?\]((ht|f)tps?://)([^\s<"]*?)\[/img\]%U', '\1\3', $comm['comment_content']);

			// If we split up the message before we have to concatenate it together again (code tags)
			if (isset($inside))
			{
				$outside = explode("\1", $comm['comment_content']);
				$comm['comment_content'] = '';

				$num_tokens = count($outside);
				for ($i = 0; $i < $num_tokens; ++$i)
				{
					$comm['comment_content'] .= $outside[$i];
					if (isset($inside[$i]))
						$comm['comment_content'] .= '[code]'.$inside[$i].'[/code]';
				}

				unset($inside);
			}

			if ($pun_config['o_censoring'] == '1')
				$comm['comment_content'] = censor_words($comm['comment_content']);

			$comm['comment_content'] = pun_htmlspecialchars($comm['comment_content']);

			if ($pun_config['p_message_bbcode'] == '1')
			{
				// If username contains a square bracket, we add "" or '' around it (so we know when it starts and ends)
				if (strpos($comm['username'], '[') !== false || strpos($comm['username'], ']') !== false)
				{
					if (strpos($comm['username'], '\'') !== false)
						$comm['username'] = '"'.$comm['username'].'"';
					else
						$comm['username'] = '\''.$comm['username'].'\'';
				}
				else
				{
					// Get the characters at the start and end of $q_poster
					$ends = substr($comm['username'], 0, 1).substr($comm['username'], -1, 1);

					// Deal with quoting "Username" or 'Username' (becomes '"Username"' or "'Username'")
					if ($ends == '\'\'')
						$comm['username'] = '"'.$comm['username'].'"';
					else if ($ends == '""')
						$comm['username'] = '\''.$comm['username'].'\'';
				}

				$quote = '[quote='.$comm['username'].']'.$comm['comment_content'].'[/quote]'."\n";
			}
			else
				$quote = '> '.$comm['username'].' '.$lang_site['wrote']."\n\n".'> '.$comm['comment_content']."\n";
		}
	}
	$titre_page = $lang_site['Add comment'];
	$module = 'tutorials';
	require './includes/top.php';
?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="tutorials.php"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="tutorials.php?cat=<?php echo $tut['tcat_id']; ?>"><?php echo $catclear; ?></a> &gt; <a href="tutorials.php?tut=<?php echo $tut['tentry_id']; ?>"><?php echo pun_htmlspecialchars($tut['tentry_name']); ?></a> &gt; <a href="tutorials.php?add_comment=<?php echo $tut['tentry_id']; ?>"><?php echo $lang_site['Add comment']; ?></a></p>
<h3>[<?php echo $catname; ?>] <?php echo pun_htmlspecialchars($tut['tentry_name']); ?></h3>
<?php echo $lang_site['Explain add comment'];
	if (!empty($errors))
	{
?>
<div id="posterror" class="block">
	<h4><span><?php echo $lang_site['Comment errors']; ?></span></h4>
	<div class="box">
		<p><?php echo $lang_site['Comment errors info']; ?></p>
		<ul class="error-list">
<?php
	foreach ($errors as $cur_error)
		echo "\t\t\t\t".'<li><strong>'.$cur_error.'</strong></li>'."\n";
?>
		</ul>
	</div>
</div>
<?php
	}
	else if (isset($_POST['preview']))
	{
		require_once PUN_ROOT.'include/parser.php';
		$preview_message = parse_message($censored_msg,0);
?>
<div id="postpreview" class="blockpost block">
	<h4><span><?php echo $lang_site['Comment preview']; ?></span></h4>
	<div class="box">
		<?php echo $preview_message."\n"; ?>
	</div>
</div>
<?php
	}
?>
<form method="post" action="tutorials.php?add_comment=<?php echo $tut['tentry_id']; ?>">
	<fieldset>
		<legend><?php echo $lang_site['Add comment']; ?></legend>
		<p class="form">
			<input type="hidden" name="form-sent" value="1" />
			<input type="hidden" name="poster_id" value="<?php echo $pun_user['id']; ?>" />
			<label for="textcontent"><?php echo $lang_site['Message']; ?></label><br />
			<textarea name="content" id="textcontent" cols="95" rows="20"><?php echo (!empty($orig_message)) ? pun_htmlspecialchars($orig_message) : ((isset($quote)) ? $quote : ''); ?></textarea>
		</p>
		<ul class="bblinks">
			<li><span><a href="<?php echo PUN_ROOT; ?>help.php#bbcode" onclick="window.open(this.href); return false;"><?php echo $lang_site['BBCode']; ?></a> <?php echo ($pun_config['p_message_bbcode'] == '1') ? $lang_site['on'] : $lang_site['off']; ?></span></li>
			<li><span><a href="<?php echo PUN_ROOT; ?>help.php#img" onclick="window.open(this.href); return false;"><?php echo $lang_site['img tag']; ?></a> <?php echo ($pun_config['p_message_bbcode'] == '1' && $pun_config['p_message_img_tag'] == '1') ? $lang_site['on'] : $lang_site['off']; ?></span></li>
			<li><span><a href="<?php echo PUN_ROOT; ?>help.php#smilies" onclick="window.open(this.href); return false;"><?php echo $lang_site['Smilies']; ?></a> <?php echo ($pun_config['o_smilies'] == '1') ? $lang_site['on'] : $lang_site['off']; ?></span></li>
		</ul>
	</fieldset>
	<p class="buttons"><input type="submit" name="save" value="<?php echo $lang_site['Submit']; ?>" /> <input type="submit" name="preview" value="<?php echo $lang_site['Preview post']; ?>" /> <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
/* Édition d'un commentaire */			
elseif(isset($_GET['edit_comment']))
{
	$comment_id = intval($_GET['edit_comment']);
	if($comment_id < 1)
		site_msg($lang_site['Bad request']);

	if(isset($_GET['csrf']))
	{
		$check = sha1($pun_user['username'].$_SERVER['REMOTE_ADDR']);
		if($check !== $_GET['csrf'])
			site_msg($lang['Bad request']);
	}

	if(isset($_POST['form-sent']))
	{
		$tut_id = intval($_POST['tut_id']);
		if($tut_id < 1)
			site_msg($lang_site['Bad request']);

		$poster = intval($_POST['poster_id']);

		if(!$pun_user['is_admmod'])
		{
			if($poster != $pun_user['id'])
				site_msg($lang_site['No permission']);

			$query = $db->query('SELECT tentry_publish FROM tuts_entries LEFT JOIN tuts_comments ON comment_entryid=tentry_id WHERE comment_id='.$comment_id) or error('Unable to get tutorial data', __FILE__, __LINE__, $db->error());
			$publish = $db->result($query);
			if($publish != 1)
				site_msg($lang_site['No permission']);
		}

		$orig_message = pun_linebreaks(pun_trim($_POST['content']));

		$errors = array();
		/*else*/ if ($pun_config['p_message_all_caps'] == '0' && is_all_uppercase($orig_message) && !$pun_user['is_admmod'])
			$errors[] = $lang_site['All caps message'];

		if ($pun_config['p_message_bbcode'] == '1')
		{
			require PUN_ROOT.'include/parser.php';
			$parsed_msg = preparse_bbcode($orig_message, $errors);
		}
		else
			$parsed_msg = $orig_message;
		if (empty($errors))
		{
			if ($parsed_msg == '')
				$errors[] = $lang_post['Empty comment'];
			else if ($pun_config['o_censoring'] == '1')
			{
				// Censor message to see if that causes problems
				$censored_msg = pun_trim(censor_words($parsed_msg));

				if ($censored_msg == '')
					$errors[] = $lang_post['No message after censoring'];
			}
			else
				$censored_msg = pun_trim($parsed_msg);
		}

		if(empty($errors) && !isset($_POST['preview']))
		{
			$db->query('UPDATE tuts_comments SET comment_content=\''.$db->escape($censored_msg).'\', comment_lastupdate='.time().' WHERE comment_id='.$comment_id) or error('Unable to edit comment', __FILE__, __LINE__, $db->error());
		
			//On confirme l'ajout
			if(preg_match('#admin_comments.php#',$_SERVER['HTTP_REFERER']) && $pun_user['is_admmod'])
				site_redirect('admin_comments.php', $lang_site['Comment edited redirect']);
			else
				site_redirect('tutorials.php?viewcomment='.$tut_id, $lang_site['Comment edited redirect']);
		}
	}
	$lang = $lang_site['Lang'];
	if($pun_user['is_admmod'])
		$sql = 'SELECT comment_id, comment_entryid, comment_content, comment_author, tcat_id, tcat_name, tcat_clearname, tentry_id, tentry_name, tentry_catid FROM tuts_comments LEFT JOIN tuts_entries ON comment_entryid=tentry_id LEFT JOIN tuts_cat ON tentry_catid=tcat_id WHERE comment_id='.$comment_id;
	else
		$sql = 'SELECT comment_id, comment_entryid, comment_content, comment_author, tcat_id, tcat_name, tcat_clearname, tentry_id, tentry_name, tentry_catid FROM tuts_comments LEFT JOIN tuts_entries ON comment_entryid=tentry_id LEFT JOIN tuts_cat ON tentry_catid=tcat_id WHERE comment_id='.$comment_id.' AND tenry_publish=1';

	$query = $db->query($sql) or error('Unable to get comment data', __FILE__, __LINE__, $db->error());

	if(!$db->num_rows($query))
		site_msg($lang_site['Comment not found']);
	$comment = $db->fetch_assoc($query);
	if(!$pun_user['is_admmod'] && $pun_user['id'] != $commauthor)
		site_msg($lang_site['No permission']);

	$catname = pun_htmlspecialchars(shorttext_lang($comment['tcat_name'],$lang));
	$catclear = pun_htmlspecialchars(shorttext_lang($comment['tcat_clearname'],$lang));

	$titre_page = $lang_site['Edit comment'];
	$module = 'tutorials';
	require './includes/top.php';
?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="tutorials.php"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="tutorials.php?cat=<?php echo $comment['tcat_id']; ?>"><?php echo $catclear; ?></a> &gt; <a href="tutorials.php?tut=<?php echo $comment['tentry_id']; ?>"><?php echo pun_htmlspecialchars($comment['tentry_name']); ?></a> &gt; <a href="tutorials.php?edit_comment=<?php echo $comment_id; ?>"><?php echo $lang_site['Edit comment']; ?></a></p>
<h3>[<?php echo $catname; ?>] <?php echo pun_htmlspecialchars($comment['tentry_name']); /* ?> &gt; <?php echo $lang_site['Edit comment']; */ ?></h3>

<?php
	if (!empty($errors))
	{
?>
<div id="posterror" class="block">
	<h4><span><?php echo $lang_site['Comment errors']; ?></span></h4>
	<div class="box">
		<p><?php echo $lang_site['Comment errors info']; ?></p>
		<ul class="error-list">
<?php
	foreach ($errors as $cur_error)
		echo "\t\t\t\t".'<li><strong>'.$cur_error.'</strong></li>'."\n";
?>
		</ul>
	</div>
</div>
<?php
	}
	else if (isset($_POST['preview']))
	{
		require_once PUN_ROOT.'include/parser.php';
		$preview_message = parse_message($censored_msg,0);
?>
<div id="postpreview" class="blockpost">
	<h4><span><?php echo $lang_site['Comment preview']; ?></span></h4>
	<div class="box">
		<?php echo $preview_message."\n"; ?>
	</div>
</div>
<?php
	}
?>
<form method="post" action="tutorials.php?edit_comment=<?php echo $comment_id; ?>&amp;csrf=<?php echo sha1($pun_user['username'].$_SERVER['REMOTE_ADDR']); ?>">
	<fieldset>
		<legend><?php echo $lang_site['Edit comment']; ?></legend>
		<p class="form">
			<input type="hidden" name="poster_id" value="$commauthor" />
			<input type="hidden" name="form-sent" value="1" />
			<input type="hidden" name="tut_id" value="<?php echo $comment['tentry_id']; ?>">
			<label for="text_content"><?php echo $lang_site['Message']; ?></label><br />
			<textarea name="content" id="text_content" cols="95" rows="20"><?php echo ((!empty($orig_message)) ? pun_htmlspecialchars($orig_message) : pun_htmlspecialchars($comment['comment_content'])); ?></textarea>
		</p>
		<ul class="bblinks">
			<li><span><a href="<?php echo PUN_ROOT; ?>help.php#bbcode" onclick="window.open(this.href); return false;"><?php echo $lang_site['BBCode'] ?></a> <?php echo ($pun_config['p_message_bbcode'] == '1') ? $lang_site['on'] : $lang_site['off']; ?></span></li>
			<li><span><a href="<?php echo PUN_ROOT; ?>help.php#img" onclick="window.open(this.href); return false;"><?php echo $lang_site['img tag'] ?></a> <?php echo ($pun_config['p_message_bbcode'] == '1' && $pun_config['p_message_img_tag'] == '1') ? $lang_site['on'] : $lang_site['off']; ?></span></li>
			<li><span><a href="<?php echo PUN_ROOT; ?>help.php#smilies" onclick="window.open(this.href); return false;"><?php echo $lang_site['Smilies'] ?></a> <?php echo ($pun_config['o_smilies'] == '1') ? $lang_site['on'] : $lang_site['off']; ?></span></li>
		</ul>
	</fieldset>
	<p class="buttons"><input type="submit" name="save" value="<?php echo $lang_site['Submit']; ?>" /> <input type="submit" name="preview" value="<?php echo $lang_site['Preview post']; ?>" /> <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
/* Supprimer un commentaire */
elseif(isset($_GET['del_comment']))
{
	$comment_id = intval($_GET['del_comment']);
	if($comment_id < 1)
		site_msg($lang_site['Bad request']);

	//Only admins and moderators can delete other users' comments
	if(!$pun_user['is_admmod'])
	{
		$result = $db->query('SELECT comment_author FROM tuts_comments WHERE comment_id='.$comment_id) or error('Unable to get comment data', __FILE__, __LINE__, $db->error());
		$poster = $db->result($result);
	
		if($pun_user['id'] != $poster)
			site_msg($lang_site['Cannot delete comment']);
	}
	if(isset($_GET['csrf']))
	{
		$check = sha1($pun_user['username'].$_SERVER['REMOTE_ADDR']);
		if($check !== $_GET['csrf'])
			site_msg($lang['Bad request']);
	}

	if(isset($_POST['del_comment_comply']))
	{
		//Grab the tutorial data, so we can update the comments counter
		$result = $db->query('SELECT tentry_id,tentry_name,tentry_comments FROM tuts_entries LEFT JOIN tuts_comments ON comment_entryid=tentry_id WHERE comment_id='.$comment_id) or error('Unable to get tutorial data', __FILE__, __LINE__, $db->error());
		
		$cur_tut = $db->fetch_assoc($result);
		$nb_reset = (int)$cur_tut['tentry_comments'];
		--$nb_reset;
		//Update comment counter
		$db->query('UPDATE tuts_entries SET tentry_comments='.$nb_reset.' WHERE tentry_id='.$cur_tut['tentry_id']) or error('Unable to update comments count', __FILE__, __LINE__, $db->error());

		//And finally delete comment
		$db->query('DELETE FROM tuts_comments WHERE comment_id='.$comment_id) or error('Unable to delete comment', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_admin_home_cache();
		if(preg_match('#admin_comments.php#',$_SERVER['HTTP_REFERER']) && $pun_user['is_admmod'])
			site_redirect('admin_comments.php', $lang_site['Comment deleted redirect']);
		elseif($nb_reset > 0)
			site_redirect('tutorials.php?viewcomment='.$cur_tut['tentry_id'], $lang_site['Comment deleted redirect']);
		else
			site_redirect('tutorials.php?tut='.$cur_tut['tentry_id'], $lang_site['Comment deleted redirect']);
	}
	else
	{
		$query = $db->query('SELECT comment_content FROM tuts_comments WHERE comment_id='.$comment_id) or error('Unable to get comment data', __FILE__, __LINE__, $db->error());
		$text = $db->result($query);
	
		$titre_page = $lang_site['Delete comment'];
		$module = 'tutorials';
		require './includes/top.php';
?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="tutorials.php"><?php echo $lang_site['Tutorials']; ?></a> &gt; <?php echo $lang_site['Delete comment']; ?></p>
<div class="blockform">
	<h3><span><?php echo $lang_site['Delete comment head']; ?></span></h3>
	<div class="box">
		<form method="post" action="tutorials.php?del_comment=<?php echo $comment_id; ?>&amp;csrf=<?php echo sha1($pun_user['username'].$_SERVER['REMOTE_ADDR']); ?>">
			<div class="inform">
			<input type="hidden" name="comment_to_delete" value="<?php echo $comment_id; ?>" />
				<fieldset>
					<legend><?php echo $lang_site['Confirm delete comment subhead']; ?></legend>
					<div class="infldset">
						<p><?php printf($lang_site['Confirm delete comment info'], pun_htmlspecialchars($text)); ?></p>
						<p class="warntext"><?php echo $lang_site['Delete comment warn']; ?></p>
					</div>
				</fieldset>
			</div>
			<p class="buttons"><input type="submit" name="del_comment_comply" value="<?php echo $lang_site['Delete']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
		</form>
	</div>
</div>
<div class="clearer"></div>
<?php require './includes/bottom.php';
	}
}
/* Voir les commentaires */
elseif(isset($_GET['viewcomment']))
{
	$tut_id = intval($_GET['viewcomment']);
	if($tut_id < 1)
		site_msg($lang_site['Bad request']);

	$lang = $lang_site['Lang'];
	if($pun_user['is_admmod'])
		$sql = 'SELECT tentry_id, tentry_name, tentry_lang, tentry_catid, tcat_id, tcat_name, tcat_clearname FROM tuts_entries LEFT JOIN tuts_cat ON tentry_catid=tcat_id WHERE tentry_id='.$tut_id.' AND tentry_lang=\''.$lang.'\'';
	else
		$sql = 'SELECT tentry_id, tentry_name, tentry_lang, tentry_catid, tcat_id, tcat_name, tcat_clearname FROM tuts_entries LEFT JOIN tuts_cat ON tentry_catid=tcat_id WHERE tentry_id='.$tut_id.' AND tentry_publish=1 AND tentry_lang=\''.$lang.'\'';

	$query = $db->query($sql) or error('Unablo to get tutorial data', __FILE__, __LINE__, $db->error());

	if(!$db->num_rows($query))
		site_msg($lang_site['Tutorial not found']);

	$tut = $db->fetch_assoc($query);

	$catname = pun_htmlspecialchars(shorttext_lang($tut['tcat_name'],$lang));
	$catclear = pun_htmlspecialchars(shorttext_lang($tut['tcat_clearname'],$lang));

	$titre_page = '['.pun_htmlspecialchars($tut['tcat_name_'.$lang]).'] '.pun_htmlspecialchars($tut['tentry_name']).' - '.$lang_site['Pagename view comments'];
	$module = 'tutorials';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="tutorials.php"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="tutorials.php?cat=<?php echo $tut['tcat_id']; ?>"><?php echo $catclear; ?></a> &gt; <a href="tutorials.php?tut=<?php echo $tut['tentry_id']; ?>"><?php echo pun_htmlspecialchars($tut['tentry_name']); ?></a> &gt; <a href="tutorials.php?viewcomment=<?php echo $tut['tentry_id']; ?>"><?php echo $lang_site['Comments']; ?></a></p>
<h3>[<?php echo $catname; ?>] <?php echo pun_htmlspecialchars($tut['tentry_name']); ?></h3>
<?php
	//Posting buttons.  For security reasons, only registered users can post comments
	if($pun_user['is_guest'])
		echo '<p class="infos">'.$lang_site['Must login to comment'].'</p>';
	else
		echo '<p class="post"><a href="tutorials.php?add_comment='.$tut_id.'">'.$lang_site['Post comment'].'</a>'.(($pun_user['is_admmod']) ? ' &nbsp; <a href="admin_comments.php">'.$lang_site['Comments moderation'].'</a>' : '').'</p>';

	//Tableau des commentaires (bin, en fait, ce sera pas un tableau, mais des <div>, comme sur un blog
	$query2 = $db->query('SELECT comment_id, comment_entryid, comment_content, comment_author, comment_ip, comment_publishdate, comment_lastupdate, id, username FROM tuts_comments LEFT JOIN '.$db->prefix.'users ON comment_author=id WHERE comment_entryid='.$tut_id.' ORDER BY comment_id') or error('Unable to get comment data', __FILE__, __LINE__, $db->error());
	$nb_comments = $db->num_rows($query2);
	if($nb_comments > 0)
	{
		require PUN_ROOT.'include/parser.php';
		$count = 0;
		while($comment = $db->fetch_assoc($query2))
		{
			$count++;
			//Avatar de l'utilisateur
			if ($pun_config['o_avatars'] == '1' && $pun_user['show_avatars'] != '0')
			{
				if (isset($user_avatar_cache[$comment['comment_author']]))
					$user_avatar = $user_avatar_cache[$comment['comment_author']];
				else
					$user_avatar = $user_avatar_cache[$comment['comment_author']] = generate_avatar_markup($comment['comment_author']);
			}
?>
<hr class="sep" />
<div class="comment">
	<p class="comment-avatar">
		<?php echo $user_avatar; ?>
	</p>
	<h4><?php echo $lang_site['By'].' '.pun_htmlspecialchars($comment['username']); ?><?php if($pun_user['is_admmod']): ?> (<?php echo $comment['comment_ip']; ?>)<?php endif; ?></h4>
	<p class="comment-details"><em><?php echo $lang_site['Published'].' '.format_time($comment['comment_publishdate']); ?></em>
<?php
			//Ces liens doivent être inaccessibles aux invités
			if($pun_user['logged'])
			{
				echo ' - <a href="tutorials.php?add_comment='.$tut_id.'&amp;quote='.$comment['comment_id'].'">'.$lang_site['Quote'].'</a>';
				//Si l'utilisateur n'est pas modo ni admin, il ne peut éditer que ses propres messages
				if($pun_user['id'] == $comment['id'] || $pun_user['is_admmod'])
					echo ' - <a href="tutorials.php?edit_comment='.$comment['comment_id'].'">'.$lang_site['Edit'].'</a>';
				//Si l'utilisateur n'est pas modo ou admin, il ne peut supprimer son message que si personne n'a posté après lui
				if($count == $nb_comments || $pun_user['is_admmod'])
					echo ' - <a href="tutorials.php?del_comment='.$comment['comment_id'].'&amp;csrf='.sha1($pun_user['username'].$_SERVER['REMOTE_ADDR']).'">'.$lang_site['Delete'].'</a>';
			}
?>
	</p>
	<div class="comment-msg"><?php echo parse_message($comment['comment_content'],0);
			if($comment['comment_publishdate'] != $comment['comment_lastupdate'] && $comment['comment_lastupdate'] != 0)
				echo '<p>'.$lang_site['Updated'] . format_time($comment['comment_lastupdate']).'</p>'; ?>
	</div>
</div>
<?php
		}
		echo '<hr class="sep" />';
		//We display the buttons a second time only if there is at least one comment
		if($pun_user['is_guest'])
			echo '<p class="infos">'.$lang_site['Must login to comment'].'</p>';
		else
			echo '<p class="post"><a href="tutorials.php?add_comment='.$tut_id.'">'.$lang_site['Post comment'].'</a>'.(($pun_user['is_admmod']) ? ' &nbsp; <a href="admin_comments.php">'.$lang_site['Comments moderation panel'].'</a>' : '').'</p>';
	}
	else
		echo '<p class="notice">'.$lang_site['No comment'].'</p>';
	require './includes/bottom.php';
}

/* Accueil des tutoriels */
$lang = $lang_site['Lang'];
$query = $db->query('SELECT tcat_id, tcat_name, tcat_clearname, tcat_icon, tcat_order, tcat_lang FROM tuts_cat WHERE tcat_lang LIKE(\'%'.$lang.'%\') ORDER BY tcat_order');

if(!$db->num_rows($query))
	site_msg($lang_site['No cat']);

$titre_page = $lang_site['Pagename tut home'];
$module = 'tutorials';
require './includes/top.php';
?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="tutorials.php"><?php echo $lang_site['Tutorials']; ?></a></p>
<h3><?php echo $lang_site['Title tutorials']; ?></h3>
<?php echo $lang_site['Explain tuts home'];
$row = 0;
while($cur_cat = $db->fetch_assoc($query))
{
	++$row;
	if(($row % 3) == 1)
		$class = ' cat-left';
	elseif(($row % 3) == 0)
		$class = ' cat-right';
	else
		$class = '';

	$catname = pun_htmlspecialchars(shorttext_lang($cur_cat['tcat_name'],$lang));
	$catclear = pun_htmlspecialchars(shorttext_lang($cur_cat['tcat_clearname'],$lang));

?><div class="home-block catblock<?php echo $class; ?>" id="tuto_<?php echo strtolower($catclear); ?>">
	<h4><?php echo $catname; ?></h4>
	<p class="align"><a href="tutorials.php?cat=<?php echo $cur_cat['tcat_id']; ?>"><img src="<?php echo $cur_cat['tcat_icon']; ?>" alt="<?php echo $catname; ?>" /><br /><?php echo $lang_site['View tutorials']; ?></a></p></div><?php
}
require './includes/bottom.php';
