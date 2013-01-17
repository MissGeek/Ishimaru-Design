<?php require './includes/init-main.php';
if($pun_user['g_id'] != PUN_ADMIN)
	site_msg($lang_site['No permission']);

//Cette page n'est utilisable que si des catégories ont été créées
$catres = $db->query('SELECT COUNT(tcat_id) FROM tuts_cat') or error('Unable to get category data', __FILE__, __LINE__, $db->error());
if(!$nb_cats = $db->result($catres))
	site_msg($lang_site['Admin must have categories']);

if(isset($_GET['add_type']))
{
	if($_GET['add_type'] != true)
		site_msg($lang_site['Bad request']);

	site_confirm_referrer('admin_tuts_types.php');

	if(isset($_POST['save']))
	{
		//Certain types seront utilisés par plusieurs catégories, donc on oblige les deux langues
		$typename = pun_trim($_POST['typename']);
		if($typename == '')
			site_msg($lang_site['Admin no typename']);

/*		$catids = array_filter($_POST['typecat'],'is_numeric');
		if(empty($catids))
			site_msg($lang_site['Admin no typecat']);*/

		$db->query('INSERT INTO tuts_type(type_id,type_name) VALUES(\'\',\''.$db->escape($typename).'\')') or error('Unable to add tutorial type', __FILE__, __LINE__, $db->error());
		site_redirect('admin_tuts_types.php', $lang_site['Admin type added redirect']);
	}
	$lang = $lang_site['Lang'];
//	$c = $db->query('SELECT tcat_id, tcat_name_'.$lang.' FROM tuts_cat ORDER BY tcat_id') or error('Unable to get category data', __FILE__, __LINE__, $db->error());
//	if($db->num_rows($c) < 1)
//		site_msg($lang_site['No categories']);

	$titre_page = $lang_site['Admin add type'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=tutorials"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="admin_tuts_types.php"><?php echo $lang_site['Types']; ?></a> &gt; <?php echo $lang_site['Admin add type']; ?></p>
<h3><?php echo $lang_site['Admin add type']; ?></h3>
<form method="post" action="admin_tuts_types.php?add_type=true">
	<p class="form">
		<label for="typename"><?php echo $lang_site['Admin type name']; ?><br /><input type="text" name="typename" id="typename" size="50" maxlength="64" value="{fr|Exemple de type}||{en|Sample type}" /></label>
	</p>
	<hr class="sep" />
	<?php /*p class="form">
		<strong class="label"><?php echo $lang_site['Categories']; ?></strong> 
	<?php
	while ($cur_cat = $db->fetch_assoc($c))
	{
		?>
		<label for="cat_<?php echo $cur_cat['tcat_id']; ?>"><?php echo $cur_cat['tcat_name_'.$lang]; ?></label> <input type="checkbox" name="typecat[]" value="<?php echo $cur_cat['tcat_id']; ?>" id="cat_<?php echo $cur_cat['tcat_id']; ?>" /> &nbsp; 
		<?php
	}
	?>
	</p */ ?>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
if(isset($_GET['edit_type']))
{
	$edit_type = intval($_GET['edit_type']);
	if($edit_type < 1)
		site_msg($lang_site['Bad request']);

	if(isset($_POST['save']))
	{
		site_confirm_referrer('admin_tuts_types.php');

		$typename = pun_trim($_POST['typename']);
		if($typename == '')
			site_msg($lang_site['Admin no typename']);

//		$catids = array_filter($_POST['typecat'],'is_numeric');
//		if(count($catids) < 1)
//			site_msg($lang_site['Admin no typecat']);

		$db->query('UPDATE tuts_type SET type_name=\''.$db->escape($typename).'\' WHERE type_id='.$edit_type) or error('Unable to update tutorial type', __FILE__, __LINE__, $db->error());
		site_redirect('admin_tuts_types.php', $lang_site['Admin type edited redirect']);
	}
	//fetch cat info
	$result = $db->query('SELECT type_id, type_name FROM tuts_type WHERE type_id='.$edit_type) or error('Unable to fetch tutorial type data', __FILE__, __LINE__, $db->error());
	if(!$db->num_rows($result))
		site_msg($lang_site['Bad request']);

	$cur_type = $db->fetch_assoc($result);

	$titre_page = $lang_site['Admin edit type'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=tutorials"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="admin_tuts_types.php"><?php echo $lang_site['Types']; ?></a> &gt; <?php echo $lang_site['Admin edit type']; ?></p>
<h3><?php echo $lang_site['Admin edit type']; ?></h3>
<form method="post" action="admin_tuts_types.php?edit_type=<?php echo $edit_type; ?>">
	<p>
		<label for="typename"><?php echo $lang_site['Admin type name']; ?><br /><input type="text" value="<?php echo pun_htmlspecialchars($cur_type['type_name']); ?>" name="typename" id="typename" size="50" maxlength="32" /></label>
	</p>
	<hr class="sep" />
	<?php /*p class="form">
		<strong class="label"><?php echo $lang_site['Categories']; ?></strong> 
	<?php
	$lang = $lang_site['Lang'];
	$c = $db->query('SELECT tcat_id, tcat_name_'.$lang.' FROM tuts_cat ORDER BY tcat_id') or error('Unable to get category data', __FILE__, __LINE__, $db->error());

	$typecat = explode(',',$cur_type['type_cat']);

	while ($cur_cat = $db->fetch_assoc($c))
	{
		?>
		<label for="cat_<?php echo $cur_cat['tcat_id']; ?>"><?php echo $cur_cat['tcat_name_'.$lang]; ?></label> <input type="checkbox" name="typecat[]" value="<?php echo $cur_cat['tcat_id']; ?>" id="cat_<?php echo $cur_cat['tcat_id']; ?>" <?php echo (in_array($cur_cat['tcat_id'],$typecat)) ? 'checked="checked" ' : ''; ?>
		<?php
	}
	?>
	</p */ ?>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
if(isset($_GET['del_type']))
{
	site_confirm_referrer('admin_tuts_types.php');
	$type_id = intval($_GET['del_type']);
	if($type_id < 1)
		site_msg($lang_site['Bad request']);

	if(isset($_POST['del_type_comply']))
	{
		@set_time_limit(0);

		$tutres = $db->query('SELECT tentry_id FROM tuts_entries WHERE tentry_type LIKE(\'%'.$type_id.'%\')') or error('Unable to get tutorial data', __FILE__, __LINE__, $db->error());

		while ($cur_tut = $db->fetch_assoc($tutres))
			$db->query('UPDATE tuts entries SET tentry_type=0 WHERE tentry_id='.$cur_tut['tentry_id']) or error('Unable to update tutorial data', __FILE__, __LINE__, $db->error());
		$db->query('DELETE FROM tuts_type WHERE type_id='.$type_id) or error('Unable to delete tutorial type', __FILE__, __LINE__, $db->error());
		site_redirect('admin_tuts_types.php', $lang_site['Admin type deleted redirect']);
	}
	else
	{
		$lang = $lang_site['Lang'];
		$result = $db->query('SELECT type_name FROM tuts_type WHERE type_id='.$type_id) or error('Unable to get tutorial type info', __FILE__, __LINE__, $db->error());
		$type_name = $db->result($result);

		$titre_page = $lang_site['Admin delete type'];
		$module = 'admin';
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=tutorials"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="admin_tuts_types.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php echo $lang_site['Admin delete type head']; ?></p>
<h3><?php echo $lang_site['Admin delete type head']; ?></h3>
<form method="post" action="admin_tuts_types.php?del_type=<?php echo $type_id; ?>">
	<div class="inform">
	<input type="hidden" name="type_to_delete" value="<?php echo $type_id; ?>" />
		<fieldset>
			<legend><?php echo $lang_site['Admin confirm delete type subhead']; ?></legend>
			<div class="infldset">
				<p><?php sprintf($lang_site['Admin confirm delete type info'], pun_htmlspecialchars(shorttext_lang($type_name,$lang))); ?></p>
				<p class="warntext"><?php echo $lang_site['Admin delete type warn']; ?></p>
			</div>
		</fieldset>
	</div>
	<p class="buttons"><input type="submit" name="del_type_comply" value="<?php echo $lang_site['Delete']; ?>" /> &nbsp; &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
	}
}
$titre_page = $lang_site['Pagename adm tuts types'];
$module = 'admin';
require './includes/top.php'; ?>
<!-- Début partie principale -->
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=tutorials"><?php echo $lang_site['Tutorials']; ?></a> &gt; <a href="admin_tuts_types.php"><?php echo $lang_site['Types']; ?></a></p>
<h3><?php echo $lang_site['Title adm tuts types']; ?></h3>
<?php echo $lang_site['Explain adm tuts types']; ?>
<ul class="adm-tabs">
	<li><a href="admin.php?adm=tutorials"><?php echo $lang_site['Overview']; ?></a></li>
	<li><a href="admin_tuts_cat.php"><?php echo $lang_site['Categories']; ?></a></li>
	<li class="tab-active"><?php echo $lang_site['Types']; ?></li>
	<li><a href="admin_tutorials.php"><?php echo $lang_site['Tutorials']; ?></a></li>
	<li><a href="admin_comments.php"><?php echo $lang_site['Comments']; ?></a></li>
</ul>
<?php /*form method="post" action="admin_tuts_cat.php"*/ ?>
<div class="adm-cont">
	<table class="adm-table wide" id="tbl-tuts-type-overview">
		<tr>
			<th class="name"><?php echo $lang_site['Name']; ?></th>
			<?php /*th class="cat"><?php echo $lang_site['Categories']; ?></th */ ?>
			<th class="edit"><?php echo $lang_site['Actions']; ?></th>
		</tr>
<?php
$lang = $lang_site['Lang'];
$type_result = $db->query('SELECT type_id, type_name FROM tuts_type ORDER BY type_id') or error('Unable to get tutorial type data', __FILE__, __LINE__, $db->error());

//Tout ça pourrait être mis en cache
if($db->num_rows($type_result) > 0)
{
	while($cur_type = $db->fetch_assoc($type_result))
	{
//		$categories = explode('.',$cur_type['type_cat'];
//		$c = $db->query('SELECT tcat_name_'.$lang.' FROM tuts_cat WHERE tcat_id IN('.$cur_type['type_cat'].')') or error('Unable to get category data', __FILE__, __LINE__, $db->error());
?>
		<tr class="typerow">
			<td class="name"><?php echo pun_htmlspecialchars(shorttext_lang($cur_type['type_name'],$lang)); ?></td>
			<?php /*td class="cat center nowrap"><?php
		$categ = NULL;
		while ($cur_cat = $db->fetch_assoc($c))
		{
			if($categ != NULL)
				echo ', ';

			echo $cur_cat['tcat_name_'.$lang];
			$categ = $cur_cat['tcat_name_'.$lang];
		}
			?></td */ ?>
			<td class="actions center nowrap"><a href="admin_tuts_types.php?edit_type=<?php echo $cur_type['type_id']; ?>"><?php echo $lang_site['Edit']; ?></a>&nbsp;<a href="admin_tuts_types.php?del_type=<?php echo $cur_type['type_id']; ?>"><?php echo $lang_site['Delete']; ?></a></td>
		</tr>
<?php
	}
}
else
	echo '<tr><td colspan="2" class="center">'.$lang_site['Admin no type'].'</td></tr>';
?>
	</table>
	<p class="new-data"><a href="admin_tuts_types.php?add_type=true"><?php echo $lang_site['Admin add type']; ?></a></p>
	<div class="clearfix"></div>
</div>
</form>
<?php require './includes/bottom.php';
