<?php require './includes/init-main.php';
if($site_config['o_enable_res'] == '0')
	site_msg($lang_site['Module disabled']);

$lang_site['Lang'] = $lang;
//Pour l'inclusion des modules appropriés
if(isset($_GET['cat']))
{
	$cat_id = intval($_GET['cat']);
	if($cat_id < 1)
		site_msg($lang['Bad request']);

	$query1 = $db->query('SELECT rcat_id, rcat_name, rcat_clearname, rcat_lang, rcat_desc FROM res_cat WHERE rcat_id=\''.$cat_id.'\' AND rcat_lang LIKE (\'%'.$lang.'%\')') or error('Unable to fetch category data', __FILE__, __LINE__, $db->error());

	if(!$db->num_rows($query1))
		site_msg($lang_site['Category not found']);

	$cur_cat = $db->fetch_assoc($query1);

	$query2 = $db->query('SELECT rsub_id, rsub_name, rsub_lang, rsub_desc, rsub_catid FROM res_subcat WHERE rsub_catid=\''.$cat_id.'\' AND rsub_lang LIKE (\'%'.$lang.'%\')') or error('Unable to fetch subcat data', __FILE__, __LINE__, $db->error());
	if(!$db->num_rows($query2))
		site_msg($lang_site['No subcat']);

	require PUN_ROOT.'include/parser.php';
	$catname = pun_htmlspecialchars(shorttext_lang($cur_cat['rcat_name'],$lang));
	$catclear = pun_htmlspecialchars(shorttext_lang($cur_cat['rcat_clearname'],$lang));
	$catdesc = parse_message(longtext_lang($cur_cat['rcat_desc'],$lang),0);

	$module = 'resources';
	$titre_page = $lang_site['Pagename res for'] . $catname;
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="resources.php"><?php echo $lang_site['Resources']; ?></a> &gt; <a href="resources.php?cat=<?php echo $cur_cat['rcat_id']; ?>"><?php echo $catclear; ?></a></p>
<h3><?php echo $lang_site['Title resources for'].' '.$catname; ?></h3>
<?php echo $catdesc; ?>
<?php
	while($cur_subcat = $db->fetch_assoc($query2))
	{
		$subname = pun_htmlspecialchars(shorttext_lang($cur_subcat['rsub_name'],$lang));
		$subclear = pun_htmlspecialchars(shorttext_lang($cur_subcat['rsub_clearname'],$lang));
		$subdesc = parse_message(longtext_lang($cur_subcat['rsub_desc'],$lang),0);
?>
<h4 class="subtitle"><?php echo $subname; ?></h4>
<p><?php echo $subdesc; ?><br /><a href="resources.php?subcat=<?php echo $cur_subcat['rsub_id']; ?>"><?php echo $lang_site['View list'] . $subname; ?></a></p>
<?php
	}
	require './includes/bottom.php';
}
elseif(isset($_GET['subcat']))
{
	$subcat_id = intval($_GET['subcat']);
	if($subcat_id < 1)
		site_msg($lang_site['Bad request']);

	$sub_result = $db->query('SELECT rsub_id, rsub_name, rsub_clearname, rsub_lang, rsub_type, rsub_desc, rsub_catid, rcat_name, rcat_clearname FROM res_subcat LEFT JOIN res_cat ON rsub_catid=rcat_id WHERE rsub_id='.$subcat_id.' AND rsub_lang LIKE (\'%'.$lang.'%\')') or error('Unable to get subcat data', __FILE__, __LINE__, $db->error());

	if(!$db->num_rows($sub_result) > 0)
		site_msg($lang_site['Subcat not found']);

	$cur_subcat = $db->fetch_assoc($sub_result);

	require PUN_ROOT.'include/parser.php';
	$catname = pun_htmlspecialchars(shorttext_lang($cur_subcat['rcat_name'],$lang));
	$catclear = pun_htmlspecialchars(shorttext_lang($cur_subcat['rcat_clearname'],$lang));
	$subname = pun_htmlspecialchars(shorttext_lang($cur_subcat['rsub_name'],$lang));
	$subclear = pun_htmlspecialchars(shorttext_lang($cur_subcat['rsub_clearname'],$lang));
	$subdesc = parse_message(longtext_lang($cur_subcat['rsub_desc'],$lang),0);

	//We check if we list hacks or styles
	if($cur_subcat['rsub_type'] == 'hack')
	{
		$filter = !$pun_user['is_admmod'] ? ' AND tentry_publish=1' : '';
		$res_result = $db->query('SELECT rentry_id, rentry_name, rentry_shortdesc, rentry_catid, rentry_subcatid FROM res_entries WHERE rentry_subcatid=\''.$cur_subcat['rsub_id'].'\' AND rentry_lang LIKE(\'%'.$lang.'%\')'.$filter.' ORDER BY rentry_id DESC');

		if(!$db->num_rows($res_result))
			site_msg($lang_site['No resource']);

		$module = 'resources';
		$titre_page = $subname . $lang_site['Pagename for'] . $catname;
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="resources.php"><?php echo $lang_site['Resources']; ?></a> &gt; <a href="resources.php?cat=<?php echo $cur_subcat['rsub_catid']; ?>"><?php echo $catclear; ?></a> &gt; <a href="resources.php?subcat=<?php echo $cur_subcat['rsub_id']; ?>"><?php echo $catclear; ?></a></p>
<h3><?php echo order_by_lang($lang,$catclear,$subclear); ?></h3>
<?php echo $subdesc;
		$row = 0;
		while($cur_res = $db->fetch_assoc($res_result))
		{
			++$row;
			if(($row % 2) == 1)
				$class = 'block-left';
			else
				$class = 'block-right';

			$resname = pun_htmlspecialchars(shorttext_lang($cur_res['rentry_name'],$lang));
			$resshort = pun_htmlspecialchars(shorttext_lang($cur_res['rentry_shortdesc'],$lang));
?>
<div class="home-block mod-block <?php echo $class; ?>">
	<h4 lang="en"><?php echo $resname; ?></h4>
	<p><?php echo $resshort; ?><a class="align" href="resources.php?res=<?php echo $cur_res['rentry_id']; ?>"><?php echo $lang_site['View details']; ?></a></p>
</div>
<?php
		}
		require './includes/bottom.php';
	}
	else
	{
		$filter = !$pun_user['is_admmod'] ? ' AND rentry_publish=1' : '';
		$res_result = $db->query('SELECT rentry_id, rentry_name, rentry_catid, rentry_subcatid, rentry_screen_main, rscreen_id, rscreen_legend, rscreen_url_full, rscreen_url_small FROM res_entries LEFT JOIN res_screens ON rentry_screen_main=rscreen_id WHERE rentry_subcatid='.$cur_subcat['rsub_id'].' AND rentry_lang LIKE(\'%'.$lang.'%\')'.$filter.' ORDER BY rentry_id DESC') or error('Unable to get resource data', __FILE__, __LINE__, $db->error());

		if(!$res_result)
			site_msg($lang_site['No resource']);

		$module = 'resources';
		$titre_page = $subname . $lang_site['Pagename for'] . $catname;
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="resources.php"><?php echo $lang_site['Resources']; ?></a> &gt; <a href="resources.php?cat=<?php echo $cur_subcat['rsub_catid']; ?>"><?php echo $catclear; ?></a> &gt; <a href="resources.php?subcat=<?php echo $cur_subcat['rsub_id']; ?>"><?php echo $subclear; ?></a></p>
<h3><?php echo order_by_lang($lang,$catname,$subname); ?></h3>
<?php echo $subdesc;
		$row = 0;
		$class = '';
		while($cur_res = $db->fetch_assoc($res_result))
		{
			++$row;
			if(($row % 3) == 1)
				$class = 'block-left';
			elseif(($row % 3) == 0)
				$class = 'block-right';
			else
				$class = '';

			$resname = pun_htmlspecialchars(shorttext_lang($cur_res['rentry_name'],$lang));
			$resshort = pun_htmlspecialchars(shorttext_lang($cur_res['rentry_shortdesc'],$lang));
			$screenlegend = pun_htmlspecialchars(shorttext_lang($cur_res['rscreen_legend'],$lang));

			?><div class="home-block style-thumb <?php echo $class; ?>">
	<h4><?php echo $resname; ?></h4>
	<p class="align"><a href="<?php echo $cur_res['rscreen_url_full']; ?>"><img src="<?php echo $cur_res['rscreen_url_small']; ?>" alt="<?php echo order_by_lang($lang,$lang_site['Preview style'],$resshort); ?>" /></a><br /><a href="resources.php?res=<?php echo $cur_res['rentry_id']; ?>"><?php echo $lang_site['View info']; ?></a></p>
</div><?php
		}
		require './includes/bottom.php';
	}
}
elseif(isset($_GET['res']))
{
	$res_id = intval($_GET['res']);
	if($res_id < 1)
		site_msg($lang_site['Bad request']);

	$filter = !$pun_user['is_admmod'] ? ' AND publis=1' : '';

	$query1 = $db->query('SELECT
		rentry_id, rentry_name, rentry_screen_main, rentry_desc, rentry_authornotes,
		rentry_subcatid, rentry_catid, rentry_download, rentry_lang, rentry_publishdate, rentry_lastupdate,
		rsub_id, rsub_name, rsub_clearname, rsub_type,
		rcat_id, rcat_name, rcat_clearname,
		rscreen_id, rscreen_url_full, rscreen_url_small, rscreen_legend
	FROM res_entries
	LEFT JOIN res_subcat ON rentry_subcatid=rsub_id
	LEFT JOIN res_cat ON rentry_catid=rcat_id
	LEFT JOIN res_screens ON rentry_screen_main=rscreen_id
	WHERE rentry_id='.$res_id.' AND rentry_lang LIKE(\'%'.$lang.'%\')'.$filter) or error('Unable to get resource data', __FILE__, __LINE__, $db->error());

	if(!$db->num_rows($query1))
		site_msg($lang_site['Resource not found']);

	$res = $db->fetch_assoc($query1);

	require PUN_ROOT.'include/parser.php';

	$resname = pun_htmlspecialchars(shorttext_lang($res['rentry_name'],$lang));
	$resdesc = parse_message(longtext_lang($res['rentry_desc'],$lang),0);
	$resauthor = parse_message(longtext_lang($res['rentry_authornotes'],$lang),0);
	$screenlegend = pun_htmlspecialchars(shorttext_lang($res['rscreen_legend'],$lang));
	$catname = pun_htmlspecialchars(shorttext_lang($res['rcat_name'],$lang));
	$catclear = pun_htmlspecialchars(shorttext_lang($res['rcat_clearname'],$lang));
	$subname = pun_htmlspecialchars(shorttext_lang($res['rsub_name'],$lang));
	$subclear = pun_htmlspecialchars(shorttext_lang($res['rsub_clearname'],$lang));

	$module = 'resources';
	$titre_page = '['.$subname.'] '.$resname . $lang_site['Pagename for'] . $catname;
	$pageclass = 'style';
	require './includes/top.php';
	?>
	<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="resources.php"><?php echo $lang_site['Resources']; ?></a> &gt; <a href="resources.php?cat=<?php echo $res['rcat_id']; ?>"><?php echo $catclear; ?></a> &gt; <a href="resources.php?subcat=<?php echo $res['rsub_id']; ?>"><?php echo $subclear; ?></a> &gt; <a href="resources.php?id=<?php echo $res['rentry_id']; ?>"><?php echo $resname; ?></a></p>
	<h3><?php echo '['.$subname.'] '.$resname . $lang_site['Pagename for'] . $catname; ?></h3>
	<p class="style-preview"><a href="<?php echo $res['rscreen_url_full']; ?>"><img src="<?php echo $res['rscreen_url_small']; ?>" alt="<?php echo $lang_site['Preview'] . pun_htmlspecialchars($res['rsub_type']); ?>" /></a><br /><a class="download" href="<?php echo $res['rentry_download']; ?>"><?php echo $lang_site['Download'] . pun_htmlspecialchars($res['rsub_type']); ?></a></p>
	<div class="style-desc"><?php echo $resdesc; ?></div>
	<hr class="sep" />
	<h4><?php echo $lang_site['Author notes']; ?></h4>
	<?php echo $resauthor; ?>
	<hr class="sep" />
	<h4><?php echo $lang_site['Screenshots']; ?></h4>
	<?php
	$query2 = $db->query('SELECT rscreen_id, rscreen_legend, rscreen_url_full, rscreen_url_small, rscreen_entryid FROM res_screens WHERE rscreen_entryid='.$res_id.' ORDER BY rscreen_id DESC');

	if($db->num_rows($query2) > 0)
	{
		echo '<p class="diapo">';
		while($screen = $db->fetch_assoc($query2))
		{
			$screenlegend = pun_htmlspecialchars(shorttext_lang($screen['rscreen_legend'],$lang));
			echo '<a href="'.$screen['rscreen_url_full'].'"><img src="'.$screen['rscreen_url_small'].'" alt="'.$screenlegend.'" /></a>';
		}
		echo '</p>';
	}
	else
		echo '<p class="notice">'.$lang_site['No screen'].'</p>';
	require './includes/bottom.php';
}

$query = $db->query('SELECT rcat_id, rcat_name, rcat_clearname, rcat_icon, rcat_order, rcat_lang, rsub_id, rsub_name, rsub_clearname, rsub_catid FROM res_cat LEFT JOIN res_subcat ON rcat_id=rsub_catid WHERE rcat_lang LIKE(\'%'.$lang.'%\') ORDER BY rcat_order, rsub_id');
if(!$db->num_rows($query))
	site_msg($lang_site['No cat']);

$titre_page = $lang_site['Pagename res home'];
$module = 'resources';
require './includes/top.php'; ?>
<p class="crumbs"><?php echo$pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="resources.php"><?php echo $lang_site['Resources']; ?></a></p>
<h3><?php echo $lang_site['Title resources']; ?></h3>
<?php echo $lang_site['Explain res home'];

$count = 0;
while($cur_cat = $db->fetch_assoc($query))
{
	$catname = pun_htmlspecialchars(shorttext_lang($cur_cat['rcat_name'],$lang));
	$catclear = pun_htmlspecialchars(shorttext_lang($cur_cat['rcat_clearname'],$lang));
	$subname = pun_htmlspecialchars(shorttext_lang($cur_cat['rsub_name'],$lang));
	$subclear = pun_htmlspecialchars(shorttext_lang($cur_cat['rsub_clearname'],$lang));

	++$count;
	if($category != $cur_cat['rcat_id'])
	{
		if($category != NULL)
		{
?>
	</p>
</div><?php
		}

		++$row;
		if(($row % 3) == 1)
			$class = ' cat-left';
		elseif(($row % 3) == 0)
			$class = ' cat-right';
		else
			$class = '';
		$category = $cur_cat['rcat_id'];
?><div class="home-block catblock<?php echo $class; ?>" id="res_<?php echo strtolower($catclear); ?>">
	<h4><?php echo $catname; ?></h4>
	<p class="align"><a href="resources.php?cat=<?php echo $cur_cat['rcat_id']; ?>"><img src="<?php echo $cur_cat['rcat_icon']; ?>" alt="<?php echo $catname; ?>" /></a><br />
<?php
	}
	else
		echo ' - '; //Séparateur entre les liens
	if(!empty($cur_cat['rsub_id']))
		echo '<a href="resources.php?subcat='.$cur_cat['rsub_id'].'">'.$subclear.'</a>';
	else
		echo '&nbsp;';
}
if($count > 0)
	echo '</p></div>';
require './includes/bottom.php';
