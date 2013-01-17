<?php require './includes/init-main.php';
if(!$pun_user['is_admmod'])
	message($lang_site['No permission']);

$lang = $lang_site['Lang'];
//Pour l'inclusion des modules appropriés
$module = isset($_GET['adm']) ? $_GET['adm'] : NULL;
if($module == 'cfg')
{
	if($pun_user['g_id'] != PUN_ADMIN)
		site_msg($lang_site['No view']);

	if(isset($_POST['form-sent']))
	{
		site_confirm_referrer('admin.php?adm=cfg');

		$form = array(
			'site_desc'				=> pun_trim($_POST['form']['site_desc']),

			'enable_intro'			=> $_POST['form']['enable_intro'] != '1' ? '0' : '1',
			'site_intro'			=> pun_trim($_POST['form']['site_intro']),

			'enable_news'			=> $_POST['form']['enable_news'] != '1' ? '0' : '1',
			'forum_news'			=> pun_trim($_POST['form']['forum_news']),
			'nb_news_home'			=> intval($_POST['form']['nb_news_home']),
			'nb_news_page'			=> intval($_POST['form']['nb_news_page']),

			'enable_res'			=> $_POST['form']['enable_res'] != '1' ? '0' : '1',
			'nb_res_home'			=> intval($_POST['form']['nb_res_home']),
			'styles_per_page'		=> intval($_POST['form']['styles_per_page']),
			'hacks_per_page'		=> intval($_POST['form']['hacks_per_page']),

			'enable_tuts'			=> $_POST['form']['enable_tuts'] != '1' ? '0' : '1',
			'nb_tuts_home'			=> intval($_POST['form']['nb_tuts_home']),
			'tuts_per_page'			=> intval($_POST['form']['tuts_per_page']),

			'enable_lastposts'		=> $_POST['form']['enable_lastposts'] != '1' ? '0' : '1',
			'nb_lastposts'			=> intval($_POST['form']['nb_lastposts']),

			'enable_social'			=> $_POST['form']['enable_social'] != '1' ? '0' : '1',
			'social_links'			=> pun_trim($_POST['form']['social_links']),

			'enable_footer_links'	=> $_POST['form']['enable_footer_links'] != '1' ? '0' : '1',
			'footer_sitelinks'		=> pun_trim($_POST['form']['footer_sitelinks']),
			'footer_affiliates'		=> pun_trim($_POST['form']['footer_affiliates']),
		);

		if($form['nb_news_home'] <= 5)
			$form['nb_news_home'] = 5;
		elseif($form['nb_news_home'] >= 30)
			$form['nb_news_home'] = 30;
		if($form['nb_res_home'] <= 5)
			$form['nb_res_home'] = 5;
		elseif($form['nb_res_home'] >= 30)
			$form['nb_res_home'] = 30;
		if($form['nb_tuts_home'] <= 5)
			$form['nb_tuts_home'] = 5;
		elseif($form['nb_tuts_home'] >= 30)
			$from['nb_tuts_home'] = 30;
		if($form['nb_news_page'] <= 5)
			$form['nb_news_page'] = 5;
		elseif($form['nb_news_page'] >= 30)
			$form['nb_news_page'] = 30;
		if($form['styles_per_page'] <= 12)
			$form['styles_per_page'] = 12;
		elseif($form['styles_per_page'] >= 48)
			$form['styles_per_page'] = 48;
		if($form['hacks_per_page'] <= 12)
			$form['hacks_per_page'] = 12; 
		elseif($form['hacks_per_page'] >= 48)
			$form['hacks_per_page'] = 48;
		if($form['tuts_per_page'] <= 5)
			$form['tuts_per_page'] = 5;
		elseif($form['tuts_per_page'] >= 30)
			$form['tuts_per_page'] = 30;

		// Make sure base_url doesn't end with a slash
		if (substr($form['base_url'], -1) == '/')
			$form['base_url'] = substr($form['base_url'], 0, -1);

		//Only the founder can manage the ads widget (security)
		if($pun_user['id'] == 2)		
		{
			$form['enable_ads'] = $_POST['form']['enable_ads'] != '1' ? '0' : '1';
			$form['your_ads'] = pun_trim($_POST['form']['your_ads']);

			if($form['your_ads'] != '')
				$form['your_ads'] = pun_linebreaks($form['your_ads']);
			else
			{
				$form['your_ads'] = $lang_site['Enter your ads here'];
				$form['enable_ads'] = 0;
			}
		}

		if($form['footer_sitelinks'] != '')
			$form['footer_sitelinks'] = pun_linebreaks(strip_tags($form['footer_sitelinks']));
		if($form['footer_affiliates'] != '')
			$form['footer_affiliates'] = pun_linebreaks(strip_tags($form['footer_affiliates']));
		if($form['site_desc'] != '')
			$form['site_desc'] = pun_linebreaks($form['site_desc']);
		if($form['site_intro'] != '')
			$form['site_intro'] = pun_linebreaks($form['site_intro']);
		if($form['social_links'] != '')
			$form['social_links'] = pun_linebreaks($form['social_links']);

	foreach ($form as $key => $input)
	{
		// Only update values that have changed
		if (array_key_exists('o_'.$key, $site_config) && $site_config['o_'.$key] != $input)
		{
			if ($input != '' || is_int($input))
				$value = '\''.$db->escape($input).'\'';
			else
				$value = 'NULL';

			$db->query('UPDATE site_config SET conf_value='.$value.' WHERE conf_name=\'o_'.$db->escape($key).'\'') or error('Unable to update site config', __FILE__, __LINE__, $db->error());
		}
	}

	// Regenerate the config cache
	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_site_config_cache();

	site_redirect('admin.php?adm=cfg', $lang_site['Options updated redirect']);

	}

	$titre_page = $lang_site['Pagename adm cfg'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=cfg"><?php echo $lang_site['Settings']; ?></a></p>
<h3><?php echo $lang_site['Title adm cfg']; ?></h3>
<?php echo $lang_site['Explain adm cfg']; ?>		
<form method="post" action="admin.php?adm=cfg">
	<fieldset>
		<legend><?php echo $lang_site['Admin general settings']; ?></legend>
		<p class="form">
		<input name="form-sent" value="1" type="hidden" />
		<label><?php echo $lang_site['Admin cfg site desc']; ?></label><br />
		<textarea name="form[site_desc]" cols="60" rows="5"><?php echo pun_htmlspecialchars($site_config['o_site_desc']); ?></textarea>
		</p>
	</fieldset>
	<fieldset>
		<legend><?php echo $lang_site['Admin intro module settings']; ?></legend>
		<p class="form">
		<label><?php echo $lang_site['Admin cfg enable intro']; ?></label>
		<input type="radio" name="form[enable_intro]" value="1"<?php if($site_config['o_enable_intro'] == '1') echo ' checked="checked"'; ?> /> <?php echo $lang_site['Yes']; ?> &nbsp;
		<input type="radio" name="form[enable_intro]" value="0"<?php if($site_config['o_enable_intro'] == '0') echo ' checked="checked"'; ?> /> <?php echo $lang_site['No']; ?><br />
		<label><?php echo $lang_site['Admin cfg site intro']; ?></label><br />
		<textarea name="form[site_intro]" cols="60" rows="5"><?php echo pun_htmlspecialchars($site_config['o_site_intro']); ?></textarea>
		</p>
	</fieldset>
	<fieldset>
		<legend><?php echo $lang_site['Admin news module settings'] ?></legend>
		<p class="form">
		<label><?php echo $lang_site['Admin cfg enable news']; ?></label>
		<input type="radio" name="form[enable_news]" value="1"<?php if($site_config['o_enable_news'] == '1') echo ' checked="checked"'; ?> /> <?php echo $lang_site['Yes']; ?> &nbsp;
		<input type="radio" name="form[enable_news]" value="0"<?php if($site_config['o_enable_news'] == '0') echo ' checked="checked"'; ?> /> <?php echo $lang_site['No']; ?><br />
		<label><?php echo $lang_site['Admin cfg forum news']; ?></label>
		<input type="text" size="20" name="form[forum_news]" value="<?php echo $site_config['o_forum_news']; ?>" /><br />
		<label><?php echo $lang_site['Admin cfg nb news home']; ?></label>
		<input type="text" size="2" name="form[nb_news_home]" value="<?php echo $site_config['o_nb_news_home']; ?>" /><br />
		<label><?php echo $lang_site['Admin cfg nb news page']; ?></label>
		<input type="text" size="2" name="form[nb_news_page]" value="<?php echo $site_config['o_nb_news_page']; ?>" /><br />
		</p>
	</fieldset>
	<fieldset>
		<legend><?php echo $lang_site['Admin resources module settings']; ?></legend>
		<p class="form">
		<label><?php echo $lang_site['Admin cfg enable res']; ?></label>
		<input type="radio" name="form[enable_res]" value="1"<?php if($site_config['o_enable_res'] == '1') echo ' checked="checked"'; ?> /> <?php echo $lang_site['Yes']; ?> &nbsp;
		<input type="radio" name="form[enable_res]" value="0"<?php if($site_config['o_enable_res'] == '0') echo ' checked="checked"'; ?> /> <?php echo $lang_site['No']; ?><br />
		<label><?php echo $lang_site['Admin cfg nb res home']; ?></label>
		<input type="text" size="2" name="form[nb_res_home]" value="<?php echo $site_config['o_nb_res_home']; ?>" /><br />
		<label><?php echo $lang_site['Admin cfg styles per page']; ?></label>
		<input type="text" size="2" name="form[styles_per_page]" value="<?php echo $site_config['o_styles_per_page']; ?>" /> <em><?php echo $lang_site['Multiple 3']; ?></em><br />
		<label><?php echo $lang_site['Admin cfg hacks per page']; ?></label>
		<input type="text" size="2" name="form[hacks_per_page]" value="<?php echo $site_config['o_hacks_per_page']; ?>" /> <em><?php echo $lang_site['Multiple 2']; ?></em>
		</p>
	</fieldset>
	<fieldset>
		<legend><?php echo $lang_site['Admin tutorials module settings']; ?></legend>
		<p class="form">
		<label><?php echo $lang_site['Admin cfg enable tuts']; ?></label>
		<input type="radio" name="form[enable_tuts]" value="1"<?php if($site_config['o_enable_tuts'] == '1') echo ' checked="checked"'; ?> /> <?php echo $lang_site['Yes']; ?> &nbsp;
		<input type="radio" name="form[enable_tuts]" value="0"<?php if($site_config['o_enable_tuts'] == '0') echo ' checked="checked"'; ?> /> <?php echo $lang_site['No']; ?><br />
		<label><?php echo $lang_site['Admin cfg nb tuts home']; ?></label>
		<input type="text" size="2" name="form[nb_tuts_home]" value="<?php echo $site_config['o_nb_tuts_home']; ?>" /><br />
		<label><?php echo $lang_site['Admin cfg tuts per page']; ?></label>
		<input type="text" size="2" name="form[tuts_per_page]" value="<?php echo $site_config['o_tuts_per_page']; ?>" />
		</p>
	</fieldset>
	<fieldset>
		<legend><?php echo $lang_site['Admin sidebar modules settings']; ?></legend>
		<p class="form">
		<label><?php echo $lang_site['Admin cfg enable lastposts']; ?></label>
		<input type="radio" name="form[enable_lastposts]" value="1"<?php if($site_config['o_enable_lastposts'] == '1') echo ' checked="checked"'; ?> /> <?php echo $lang_site['Yes']; ?>
		<input type="radio" name="form[enable_lastposts]" value="0"<?php if($site_config['o_enable_lastposts'] == '0') echo ' checked="checked"'; ?> /> <?php echo $lang_site['No']; ?><br />
		<label><?php echo $lang_site['Admin cfg nb lastposts']; ?></label>
		<input type="text" size="2" name="form[nb_lastposts]" value="<?php echo $site_config['o_nb_lastposts']; ?>" /><br />
		<?php if ($pun_user['id'] == 2): ?>
		<label><?php echo $lang_site['Admin cfg enable ads']; ?></label>
		<input type="radio" name="form[enable_ads]" value="1"<?php if($site_config['o_enable_ads'] == '1') echo ' checked="checked"'; ?> /> <?php echo $lang_site['Yes']; ?> &nbsp;
		<input type="radio" name="form[enable_ads]" value="0"<?php if($site_config['o_enable_ads'] == '0') echo ' checked="checked"'; ?> /> <?php echo $lang_site['No']; ?><br />
		<label><?php echo $lang_site['Admin cfg your ads']; ?></label><br />
		<textarea name="form[your_ads]" cols="60" rows="5"><?php echo pun_htmlspecialchars($site_config['o_your_ads']); ?></textarea> <em><?php echo $lang_site['Your ads help']; ?></em><br />
		<?php endif; ?>
		<label><?php echo $lang_site['Admin cfg enable social']; ?></label>
		<input type="radio" name="form[enable_social]" value="1"<?php if($site_config['o_enable_social'] == '1') echo ' checked="checked"'; ?> /> <?php echo $lang_site['Yes']; ?> &nbsp;
		<input type="radio" name="form[enable_social]" value="0"<?php if($site_config['o_enable_social'] == '0') echo ' checked="checked"'; ?> /> <?php echo $lang_site['No']; ?><br />
		<label><?php echo $lang_site['Admin cfg social links']; ?></label><br />
		<textarea name="form[social_links]" cols="60" rows="5"><?php echo pun_htmlspecialchars($site_config['o_social_links']); ?></textarea>
		</p>
	</fieldset>
	<fieldset>
		<legend><?php echo $lang_site['Admin footer settings']; ?></legend>
		<p class="form">
		<label><?php echo $lang_site['Admin cfg enable footer links']; ?></label>
		<input type="radio" name="form[enable_footer_links]" value="1"<?php if($site_config['o_enable_footer_links'] == '1') echo ' checked="checked"'; ?> /> <?php echo $lang_site['Yes']; ?> &nbsp;
		<input type="radio" name="form[enable_footer_links]" value="0"<?php if($site_config['o_enable_footer_links'] == '0') echo ' checked="checked"'; ?> /> <?php echo $lang_site['No']; ?><br />
		<label><?php echo $lang_site['Admin cfg footer sitelinks']; ?></label><br />
		<textarea name="form[footer_sitelinks]" cols="60" rows="5"><?php echo pun_htmlspecialchars($site_config['o_footer_sitelinks']); ?></textarea><br />
		<label><?php echo $lang_site['Admin cfg footer affiliates']; ?></label><br />
		<textarea name="form[footer_affiliates]" cols="60" rows="5"><?php echo pun_htmlspecialchars($site_config['o_footer_affiliates']); ?></textarea>
		</p>
	</fieldset>
	<p class="submit"><input type="submit" value="<?php echo $lang_site['Save changes']; ?>" /></p>
</form>
<?php require './includes/bottom.php';
}
elseif($module == 'resources')
{
	$titre_page = $lang_site['Pagename adm resources'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=resources"><?php echo $lang_site['Resources']; ?></a></p>
<h3><?php echo $lang_site['Title adm resources']; ?></h3>
<?php echo $lang_site['Explain adm res home']; ?>
<ul class="adm-tabs">
	<li class="tab-active"><?php echo $lang_site['Overview']; ?></li>
	<li><a href="admin_res_cat.php"><?php echo $lang_site['Categories']; ?></a></li>
	<li><a href="admin_resources.php"><?php echo $lang_site['Resources']; ?></a></li>
</ul>
<div class="adm-cont">
<?php
if (file_exists('./cache/cache_adm_res_home-'.$lang.'.php'))
	include './cache/cache_adm_res_home-'.$lang.'.php';

if (!defined('PUN_ADM_RES_HOME_LOADED'))
{
	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_admin_res_home_cache($lang);
	require './cache/cache_adm_res_home-'.$lang.'.php';
}
?>
	<div class="clearfix"></div>
</div>
<?php require './includes/bottom.php';
}
elseif($module == 'tutorials')
{
	$lang = $lang_site['Lang'];
	$titre_page = $lang_site['Pagename adm tutorials'];
	$module = 'admin';
	require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin.php?adm=tutorials"><?php echo $lang_site['Tutorials']; ?></a></p>
<h3><?php echo $lang_site['Title adm tutorials']; ?></h3>
<?php echo $lang_site['Explain adm tuts home']; ?>
<ul class="adm-tabs">
	<li class="tab-active"><?php echo $lang_site['Overview']; ?></li>
	<?php if ($pun_user['g_id'] == PUN_ADMIN): ?><li><a href="admin_tuts_cat.php"><?php echo $lang_site['Categories']; ?></a></li>
	<li><a href="admin_tuts_types.php"><?php echo $lang_site['Types']; ?></a></li><?php endif; ?>
	<li><a href="admin_tutorials.php"><?php echo $lang_site['Tutorials']; ?></a></li>
	<li><a href="admin_comments.php"><?php echo $lang_site['Comments']; ?></a></li>
</ul>
<div class="adm-cont">
<?php
if (file_exists('./cache/cache_adm_tuts_home-'.$lang.'.php'))
	include './cache/cache_adm_tuts_home-'.$lang.'.php';

if (!defined('PUN_ADM_TUTS_HOME_LOADED'))
{
	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_admin_tuts_home_cache($lang);
	require './cache/cache_adm_tuts_home-'.$lang.'.php';
}
?>
	<div class="clearfix"></div>
</div>
<?php require './includes/bottom.php';
}
$titre_page = $lang_site['Pagename adm home'];
$module = 'admin';
require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a></p>
<h3><?php echo $lang_site['Title adm home']; ?></h3>
<?php echo $lang_site['Explain adm home']; ?>
<div class="adm-block block-left" id="adm-menu">
	<h4><?php echo $lang_site['Admin heading']; ?></h4>
	<ul class="adm-links">
		<li><a href="admin.php?adm=cfg"><?php echo $lang_site['Admin manage settings']; ?></a></li>
		<li><a href="admin_pages.php"><?php echo $lang_site['Admin manage pages']; ?></a></li>
		<li><a href="admin.php?adm=resources"><?php echo $lang_site['Admin manage res']; ?></a></li>
		<li><a href="admin.php?adm=tutorials"><?php echo $lang_site['Admin manage tuts']; ?></a></li>
	</ul>
</div><?php
if (file_exists('./cache/cache_adm_home.php'))
	include './cache/cache_adm_home.php';

if (!defined('PUN_ADM_HOME_LOADED'))
{
	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_admin_home_cache();
	require './cache/cache_adm_home.php';
}
require './includes/bottom.php';
