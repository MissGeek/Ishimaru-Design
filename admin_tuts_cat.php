<?php require './includes/init-main.php';
if($pun_user['g_id'] != PUN_ADMIN)
	site_msg($lang_site['No permission']);

$lang = $lang_site['Lang'];
if(isset($_GET['add_cat']))
{
	if($_GET['add_cat'] != true)
		site_msg($lang_site['Bad request']);

	site_confirm_referrer('admin_tuts_cat.php');

	if(isset($_POST['save']))
	{
		$catname = pun_trim($_POST['catname']);	
		if($catname == '')
			site_msg($lang_site['Admin no catname']);
//		if(!preg_match('#(\{(en|fr)\|.+\})+#',$catname))
//			site_msg($lang_site['Wrong format']);
	
		$catclear = pun_trim(str_replace(' ','-',$_POST['catclear']));
		if($catclear == '')
			site_msg($lang_site['Admin no clearname']);
//		if(!preg_match('#^\{(en|fr)\|.+\}$#',$catclear))
//			site_msg($lang_site['Wrong format']);
	
		$desc = pun_linebreaks(pun_trim($_POST['catdesc']));
	
		if ($pun_config['p_message_bbcode'] == '1')
		{
			require PUN_ROOT.'include/parser.php';
			$catdesc = preparse_bbcode($desc, $errors);
		}
		if($catdesc == '')
			site_msg($lang_site['Admin no desc']);
//		if(!preg_match('#^\{(en|fr)\|.+\}$#',$catdesc))
//			site_msg($lang_site['Wrong format']);

		$disp_lang = array();
		if($_POST['lang_fr'] == 'on')
			$disp_lang[] = 'fr';
		if($_POST['lang_en'] == 'on')
			$disp_lang[] = 'en';
		$sql_lang = implode(',',$disp_lang);

		$has_versions = ($_POST['has_versions'] == 'on') ? 1 : 0;

		$fields = 'tcat_icon';
		$icon = 'caticon';
		$size = 102400; // 100 Kio - 1 Kio valant 1024 octets
		$folder = 'tuts-cat-icons';
		$thumb = false;
		require 'includes/upload.php';

		if(empty($img_value))
			site_msg($lang_site['Admin no pic']);
		else
		{
			$sql = 'INSERT INTO tuts_cat(tcat_id,tcat_name,tcat_clearname,tcat_desc,tcat_icon,tcat_order,tcat_lang,tcat_hasversions) VALUES(\'\',\''.$db->escape($catname).'\',\''.$db->escape($catclear).'\',\''.$db->escape($catdesc).'\',\''.$db->escape($img_value).'\',0,\''.$db->escape($sql_lang).'\','.$has_versions.')';
			$db->query($sql) or error('Unable to add category data', __FILE__, __LINE__, $db->error());

			if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
				require './includes/cache.php';

			generate_tuts_submenu_cache($lang);
			generate_admin_tuts_home_cache($lang);
			site_redirect('admin_tuts_cat.php', $lang_site['Admin cat added redirect']);
		}
	}
	$titre_page = $lang_site['Admin add cat'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=tutorials"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="admin_tuts_cat.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php echo $lang_site['Admin add cat']; ?></p>
<h3><?php echo $lang_site['Admin add cat']; ?></h3>
<form method="post" action="admin_tuts_cat.php?add_cat=true" enctype="multipart/form-data">
	<p class="form">
		<label for="catname"><?php echo $lang_site['Admin cat name']; ?><br /><input type="text" name="catname" id="catname" size="50" maxlength="64" value="{fr|Exemple de nom}||{en|Sample category name}" /></label><br />
		<label for="catclear"><?php echo $lang_site['Admin cat clear']; ?><br /><input type="text" name="catclear" id="catclear" size="50" maxlength="64" value="{fr|Exemple de nom}||{en|Sample category name}" /></label><br />
		<label for="catdesc"><?php echo $lang_site['Admin cat desc']; ?><br /><textarea name="catdesc" id="catdesc" cols="70" rows="5">{fr|Exemple de description}

{en|Sample description}</textarea></label>
	</p>
	<hr class="sep" />
	<p class="form">
		<strong class="label"><?php echo $lang_site['Languages']; ?></strong> <label for="disp_fr"><?php echo $lang_site['French']; ?></label> <input type="checkbox" name="lang_fr" id="disp_fr" /> <label for="disp_en"><?php echo $lang_site['English']; ?></label> <input type="checkbox" name="lang_en" id="disp_en" /><br />
		<label for="has_versions"><?php echo $lang_site['Has versions']; ?></label> <input type="checkbox" name="has_versions" id="has_versions" /> <em><?php echo $lang_site['Has versions info']; ?></em><br />
		<label class="label" for="cat_icon"><?php echo $lang_site['Admin cat icon']; ?></label>
		<input type="hidden" name="MAX_FILE_SIZE" value="102400" />
		<input type="file" name="caticon" id="cat_icon" />
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
elseif(isset($_GET['add_ver']))
{
	$parent_cat = intval($_GET['add_ver']);
	if($parent_cat < 1)
		site_msg($lang_site['Bad request']);

	site_confirm_referrer('admin_tuts_cat.php');

	if(isset($_POST['save']))
	{
		$vername = pun_trim($_POST['version_name']);
		if($vername == '')
			site_msg($lang_site['Admin no vername']);

		$sql = 'INSERT INTO tuts_versions(version_id,version_name,version_cat) VALUES(\'\',\''.$db->escape($vername).'\','.$parent_cat.')';
		$db->query($sql) or error('Unable to add version data', __FILE__, __LINE__, $db->error());
		site_redirect('admin_tuts_cat.php', $lang_site['Admin version added redirect']);
	}

	$titre_page = $lang_site['Admin add version'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=tutorials"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="admin_tuts_cat.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php echo $lang_site['Admin add version']; ?></p>
<h3><?php echo $lang_site['Admin add version']; ?></h3>
<form method="post" action="admin_tuts_cat.php?add_ver=<?php echo $parent_cat; ?>">
	<p class="form">
		<input type="hidden" name="parent_id" value="" />
		<label for="vername"><?php echo $lang_site['Admin ver name']; ?><br /><input type="text" name="version_name" id="vername" size="50" maxlength="64" /></label>
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
if(isset($_GET['edit_cat']))
{
	$edit_cat = intval($_GET['edit_cat']);
	if($edit_cat < 1)
		site_msg($lang_site['Bad request']);

	if(isset($_POST['save']))
	{
		site_confirm_referrer('admin_tuts_cat.php');
		$catname = pun_trim($_POST['catname']);	
		if($catname == '')
			site_msg($lang_site['Admin no catname']);
//		if(!preg_match('#(\{(en|fr)\|.+\})+#',$catname))
//			site_msg($lang_site['Wrong format']);
	
		$catclear = pun_trim(str_replace(' ','-',$_POST['catclear']));
		if($catclear == '')
			site_msg($lang_site['Admin no clearname']);
//		if(!preg_match('#^\{(en|fr)\|.+\}$#',$catclear))
//			site_msg($lang_site['Wrong format']);
	
		$desc = pun_linebreaks(pun_trim($_POST['catdesc']));
	
		if ($pun_config['p_message_bbcode'] == '1')
		{
			require PUN_ROOT.'include/parser.php';
			$catdesc = preparse_bbcode($desc, $errors);
		}
		if($catdesc == '')
			site_msg($lang_site['Admin no desc']);
//		if(!preg_match('#^\{(en|fr)\|.+\}$#',$catdesc))
//			site_msg($lang_site['Wrong format']);

		$disp_lang = array();
		if($_POST['lang_fr'] == 'on')
			$disp_lang[] = 'fr';
		if($_POST['lang_en'] == 'on')
			$disp_lang[] = 'en';
		$sql_lang = implode(',',$disp_lang);

		$has_versions = ($_POST['has_versions'] == 'on') ? 1 : 0;
		$oldfile = (isset($_POST['oldfile'])) ? $_POST['oldfile'] : '';
		$icon = 'caticon';
		$size = 102400; // 100 Kio - 1 Kio valant 1024 octets
		$folder = 'tuts-cat-icons';
//			$thumb = 'no';
		require('includes/upload.php');

		$cat_id = $_POST['cat_id'];
		$sql = 'UPDATE tuts_cat SET tcat_name=\''.$db->escape($catname).'\',tcat_clearname=\''.$db->escape($catclear).'\',tcat_desc=\''.$db->escape($catdesc).'\', ';
		if(!empty($img_value))
			$sql .= 'tcat_icon=\''.$db->escape($img_value).'\',';
		$sql .= 'tcat_lang=\''.$db->escape($sql_lang).'\', tcat_hasversions='.$has_versions.' WHERE tcat_id=\''.$edit_cat.'\'';
		$db->query($sql) or error('Unable to update category data', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_lasttuts_cache($lang);
		generate_tuts_submenu_cache($lang);
		generate_admin_tuts_home_cache($lang);
		site_redirect('admin_tuts_cat.php', $lang_site['Admin cat edited redirect']);
	}
	//fetch cat info
	$result = $db->query('SELECT tcat_id, tcat_name, tcat_clearname, tcat_desc, tcat_icon, tcat_lang, tcat_hasversions FROM tuts_cat WHERE tcat_id='.$edit_cat) or error('Unable to fetch category data', __FILE__, __LINE__, $db->error());
	if(!$db->num_rows($result))
		site_msg($lang_site['Bad request']);

	$cur_cat = $db->fetch_assoc($result);

	$titre_page = $lang_site['Admin edit cat'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=tutorials"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="admin_tuts_cat.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php echo $lang_site['Admin edit cat']; ?></p>
<h3><?php echo $lang_site['Admin edit cat']; ?></h3>
<form method="post" action="admin_tuts_cat.php?edit_cat=<?php echo $cur_cat['tcat_id']; ?>" enctype="multipart/form-data">
	<p>
		<input type="hidden" name="oldfile" value="<?php echo pun_htmlspecialchars($cur_cat['tcat_icon']); ?>" />
		<label for="catname"><?php echo $lang_site['Admin cat name']; ?><br /><input type="text" name="catname" id="catname" size="50" maxlength="64" value="<?php echo pun_htmlspecialchars($cur_cat['tcat_name']); ?>" /></label><br />
		<label for="catclear"><?php echo $lang_site['Admin cat clear']; ?><br /><input type="text" name="catclear" id="catclear" size="50" maxlength="64" value="<?php echo pun_htmlspecialchars($cur_cat['tcat_clearname']); ?>" /></label><br />
		<label for="catdesc"><?php echo $lang_site['Admin cat desc']; ?><br /><textarea name="catdesc" id="catdesc" cols="70" rows="5"><?php echo pun_htmlspecialchars($cur_cat['tcat_desc']); ?></textarea></label>
	</p>
	<hr class="sep" />
	<p class="form">
		<strong class="label"><?php echo $lang_site['Languages']; ?></strong> <label for="disp_fr"><?php echo $lang_site['French']; ?></label> <input type="checkbox" name="lang_fr" id="disp_fr" <?php echo (preg_match('#fr#',$cur_cat['tcat_lang'])) ? 'checked="checked" ':''; ?>/> <label for="disp_en"><?php echo $lang_site['English']; ?></label> <input type="checkbox" name="lang_en" id="disp_en" <?php echo (preg_match('#en#',$cur_cat['tcat_lang'])) ? 'checked="checked" ':''; ?>/><br />
		<label for="has_versions"><?php echo $lang_site['Has versions']; ?></label> <input type="checkbox" name="has_versions" id="has_versions"<?php echo $cur_cat['tcat_hasversions'] == 1 ? ' checked="checked"' : ''; ?> /> <em><?php echo $lang_site['Has versions info']; ?></em><br />
		<label class="label" for="cat_icon"><?php echo $lang_site['Admin cat icon']; ?></label>
		<input type="hidden" name="MAX_FILE_SIZE" value="102400" />
		<input type="file" name="caticon" id="cat_icon" />
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
elseif(isset($_GET['edit_ver']))
{
	$ver_id = intval($_GET['edit_ver']);
	if($ver_id < 1)
		site_msg($lang_site['Bad request']);

	site_confirm_referrer('admin_tuts_cat.php');

	if(isset($_POST['save']))
	{
		$parent_cat = intval($_POST['parent_id']);
		if($parent_cat < 1)
			message($lang_site['Bad request']);

		$vername = pun_trim($_POST['version_name']);
		if($vername == '')
			site_msg($lang_site['Admin no vername']);

		$sql = 'UPDATE tuts_versions SET version_name=\''.$db->escape($vername).'\' WHERE version_id='.$ver_id;
		$db->query($sql) or error('Unable to edit version data', __FILE__, __LINE__, $db->error());
		site_redirect('admin_tuts_cat.php', $lang_site['Admin version added redirect']);
	}

	$ver_result = $db->query('SELECT version_id, version_name, version_cat FROM tuts_versions WHERE version_id='.$ver_id) or error('Unable to fetch version data', __FILE__, __LINE__, $db->error());
	if(!$db->num_rows($ver_result))
		site_msg($lang_site['Bad request']);

	$cur_ver = $db->fetch_assoc($ver_result);

	$titre_page = $lang_site['Admin edit version'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=tutorials"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="admin_tuts_cat.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php echo $lang_site['Admin edit version']; ?></p>
<h3><?php echo $lang_site['Admin edit version']; ?></h3>
<form method="post" action="admin_tuts_cat.php?edit_ver=<?php echo $ver_id; ?>">
	<p class="form">
		<input type="hidden" name="parent_id" value="<?php echo $cur_ver['version_cat']; ?>" />
		<label for="vername"><?php echo $lang_site['Admin ver name']; ?><br /><input type="text" name="version_name" id="vername" size="50" maxlength="64" value="<?php echo $cur_ver['version_name']; ?>" /></label>
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
if(isset($_GET['del_cat']))
{
	site_confirm_referrer('admin_tuts_cat.php');
	$cat_id = intval($_GET['del_cat']);
	if($cat_id < 1)
		site_msg($lang_site['Bad request']);

	if(isset($_POST['del_cat_comply']))
	{
		@set_time_limit(0);

		require './includes/lib_cat.php';
		prune_versions($cat_id);
		prune_tutorials($cat_id,'cat');
		$query = $db->query('SELECT tcat_icon FROM tuts_cat WHERE tcat_id='.$cat_id) or error('Unable to fetch icon data', __FILE__, __LINE__, $db->error());
		$icon_to_del = $db->result($query);
		remove_file($icon_to_del,'tuts-cat-icons');

		// Delete the category
		$db->query('DELETE FROM tuts_cat WHERE tcat_id='.$cat_id) or error('Unable to delete category', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_lasttuts_cache($lang);	
		generate_tuts_submenu_cache($lang);
		generate_admin_home_cache();
		generate_admin_tuts_home_cache($lang);
		site_redirect('admin_tuts_cat.php', $lang_site['Admin cat deleted redirect']);
	}
	else
	{
		$result = $db->query('SELECT tcat_name FROM tuts_cat WHERE tcat_id='.$cat_id) or error('Unable to fetch category info', __FILE__, __LINE__, $db->error());
		$cat_name = $db->result($result);

		$titre_page = $lang_site['Admin delete category'];
		$module = 'admin';
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=tutorials"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="admin_tuts_cat.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php $lang_site['Admin delete cat head']; ?></p>
<h3><?php echo $lang_site['Admin delete tut cat head']; ?></h3>
<form method="post" action="admin_tuts_cat.php?del_cat=<?php echo $cat_id; ?>">
	<div class="inform">
	<input type="hidden" name="cat_to_delete" value="<?php echo $cat_id; ?>" />
		<fieldset>
			<legend><?php echo $lang_site['Admin confirm delete cat subhead']; ?></legend>
			<div class="infldset">
				<p><?php sprintf($lang_site['Admin confirm delete cat info'], pun_htmlspecialchars(shorttext_lang($cat_name,$lang))); ?></p>
				<p class="warntext"><?php echo $lang_site['Admin delete tut cat warn']; ?></p>
			</div>
		</fieldset>
	</div>
	<p class="buttons"><input type="submit" name="del_cat_comply" value="<?php echo $lang_site['Delete']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
	}
}
elseif(isset($_GET['del_ver']))
{
	site_confirm_referrer('admin_tuts_cat.php');
	$ver_id = intval($_GET['del_ver']);
	if($ver_id < 1)
		site_msg($lang_site['Bad request']);

	if(isset($_POST['del_ver_comply']))
	{
		@set_time_limit(0);
		require './includes/lib_cat.php';
		prune_tutorials($ver_id,'ver');
		$db->query('DELETE FROM tuts_versions WHERE version_id='.$ver_id) or error('Unable to delete version', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_lasttuts_cache($lang);
		generate_admin_home_cache();
		generate_admin_tuts_home_cache($lang);
		site_redirect('admin_tuts_cat.php', $lang_site['Admin version deleted redirect']);
	}
	else
	{
		$result = $db->query('SELECT version_name FROM tuts_versions WHERE version_id='.$ver_id) or error('Unable to fetch version info', __FILE__, __LINE__, $db->error());
		$ver_name = $db->result($result);

		$titre_page = $lang_site['Admin delete version'];
		$module = 'admin';
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=resources"><?php echo $lang_site['Resources']; ?></a> &gt; <a href="admin_tuts_cat.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php echo $lang_site['Admin delete version head']; ?></p>
		<h3><?php echo $lang_site['Admin delete version head']; ?></h3>
		<div class="box">
			<form method="post" action="admin_tuts_cat.php?del_ver=<?php echo $ver_id; ?>">
				<div class="inform">
				<input type="hidden" name="ver_to_delete" value="<?php echo $ver_id; ?>" />
					<fieldset>
						<legend><?php echo $lang_site['Admin confirm delete version subhead']; ?></legend>
						<div class="infldset">
							<p><?php sprintf($lang_site['Admin confirm delete version info'], pun_htmlspecialchars($ver_name)); ?></p>
							<p class="warntext"><?php echo $lang_site['Admin delete version warn']; ?></p>
						</div>
					</fieldset>
				</div>
				<p class="buttons"><input type="submit" name="del_ver_comply" value="<?php echo $lang_site['Delete']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
			</form>
		</div>
<?php require './includes/bottom.php';
	}
}
if(isset($_POST['update_positions']))
{
	site_confirm_referrer('admin_tuts_cat.php');

	foreach ($_POST['position'] as $cat_id => $disp_position)
	{
		$disp_position = trim($disp_position);
		if ($disp_position == '' || preg_match('%[^0-9]%', $disp_position))
			site_msg($lang_site['Admin must be integer']);

		$db->query('UPDATE tuts_cat SET tcat_order='.$disp_position.' WHERE tcat_id='.intval($cat_id)) or error('Unable to update categories', __FILE__, __LINE__, $db->error());
	}

	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_tuts_submenu_cache($lang);

	site_redirect('admin_tuts_cat.php', $lang_site['Admin cat edited redirect']);
}
$titre_page = $lang_site['Pagename adm tuts cat'];
$module = 'admin';
require './includes/top.php'; ?>
<!-- Début partie principale -->
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=tutorials"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="admin_tuts_cat.php"><?php echo $lang_site['Categories']; ?></a></p>
<h3><?php echo $lang_site['Title adm tuts cat']; ?></h3>
<?php echo $lang_site['Explain adm tuts cat']; ?>
<ul class="adm-tabs">
	<li><a href="admin.php?adm=tutorials"><?php echo $lang_site['Overview']; ?></a></li>
	<li class="tab-active"><?php echo $lang_site['Categories']; ?></li>
	<li><a href="admin_tuts_types.php"><?php echo $lang_site['Types']; ?></a></li>
	<li><a href="admin_tutorials.php"><?php echo $lang_site['Tutorials']; ?></a></li>
	<li><a href="admin_comments.php"><?php echo $lang_site['Comments']; ?></a></li>
</ul>
<form method="post" action="admin_tuts_cat.php">
<div class="adm-cont">
	<p class="new-data"><input type="submit" name="update_positions" value="<?php echo $lang_site['Update positions']; ?>" /> &nbsp; <a href="admin_tuts_cat.php?add_cat=true"><?php echo $lang_site['Admin add cat']; ?></a></p>
	<table class="adm-table wide" id="tbl-tuts-cat-overview">
		<tr>
			<th class="name"><?php echo $lang_site['Name']; ?></th>
			<th class="position"><?php echo $lang_site['Position']; ?></th>
			<th class="version"><?php echo $lang_site['Versions']; ?></th>
			<th class="edit"><?php echo $lang_site['Actions']; ?></th>
		</tr>
<?php
$lang = $lang_site['Lang'];
$cat_result = $db->query('SELECT tcat_id, tcat_name, tcat_order, tcat_hasversions, version_id, version_name, version_cat FROM tuts_cat LEFT JOIN tuts_versions ON version_cat=tcat_id ORDER BY tcat_order, version_name');

if($db->num_rows($cat_result) > 0)
{
	$category = NULL;
	while($cur_cat = $db->fetch_assoc($cat_result))
	{
		if($category != $cur_cat['tcat_id'])
		{
			$category = $cur_cat['tcat_id'];
?>
		<tr class="catrow">
			<td class="name"><?php echo pun_htmlspecialchars(shorttext_lang($cur_cat['tcat_name'],$lang)); ?></td>
			<td class="position center nowrap"><input type="text" name="position[<?php echo $cur_cat['tcat_id']; ?>]" size="3" maxlength="3" value="<?php echo $cur_cat['tcat_order']; ?>" /></td>
			<td class="version center"><?php echo $cur_cat['tcat_hasversions'] == 1 ? $lang_site['Yes'] : $lang_site['No']; ?></td>
			<td class="actions center nowrap"><?php if($cur_cat['tcat_hasversions'] == 1): ?><a href="admin_tuts_cat.php?add_ver=<?php echo $cur_cat['tcat_id']; ?>" title="<?php echo $lang_site['Add version']; ?>"><?php echo $lang_site['Add']; ?></a>&nbsp;<?php endif; ?><a href="admin_tuts_cat.php?edit_cat=<?php echo $cur_cat['tcat_id']; ?>"><?php echo $lang_site['Edit']; ?></a>&nbsp;<a href="admin_tuts_cat.php?del_cat=<?php echo $cur_cat['tcat_id']; ?>"><?php echo $lang_site['Delete']; ?></a></td>
		</tr>
<?php
		}
		if(!empty($cur_cat['version_id']))
		{
?>
		<tr class="subrow">
			<td class="name indent nowrap" colspan="3"><?php echo pun_htmlspecialchars($cur_cat['version_name']); ?></td>
			<td class="actions center nowrap"><a href="admin_tuts_cat.php?edit_ver=<?php echo $cur_cat['version_id']; ?>"><?php echo $lang_site['Edit']; ?></a>&nbsp;<a href="admin_tuts_cat.php?del_ver=<?php echo $cur_cat['version_id']; ?>"><?php echo $lang_site['Delete']; ?></a></td>
		</tr>
<?php
		}
	}
}
else
	echo '<tr><td colspan="3" class="center">'.$lang_site['No cat'].'</td></tr>';
?>
	</table>
	<p class="new-data"><input type="submit" name="update_positions" value="<?php echo $lang_site['Update positions']; ?>" /> &nbsp; <a href="admin_tuts_cat.php?add_cat=true"><?php echo $lang_site['Admin add cat']; ?></a></p>
	<div class="clearfix"></div>
</div>
</form>
<?php require './includes/bottom.php';
