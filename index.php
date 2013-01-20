<?php require './includes/init-main.php';
if(isset($_GET['module']))
{
	$module = pun_htmlspecialchars($_GET['module']);
	if($module == 'news')
	{
		$lang = $lang_site['Lang'];
		// Module de news de Connectix Boards, adapté pour FluxBB 1.4
		// Modifiez les deux lignes suivantes pour que cela corresponde à votre forum
		define('PUN_NEWS',forum_news($site_config['o_forum_news'],$lang)); // ID du forum consacré aux nouvelles.
		define('NB_NEWS',$site_config['o_nb_news_page']); // Nombre de news à afficher
		// Récupération des ids des messages à afficher
		$query = $db->query('SELECT first_post_id FROM '.$db->prefix.'topics WHERE forum_id='.PUN_NEWS.' GROUP BY id ORDER BY first_post_id DESC LIMIT 0,'.NB_NEWS);
		$ids = array();
		if(!$db->num_rows($query))
			site_msg($lang_site['No news']);

		while($result = $db->fetch_assoc($query))
			$ids[] = $result['first_post_id'];
		// Récupération des données des messages sélectionnés
		$result2 = $db->query('SELECT p.id AS msg_id, p.message AS msg_text, p.posted AS msg_posted, p.topic_id AS msg_topic, p.hide_smilies AS msg_smilies, t.id AS topic_id, t.subject AS topic_subject, t.num_replies AS topic_replies, t.poster AS topic_poster, u.id AS user_id, u.username AS user_name FROM '.$db->prefix.'posts AS p LEFT JOIN '.$db->prefix.'topics AS t ON t.id=p.topic_id LEFT JOIN '.$db->prefix.'users AS u ON t.poster=u.id WHERE p.id IN ('.implode(',',$ids).') ORDER BY p.id DESC');
		if(!$db->num_rows($result2) > 0)
			site_msg($lang_site['No news']);

		$titre_page = $lang_site['Pagename news'];
		require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="index.php?module=news"><?php echo $lang_site['News']; ?></a></p>
<h3><?php echo $lang_site['Title news']; ?></h3>
<?php echo $lang_site['Explain news'];
		require PUN_ROOT.'include/parser.php';
		// Affichage des résultats
		while ($val = $db->fetch_assoc($result2))
		{
			$username = ($val['user_id'] > 1) ? '<a href="'.PUN_ROOT.'profile.php?id='.$val['user_id'].'">'.pun_htmlspecialchars($val['user_name']).'</a>' : pun_htmlspecialchars($val['topic_poster']);
?>
<div class="news">
	<h4><?php echo pun_htmlspecialchars($val['topic_subject']); ?></h4>
	<p class="news_info"><?php echo $lang_site['Published'] . format_time($val['msg_posted']).' '.$lang_site['By'] . $username; ?> - <a href="<?php echo PUN_ROOT.'viewtopic.php?id='.$val['msg_topic']; ?>"><?php echo $val['topic_replies'] . $lang_site['comments']; ?></a></p>
	<div class="p"><?php echo parse_message($val['msg_text'],$val['msg_smilies']); ?>
		<p class="goto"><a href="#top"><?php echo $lang_site['Goto top']; ?></a></p>
	</div>
</div>
<?php
		}
?>
<p class="links"><a href="<?php echo PUN_ROOT.'viewforum.php?id='.PUN_NEWS; ?>" id="shownews"><?php echo $lang_site['Show all']; ?></a> | <a href="archives.php"><?php echo $lang_site['Archives']; ?></a></p>
<?php require './includes/bottom.php';	
	}
	elseif ($module == 'page')
	{
		if(isset($_GET['page']))
		{
			$page_id = intval($_GET['page']);
			if($page_id < 1)
				site_msg($lang_site['Bad request']);

			$query2 = $db->query('SELECT page_title, page_title_clean, page_text FROM site_pages WHERE page_id='.$page_id);
			if(!$db->num_rows($query2))
				site_msg($lang_site['Page not found']);

			$cur_page = $db->fetch_assoc($query2);
			require PUN_ROOT.'include/parser.php';
			$titre_page = $cur_page['page_title'];
			require './includes/top.php';
			$crumbs = '<p class="crumbs">'.$pun_config['o_board_title'].' &gt; <a href="index.php">'.$lang_site['Home'].'</a> &gt; <a href="index.php?page='.$cur_page['page_id'].'">'.pun_htmlspecialchars($cur_page['page_title']).'</a></p>';
			echo $crumbs;
			echo '<h3>'.pun_htmlspecialchars($cur_page['page_title']).'</h3>';
			echo parse_message($cur_page['page_text'],0);
			require './includes/bottom.php';
			//Page d'accueil
		}
	}
}
$titre_page = $lang_site['Pagename home'];
require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a></p>
<h3><?php echo sprintf($lang_site['Title home'],$pun_config['o_board_title']); ?></h3>
<?php echo $lang_site['Explain home'];
//Page d'accueil
define('PUN_NEWS',forum_news($site_config['o_forum_news'],$lang));
define('HOME_NEWS',$site_config['o_nb_news_home']);
define('HOME_TUTS',$site_config['o_nb_tuts_home']);
define('HOME_RES',$site_config['o_nb_res_home']);

