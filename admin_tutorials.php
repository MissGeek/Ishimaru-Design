<?php require './includes/init-main.php';
if(!$pun_user['is_admmod'])
	message($lang_common['No permission']);

$lang = $lang_site['Lang'];
if(isset($_GET['add_tut']))
{
	if($_GET['add_tut'] !== 'true')
		site_msg($site_lang['Bad request'].'-1');

	site_confirm_referrer('admin_tutorials.php');

	if(isset($_POST['save']))
	{
		$cat_id = pun_trim($_POST['tut_cat']);
		$catver = get_cat_version($cat_id);
		if($catver == 'null')
			site_msg($lang_site['Bad request']);

		$cat_res = $db->query('SELECT tcat_lang FROM tuts_cat WHERE tcat_id='.$catver['cat']) or error('Unable to get category ID', __FILE__, __LINE__, $db->error());
		$langs = $db->result($cat_res);
		$tut_lang = pun_trim($_POST['tut_lang']);
		if(!preg_match('#'.$tut_lang.'#',$langs))
			site_msg($lang_site['Admin no language']);

		$tut_name = pun_trim($_POST['tut_name']);
		if($tut_name == '')
			site_msg($lang_site['No tutname'].'-4');

		require PUN_ROOT.'include/parser.php';
		$desc = pun_linebreaks(pun_trim($_POST['tut_desc']));
		if ($pun_config['p_message_bbcode'] == '1')
			$tut_desc = preparse_bbcode($desc, $errors);
		if($tut_desc == '')
			site_msg($lang_site['Admin no desc'].'-5');

		//Un tuto ajouté sera forcément envoyé par son auteur
		$tut_author = intval($_POST['tut_author']);
		if($tut_author != $pun_user['id'])
			site_msg($lang_site['User ID mismatch'].'-7');

		$tut_type = intval($_POST['tut_type']);
		if($tut_type < 1)
			site_msg($lang_site['No tutorial type']);

		$tut_level = intval($_POST['tut_level']);
		if($tut_level < 1)
			site_msg($lang_site['No tutorial level']);

		$fields = 'tentry_icon';
		$icon = 'tuticon';
		$size = 102400; // 100 Kio - 1 Kio valant 1024 octets
		$folder = 'tut';
		require('includes/upload.php');

		if(empty($img_value))
			site_msg($lang_site['Admin no pic'].'-8');

		$now = time();

		$sql = 'INSERT INTO tuts_entries(tentry_id, tentry_name, tentry_icon, tentry_desc, tentry_type, tentry_level, tentry_lang, tentry_author, tentry_publish, tentry_publishdate, tentry_lastupdate, tentry_comments, tentry_catid, tentry_version) VALUES(\'\',\''.$db->escape($tut_name).'\',\''.$db->escape($img_value).'\',\''.$db->escape($tut_desc).'\','.$tut_type.','.$tut_level.',\''.$db->escape($tut_lang).'\','.$tut_author.',0,'.$now.','.$now.',0,'.$catver['cat'].','.$catver['ver'].')';
		$db->query($sql) or error('Unable to add tutorial', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_admin_home_cache();
		generate_admin_tuts_home_cache($lang);
		site_redirect('admin_tutorials.php?edit_tut=last', $lang_site['Admin tut added redirect']);
//		}
	}
	$lang = $lang_site['Lang'];
	//Requête pour les catégories du menu déroulant
	$cat_result = $db->query('SELECT tcat_id, tcat_name, tcat_order, tcat_hasversions, version_id, version_name, version_cat FROM tuts_cat LEFT JOIN tuts_versions ON version_cat=tcat_id ORDER BY tcat_id') or error('Unable to get category data', __FILE__, __LINE__, $db->error());
	if($db->num_rows($cat_result) == 0)
		site_msg($lang_site['Admin must have categories'].'-2');

	$type_result = $db->query('SELECT type_id, type_name FROM tuts_type ORDER BY type_name') or error('Unable to get tutorial type data', __FILE__, __LINE__, $db->error());
	if($db->num_rows($type_result) == 0)
		site_msg($lang_site['Admin must have tutorial type']);

	$titre_page = $lang_site['Admin add tut'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_tutorials.php"><?php echo $lang_site['Tutorials']; ?></a> &gt; <?php echo $lang_site['Admin add tut']; ?></p>
<h3><?php echo $lang_site['Admin add tut']; ?></h3>
<?php echo $lang_site['Explain adm add tut']; ?>
<form method="post" action="admin_tutorials.php?add_tut=true" enctype="multipart/form-data">
	<p class="form">
		<label for="tutname"><?php echo $lang_site['Admin tut name']; ?><br /><input type="text" name="tut_name" id="tutname" size="50" maxlength="128" /></label><br />
		<label for="tutdesc"><?php echo $lang_site['Admin tut desc']; ?><br /><textarea name="tut_desc" id="tutdesc" cols="70" rows="5"></textarea></label><br />
	</p>
	<hr class="sep" />
	<p class="form">
		<input type="hidden" name="tut_author" value="<?php echo $pun_user['id']; ?>" />
		<strong class="label"><?php echo $lang_site['Language']; ?></strong> <label for="disp_fr"><?php echo $lang_site['French']; ?></label> <input type="radio" name="tut_lang" id="disp_fr" value="fr" /> <label for="disp_en"><?php echo $lang_site['English']; ?></label> <input type="radio" name="tut_lang" id="disp_en" value="en" /><br />
		<label for="category"><?php echo $lang_site['Category']; ?> <select name="tut_cat" id="category"><?php
		while($cur_cat = $db->fetch_assoc($cat_result))
		{
			if ($category != $cur_cat['tcat_id'])
			{
				$category = $cur_cat['tcat_id'];
				echo "\n\t\t\t".'<option value="c'.$cur_cat['tcat_id'].'"'.(($cur_cat['tcat_hasversions'] == 1) ? ' disabled="disabled"' : '').'>'.pun_htmlspecialchars(shorttext_lang($cur_cat['tcat_name'],$lang)).'</option>';
			}
			if(!empty($cur_cat['version_id']))
			{
				$version = $cur_cat['version_id'];
				echo "\n\t\t\t".'<option value="v'.$cur_cat['version_id'].'">-- '.pun_htmlspecialchars($cur_cat['version_name']).'</option>';
			}
			else
				$version = NULL;
		}
?>

		</select></label><br />
		<label for="level"><?php echo $lang_site['Admin tut level']; ?> <select name="tut_level" id="level">
			<option value="1"><?php echo $lang_site['Newbie']; ?></option>
			<option value="2"><?php echo $lang_site['Intermediate']; ?></option>
			<option value="3"><?php echo $lang_site['Advanced']; ?></option>
		</select></label><br />
		<label for="type"><?php echo $lang_site['Admin tut type']; ?> <select name="tut_type" id="type"><?php
		while($cur_type = $db->fetch_assoc($type_result))
		{
			echo "\n\t\t\t".'<option value="'.$cur_type['type_id'].'">'.pun_htmlspecialchars(shorttext_lang($cur_type['type_name'],$lang)).'</option>';
		}
?>

		</select></label><br />
		<label class="label" for="tut_icon"><?php echo $lang_site['Admin tut icon']; ?></label>
		<input type="hidden" name="MAX_FILE_SIZE" value="102400" />
		<input type="file" name="tuticon" id="tut_icon" />
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
elseif(isset($_GET['edit_tut']))
{
	if($_GET['edit_tut'] != 'last')
	{
		$edit_tut = intval($_GET['edit_tut']);
		if($edit_tut < 1)
			site_msg($lang_site['Bad request']);
	}
	if(isset($_GET['add_part']))
	{
		site_confirm_referrer('admin_tutorials.php');
		$tut_id = intval($_GET['add_part']);
		if($tut_id < 1 || $tut_id != $edit_tut)
			site_msg($lang_site['Bad request']);
		
		if(isset($_POST['save']))
		{
			$part_name = pun_trim($_POST['part_name']);
			if($part_name == '')
				site_msg($lang_site['Admin no part name']);

			require PUN_ROOT.'include/parser.php';			
			$text = pun_linebreaks(pun_trim($_POST['part_text']));
			if ($pun_config['p_message_bbcode'] == '1')
				$part_text = preparse_bbcode($text, $errors);
			if($part_text == '')
				site_msg($lang_site['Admin no text'].'-part2');

			$now = time();

			$sql = 'INSERT INTO tuts_texts(text_id, text_name, text_text, text_order, text_entryid, text_publishdate, text_lastupdate) VALUES(\'\',\''.$db->escape($part_name).'\',\''.$db->escape($part_text).'\',0,'.$tut_id.','.$now.','.$now.')';
			$db->query($sql) or error('Unable to add tutorial part', __FILE__, __LINE__, $db->error());
			site_redirect('admin_tutorials.php?edit_tut='.$tut_id, $lang_site['Admin part added redirect']);
		}

		//Affichage du formulaire
		$titre_page = $lang_site['Admin add part'];
		$module = 'admin';
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_tutorials.php"><?php echo $lang_site['Tutorials']; ?></a> &gt; <?php echo $lang_site['Admin add part']; ?></p>
<h3><?php echo $lang_site['Admin add part']; ?></h3>
<?php echo $lang_site['Explain adm add part']; ?>
<form method="post" action="admin_tutorials.php?edit_tut=<?php echo $tut_id; ?>&amp;add_part=<?php echo $tut_id; ?>">
	<p class="form">
		<label for="partname"><?php echo $lang_site['Admin part name']; ?><br /><input type="text" name="part_name" id="partname" size="50" maxlength="128" /></label><br />
		<label for="parttext"><?php echo $lang_site['Admin part text']; ?><br /><textarea name="part_text" id="parttext" cols="70" rows="5"></textarea></label><br />
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
	}
	if(isset($_GET['edit_part']))
	{
		$part_id = intval($_GET['edit_part']);
		if($part_id < 1)
			site_msg($lang_site['Bad request']);

		if(isset($_POST['save']))
		{
			$part_name = pun_trim($_POST['part_name']);
			if($part_name == '')
				site_msg($lang_site['Admin no part name']);

			require PUN_ROOT.'include/parser.php';			
			$text = pun_linebreaks(pun_trim($_POST['part_text']));
			if ($pun_config['p_message_bbcode'] == '1')
				$part_text = preparse_bbcode($text, $errors);
			if($part_text == '')
				site_msg($lang_site['Admin no text']);

			$sql = 'UPDATE tuts_texts SET text_name=\''.$db->escape($part_name).'\', text_text=\''.$db->escape($part_text).'\', text_lastupdate='.time().' WHERE text_id='.$part_id;
			$db->query($sql) or error('Unable to edit tutorial part', __FILE__, __LINE__, $db->error());
			site_redirect('admin_tutorials.php?edit_tut='.$edit_tut, $lang_site['Admin part edited redirect']);
		}

		//Affichage du formulaire
		$result = $db->query('SELECT text_id, text_name, text_text, text_entryid FROM tuts_texts WHERE text_id='.$part_id) or error('Unable to get tutorial part data', __FILE__, __LINE__, $db->error());
		if(empty($result))
			site_msg($lang_site['Bad request'].'-request');

		$cur_part = $db->fetch_assoc($result);

		$titre_page = $lang_site['Admin edit part'];
		$module = 'admin';
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_tutorials.php"><?php echo $lang_site['Tutorials']; ?></a> &gt; <?php echo $lang_site['Admin edit part']; ?></p>
<h3><?php echo $lang_site['Admin edit part']; ?></h3>
<?php echo $lang_site['Explain adm edit part']; ?>
<form method="post" action="admin_tutorials.php?edit_tut=<?php echo $edit_tut; ?>&amp;edit_part=<?php echo $part_id; ?>" enctype="multipart/form-data">
	<p class="form">
		<label for="partname"><?php echo $lang_site['Admin part name']; ?><br /><input type="text" name="part_name" id="partname" size="50" maxlength="128" value="<?php echo pun_htmlspecialchars($cur_part['text_name']); ?>" /></label><br />
		<label for="parttext"><?php echo $lang_site['Admin part text']; ?><br /><textarea name="part_text" id="parttext" cols="70" rows="5"><?php echo pun_htmlspecialchars($cur_part['text_text']); ?></textarea></label><br />
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
	}
	if(isset($_GET['del_part']))
	{
		site_confirm_referrer('admin_tutorials.php?edit_tut'.$edit_tut);
		$part_id = intval($_GET['del_part']);
		if($part_id < 1)
			site_msg($lang_site['Bad request']);

		if(isset($_POST['del_part_comply']))
		{
			@set_time_limit(0);

			$db->query('DELETE FROM tuts_texts WHERE text_id='.$part_id) or error('Unablo to prune tutorial part', __FILE__, __LINE__, $db->error());
			site_redirect('admin_tutorials.php?edit_tut='.$edit_tut, $lang_site['Admin part deleted redirect']);
		}
		else
		{
			$result = $db->query('SELECT text_name FROM tuts_texts WHERE text_id='.$part_id) or error('Unable to fetch tutorial part info', __FILE__, __LINE__, $db->error());
			$text_name = $db->result($result);

			$titre_page = $lang_site['Admin delete part'];
			$module = 'admin';
			require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_tutorials.php"><?php echo $lang_site['Tutorials']; ?></a> &gt; <?php echo $lang_site['Admin delete part']; ?></p>
<h3><?php echo $lang_site['Admin delete tut part head']; ?></h3>
<div class="box">
	<form method="post" action="admin_tutorials.php?edit_tut=<?php echo $edit_tut; ?>&amp;del_part=<?php echo $part_id; ?>">
		<div class="inform">
		<input type="hidden" name="part_to_delete" value="<?php echo $part_id; ?>" />
			<fieldset>
				<legend><?php echo $lang_site['Admin confirm delete tut part subhead']; ?></legend>
				<div class="infldset">
					<p><?php sprintf($lang_site['Admin confirm delete tut part info'], pun_htmlspecialchars($text_name)); ?></p>
					<p class="warntext"><?php echo $lang_site['Admin delete tut part warn']; ?></p>
				</div>
			</fieldset>
		</div>
		<p class="buttons"><input type="submit" name="del_part_comply" value="<?php echo $lang_site['Delete']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
	</form>
</div>
<?php require './includes/bottom.php';
		}
	}
	if(isset($_POST['update_positions']))
	{
		// Pour la mise à jour de l'ordre des parties.  Je reprendrai le script utilisé pour les catégories
		site_confirm_referrer('admin_tutorials.php?edit_tut='.$edit_tut);

		foreach ($_POST['position'] as $part_id => $disp_position)
		{
			$disp_position = trim($disp_position);
			if ($disp_position == '' || preg_match('%[^0-9]%', $disp_position))
				site_msg($lang_site['Admin must be integer']);

			$db->query('UPDATE tuts_texts SET text_order='.$disp_position.' WHERE text_id='.intval($part_id)) or error('Unable to update tutorial parts', __FILE__, __LINE__, $db->error());
		}
		site_redirect('admin_tutorials.php?edit_tut='.$edit_tut, $lang_site['Admin part edited redirect']);
	}

	site_confirm_referrer('admin_tutorials.php');
	if(isset($_POST['save']))
	{
		$cat_id = pun_trim($_POST['tut_cat']);
		$catver = get_cat_version($cat_id);
		if($catver == 'null')
			site_msg($lang_site['Bad request'].'-1');

		$tut_name = pun_trim($_POST['tut_name']);
		if($tut_name == '')
			site_msg($lang_site['Admin no tutname'].'-2');

		require PUN_ROOT.'include/parser.php';
		$desc = pun_linebreaks(pun_trim($_POST['tut_desc']));
		if ($pun_config['p_message_bbcode'] == '1')
			$tut_desc = preparse_bbcode($desc, $errors);
		if($tut_desc == '')
			site_msg($lang_site['Admin no desc'].'-3');

		$tut_type = intval($_POST['tut_type']);
		if($tut_type < 1)
			site_msg($lang_site['No tutorial type'].'-5');

		$tut_level = intval($_POST['tut_level']);
		if($tut_level < 1)
			site_msg($lang_site['No tutorial level'].'-6');

		$sql = 'UPDATE tuts_entries SET tentry_name=\''.$db->escape($tut_name).'\', tentry_desc=\''.$db->escape($tut_desc).'\', tentry_type='.$tut_type.', tentry_level='.$tut_level.', tentry_lastupdate='.time().', tentry_catid='.$catver['cat'].', tentry_version='.$catver['ver'].' WHERE tentry_id='.$edit_tut;
		$db->query($sql) or error('Unable to edit tutorial', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_lasttuts_cache($lang);
		generate_admin_tuts_home_cache($lang);
		site_redirect('admin_tutorials.php?edit_tut='.$edit_tut, $lang_site['Admin tut edited redirect']);
	}

	if($_GET['edit_tut'] == 'last')
	{
		$t = $db->query('SELECT MAX(tentry_id) FROM tuts_entries') or error('Unable to get last id', __FILE__, __LINE__, $db->error());
		$edit_tut = $db->result($t);
		if($edit_tut < 1)
			site_msg($lang_site['Bad request']);
	}

	$lang = $lang_site['Lang'];
	$tut_result = $db->query('SELECT tentry_id, tentry_name, tentry_desc, tentry_type, tentry_level, tentry_catid,tentry_version FROM tuts_entries WHERE tentry_id='.$edit_tut) or error('Unable to get tutorial data', __FILE__, __LINE__, $db->error());
	if(empty($tut_result))
		site_msg($lang_site['Bad request']);
	//Requête pour les catégories du menu déroulant
	$cat_result = $db->query('SELECT tcat_id, tcat_name, tcat_order, tcat_hasversions, version_id, version_name, version_cat FROM tuts_cat LEFT JOIN tuts_versions ON version_cat=tcat_id ORDER BY tcat_order, tcat_id, version_name');
	if($db->num_rows($cat_result) == 0)
		site_msg($lang_site['Admin must have categories']);

	$type_result = $db->query('SELECT type_id, type_name FROM tuts_type ORDER BY type_name') or error('Unable to get tutorial type data', __FILE__, __LINE__, $db->error());
	if($db->num_rows($type_result) == 0)
		site_msg($lang_site['Admin must have tutorial types']);

	$cur_tut = $db->fetch_assoc($tut_result);

	$titre_page = $lang_site['Admin edit tut'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_tutorials.php"><?php echo $lang_site['Tutorials']; ?></a> &gt; <?php echo $lang_site['Admin edit tut']; ?></p>
<h3><?php echo $lang_site['Admin edit tut']; ?></h3>
<?php
	$text_result = $db->query('SELECT text_id, text_name, text_order, text_entryid, text_publishdate, text_lastupdate FROM tuts_texts WHERE text_entryid='.$edit_tut.' ORDER BY text_order') or error('Unable to get tutorial part data', __FILE__, __LINE__, $db->error());
	if($db->num_rows($text_result) > 0)
	{
?>
<form method="post" action="admin_tutorials.php?edit_tut=<?php echo $edit_tut; ?>">
<table class="adm-table" id="tbl-part-list">
	<tr>
		<th class="name"><?php echo $lang_site['Name']; ?></th>
		<th class="order"><?php echo $lang_site['Position']; ?></th>
		<th class="actions nowrap"><?php echo $lang_site['Actions']; ?></th>
		<th class="lastupdate"><?php echo $lang_site['Last update']; ?></th>
	</tr>
<?php
		while($cur_part = $db->fetch_assoc($text_result))
		{
?>
	<tr>
		<td class="name"><?php echo pun_htmlspecialchars($cur_part['text_name']); ?></td>
		<td class="order center"><input type="text" name="position[<?php echo $cur_part['text_id']; ?>]" size="2" maxlength="2" value="<?php echo $cur_part['text_order']; ?>" /></td>
		<td class="actions center nowrap"><a href="admin_tutorials.php?edit_tut=<?php echo $edit_tut; ?>&amp;edit_part=<?php echo $cur_part['text_id']; ?>"><?php echo $lang_site['Edit']; ?></a> <a href="admin_tutorials.php?edit_tut=<?php echo $edit_tut; ?>&amp;del_part=<?php echo $cur_part['text_id']; ?>"><?php echo $lang_site['Delete']; ?></a></td>
		<td class="lastupdate center"><?php echo format_time($cur_part['text_lastupdate']); ?></td>
	</tr>
<?php
		}
?>
</table>
<p class="new-data"><input type="submit" name="update_positions" value="<?php echo $lang_site['Update positions']; ?>" /> &nbsp; <a href="admin_tutorials.php?edit_tut=<?php echo $cur_tut['tentry_id']; ?>&amp;add_part=<?php echo $cur_tut['tentry_id']; ?>"><?php echo $lang_site['Admin add part']; ?></a></p>
</form>
<?php
	}
	else
		echo '<p class="notice">'.$lang_site['No part'].'<br /><a href="admin_tutorials.php?edit_tut='.$cur_tut['tentry_id'].'&amp;add_part='.$cur_tut['tentry_id'].'">'.$lang_site['Admin add part'].'</a></p>';
?>
<form method="post" action="admin_tutorials.php?edit_tut=<?php echo $cur_tut['tentry_id']; ?>" enctype="multipart/form-data">
	<p class="form">
		<label for="tutname"><?php echo $lang_site['Admin tut name']; ?><br /><input type="text" name="tut_name" id="tutname" size="50" maxlength="128" value="<?php echo pun_htmlspecialchars($cur_tut['tentry_name']); ?>" /></label><br />
		<label for="tutdesc"><?php echo $lang_site['Admin tut desc']; ?><br /><textarea name="tut_desc" id="tutdesc" cols="70" rows="5"><?php echo pun_htmlspecialchars($cur_tut['tentry_desc']); ?></textarea></label>
	</p>
	<hr class="sep" />
	<p class="form">
		<label for="category"><?php echo $lang_site['Category']; ?> <select name="tut_cat" id="category"><?php
		$category = NULL;
		$version = NULL;
		while($cur_cat = $db->fetch_assoc($cat_result))
		{
			if ($category != $cur_cat['tcat_id'])
			{
				$category = $cur_cat['tcat_id'];
				echo "\n\t\t\t".'<option value="c'.$cur_cat['tcat_id'].'"'.(($cur_cat['tcat_hasversions'] == 1) ? ' disabled="disabled"' : '').(($cur_cat['tcat_id'] == $cur_tut['tentry_catid']) ? ' selected="selected"' : '').'>'.pun_htmlspecialchars(shorttext_lang($cur_cat['tcat_name'],$lang)).'</option>';
			}
			if(!empty($cur_cat['version_id']))
			{
				$version = $cur_cat['version_id'];
				echo "\n\t\t\t".'<option value="v'.$cur_cat['version_id'].'"'.(($cur_tut['tentry_version'] == $cur_cat['version_id']) ? ' selected="selected"' : '').'>-- '.pun_htmlspecialchars($cur_cat['version_name']).'</option>';
			}
			else
				$version = NULL;
		}
?>

		</select></label><br />
		<label for="level"><?php echo $lang_site['Admin tut level']; ?> <select name="tut_level" id="level">
			<option value="1"<?php echo ($cur_tut['tentry_level'] == 1) ? ' selected="selected"' : ''; ?>><?php echo $lang_site['Newbie']; ?></option>
			<option value="2"<?php echo ($cur_tut['tentry_level'] == 2) ? ' selected="selected"' : ''; ?>><?php echo $lang_site['Intermediate']; ?></option>
			<option value="3"<?php echo ($cur_tut['tentry_level'] == 3) ? ' selected="selected"' : ''; ?>><?php echo $lang_site['Advanced']; ?></option>
		</select></label><br />
		<label for="type"><?php echo $lang_site['Admin tut type']; ?> <select name="tut_type" id="type"><?php
		while($cur_type = $db->fetch_assoc($type_result))
		{
			echo "\n\t\t\t".'<option value="'.$cur_type['type_id'].'"'.($cur_type['type_id'] == $cur_tut['tentry_type'] ? ' selected="selected"' : '').'>'.pun_htmlspecialchars(shorttext_lang($cur_type['type_name'],$lang)).'</option>';
		}
?>

		</select></label>
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
elseif(isset($_GET['del_tut']))
{
	site_confirm_referrer('admin_tutorials.php');
	$tut_id = intval($_GET['del_tut']);
	if($tut_id < 1)
		site_msg($lang_site['Bad request']);

	exit(var_dump($tut_id));

	if(isset($_POST['del_tut_comply']))
	{
		@set_time_limit(0);

		// Fetch screenshots to prune
		$result = $db->query('SELECT text_id, text_name FROM tuts_texts WHERE text_entryid='.$tut_id) or error('Unable to fetch tutorial parts', __FILE__, __LINE__, $db->error());

		$part_ids = '';
		while ($row = $db->fetch_assoc($result))
			$part_ids .= (($part_ids != '') ? ',' : '').$row['text_id'];

		if ($part_ids != '')
		{
			$db->query('DELETE FROM tuts_texts WHERE text_id IN('.$part_ids.')') or error('Unable to prune tutorial parts', __FILE__, __LINE__, $db->error());
		}
		//Pour la suppression de l'icône.  Commenté pour le moment
		$result2 = $db->query('SELECT tentry_icon FROM tuts_entries WHERE tentry_id='.$tut_id) or error('Unable to get tutorial data', __FILE__, __LINE__, $db->error());
		$img_to_delete = $db->result($result2);
		if(!empty($img_to_delete))
			remove_file($img_to_delete,'tut');

		$db->query('DELETE FROM tuts_entries WHERE tentry_id='.$tut_id) or error('Unable to prune tutorial', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_lasttuts_cache($lang);
		generate_admin_home_cache();
		generate_admin_tuts_home_cache($lang);
		site_redirect('admin_tutorials.php', $lang_site['Admin tut deleted redirect']);
	}
	else
	{
		$result = $db->query('SELECT tentry_name FROM tuts_entries WHERE tentry_id='.$tut_id) or error('Unable to fetch tutorial info');
		$tut_name = $db->result($result);

		$titre_page = $lang_site['Admin delete tut'];
		$module = 'admin';
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_tutorials.php"><?php echo $lang_site['Tutorials']; ?></a> &gt; <?php echo $lang_site['Admin delete tut head']; ?></p>
<h3><?php echo $lang_site['Admin delete tut head']; ?></h3>
<form method="post" action="admin_tutorials.php?del_tut=<?php echo $tut_id; ?>">
	<div class="inform">
	<input type="hidden" name="tut_to_delete" value="<?php echo $tut_id; ?>" />
		<fieldset>
			<legend><?php echo $lang_site['Admin confirm delete tut subhead']; ?></legend>
			<div class="infldset">
				<p><?php sprintf($lang_site['Admin confirm delete tut info'], pun_htmlspecialchars($tut_name)); ?></p>
				<p class="warntext"><?php echo $lang_site['Admin delete tut warn']; ?></p>
			</div>
		</fieldset>
	</div>
	<p class="buttons"><input type="submit" name="del_tut_comply" value="<?php echo $lang_site['Delete']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
	}
}
if(isset($_GET['approve']))
{
	$approve = intval($_GET['approve']);
	if($approve < 1)
		site_msg($lang_site['Bad request']);

	$db->query('UPDATE tuts_entries SET tentry_publish=1 WHERE tentry_id='.$approve) or error('Unable to update tutorial status', __FILE_, __LINE__, $db->error());

	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_lasttuts_cache($lang);
	site_redirect('admin_tutorials.php', $lang_site['Admin tut approved redirect']);
}
elseif(isset($_GET['unapprove']))
{
	$unapprove = intval($_POST['unapprove']);
	if($unapprove < 1)
		site_msg($lang_site['Bad request']);

	$db->query('UPDATE tuts_entries SET tentry_publish=0 WHERE tentry_id='.$unapprove) or error('Unable to update tutorial status', __FILE__, __LINE__, $db->error());

	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_lasttuts_cache($lang);
	site_redirect('admin_tutorials.php', $lang_site['Admin tut unapproved redirect']);
}

$titre_page = $lang_site['Pagename adm tutorials'];
$module = 'admin';
require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_tutorials.php"><?php echo $lang_site['Tutorials']; ?></a></p>
<h3><?php echo $lang_site['Title adm tutorials']; ?></h3>
<?php echo $lang_site['Explain adm tutorials']; ?>
<ul class="adm-tabs">
	<li><a href="admin.php?adm=tutorials"><?php echo $lang_site['Overview']; ?></a></li>
	<?php if ($pun_user['g_id'] == PUN_ADMIN): ?><li><a href="admin_tuts_cat.php"><?php echo $lang_site['Categories']; ?></a></li>
	<li><a href="admin_tuts_types.php"><?php echo $lang_site['Types']; ?></a></li><?php endif; ?>
	<li class="tab-active"><?php echo $lang_site['Tutorials']; ?></li>
	<li><a href="admin_comments.php"><?php echo $lang_site['Comments']; ?></a></li>
</ul>
<div class="adm-cont">
	<p class="new-data"><a href="admin_tutorials.php?add_tut=true"><?php echo $lang_site['Admin add tut']; ?></a></p>
	<table class="adm-table" id="tbl-tut-list">
		<tr>
			<th class="name"><?php echo $lang_site['Name']; ?></th>
			<th class="author"><?php echo $lang_site['Author']; ?></th>
			<th class="category"><?php echo $lang_site['Category']; ?></th>
			<th class="lang"><?php echo $lang_site['Language']; ?></th>
			<th class="comments"><?php echo $lang_site['Comments']; ?></th>
			<th class="lastupdate"><?php echo $lang_site['Last update']; ?></th>
			<th class="actions"><?php echo $lang_site['Actions']; ?></th>
		</tr>
<?php
$lang = $lang_site['Lang'];
$sql = 'SELECT tentry_id, tentry_name, tentry_lang, tentry_author, tentry_comments, tentry_publish, tentry_publishdate, tentry_lastupdate, tcat_id, tcat_name, tcat_hasversions, version_id, version_name, version_cat, id, username
		FROM tuts_entries
		LEFT JOIN tuts_cat ON tentry_catid=tcat_id
		LEFT JOIN tuts_versions ON tentry_version=version_id
		LEFT JOIN '.$db->prefix.'users ON tentry_author=id
		ORDER BY tentry_id DESC';
$result = $db->query($sql) or error('Unable to fetch tutorial data', __FILE__, __LINE__, $db->error());

if($db->num_rows($result) > 0)
{
	while($cur_tut = $db->fetch_assoc($result))
	{
?>
		<tr>
			<td class="name"><?php echo pun_htmlspecialchars($cur_tut['tentry_name']); ?></td>
			<td class="author center"><?php echo pun_htmlspecialchars($cur_tut['username']); ?></td>
			<td class="category center"><?php echo pun_htmlspecialchars(shorttext_lang($cur_tut['tcat_name'],$lang)); ?><?php echo ($cur_tut['tcat_hasversions'] == 1) ? ' ('.pun_htmlspecialchars($cur_tut['version_name']).')' : ''; ?></td>
			<td class="lang center"><?php echo pun_htmlspecialchars($cur_tut['tentry_lang']); ?></td>
			<td class="comments center"><a href="admin_comments.php?id="<?php echo $cur_tut['tentry_id']; ?>"><?php echo $cur_tut['tentry_comments']; ?></a></td>
			<td class="lastupdate center"><?php echo format_time($cur_tut['tentry_lastupdate']); ?></td>
			<td class="actions center"><a href="admin_tutorials.php?edit_tut=<?php echo $cur_tut['tentry_id']; ?>"><?php echo $lang_site['Edit']; ?></a> <a href="admin_tutorials.php?del_tut=<?php echo $cur_tut['tentry_id']; ?>"><?php echo $lang_site['Delete']; ?></a> <a href="admin_tutorials.php?<?php echo ($cur_tut['tentry_publish'] == 1) ? 'unapprove='.$cur_tut['tentry_id'] : 'approve='.$cur_tut['tentry_id']; ?>"><?php echo ($cur_tut['tentry_publish'] == 1) ? $lang_site['Unapprove'] : $lang_site['Approve']; ?></a></td>
		</tr>
<?php
	}
}
	else echo '<tr><td colspan="7" class="center">'.$lang_site['No tutorial'].'</td></tr>'; ?>
	</table>
	<p class="new-data"><a href="admin_tutorials.php?add_tut=true"><?php echo $lang_site['Admin add tut']; ?></a></p>
	<div class="clearfix"></div>
</div>
<?php require './includes/bottom.php'; ?>
