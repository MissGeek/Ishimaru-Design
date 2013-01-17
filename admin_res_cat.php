<?php require './includes/init-main.php';
if($pun_user['g_id'] != PUN_ADMIN)
	site_msg($lang_site['No permission']);

$lang = $lang_site['Lang'];
if(isset($_GET['add_cat']))
{
	if($_GET['add_cat'] != true)
		site_msg($lang_site['Bad request']);

	site_confirm_referrer('admin_res_cat.php');

	if(isset($_POST['save']))
	{
		$errors = array();

		$catname = pun_trim($_POST['catname']);	
		if($catname == '')
			site_msg($lang_site['Admin no catname']);
//		if(!preg_match('#(\{(en|fr)\|.+\})$#',$catname))
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

		$fields = 'rcat_icon';
		$icon = 'caticon';
		$size = 102400; // 100 Kio - 1 Kio valant 1024 octets
		$folder = 'res-cat-icons';
		$thumb = false;
		require 'includes/upload.php';

		if(empty($img_value))
			site_msg($lang_site['Admin no pic']);
		else
		{
			$sql = 'INSERT INTO res_cat(rcat_id,rcat_name,rcat_clearname,rcat_desc,rcat_icon,rcat_order,rcat_lang) VALUES(\'\',\''.$db->escape($catname).'\',\''.$db->escape($catclear).'\',\''.$db->escape($catdesc).'\',\''.$db->escape($img_value).'\',0,\''.$db->escape($sql_lang).'\')';
			$db->query($sql) or error('Unable to add category data', __FILE__, __LINE__, $db->error());

			if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
				require './includes/cache.php';

			generate_res_submenu_cache($lang);
			generate_admin_res_home_cache($lang);
			site_redirect('admin_res_cat.php', $lang_site['Admin cat added redirect']);
		}
	}
	$titre_page = $lang_site['Admin add cat'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=resources"><?php echo $lang_site['Resources']; ?></a> &gt; <a href="admin_res_cat.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php echo $lang_site['Admin add cat']; ?></p>
<h3><?php echo $lang_site['Admin add cat']; ?></h3>
<form method="post" action="admin_res_cat.php?add_cat=true" enctype="multipart/form-data">
	<p class="form">
		<label for="catname"><?php echo $lang_site['Admin cat name']; ?><br /><input type="text" name="catname" id="catname" size="50" maxlength="64" value="{fr|Exemple de nom}||{en|Sample name}" /></label><br />
		<label for="catclear"><?php echo $lang_site['Admin cat clear']; ?><br /><input type="text" name="catclear" id="catclear" size="50" maxlength="64" value="{fr|Exemple de nom}||{en|Sample name}" /></label><br />
		<label for="catdesc"><?php echo $lang_site['Admin cat desc']; ?><br /><textarea name="catdesc" id="catdesc" cols="70" rows="5">{fr|Exemple de description}

{en|Sample description}</textarea></label>
	</p>
	<hr class="sep" />
	<p class="form">
		<strong class="label"><?php echo $lang_site['Languages']; ?></strong> <label for="disp_fr"><?php echo $lang_site['French']; ?></label> <input type="checkbox" name="lang_fr" id="disp_fr" /> <label for="disp_en"><?php echo $lang_site['English']; ?></label> <input type="checkbox" name="lang_en" id="disp_en" /><br />
		<label class="label" for="cat_icon"><?php echo $lang_site['Admin cat icon']; ?></label>
		<input type="hidden" name="MAX_FILE_SIZE" value="102400" />
		<input type="file" name="caticon" id="cat_icon" />
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
elseif(isset($_GET['add_sub']))
{
	$parent_cat = intval($_GET['add_sub']);
	if($parent_cat < 1)
		site_msg($lang_site['Bad request']);

	site_confirm_referrer('admin_res_cat.php');

	if(isset($_POST['save']))
	{
		$subname = pun_trim($_POST['subname']);	
		if($subname == '')
			site_msg($lang_site['Admin no subname']);
//		if(!preg_match('#(\{(en|fr)\|.+\})+#',$subname))
//			site_msg($lang_site['Wrong format']);
	
		$subclear = pun_trim(str_replace(' ','-',$_POST['subclear']));
		if($subclear == '')
			site_msg($lang_site['Admin no clearname']);
//		if(!preg_match('#^\{(en|fr)\|.+\}$#',$subclear))
//			site_msg($lang_site['Wrong format']);
	
		$desc = pun_linebreaks(pun_trim($_POST['subdesc']));
	
		if ($pun_config['p_message_bbcode'] == '1')
		{
			require PUN_ROOT.'include/parser.php';
			$subdesc = preparse_bbcode($desc, $errors);
		}
		if($subdesc == '')
			site_msg($lang_site['Admin no desc']);
//		if(!preg_match('#^\{(en|fr)\|.+\}$#',$subdesc))
//			site_msg($lang_site['Wrong format']);

		$disp_lang = array();
		if($_POST['lang_fr'] == 'on')
			$disp_lang[] = 'fr';
		if($_POST['lang_en'] == 'on')
			$disp_lang[] = 'en';
		$sql_lang = implode(',',$disp_lang);

		$types = array('hack','style');
		$subtype = (in_array($_POST['type'],$types)) ? $_POST['type'] : '';

		if(empty($subtype))
			site_msg($lang_site['Admin no type']);

		$sql = 'INSERT INTO res_subcat(rsub_id,rsub_name,rsub_clearname,rsub_desc,rsub_type,rsub_catid,rsub_lang) VALUES(\'\',\''.$db->escape($subname).'\',\''.$db->escape($subclear).'\',\''.$db->escape($subdesc).'\',\''.$db->escape($subtype).'\','.$parent_cat.',\''.$db->escape($sql_lang).'\')';
		$db->query($sql) or error('Unable to add subcategory data', __FILE__, __LINE__, $db->error());
		site_redirect('admin_res_cat.php', $lang_site['Admin subcat added redirect']);
	}

	$titre_page = $lang_site['Admin add subcat'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=resources"><?php echo $lang_site['Resources']; ?></a> &gt; <a href="admin_res_cat.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php echo $lang_site['Admin add subcat']; ?></p>
<h3><?php echo $lang_site['Admin add subcat']; ?></h3>
<form method="post" action="admin_res_cat.php?add_sub=<?php echo $parent_cat; ?>">
	<p class="form">
		<input type="hidden" name="parent_id" value="<?php echo $parent_cat; ?>" />
		<label for="subname"><?php echo $lang_site['Admin sub name']; ?><br /><input type="text" name="subname" id="subname" size="50" maxlength="64" value="{fr|Exemple de nom}||{en|Sample subcat name}" /></label><br />
		<label for="subclear"><?php echo $lang_site['Admin sub clear']; ?><br /><input type="text" name="subclear" id="subclear" size="50" maxlength="64" value="{fr|Exemple de nom}||{en|Sample subcat name}" /></label><br />
		<label for="subdesc"><?php echo $lang_site['Admin sub desc']; ?><br /><textarea name="subdesc" id="subdesc" cols="70" rows="5">{fr|Exemple de description}

{en|Sample description}</textarea></label>
	</p>
	<hr class="sep" />
	<p class="form">
		<strong class="label"><?php echo $lang_site['Languages']; ?></strong>
		<label for="disp_fr"><?php echo $lang_site['French']; ?></label> <input type="checkbox" name="lang_fr" id="disp_fr" /> <label for="disp_en"><?php echo $lang_site['English']; ?></label> <input type="checkbox" name="lang_en" id="disp_en" /><br />
		<strong class="label"><?php echo $lang_site['Type']; ?></strong>
		<label for="type_hack"><?php echo $lang_site['Hack']; ?></label> <input type="radio" name="type" id="type_hack" value="hack" /> <label for="type_style"><?php echo $lang_site['Style']; ?></label> <input type="radio" name="type" id="type_style" value="style" />
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
		site_confirm_referrer('admin_res_cat.php');
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

		$oldfile = (isset($_POST['oldfile'])) ? $_POST['oldfile'] : '';
		$icon = 'caticon';
		$size = 102400; // 100 Kio - 1 Kio valant 1024 octets
		$folder = 'res-cat-icons';
		require('includes/upload.php');

		$cat_id = $_POST['cat_id'];

		$sql = 'UPDATE res_cat SET rcat_name=\''.$db->escape($catname).'\',rcat_clearname=\''.$db->escape($catclear).'\',rcat_desc=\''.$db->escape($catdesc).'\', ';
		if(!empty($img_value))
			$sql .= 'rcat_icon=\''.$db->escape($img_value).'\',';
		$sql .= 'rcat_lang=\''.$db->escape($sql_lang).'\' WHERE rcat_id='.$edit_cat;
		$db->query($sql) or error('Unable to update category data', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';
		generate_lastres_cache($lang);
		generate_res_submenu_cache($lang);
		generate_admin_res_home_cache($lang);
		site_redirect('admin_res_cat.php', $lang_site['Admin cat edited redirect']);
	}
	//fetch cat info
	$result = $db->query('SELECT rcat_id, rcat_name, rcat_clearname, rcat_desc, rcat_icon, rcat_lang FROM res_cat WHERE rcat_id='.$edit_cat) or error('Unable to fetch category data', __FILE__, __LINE__, $db->error());
	if(!$db->num_rows($result))
		site_msg($lang_site['Bad request']);

	$cur_cat = $db->fetch_assoc($result);

	$titre_page = $lang_site['Admin edit cat'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=resources"><?php echo $lang_site['Resources']; ?></a> &gt; <a href="admin_res_cat.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php echo $lang_site['Admin edit cat']; ?></p>
<h3><?php echo $lang_site['Admin edit cat']; ?></h3>
<form method="post" action="admin_res_cat.php?edit_cat=<?php echo $cur_cat['rcat_id']; ?>" enctype="multipart/form-data">
	<p class="form">
		<input type="hidden" name="oldfile" value="<?php echo pun_htmlspecialchars($cur_cat['rcat_icon']); ?>" />
		<label for="catname"><?php echo $lang_site['Admin cat name']; ?><br /><input type="text" name="catname" id="catname" size="50" maxlength="64" value="<?php echo pun_htmlspecialchars($cur_cat['rcat_name']); ?>" /></label><br />
		<label for="catclear_fr"><?php echo $lang_site['Admin cat clear']; ?><br /><input type="text" name="catclear" id="catclear" size="50" maxlength="64" value="<?php echo pun_htmlspecialchars($cur_cat['rcat_clearname']); ?>" /></label><br />
		<label for="catdesc"><?php echo $lang_site['Admin cat desc']; ?><br /><textarea name="catdesc" id="catdesc" cols="70" rows="5"><?php echo pun_htmlspecialchars($cur_cat['rcat_desc']); ?></textarea></label>
	</p>
	<hr class="sep" />
	<p class="form">
		<strong class="label"><?php echo $lang_site['Languages']; ?></strong> <label for="disp_fr"><?php echo $lang_site['French']; ?></label> <input type="checkbox" name="lang_fr" id="disp_fr" <?php echo (preg_match('#fr#',$cur_cat['rcat_lang'])) ? 'checked="checked" ':''; ?>/> <label for="disp_en"><?php echo $lang_site['English']; ?></label> <input type="checkbox" name="lang_en" id="disp_en" <?php echo (preg_match('#en#',$cur_cat['rcat_lang'])) ? 'checked="checked" ':''; ?>/><br />
		<label class="label" for="cat_icon"><?php echo $lang_site['Admin cat icon']; ?></label>
		<input type="hidden" name="MAX_FILE_SIZE" value="102400" />
		<input type="file" name="caticon" id="cat_icon" />
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
elseif(isset($_GET['edit_sub']))
{
	$edit_sub = intval($_GET['edit_sub']);
	if($edit_sub < 1)
		site_msg($lang_site['Bad request']);

	if(isset($_POST['save']))
	{
		site_confirm_referrer('admin_res_cat.php');

		$parent_cat = intval($_POST['parent_id']);
		if($parent_cat < 1)
			message($lang_site['Bad request']);

		$subname = pun_trim($_POST['subname']);	
		if($subname == '')
			site_msg($lang_site['Admin no subname']);
//		if(!preg_match('#(\{(en|fr)\|.+\})+#',$subname))
//			site_msg($lang_site['Wrong format']);
	
		$subclear = pun_trim(str_replace(' ','-',$_POST['subclear']));
		if($subclear == '')
			site_msg($lang_site['Admin no clearname']);
//		if(!preg_match('#^\{(en|fr)\|.+\}$#',$subclear))
//			site_msg($lang_site['Wrong format']);
	
		$desc = pun_linebreaks(pun_trim($_POST['subdesc']));
	
		if ($pun_config['p_message_bbcode'] == '1')
		{
			require PUN_ROOT.'include/parser.php';
			$subdesc = preparse_bbcode($desc, $errors);
		}
		if($subdesc == '')
			site_msg($lang_site['Admin no desc']);
//		if(!preg_match('#^\{(en|fr)\|.+\}$#',$subdesc))
//			site_msg($lang_site['Wrong format']);

		$disp_lang = array();
		if($_POST['lang_fr'] == 'on')
			$disp_lang[] = 'fr';
		if($_POST['lang_en'] == 'on')
			$disp_lang[] = 'en';
		$sql_lang = implode(',',$disp_lang);

		$sql = 'UPDATE res_subcat SET rsub_name=\''.$db->escape($subname).'\',rsub_clearname=\''.$db->escape($subclear).'\',rsub_desc=\''.$db->escape($subdesc).'\', rsub_lang=\''.$db->escape($sql_lang).'\' WHERE rsub_id='.$edit_sub;
		$db->query($sql) or error('Unable to update subcategory data', __FILE__, __LINE__, $db->error());

		site_redirect('admin_res_cat.php', $lang_site['Admin subcat edited redirect']);
	}
	//fetch cat info
	$result = $db->query('SELECT rsub_id, rsub_name, rsub_clearname, rsub_desc, rsub_catid, rsub_lang FROM res_subcat WHERE rsub_id='.$edit_sub) or error('Unable to fetch subcat data', __FILE__, __LINE__, $db->error());
	if(!$db->num_rows($result))
		site_msg($lang_site['Bad request']);

	$cur_subcat = $db->fetch_assoc($result);
	
	$titre_page = $lang_site['Admin edit subcat'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=resources"><?php echo $lang_site['Resources']; ?></a> &gt; <a href="admin_res_cat.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php echo $lang_site['Admin edit subcat']; ?></p>
<h3><?php echo $lang_site['Admin edit subcat']; ?></h3>
<form method="post" action="admin_res_cat.php?edit_sub=<?php echo $cur_subcat['rsub_id']; ?>">
	<p class="form">
		<input type="hidden" name="parent_id" value="<?php echo $cur_subcat['rsub_catid']; ?>" />
		<label for="subname"><?php echo $lang_site['Admin sub name']; ?><br /><input type="text" name="subname" id="subname" size="50" maxlength="64" value="<?php echo pun_htmlspecialchars($cur_subcat['rsub_name']); ?>" /></label><br />
		<label for="subclear"><?php echo $lang_site['Admin sub clear']; ?><br /><input type="text" name="subclear" id="subclear" size="50" maxlength="64" value="<?php echo pun_htmlspecialchars($cur_subcat['rsub_clearname']); ?>" /></label><br />
		<label for="subdesc"><?php echo $lang_site['Admin sub desc']; ?><br /><textarea name="subdesc" id="subdesc" cols="70" rows="5"><?php echo pun_htmlspecialchars($cur_subcat['rsub_desc']); ?></textarea></label>
	</p>
	<hr class="sep" />
	<p class="form">
		<strong class="label"><?php echo $lang_site['Languages']; ?></strong><label for="disp_fr"><?php echo $lang_site['French']; ?></label> <input type="checkbox" name="lang_fr" id="disp_fr" <?php echo (preg_match('#fr#',$cur_subcat['rsub_lang'])) ? 'checked="checked" ':''; ?>/> <label for="disp_en"><?php echo $lang_site['English']; ?></label> <input type="checkbox" name="lang_en" id="disp_en" <?php echo (preg_match('#en#',$cur_subcat['rsub_lang'])) ? 'checked="checked" ':''; ?>/>
	</p>
	<p class="submit"><input type="submit" name="save" value="<?php echo $lang_site['Save changes']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
</form>
<?php require './includes/bottom.php';
}
//Suppression d'une catégorie ou d'une sous-catégorie
if(isset($_GET['del_cat']))
{
	site_confirm_referrer('admin_res_cat.php');
	$cat_id = intval($_GET['del_cat']);
	if($cat_id < 1)
		site_msg($lang_site['Bad request']);

	if(isset($_POST['del_cat_comply']))
	{
		@set_time_limit(0);

		require './includes/lib_cat.php';
		$result = $db->query('SELECT rsub_id FROM res_subcat WHERE rsub_catid='.$cat_id) or error('Unable to fetch subcat list', __FILE__, __LINE__, $db->error());
		$num_subcats = $db->num_rows($result);

		for ($i = 0; $i < $num_subcats; ++$i)
		{
			$cur_subcat = $db->result($result, $i);
			prune_res_subcat($cur_subcat);
			$db->query('DELETE FROM res_subcat WHERE rsub_id='.$cur_subcat) or error('Unable to delete subcat'. __FILE__, __LINE__, $db->error());
		}
		//Get the cat's icon
		$query = $db->query('SELECT rcat_icon FROM res_cat WHERE rcat_id='.$cat_id) or error('Unable to fetch icon data', __FILE__, __LINE__, $db->error());
		$icon_to_del = $db->result($query);
		remove_file($icon_to_del,'res-cat-icons');

		// Delete the category
		$db->query('DELETE FROM res_cat WHERE rcat_id='.$cat_id) or error('Unable to delete category', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_admin_home_cache();
		generate_res_submenu_cache($lang);
		generate_admin_res_home_cache($lang);
		generate_lastres_cache($lang);
		site_redirect('admin_res_cat.php', $lang_site['Admin cat deleted redirect']);
	}
	else
	{
		$result = $db->query('SELECT rcat_name FROM res_cat WHERE rcat_id='.$cat_id) or error('Unable to fetch category info', __FILE__, __LINE__, $db->error());
		$cat_name = $db->result($result);

		$titre_page = $lang_site['Admin delete category'];
		$module = 'admin';
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=resources"><?php echo $lang_site['Resources']; ?></a> &gt; <a href="admin_res_cat.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php echo $lang_site['Admin delete res cat head']; ?></p>
		<h3><?php echo $lang_site['Admin delete res cat head']; ?></h3>
		<div class="box">
			<form method="post" action="admin_res_cat.php?del_cat=<?php echo $cat_id; ?>">
				<div class="inform">
				<input type="hidden" name="cat_to_delete" value="<?php echo $cat_id; ?>" />
					<fieldset>
						<legend><?php echo $lang_site['Admin confirm delete cat subhead']; ?></legend>
						<div class="infldset">
							<p><?php sprintf($lang_site['Admin confirm delete cat info'], pun_htmlspecialchars(shorttext_lang($cat_name,$lang))); ?></p>
							<p class="warntext"><?php echo $lang_site['Admin delete res cat warn']; ?></p>
						</div>
					</fieldset>
				</div>
				<p class="buttons"><input type="submit" name="del_cat_comply" value="<?php echo $lang_site['Delete']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
			</form>
		</div>
<?php require './includes/bottom.php';
	}
}
elseif(isset($_GET['del_sub']))
{
	site_confirm_referrer('admin_res_cat.php');
	$sub_id = intval($_GET['del_sub']);
	if($sub_id < 1)
		site_msg($lang_site['Bad request']);

	if(isset($_POST['del_sub_comply']))
	{
		@set_time_limit(0);
		require './includes/lib_cat.php';
		prune_res_subcat($sub_id);
		$db->query('DELETE FROM res_subcat WHERE rsub_id='.$sub_id) or error('Unable to delete subcategory', __FILE__, __LINE__, $db->error());

		if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
			require './includes/cache.php';

		generate_res_submenu_cache($lang);
		generate_admin_home_cache();
		generate_admin_res_home_cache($lang);
		generate_lastres_cache($lang);
		site_redirect('admin_res_cat.php', $lang_site['Admin subcat deleted redirect']);
	}
	else
	{
		$result = $db->query('SELECT rsub_name FROM res_subcat WHERE rsub_id='.$sub_id) or error('Unable to fetch subcategory info', __FILE__, __LINE__, $db->error());
		$sub_name = $db->result($result);

		$titre_page = $lang_site['Admin delete subcat'];
		$module = 'admin';
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=resources"><?php echo $lang_site['Resources']; ?></a> &gt; <a href="admin_res_cat.php"><?php echo $lang_site['Categories']; ?></a> &gt; <?php echo $lang_site['Admin delete res subcat head']; ?></p>
		<h3><?php echo $lang_site['Admin delete res subcat head']; ?></h3>
		<div class="box">
			<form method="post" action="admin_res_cat.php?del_sub=<?php echo $sub_id; ?>">
				<div class="inform">
				<input type="hidden" name="subcat_to_delete" value="<?php echo $sub_id; ?>" />
					<fieldset>
						<legend><?php echo $lang_site['Admin confirm delete subcat subhead']; ?></legend>
						<div class="infldset">
							<p><?php sprintf($lang_site['Admin confirm delete subcat info'], pun_htmlspecialchars(shorttext_lang($sub_name,$lang))); ?></p>
							<p class="warntext"><?php echo $lang_site['Admin delete res cat warn']; ?></p>
						</div>
					</fieldset>
				</div>
				<p class="buttons"><input type="submit" name="del_sub_comply" value="<?php echo $lang_site['Delete']; ?>" /> &nbsp; <a href="javascript:history.go(-1)"><?php echo $lang_site['Go back']; ?></a></p>
			</form>
		</div>
<?php require './includes/bottom.php';
	}
}
if(isset($_POST['update_positions']))
{
	site_confirm_referrer('admin_res_cat.php');

	foreach ($_POST['position'] as $cat_id => $disp_position)
	{
		$disp_position = trim($disp_position);
		if ($disp_position == '' || preg_match('%[^0-9]%', $disp_position))
			site_msg($lang_site['Admin must be integer']);

		$db->query('UPDATE res_cat SET rcat_order='.$disp_position.' WHERE rcat_id='.intval($cat_id)) or error('Unable to update categories', __FILE__, __LINE__, $db->error());
	}

	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_res_submenu_cache($lang);
	site_redirect('admin_res_cat.php', $lang_site['Admin cat edited redirect']);
}

$titre_page = $lang_site['Pagename adm res cat'];
$module = 'admin';
require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=resources"><?php echo $lang_site['Resources']; ?></a> &gt; <a href="admin_res_cat.php"><?php echo $lang_site['Categories']; ?></a></p>
<h3><?php echo $lang_site['Title adm res cat']; ?></h3>
<?php echo $lang_site['Explain adm res cat']; ?>
<ul class="adm-tabs">
	<li><a href="admin.php?adm=resources"><?php echo $lang_site['Overview']; ?></a></li>
	<li class="tab-active"><?php echo $lang_site['Categories']; ?></li>
	<li><a href="admin_resources.php"><?php echo $lang_site['Resources']; ?></a></li>
</ul>
<form method="post" action="admin_res_cat.php">
<div class="adm-cont">
	<p class="new-data"><input type="submit" name="update_positions" value="<?php echo $lang_site['Update positions']; ?>" /> &nbsp; <a href="admin_res_cat.php?add_cat=true"><?php echo $lang_site['Admin add cat']; ?></a></p>
	<table class="adm-table wide" id="tbl-res-cat-overview">
		<tr>
			<th class="name"><?php echo $lang_site['Name']; ?></th>
			<th class="position"><?php echo $lang_site['Position']; ?></th>
			<th class="edit"><?php echo $lang_site['Actions']; ?></th>
		</tr>
<?php
$lang = $lang_site['Lang'];
$cat_result = $db->query('SELECT rcat_id, rcat_name, rcat_order, rsub_id, rsub_name, rsub_clearname, rsub_catid, rsub_type FROM res_cat LEFT JOIN res_subcat ON rcat_id=rsub_catid ORDER BY rcat_order, rsub_name');

if($db->num_rows($cat_result) > 0)
{
	$category = NULL;
	while($cur_cat = $db->fetch_assoc($cat_result))
	{
		if($category != $cur_cat['rcat_id'])
		{
			$category = $cur_cat['rcat_id'];
?>
		<tr class="catrow">
			<td class="name"><?php echo pun_htmlspecialchars(shorttext_lang($cur_cat['rcat_name'],$lang)); ?></td>
			<td class="position center nowrap"><input type="text" name="position[<?php echo $cur_cat['rcat_id']; ?>]" size="3" maxlength="3" value="<?php echo $cur_cat['rcat_order']; ?>" /></td>
			<td class="actions center nowrap"><a href="admin_res_cat.php?add_sub=<?php echo $cur_cat['rcat_id']; ?>" title="<?php echo $lang_site['Add subcat']; ?>"><?php echo $lang_site['Add']; ?></a>&nbsp;<a href="admin_res_cat.php?edit_cat=<?php echo $cur_cat['rcat_id']; ?>"><?php echo $lang_site['Edit']; ?></a>&nbsp;<a href="admin_res_cat.php?del_cat=<?php echo $cur_cat['rcat_id']; ?>"><?php echo $lang_site['Delete']; ?></a></td>
		</tr>
<?php
		}
		if(!empty($cur_cat['rsub_id']))
		{
?>
		<tr class="subrow">
			<td class="name indent nowrap" colspan="2"><?php echo pun_htmlspecialchars(shorttext_lang($cur_cat['rsub_name'],$lang)); ?> (type: <?php echo $cur_cat['rsub_type']; ?>)</td>
			<td class="actions center nowrap"><a href="admin_res_cat.php?edit_sub=<?php echo $cur_cat['rsub_id']; ?>"><?php echo $lang_site['Edit']; ?></a>&nbsp;<a href="admin_res_cat.php?del_sub=<?php echo $cur_cat['rsub_id']; ?>"><?php echo $lang_site['Delete']; ?></a></td>
		</tr>
<?php
		}
	}
}
else
	echo '<tr><td colspan="3" class="center">'.$lang_site['No cat'].'</td></tr>';
?>
	</table>
	<p class="new-data"><input type="submit" name="update_positions" value="<?php echo $lang_site['Update positions']; ?>" /> &nbsp; <a href="admin_res_cat.php?add_cat=true"><?php echo $lang_site['Admin add cat']; ?></a></p>
	<div class="clearfix"></div>
</div>
</form>
<?php require './includes/bottom.php';
