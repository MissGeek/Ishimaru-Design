<?php
if ($pun_user['is_guest'])
{
	?>
	<!-- Panneau de connexion -->
	<div class="submenu">
	  <h2><?php echo $lang_site['Log in']; ?></h2>
	  <form id="login" action="<?php echo PUN_ROOT.'login.php?action=in'; ?>" method="post">
		<p class="form">
		  <input type="hidden" name="form_sent" value="1" />
		  <input type="hidden" name="redirect_url" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
		  <label for="username"><?php echo $lang_site['Username']; ?> &nbsp;</label><input class="fastlogin" id="username" type="text" name="req_username" size="10" /><br />
		  <label for="password"><?php echo $lang_site['Password']; ?> &nbsp;</label><input class="fastlogin" id="password" type="password" name="req_password" size="10" /><br />
		  <label for="autologin"><?php echo $lang_site['Autologin']; ?> &nbsp;</label><input class="check" id="autologin" type="checkbox" checked="checked" name="save_pass" />
		</p>
		<p class="submit">
		  <input class="connect" type="submit" name="login" value="<?php echo $lang_site['Log in']; ?>" /> <a href="<?php echo PUN_ROOT.'register.php' ?>"><?php echo $lang_site['Register']; ?></a>
		</p>
	  </form>
	</div>
	<?php
}
else
{
	?>
	<!-- Options de l'utilisateur -->
	<div class="submenu">
	  <h2><?php echo $pun_user['username']; ?></h2>
	  <ul>
		<li><a href="<?php echo PUN_ROOT.'profile.php?id='.$pun_user['id']; ?>"><?php echo $lang_site['Profile']; ?></a></li>
		<li><a href="<?php echo PUN_ROOT.'pmsnew.php'; ?>"><?php echo $lang_site['PMs']; ?></a></li>
		<li><a href="<?php echo PUN_ROOT.'search.php?action=show_replies'; ?>"><?php echo $lang_site['My topics']; ?></a></li>
		<?php if ($pun_user['is_admmod']): echo '<li><a href="admin.php">'. $lang_site['Admin'].'</a></li>'; endif; ?>
		<li><a href="<?php echo 'logout.php?action=out&amp;id='.$pun_user['id'].'&amp;csrf_token='.pun_hash($pun_user['id'].pun_hash(get_remote_address())); ?>"><?php echo $lang_site['Log out']; ?></a></li>
	  </ul>
	</div>
	<?php
}
?>