$count = 0;

if($site_config['o_enable_intro'] == '1'): ?>
<div class="home-block block-left" id="about">
	<h4><?php echo pun_htmlspecialchars($pun_config['o_board_title']).$lang_site['About site']; ?></h4>
	<?php echo intro_module($site_config['o_site_intro'],$lang); ?><?php $count++; ?>
</div><?php endif; ?><?php if($site_config['o_enable_news'] == '1'): ?><div class="home-block<?php echo ' '.block_side($count); ?>" id="latest-news">
	<h4><?php echo $lang_site['Latest news']; ?></h4>
	<ul>
<?php
$lang = $lang_site['Lang'];

//Étant donné que l'ajout de news se fait via le forum et que ce n'est pas très pensable
//de modifier post.php pour regénérer le cache, une petite requête à chaque chargement ainsi qu'une
//constante et une condition écrites à même le fichier de cache permettent de
//vérifier si une news s'est ajoutée et donc, si le cache a besoin d'être regénéré.
$news = $db->query('SELECT MAX(id) FROM '.$db->prefix.'topics WHERE forum_id='.PUN_NEWS);
$last_news_id = $db->result($news);
if (file_exists('./cache/cache_lastnews-'.$lang.'.php'))
	include './cache/cache_lastnews-'.$lang.'.php';

if (!defined('PUN_LASTNEWS_LOADED') || $last_news_id != LAST_NEWS_ID)
{
	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_lastnews_cache($lang);
	require './cache/cache_lastnews-'.$lang.'.php';
}
$count++;
?>
	</ul>
</div><?php endif; ?><?php if($count > 0 && $count % 2 == 0): ?><hr class="sep" /><?php endif; ?><?php if($site_config['o_enable_tuts'] == '1'): ?><div class="home-block<?php echo ' '.block_side($count); ?>" id="latest-tuts">
	<h4><?php echo $lang_site['Latest tutorials']; ?></h4>
	<ul>
<?php
if (file_exists('./cache/cache_lasttuts-'.$lang.'.php'))
	include './cache/cache_lasttuts-'.$lang.'.php';

if (!defined('PUN_LASTTUTS_LOADED'))
{
	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_lasttuts_cache($lang);
	require './cache/cache_lasttuts-'.$lang.'.php';
}
$count++;
?>
	</ul>
</div><?php endif; ?><?php if($count > 0 && $count % 2 == 0): ?><hr class="sep" /><?php endif; ?><?php if($site_config['o_enable_res']): ?><div class="home-block<?php echo ' '.block_side($count); ?>" id="latest-styles">
	<h4><?php echo $lang_site['Latest resources']; ?></h4>
	<ul>
<?php
if (file_exists('./cache/cache_lastres-'.$lang.'.php'))
	include './cache/cache_lastres-'.$lang.'.php';

if (!defined('PUN_LASTRES_LOADED'))
{
	if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
		require './includes/cache.php';

	generate_lastres_cache($lang);
	require './cache/cache_lastres-'.$lang.'.php';
}
?>
	</ul>
</div><?php endif; ?>
<?php require './includes/bottom.php';
