<?php require './includes/init-main.php';
if(!$pun_user['is_admmod'])
	message($lang_site['No view']);

$lang = $lang_site['Lang'];
if(isset($_GET['add_res']))
{
	if($_GET['add_res'] !== 'true')
		site_msg($lang_site['Bad request']);

	site_confirm_referrer('admin_resources.php');

	if(isset($_POST['save']))
	{
		list($cat_id,$sub_id) = explode('-',$_POST['res_cat']);
		if($cat_id < 1 || $sub_id < 1)
			site_msg($_POST['Bad request']);

		$resname = pun_trim($_POST['resname']);	
		if($resname == '')
			site_msg($lang_site['Admin no resname']);
	
		$resshort = pun_trim($_POST['resshort']);
		if($resshort == '')
			site_msg($lang_site['Admin no shortdesc']);
	
		$desc = pun_linebreaks(pun_trim($_POST['resdesc']));
		$notes = pun_linebreaks(pun_trim($_POST['resnotes']));
		if ($pun_config['p_message_bbcode'] == '1')
		{
			require PUN_ROOT.'include/parser.php';
			$resdesc = preparse_bbcode($desc, $errors);
			$resnotes = preparse_bbcode($notes, $errors);
		}
		if($resdesc == '')
			site_msg($lang_site['Admin no longdesc']);
		if($resnotes == '')
			site_msg($lang_site['Admin no notes']);

		$catid_result = $db->query('SELECT rcat_lang FROM res_cat WHERE rcat_id='.$cat_id) or error('Unable to get category ID', __FILE__, __LINE__, $db->error());
		$langs = $db->result($catid_result);
		$disp_lang = array();
		if(preg_match('#fr#',$langs))
			$disp_lang[] = 'fr';
		if(preg_match('#en#',$langs))
			$disp_lang[] = 'en';
		$sql_lang = implode(',',$disp_lang);

		$download = pun_trim($_POST['download']);
		if($download == '')
			site_msg($lang_site['No download link']);
		$now = time();

		$sql = 'INSERT INTO res_entries(rentry_id,rentry_name,rentry_shortdesc,rentry_desc,rentry_authornotes,rentry_catid,rentry_subcatid,rentry_download,rentry_lang,rentry_publish,rentry_publishdate,rentry_lastupdate) VALUES(\'\',\''.$db->escape($resname).'\',\''.$db->escape($resshort).'\',\''.$db->escape($resdesc).'\',\''.$db->escape($resnotes).'\','.$cat_id.','.$sub_id.',\''.$db->escape($download).'\',\''.$db->escape($sql_lang).'\',0,'.$now.','.$now.')';
		$db->query($sql) or error('Unable to add resource data', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_admin_home_cache($lang);
		generate_admin_res_home_cache($lang);
		site_redirect('admin_resources.php?edit_res=last', $lang_site['Admin res added redirect']);
	}
	$lang = $lang_site['Lang'];
	//Requête pour les catégories
	$cat_result = $db->query('SELECT rcat_id, rcat_name, rcat_order, rsub_id, rsub_name, rsub_type FROM res_cat LEFT JOIN res_subcat ON rsub_catid=rcat_id ORDER BY rcat_id') or error('Unable to get category data', __FILE__, __LINE__, $db->error());
	if($db->num_rows($cat_result) == 0)
		site_msg($lang_site['Admin must have categories']);

	$titre_page = $lang_site['Admin add res'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_resources.php"><?php echo $lang_site['Resources']; ?></a> &gt; <?php echo $lang_site['Admin add res']; ?></p>
<h3><?php echo $lang_site['Admin add res']; ?></h3>
<form method="post" action="admin_resources.php?add_res=true" enctype="multipart/form-data">
	<p class="form">
		<label for="resname"><?php echo $lang_site['Admin res name']; ?><br /><input type="text" name="resname" id="resname" size="50" maxlength="128" value="{fr|Exemple de nom de ressource}||{en|Sample resource name}" /></label><br />
		<label for="resshort"><?php echo $lang_site['Admin res shortdesc']; ?><br /><input type="text" name="resshort" id="resshort" size="50" maxlength="512" value="{fr|Exemple de courte description - Aucun BBCode autorisé}||{en|Sample short description - No BBCode allowed}" /></label><br />
		<label for="resdesc"><?php echo $lang_site['Admin res desc']; ?><br /><textarea name="resdesc" id="resdesc" cols="70" rows="5">{fr|Exemple de description}

{en|Sample description}</textarea></label><br />
		<label for="resnotes"><?php echo $lang_site['Admin res notes'].' ('.$lang_site['French'].')'; ?><br /><textarea name="resnotes" id="resnotes" cols="70" rows="5">{fr|Exemple de notes de l'auteur}

{en|Sample author notes}</textarea></label>
	</p>
	<hr class="sep" />
	<p class="form">
		<strong class="label"><?php echo $lang_site['Languages']; ?></strong> <label for="disp_fr"><?php echo $lang_site['French']; ?></label> <input type="checkbox" name="lang_fr" id="disp_fr" /> <label for="disp_en"><?php echo $lang_site['English']; ?></label> <input type="checkbox" name="lang_en" id="disp_en" /><br />
		<label for="category"><?php echo $lang_site['Category']; ?> <select name="res_cat" id="category">
<?php
		$category = NULL;
		while($cur_cat = $db->fetch_assoc($cat_result))
		{
			if($category != $cur_cat['rcat_id'])
			{
				if ($category != NULL && $subcat != NULL)
					echo "\n\t\t".'</optgroup>';
				if ($category != $cur_cat['rcat_id'] && !empty($cur_cat['rsub_id']))
				{
					$category = $cur_cat['rcat_id'];
					echo "\t\t".'<optgroup label="'.pun_htmlspecialchars(shorttext_lang($cur_cat['rcat_name'],$lang)).'">';
				}
				if(!empty($cur_cat['rsub_id']))
				{
					$subcat = $cur_cat['rsub_id'];
					echo "\n\t\t\t".'<option value="'.$cur_cat['rcat_id'].'-'.$cur_cat['rsub_id'].'">'.pun_htmlspecialchars(shorttext_lang($cur_cat['rsub_name'],$lang)).'</option>';
				}
				else
					$subcat = NULL;
			}
			else
			{
				if(!empty($cur_cat['rsub_id']))
				{
					$subcat = $cur_cat['rsub_id'];
					echo "\n\t\t\t".'<option value="'.$cur_cat['rcat_id'].'-'.$cur_cat['rsub_id'].'">'.pun_htmlspecialchars(shorttext_lang($cur_cat['rsub_name'],$lang)).'</option>';
				}
			}
		}
?>

		</select></label><br />
		<label for="download"><?php echo $lang_site['Admin download link']; ?></label>
		<input type="text" name="download" id="download" size="20" />
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
elseif(isset($_GET['edit_res']))
{
	if($_GET['edit_res'] != 'last')
	{
		$edit_res = intval($_GET['edit_res']);
		if($edit_res < 1)
			site_msg($lang_site['Bad request']);
	}
	if(isset($_POST['add_screens']))
	{
		site_confirm_referrer('admin_resources.php');
		$legend = isset($_POST['legend']) ? $_POST['legend'] : '';
		$folder = 'res-'.$edit_res;
		$cur_id = $edit_res;
		if(!file_exists('./img/'.$folder.'/'))
			@mkdir('./img/'.$folder.'/',0777);
		if(!file_exists('./img/'.$folder.'/thumbs/'))
			@mkdir('./img/'.$folder.'/thumbs',0777);
		$icon = 'screen';
		$size = 2097152;
		require './includes/upload-res.php';			
		$default = intval($_POST['default_screen']);
		if($default == 0)
		{
			$query = $db->query('SELECT MAX(rscreen_id) FROM res_screen') or error('Unable to get screen id', __FILE__, __LINE__, $db->error());
			$last_screen = $db->result($query);
			$db->query('UPDATE res_entries SET rentry_screen_main='.$last_screen.' WHERE rentry_id='.$edit_res) or error('Unable to update default screen', __FILE__, __LINE__, $db->error());
		}

		site_redirect('admin_resources.php?edit_res='.$edit_res, $lang_site['Admin screenshot added redirect']);
	}
	if(isset($_GET['edit_screen']))
	{
		site_confirm_referrer('admin_resources.php?edit_res='.$edit_res);

		$edit_screen = intval($_GET['edit_screen']);
		if($edit_screen < 1)
			site_msg($lang_site['Bad request']);

		if(isset($_POST['save_screen']))
		{
			$legend = isset($_POST['legend']) ? $_POST['legend'] : '';
			$oldfile = $_POST['oldscreen'];
			$folder = 'res-'.$edit_res;
			$icon = 'screen';
			$size = 2097152;
			require './includes/upload-res.php';			
			site_redirect('admin_resources.php?edit_res='.$edit_res, $lang_site['Admin screenshot updated redirect']);
		}

		$result = $db->query('SELECT rscreen_id, rscreen_legend, rscreen_url_full, rscreen_entryid FROM res_screens WHERE rscreen_id='.$edit_screen.' AND rscreen_entryid='.$edit_res) or error('Unable to get screenshot data', __FILE__, __LINE__, $db->error());

		if(empty($result))
			site_msg($lang_site['Bad request']);

		$screen = $db->fetch_assoc($result);
		
		$titre_page = $lang_site['Admin edit screen'];
		$module = 'admin';
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_resources.php"><?php echo $lang_site['Resources']; ?></a> &gt; <?php echo $lang_site['Admin edit screen']; ?></p>
<h3><?php echo $lang_site['Admin edit screen']; ?></h3>
<form method="post" action="admin_resources.php?edit_res=<?php echo $edit_res; ?>&amp;edit_screen=<?php echo $edit_screen; ?>" enctype="multipart/form-data">
	<p class="form">
		<input type="hidden" name="MAX_FILE_SIZE" value="2097152" /><!-- 2 Mio -->
		<input type="hidden" name="oldscreen" value="<?php echo pun_htmlspecialchars($screen['rscreen_url_full']); ?>" />
		<label for="screen"><?php echo $lang_site['Screenshot']; ?></label>
		<input type="file" name="screen" id="screen" /><br />
		<label for="legend"><?php echo $lang_site['Admin legend']; ?>
		<input type="text" name="legend" id="legend" size="50" maxlength="256" value="<?php echo pun_htmlspecialchars($screen['rscreen_legend']); ?>" /></label><br />
	</p>
	<p class="submit"><input type="submit" name="save_screen" value="<?php echo $lang_site['Submit']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
	}
	if(isset($_GET['del_screen']))
	{
		$del_screen = intval($_GET['del_screen']);
		if($del_screen < 1)
			site_msg($lang_site['Bad request']);

		site_confirm_referrer('admin_resources.php?edit_res='.$edit_res);

		if(isset($_POST['del_screen_comply']))
		{
			$result = $db->query('SELECT rscreen_url_full FROM res_screens WHERE rscreen_id='.$del_screen.' AND rscreen_entryid='.$edit_res) or error('Unable to get screenshot data', __FILE__, __LINE__, $db->error());

			if(empty($result))
				site_msg($lang_site['Bad request']);

			$url = $db->result($result);
			remove_file($url,'res-'.$edit_res);
			$db->query('DELETE FROM res_screens WHERE rscreen_id='.$del_screen) or error('Unable to delete screenshot', __FILE__, __LINE__, $db->error());
			site_redirect('admin_resources.php?edit_res='.$edit_res, $lang_site['Admin screenshot deleted redirect']);
		}
		else
		{
			$result = $db->query('SELECT rscreen_url_full FROM res_screens WHERE rscreen_id='.$del_screen) or error('Unable to fetch screenshot info', __FILE__, __LINE__, $db->error());
			$screen_url = $db->result($result);

			$titre_page = $lang_site['Admin delete screen'];
			$module = 'admin';
			require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_resources.php"><?php echo $lang_site['Resources']; ?></a> &gt; <?php echo $lang_site['Admin delete screen head']; ?></p>
<h3><?php echo $lang_site['Admin delete screen head']; ?></h3>
<form method="post" action="admin_resources.php?edit_res=<?php echo $edit_res; ?>&amp;del_screen=<?php echo $del_screen; ?>">
	<div class="inform">
	<input type="hidden" name="screen_to_delete" value="<?php echo $del_screen; ?>" />
		<fieldset>
			<legend><?php echo $lang_site['Admin confirm delete screen subhead']; ?></legend>
			<div class="infldset">
				<p><?php sprintf($lang_site['Admin confirm delete screen info'], pun_htmlspecialchars($screen_url)); ?></p>
				<p class="warntext"><?php echo $lang_site['Admin delete screen warn']; ?></p>
			</div>
		</fieldset>
	</div>
	<p class="buttons"><input type="submit" name="del_screen_comply" value="<?php echo $lang_site['Delete']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
		}
	}
	if(isset($_GET['change_default']))
	{
		$default = intval($_GET['change_default']);
		if($default < 1)
			site_msg($lang_site['Bad request']);

		$db->query('UPDATE res_entries SET rentry_screen_main='.$default.' WHERE rentry_id='.$edit_res) or error('Unable to change default screenshot', __FILE__, __LINE__, $db->error());
		site_redirect('admin_resources.php?edit_res='.$edit_res, $lang_site['Admin default screen changed redirect']);
	}

	site_confirm_referrer('admin_resources.php');

	if(isset($_POST['save']))
	{
		list($cat_id,$sub_id) = explode('-',$_POST['res_cat']);
		if($cat_id < 1 || $sub_id < 1)
			site_msg($_POST['Bad request']);

		$resname = pun_trim($_POST['resname']);	
		if($resname == '')
			site_msg($lang_site['Admin no resname']);

		$resshort = pun_trim($_POST['resshort']);
		if($resshort == '')
			site_msg($lang_site['Admin no shortdesc']);
	
		$desc = pun_linebreaks(pun_trim($_POST['resdesc']));
		$notes = pun_linebreaks(pun_trim($_POST['resnotes']));
		if ($pun_config['p_message_bbcode'] == '1')
		{
			require PUN_ROOT.'include/parser.php';
			$resdesc = preparse_bbcode($desc, $errors);
			$resnotes = preparse_bbcode($notes, $errors);
		}
		if($resdesc == '')
			site_msg($lang_site['Admin no longdesc']);
		if($resnotes == '')
			site_msg($lang_site['Admin no notes']);

		$result = $db->query('SELECT rcat_lang FROM res_cat WHERE rcat_id='.$cat_id) or error('Unable to get category ID', __FILE__, __LINE__, $db->error());
		$langs = $db->result($result);
		$disp_lang = array();
		if(preg_match('#fr#',$langs))
			$disp_lang[] = 'fr';
		if(preg_match('#en#',$langs))
			$disp_lang[] = 'en';

		$download = pun_trim($_POST['download']);
//		exit($db->escape($download));

//		if($download = '')
//			site_msg($lang_site['No download link']);

		$sql_lang = implode(',',$disp_lang);
		$sql = 'UPDATE res_entries SET rentry_name=\''.$db->escape($resname).'\',rentry_shortdesc=\''.$db->escape($resshort).'\',rentry_desc=\''.$db->escape($resdesc).'\',rentry_authornotes=\''.$db->escape($resnotes).'\', rentry_lang=\''.$db->escape($sql_lang).'\',rentry_download=\''.$db->escape($download).'\',rentry_lastupdate='.time().' WHERE rentry_id='.$edit_res;
		$db->query($sql) or error('Unable to update resource data', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_lastres_cache($lang);
		generate_admin_res_home_cache($lang);
		site_redirect('admin_resources.php', $lang_site['Admin res edited redirect']);
	}
	if($_GET['edit_res'] === 'last')
	{
		$q = $db->query('SELECT MAX(rentry_id) FROM res_entries') or error('Unable to get last resource', __FILE__, __LINE__, $db->error());
		$edit_res = $db->result($q);
	}
	$lang = $lang_site['Lang'];
	$result1 = $db->query('SELECT rentry_id, rentry_name, rentry_shortdesc, rentry_desc, rentry_authornotes, rentry_catid, rentry_subcatid, rentry_lang, rentry_download, rentry_screen_main FROM res_entries WHERE rentry_id='.$edit_res) or error('Unable to get resource data', __FILE__, __LINE__, $db->error());
	if(empty($result1))
		site_msg($lang_site['No res data']);
	//Requête pour les catégories
	$result2 = $db->query('SELECT rcat_id, rcat_name, rcat_order, rsub_id, rsub_name, rsub_type FROM res_cat LEFT JOIN res_subcat ON rsub_catid=rcat_id ORDER BY rcat_order, rcat_id, rsub_type') or error('Unable to get category data', __FILE__, __LINE__, $db->error());
	if($db->num_rows($result2) == 0)
		site_msg($lang_site['Admin must have categories']);

	$res = $db->fetch_assoc($result1);

	$titre_page = $lang_site['Admin edit res'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_resources.php"><?php echo $lang_site['Resources']; ?></a> &gt; <?php echo $lang_site['Admin edit res']; ?></p>
<h3><?php echo $lang_site['Admin edit res']; ?></h3>
<form method="post" action="admin_resources.php?edit_res=<?php echo $edit_res; ?>" enctype="multipart/form-data">
	<p class="form">
		<label for="resname"><?php echo $lang_site['Admin res name']; ?><br /><input type="text" name="resname" id="resname" size="50" maxlength="128" value="<?php echo pun_htmlspecialchars($res['rentry_name']); ?>" /></label><br />
		<label for="resshort"><?php echo $lang_site['Admin res shortdesc']; ?><br /><input type="text" name="resshort" id="resshort" size="50" maxlength="512" value="<?php echo pun_htmlspecialchars($res['rentry_shortdesc']); ?>" /></label><br />
		<label for="resdesc"><?php echo $lang_site['Admin res desc']; ?><br /><textarea name="resdesc" id="resdesc" cols="70" rows="5"><?php echo pun_htmlspecialchars($res['rentry_desc']); ?></textarea></label><br />
		<label for="resnotes"><?php echo $lang_site['Admin res notes']; ?><br /><textarea name="resnotes" id="resnotes" cols="70" rows="5"><?php echo pun_htmlspecialchars($res['rentry_authornotes']); ?></textarea></label>
	</p>
	<hr class="sep" />
	<p class="form">
		<strong class="label"><?php echo $lang_site['Languages']; ?></strong> <label for="disp_fr"><?php echo $lang_site['French']; ?></label> <input type="checkbox" name="lang_fr" id="disp_fr" <?php echo (preg_match('#fr#',$res['rentry_lang'])) ? 'checked="checked" ' : ''; ?>/> <label for="disp_en"><?php echo $lang_site['English']; ?></label> <input type="checkbox" name="lang_en" id="disp_en" <?php (preg_match('#en#',$res['rentry_lang'])) ? 'checked="checked" ' : ''; ?> /><br />
		<label for="category"><?php echo $lang_site['Category']; ?> <select name="res_cat" id="category">
<?php
		$category = NULL;
		while($cur_cat = $db->fetch_assoc($result2))
		{
			if($category != $cur_cat['rcat_id'])
			{
				if ($category != NULL && $subcat != NULL)
					echo "\n\t\t".'</optgroup>';
				if ($category != $cur_cat['rcat_id'] && !empty($cur_cat['rsub_id']))
				{
					$category = $cur_cat['rcat_id'];
					echo "\t\t".'<optgroup label="'.pun_htmlspecialchars(shorttext_lang($cur_cat['rcat_name'],$lang)).'">';
				}
				if(!empty($cur_cat['rsub_id']))
				{
					$subcat = $cur_cat['rsub_id'];
					echo "\n\t\t\t".'<option value="'.$cur_cat['rcat_id'].'-'.$cur_cat['rsub_id'].'"'.(($res['rentry_subcatid'] == $cur_cat['rsub_id']) ? ' selected="selected"' : '').'>'.pun_htmlspecialchars(shorttext_lang($cur_cat['rsub_name'],$lang)).'</option>';
				}
				else
					$subcat = NULL;
			}
			else
			{
				if(!empty($cur_cat['rsub_id']))
				{
					$subcat = $cur_cat['rsub_id'];
					echo "\n\t\t\t".'<option value="'.$cur_cat['rcat_id'].'-'.$cur_cat['rsub_id'].'"'.(($res['rentry_subcatid'] == $cur_cat['rsub_id']) ? ' selected="selected"' : '').'>'.pun_htmlspecialchars(shorttext_lang($cur_cat['rsub_name'],$lang)).'</option>';
				}
			}
		}
?>

		</select></label><br />
		<label for="download"><?php echo $lang_site['Admin download link']; ?></label>
		<input type="text" name="download" id="download" size="20" value="<?php echo pun_htmlspecialchars($res['rentry_download']); ?>" />
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
	<hr />
	<h4><?php echo $lang_site['Admin add screen']; ?></h4>
	<p class="form">
		<input type="hidden" name="default_screen" value="<?php $res['rentry_screen_main']; ?>" />
		<input type="hidden" name="MAX_FILE_SIZE" value="2097152" /><!-- 2 Mio -->
		<?php /*input type="hidden" name="oldscreen" value="<?php echo $screen['rscreen_url_full']; ?>" */ ?>
		<label for="screen"><?php echo $lang_site['Screenshot']; ?></label>
		<input type="file" name="screen" id="screen" /><br />
		<label for="legend"><?php echo $lang_site['Admin legend french']; ?>
		<input type="text" name="legend" id="legend" size="50" maxlength="256" value="{fr|Exemple de légende}||{en|Sample legend}" /></label>
	</p>
	<p class="submit"><input type="submit" name="add_screens" value="<?php echo $lang_site['Submit']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<h3><?php echo $lang_site['Admin resource screenshots']; ?></h3>
<?php
$result3 = $db->query('SELECT rscreen_id, rscreen_legend, rscreen_url_full, rscreen_url_small FROM res_screens WHERE rscreen_entryid='.$res['rentry_id']) or error('Unable to get screenshot data', __FILE__, __LINE__, $db->error());
	if($db->num_rows($result3))
	{
		while($screen = $db->fetch_assoc($result3))
		{
			?>
<div class="home-block style-thumb">
	<h4><?php echo pun_htmlspecialchars(shorttext_lang($screen['rscreen_legend'],$lang)); ?></h4>
	<p class="align"><a href="<?php echo pun_htmlspecialchars($screen['rscreen_url_full']); ?>"><img src="<?php echo pun_htmlspecialchars($screen['rscreen_url_small']); ?>" alt="<?php echo pun_htmlspecialchars(shorttext_lang($rscreen['rscreen_legend'],$lang)); ?>" /></a><br /><a href="admin_resources.php?edit_res=<?php echo $edit_res; ?>&amp;edit_screen=<?php echo $screen['rscreen_id']; ?>"><?php echo $lang_site['Edit']; ?></a> - <a href="admin_resources.php?edit_res=<?php echo $edit_res; ?>&amp;change_default=<?php echo $screen['rscreen_id']; ?>"><?php echo $lang_site['Set as default']; ?></a> - <a href="admin_resources.php?edit_res=<?php echo $edit_res; ?>&amp;del_screen=<?php echo $screen['rscreen_id']; ?>"><?php echo $lang_site['Delete']; ?></a></p>
</div>
			<?php
		}
	}
	require './includes/bottom.php';
}
elseif(isset($_GET['del_res']))
{
	site_confirm_referrer('admin_resources.php');
	$res_id = intval($_GET['del_res']);
	if($res_id < 1)
		site_msg($lang_site['Bad request']);

	if(isset($_POST['del_res_comply']))
	{
		@set_time_limit(0);

		// Fetch screenshots to prune
		$result = $db->query('SELECT rscreen_id,rscreen_url_full FROM res_screens WHERE rscreen_entryid='.$res_id) or error('Unable to fetch screenshots', __FILE__, __LINE__, $db->error());

		$screen_ids = '';
		$img_te_delete = array();
		while ($row = $db->fetch_assoc($result))
		{
			$screen_ids .= (($screen_ids != '') ? ',' : '').$row['rscreen_id'];
			$img_to_delete[] = $row['rscreen_url_full'];
		}

		if ($screen_ids != '')
		{
			clear_dir('./img/res-'.$res_id);
			// Delete screenshot entries
			$db->query('DELETE FROM res_screens WHERE rscreen_id IN('.$screen_ids.')') or error('Unable to prune screenshots', __FILE__, __LINE__, $db->error());
		}
		// Delete resource
		$db->query('DELETE FROM res_entries WHERE rentry_id='.$res_id) or error('Unable to prune resources', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_lastres_cache($lang);
		generate_admin_home_cache();
		generate_admin_res_home_cache($lang);
		site_redirect('admin_resources.php', $lang_site['Admin res deleted redirect']);
	}
	else
	{
		$result = $db->query('SELECT rentry_name FROM res_entries WHERE rentry_id='.$res_id) or error('Unable to fetch resource info', __FILE__, __LINE__, $db->error());
		$res_name = $db->result($result);

		$titre_page = $lang_site['Admin delete res'];
		$module = 'admin';
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_resources.php"><?php echo $lang_site['Resources']; ?></a> &gt; <?php echo $lang_site['Admin delete res head']; ?></p>
<h3><?php echo $lang_site['Admin delete res head']; ?></h3>
<form method="post" action="admin_resources.php?del_res=<?php echo $res_id; ?>">
	<div class="inform">
	<input type="hidden" name="res_to_delete" value="<?php echo $res_id; ?>" />
		<fieldset>
			<legend><?php echo $lang_site['Admin confirm delete res subhead']; ?></legend>
			<div class="infldset">
				<p><?php sprintf($lang_site['Admin confirm delete res info'], pun_htmlspecialchars(shorttext_lang($res_name,$lang))); ?></p>
				<p class="warntext"><?php echo $lang_site['Admin delete res warn']; ?></p>
			</div>
		</fieldset>
	</div>
	<p class="buttons"><input type="submit" name="del_res_comply" value="<?php echo $lang_site['Delete']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
	}
}
if(isset($_GET['approve']))
{
	$approve = intval($_GET['approve']);
	if($approve < 1)
		site_msg($lang_site['Bad request']);

	$db->query('UPDATE res_entries SET rentry_publish=1 WHERE rentry_id='.$approve) or error('Unable to update resource status', __FILE_, __LINE__, $db->error());

	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_lastres_cache($lang);
	site_redirect('admin_resources.php', $lang_site['Admin res approved redirect']);
}
elseif(isset($_GET['unapprove']))
{
	$unapprove = intval($_GET['unapprove']);
	if($unapprove < 1)
		site_msg($lang_site['Bad request']);

	$db->query('UPDATE res_entries SET rentry_publish=0 WHERE rentry_id='.$unapprove) or error('Unable to update resource status', __FILE__, __LINE__, $db->error());

	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_lastres_cache($lang);
	site_redirect('admin_resources.php', $lang_site['Admin res unapproved redirect']);
}
$titre_page = $lang_site['Pagename adm resources'];
$module = 'admin';
require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_resources.php"><?php echo $lang_site['Resources']; ?></a></p>
<h3><?php echo $lang_site['Title adm resources']; ?></h3>
<?php echo $lang_site['Explain adm resources']; ?>
<ul class="adm-tabs">

	<li><a href="admin.php?adm=resources"><?php echo $lang_site['Overview']; ?></a></li>
	<?php if ($pun_user['g_id'] == PUN_ADMIN): ?><li><a href="admin_res_cat.php"><?php echo $lang_site['Categories']; ?></a></li><?php endif; ?>
	<li class="tab-active"><?php echo $lang_site['Resources']; ?></li>
</ul>
<div class="adm-cont">
	<p class="new-data"><a href="admin_resources.php?add_res=true"><?php echo $lang_site['Admin add res']; ?></a></p>
	<table class="adm-table" id="tbl-res-list">
		<tr>
			<th class="name"><?php echo $lang_site['Name']; ?></th>
			<th class="type"><?php echo $lang_site['Type']; ?></th>
			<th class="lang"><?php echo $lang_site['Languages']; ?></th>
			<th class="lastupdate"><?php echo $lang_site['Last update']; ?></th>
			<th class="actions"><?php echo $lang_site['Actions']; ?></th>
		</tr>
<?php
$lang = $lang_site['Lang'];
$sql = 'SELECT rentry_id, rentry_name, rentry_name, rentry_lang, rentry_publish, rentry_publishdate, rentry_lastupdate, rsub_id, rsub_type, rcat_id, rcat_name, rcat_name
		FROM res_entries
		LEFT JOIN res_subcat ON rentry_subcatid=rsub_id
		LEFT JOIN res_cat ON rentry_catid=rcat_id
		ORDER BY rentry_id DESC';
$result = $db->query($sql) or error('Unable to fetch resource data', __FILE__, __LINE__, $db->error());

if($db->num_rows($result) > 0)
{
	while($cur_res = $db->fetch_assoc($result))
	{
?>
		<tr>
			<td class="name"><?php echo pun_htmlspecialchars(shorttext_lang($cur_res['rentry_name'],$lang)); ?></td>
			<td class="type"><?php echo pun_htmlspecialchars(shorttext_lang($cur_res['rcat_name'],$lang)); ?> (<?php echo pun_htmlspecialchars($cur_res['rsub_type']); ?>)</td>
			<td class="lang"><?php echo $cur_res['rentry_lang']; ?></td>
			<td class="lastupdate"><?php echo format_time($cur_res['rentry_lastupdate']); ?></td>
			<td class="actions"><a href="admin_resources.php?edit_res=<?php echo $cur_res['rentry_id']; ?>"><?php echo $lang_site['Edit']; ?></a> <a href="admin_resources.php?del_res=<?php echo $cur_res['rentry_id']; ?>"><?php echo $lang_site['Delete']; ?></a> <a href="admin_resources.php?<?php echo ($cur_res['rentry_publish'] == 1) ? 'unapprove='.$cur_res['rentry_id'] : 'approve='.$cur_res['rentry_id']; ?>"><?php echo ($cur_res['rentry_publish'] == 1) ? $lang_site['Unapprove'] : $lang_site['Approve']; ?></a></td>
		</tr>
<?php
	}
}
	else echo '<tr><td colspan="5" class="center">'.$lang_site['No resource'].'</td></tr>';
?>
	</table>
	<p class="new-data"><a href="admin_resources.php?add_res=true"><?php echo $lang_site['Admin add res']; ?></a></p>
	<div class="clearfix"></div>
</div>
<?php require './includes/bottom.php'; ?>
