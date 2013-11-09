<?php require './includes/init-main.php';
if(!$pun_user['is_admmod'])
	message($lang_common['No permission']);

if(isset($_GET['add_page']))
{
	if($_GET['add_page'] !== 'true')
		site_msg($site_lang['Bad request'].'-1');

	site_confirm_referrer('admin_pages.php');

	if(isset($_POST['save']))
	{
		$page_title = pun_trim($_POST['page_title']);
		if($page_title == '')
			site_msg($lang_site['Admin no page title']);
		$page_clean = pun_trim($_POST['page_clean']);
		if($page_clean == '')
			site_msg($lang_site['Admin no clean title']);

		require PUN_ROOT.'include/parser.php';
		$text = pun_linebreaks(pun_trim($_POST['page_text']));
		if ($pun_config['p_message_bbcode'] == '1')
			$page_text = preparse_bbcode($text, $errors);
		if($page_text == '')
			site_msg($lang_site['Admin no text']);

		$langs = array('fr','en');
		$page_lang = pun_trim($_POST['page_lang']);
		if(!in_array($page_lang,$langs))
			site_msg($lang_site['Admin no language']);

		$now = time();

		$sql = 'INSERT INTO site_pages(page_id,page_title,page_title_clean,page_text,page_lang,page_publishdate,page_lastupdate) VALUES(\'\',\''.$db->escape($page_title).'\',\''.$db->escape($page_clean).'\',\''.$db->escape($page_text).'\',\''.$db->escape($page_lang).'\','.$now.','.$now.')';
		$db->query($sql) or error('Unable to add custom page', __FILE__, __LINE__, $db->error());
		site_redirect('admin_pages.php', $lang_site['Admin page added redirect']);
	}
	$lang = $lang_site['Lang'];
	$titre_page = $lang_site['Admin add page'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_pages.php"><?php echo $lang_site['Pages']; ?></a> &gt; <?php echo $lang_site['Admin add page']; ?></p>
<h3><?php echo $lang_site['Admin add page']; ?></h3>
<?php echo $lang_site['Explain adm add page']; ?>
<form method="post" action="admin_pages.php?add_page=true">
	<p class="form">
		<label for="pagetitle"><?php echo $lang_site['Admin page title']; ?><br /><input type="text" name="page_title" id="pagetitle" size="50" maxlength="128" /></label><br />
		<label for="pageclean"><?php echo $lang_site['Admin page clean title']; ?><br /><input type="text" name="page_clean" id="pageclean" size="50" maxlength="32" /></label><br />
		<label for="pagetext"><?php echo $lang_site['Admin page text']; ?><br /><textarea name="page_text" id="pagetext" cols="70" rows="5"></textarea></label><br />
		<strong class="label"><?php echo $lang_site['Language']; ?></strong> <label for="disp_fr"><?php echo $lang_site['French']; ?></label> <input type="radio" name="page_lang" id="disp_fr" value="fr" /> <label for="disp_en"><?php echo $lang_site['English']; ?></label> <input type="radio" name="page_lang" id="disp_en" value="en" /><br />
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
elseif(isset($_GET['edit_page']))
{
		$edit_page = intval($_GET['edit_page']);
		if($edit_page < 1)
			site_msg($lang_site['Bad request']);

	site_confirm_referrer('admin_pages.php');
	if(isset($_POST['save']))
	{
		$page_title = pun_trim($_POST['page_title']);
		if($page_title == '')
			site_msg($lang_site['Admin no page title']);
		$page_clean = pun_trim($_POST['page_clean']);
		if($page_clean == '')
			site_msg($lang_site['Admin no clean title']);

		require PUN_ROOT.'include/parser.php';
		$text = pun_linebreaks(pun_trim($_POST['page_text']));
		if ($pun_config['p_message_bbcode'] == '1')
			$page_text = preparse_bbcode($text, $errors);
		if($page_text == '')
			site_msg($lang_site['Admin no text']);
		$sql = 'UPDATE site_pages SET page_title=\''.$db->escape($page_title).'\', page_title_clean=\''.$db->escape($page_clean).'\', page_text=\''.$db->escape($page_text).'\', page_lastupdate='.time().' WHERE page_id='.$edit_page;
		$db->query($sql) or error('Unable to update page data', __FILE__, __LINE__, $db->error());
		site_redirect('admin_pages.php', $lang_site['Admin page edited redirect']);
	}

	$lang = $lang_site['Lang'];
	$page_result = $db->query('SELECT page_id, page_title, page_title_clean, page_text FROM site_pages WHERE page_id='.$edit_page) or error('Unable to get page data', __FILE__, __LINE__, $db->error());
	if(!$page_result)
		site_msg($lang_site['Bad request']);

	$cur_page = $db->fetch_assoc($page_result);
	$titre_page = $lang_site['Admin edit page'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_page.php"><?php echo $lang_site['Pages']; ?></a> &gt; <?php echo $lang_site['Admin edit page']; ?></p>
<h3><?php echo $lang_site['Admin edit page']; ?></h3>
<?php echo $lang_site['Explain adm edit page']; ?>
<form method="post" action="admin_pages.php?edit_page=<?php echo $edit_page; ?>">
	<p class="form">
		<label for="pagetitle"><?php echo $lang_site['Admin page title']; ?><br /><input type="text" name="page_title" id="pagetitle" size="50" maxlength="128" value="<?php echo pun_htmlspecialchars($cur_page['page_title']); ?>" /></label><br />
		<label for="pageclean"><?php echo $lang_site['Admin page clean title']; ?><br /><input type="text" name="page_clean" id="pageclean" size="50" maxlength="32" value="<?php echo pun_htmlspecialchars($cur_page['page_title_clean']); ?>" /></label><br />
		<label for="pagetext"><?php echo $lang_site['Admin page text']; ?><br /><textarea name="page_text" id="pagetext" cols="70" rows="5"><?php echo $cur_page['page_text']; ?></textarea></label>
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
elseif(isset($_GET['del_page']))
{
	site_confirm_referrer('admin_pages.php');
	$page_id = intval($_GET['del_page']);
	if($page_id < 1)
		site_msg($lang_site['Bad request']);

	if(isset($_POST['del_page_comply']))
	{
		@set_time_limit(0);

		$db->query('DELETE FROM site_pages WHERE page_id='.$page_id) or error('Unable to delete page', __FILE__, __LINE__, $db->error());
		site_redirect('admin_pages.php', $lang_site['Admin page deleted redirect']);
	}
	else
	{
		$result = $db->query('SELECT page_title FROM site_pages WHERE page_id='.$page_id) or error('Unable to fetch page info');
		$page_name = $db->result($result);

		$titre_page = $lang_site['Admin delete page'];
		$module = 'admin';
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_pages.php"><?php echo $lang_site['Pages']; ?></a> &gt; <?php echo $lang_site['Admin delete pages']; ?></p>
<h3><?php echo $lang_site['Admin delete page head']; ?></h3>
<form method="post" action="admin_pages.php?del_page=<?php echo $page_id; ?>">
	<div class="inform">
	<input type="hidden" name="page_to_delete" value="<?php echo $page_id; ?>" />
		<fieldset>
			<legend><?php echo $lang_site['Admin confirm delete page subhead']; ?></legend>
			<div class="infldset">
				<p><?php sprintf($lang_site['Admin confirm delete page info'], pun_htmlspecialchars($page_name)); ?></p>
				<p class="warntext"><?php echo $lang_site['Admin delete page warn']; ?></p>
			</div>
		</fieldset>
	</div>
	<p class="buttons"><input type="submit" name="del_page_comply" value="<?php echo $lang_site['Delete']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
	}
}

$titre_page = $lang_site['Pagename adm pages'];
$module = 'admin';
require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_pages.php"><?php echo $lang_site['Pages']; ?></a></p>
<h3><?php echo $lang_site['Title adm pages']; ?></h3>
<?php echo $lang_site['Explain adm pages']; ?>
<ul class="adm-tabs">
	<?php if ($pun_user['g_id'] == PUN_ADMIN): ?><li class="tab-active"><?php echo $lang_site['Pages']; ?></li><?php endif; ?>
</ul>
<div class="adm-cont">
	<p class="new-data"><a href="admin_pages.php?add_page=true"><?php echo $lang_site['Admin add page']; ?></a></p>
	<table class="adm-table" id="tbl-page-list">
		<tr>
			<th class="title"><?php echo $lang_site['Title']; ?></th>
			<th class="lang"><?php echo $lang_site['Language']; ?></th>
			<th class="url"><?php echo $lang_site['URL']; ?></th>
			<th class="lastupdate"><?php echo $lang_site['Last update']; ?></th>
			<th class="actions"><?php echo $lang_site['Actions']; ?></th>
		</tr>
<?php

$lang = $lang_site['Lang'];

$sql = 'SELECT page_id, page_title, page_lang, page_lastupdate FROM site_pages ORDER BY page_id DESC';
$result = $db->query($sql) or error('Unable to get page data', __FILE__, __LINE__, $db->error());

if($db->num_rows($result) > 0)
{
	while($cur_page = $db->fetch_assoc($result))
	{
?>
		<tr>
			<td class="title"><?php echo pun_htmlspecialchars($cur_page['page_title']); ?></td>
			<td class="lang center"><?php echo pun_htmlspecialchars($cur_page['page_lang']); ?></td>
			<td class="link center"><a title="<?php echo $lang_site['Use right click']; ?>" href="index.php?module=page&amp;page=<?php echo $cur_page['page_id']; ?>"><?php echo 'index.php?module=page&amp;page='.$cur_page['page_id']; ?></a>
			<td class="lastupdate center"><?php echo format_time($cur_page['page_lastupdate']); ?></td>
			<td class="actions nowrap center"><a href="admin_pages.php?edit_page=<?php echo $cur_page['page_id']; ?>"><?php echo $lang_site['Edit']; ?></a> <a href="admin_pages.php?del_page=<?php echo $cur_page['page_id']; ?>"><?php echo $lang_site['Delete']; ?></a></td>
		</tr>
<?php
	}
}
	else echo '<tr><td colspan="5" class="center">'.$lang_site['No page'].'</td></tr>'; ?>
	</table>
	<p class="new-data"><a href="admin_pages.php?add_page=true"><?php echo $lang_site['Admin add page']; ?></a></p>
	<div class="clearfix"></div>
</div>
<?php require './includes/bottom.php'; ?>
