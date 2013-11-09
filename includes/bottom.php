		</div>
		<div id="footer">
<?php
	if($site_config['o_enable_footer_links'] == '1')
	{
		if (file_exists('./cache/cache_footer-'.$lang.'.php'))
			include './cache/cache_footer-'.$lang.'.php';

		if (!defined('PUN_FOOTER_LINKS_LOADED'))
		{
			if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
				require './includes/cache.php';

			generate_footer_cache($lang);
			require './cache/cache_footer-'.$lang.'.php';
		}
		if(!empty($footer['sitelinks']))
		{
			echo $footer['sitelinks'];
		}
		if(!empty($footer['affiliates']))
		{
			echo $footer['affiliates'];
		}
	}
?>
			<p><?php echo sprintf($lang_site['Footer content'],$pun_config['o_board_title']); ?></p><p id="goto-top"<a href="#top"><?php echo $lang_site['Goto top']; ?></a></p>
<?php
	// Display executed queries (if enabled)
	if (defined('PUN_SHOW_QUERIES'))
		display_saved_queries();
?>
		</div>
	  </div>
	</div>
<!-- script type="text/javascript"
src="http://api.mywot.com/widgets/ratings.js"></script -->
  </body>
</html>
<?php
//Once a page is loaded, it's useless to continue the script, so we just stop it in order to avoid page duplication.
exit;
