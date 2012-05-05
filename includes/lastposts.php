<div class="submenu">
<h2><?php echo $lang_site['Last posts']; ?></h2>
<ul class="lasttopics">
<?php
//On définit la constante pour le lien relatif vers le profil
define('PUN_REPLIES',$site_config['o_nb_lastposts']);

//C'est pas mal plus simple d'aller chercher les forums inaccessibles à l'utilisateur avant de faire le reste
//An admin can access all forums, so we first check if the user is admin beforu continuing
if($pun_user['g_id'] != PUN_ADMIN)
{
	$req = $db->query('SELECT id FROM '.$db->prefix.'forums JOIN '.$db->prefix.'forum_perms ON forum_id=id WHERE group_id='.$pun_user['g_id'].' AND read_forum=0');
	$ids = array();
	while($auth = $db->fetch_assoc($req))
		$ids[] = $auth['id'];

	// Check if there are forums to exclude
	if(count($ids) > 0)
		$sql = 'SELECT id,subject,num_replies,last_post,last_post_id,last_poster,forum_id FROM '.$db->prefix.'topics WHERE forum_id NOT IN ('.implode(',',$ids).') ORDER BY last_post_id DESC LIMIT 0,10';
	else
		$sql = 'SELECT id,subject,num_replies,last_post,last_post_id,last_poster,forum_id FROM '.$db->prefix.'topics ORDER BY last_post_id DESC LIMIT 0,10';

}
else
	$sql = 'SELECT id,subject,num_replies,last_post,last_post_id,last_poster,forum_id FROM '.$db->prefix.'topics ORDER BY last_post_id DESC LIMIT 0,10';

// Get last replies data
$result = $db->query($sql) or error('Unable to fetch replies list', __FILE__, __LINE__, $db->error());
while ($val = $db->fetch_assoc($result))
{
	//We check if the post-per-page user preference exists.  Otherwise, we use the board's default setting
	if (!empty($pun_user['disp_posts']))
	{
		$disp_posts = $pun_user['disp_posts'];
		$num_replies = $val['num_replies'];
	}
	else
	{
		$num_replies = $pun_config['o_disp_posts_default'];
		$num_replies = $val['num_replies'];
	}
	//The URL will only use the topic ID with the last page and the last reply's anchor.
	echo '<li><a href="'.PUN_ROOT.'viewtopic.php?id'.$val['id'].'&amp;p='.ceil(($num_replies+1)/$disp_posts).'&amp;pid='.$val['last_post_id'].'#'.$val['last_post_id'].'" rel="nofollow">'. $val['subject'].'</a> &raquo; ' .date('d/m/Y - H\:i',$val['last_post']) . $lang_site['by'] .' '.$val['last_poster'].'</li>';
}
?>
</ul>
</div>
