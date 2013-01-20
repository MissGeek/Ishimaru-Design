<?php require './includes/init-main.php';
if(!$pun_user['is_admmod'])
	message($lang_common['No permission']);

$lang = $lang_site['Lang'];
		$sel = $db->query('SELECT tentry_id, tentry_name, tentry_comments, tentry_catid, tcat_id, tcat_name FROM tuts_entries LEFT JOIN tuts_cat ON tentry_catid=tcat_id ORDER BY tentry_id DESC') or error('Unable to get tutorial data', __FILE__, __LINE__, $db->error());

$titre_page = $lang_site['Pagename adm comments'];
$module = 'admin';
require './includes/top.php'; ?>
<p class="crumbs"><?php echo $pun_config['o_board_title']; ?> &gt; <a href="index.php"><?php echo $lang_site['Home']; ?></a> &gt; <a href="admin.php"><?php echo $lang_site['Admin']; ?></a> &gt; <a href="admin_comments.php"><?php echo $lang_site['Comments']; ?></a></p>
<h3><?php echo $lang_site['Title adm comments']; ?></h3>
<?php echo $lang_site['Explain adm comments']; ?>
<ul class="adm-tabs">
	<li><a href="admin.php?adm=tutorials"><?php echo $lang_site['Overview']; ?></a></li>
	<?php if ($pun_user['g_id'] == PUN_ADMIN): ?><li><a href="admin_tuts_cat.php"><?php echo $lang_site['Categories']; ?></a></li>
	<li><a href="admin_tuts_types.php"><?php echo $lang_site['Types']; ?></a></li><?php endif; ?>
	<li><a href="admin_tutorials.php"><?php echo $lang_site['Tutorials']; ?></a></li>
	<li class="tab-active"><?php echo $lang_site['Comments']; ?></li>
</ul>
<div class="adm-cont">
<form method="post" action="">
	<p class="new-data">
		<label for="select_tut"><?php echo $lang_site['Select tutorial']; ?><select id="select_tut" name="id">
<?php
		while($select = $db->fetch_assoc($sel))
			echo "\t\t\t".'<option value="'.$select['tentry_id'].'">['.pun_htmlspecialchars(shorttext_lang($select['tcat_name'],$lang)).'] '.pun_htmlspecialchars($select['tentry_name']).' ('.$select['tentry_comments'].')</option>';
?>
		</select></label> <input type="submit" value="<?php echo $lang_site['OK']; ?>" />
	</p>
</form>
<table class="adm-table" id="tbl-comment-list">
	<tr>
		<th colspan="3"><?php echo $lang_site['Comments']; ?></th>
	</tr>
<?php

$lang = $lang_site['Lang'];

$sql = 'SELECT comment_id, comment_content, comment_author, comment_ip, comment_entryid, tentry_id, tentry_name, tentry_catid, tcat_id, tcat_name, id, username FROM tuts_comments LEFT JOIN tuts_entries ON comment_entryid=tentry_id LEFT JOIN tuts_cat ON tentry_catid=tcat_id LEFT JOIN '.$db->prefix.'users ON comment_author=id';
if(isset($_GET['id']) || isset($_POST['id']))
{
	$tut_id = intval(getpost('id'));
	if($tut_id > 0)
		$sql .= ' WHERE comment_entryid='.$tut_id;
}
$sql .= ' ORDER BY comment_id DESC';
$result = $db->query($sql) or error('Unable to get comment data', __FILE__, __LINE__, $db->error());

if($db->num_rows($result) > 0)
{
	while($cur_comment = $db->fetch_assoc($result))
	{
?>
		<tr class="catrow">
			<td><?php echo $lang_site['By'].' <a href="'.PUN_ROOT.'profile.php?id='.$cur_comment['comment_author'].'">'.$cur_comment['username'].'</a> ('.$cur_comment['comment_ip'].')'; ?></td>
			<td><?php echo $lang_site['Tutorial']; ?> : <a href="tutorials.php?tut=<?php echo $cur_comment['tentry_id']; ?>"><?php echo pun_htmlspecialchars($cur_comment['tentry_name']); ?></a> (<a href="tutorials.php?cat=<?php echo $cur_comment['tcat_id']; ?>"><?php echo pun_htmlspecialchars(shorttext_lang($cur_comment['tcat_name'],$lang)); ?></a>)</td>
			<td><a href="tutorials.php?edit_comment=<?php echo $cur_comment['comment_id']; ?>&amp;csrf=<?php echo sha1($pun_user['username'].$_SERVER['REMOTE_ADDR']); ?>"><?php echo $lang_site['Edit']; ?></a> - <a href="tutorials.php?del_comment=<?php echo $cur_comment['comment_id']; ?>&amp;csrf=<?php echo sha1($pun_user['username'].$_SERVER['REMOTE_ADDR']); ?>"><?php echo $lang_site['Delete']; ?></a></td>
		</tr>
		<tr class="subrow">
			<td colspan="3"><?php echo $cur_comment['comment_content']; ?></td>
		</tr>
<?php
	}
}
	else echo '<tr><td colspan="3" class="center">'.$lang_site['No comment'].'</td></tr>'; ?>
	</table>
	<div class="clearfix"></div>
</div>
<?php require './includes/bottom.php'; ?>
