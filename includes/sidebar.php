<!-- Début du menu -->
<div id="sidebar">
<!-- Connexion rapide - a adapter pour fluxbb -->
<?php include('fastconnect.php'); ?>
<!-- Médias sociaux -->
<?php if($site_config['o_enable_social'] == '1'): ?>
<div class="submenu">
  <h2><?php echo $lang_site['Follow us']; ?></h2>
  <!-- p><a href="#"><img src="./style/facebook-48x48.png" alt="<?php echo $lang_site['Follow Facebook']; ?>" /></a> <a href="#"><img src="./style/twitter-48x48.png" alt="<?php echo $lang_site['Follow Twitter']; ?>" /></a> <a href="#"><img src="./style/google+-48x48.png" alt="<?php echo $lang_site['Follow GooglePlus']; ?>" /></a></p -->
	<?php echo social_links($site_config['o_social_links'],$lang_site['Lang']); ?>
</div>
<?php endif; ?>
<!-- Pub Google pour Servhome -->
<?php if($site_config['o_enable_ads'] == '1'): ?>
<div class="submenu">
  <h2><?php echo $lang_site['Google']; ?></h2>
  <p id="pub">
	<!-- insérer pub ici -->
	<script type="text/javascript">
<!--
google_ad_client = "pub-0238584190163668";
google_ad_width = 200;
google_ad_height = 200;
google_ad_format = "200x200_as";
google_ad_type = "text_image";
google_ad_channel = "";
google_color_border = "f2f8f0";
google_color_bg = "f2f8f0";
google_color_link = "649351";
google_color_text = "365f26";
google_color_url = "497637";
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
  </p>
</div>
<?php endif; ?>
<!-- Derniers sujets - a adapter pour fluxbb -->
<?php if($site_config['o_enable_lastposts'] == '1') include("lastposts.php"); ?>
</div>
<!-- Fin du menu -->
