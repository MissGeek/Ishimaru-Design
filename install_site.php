<?php
/* Traitement des données pour l'installation */
if(isset($_GET['act']))
{
	if($_GET['act'] == 'install')
	{
		$form = array(
			'site_desc'				=> trim($_POST['form']['site_desc']),
			'forum_url'				=> trim($_POST['form']['forum_url']),
			'base_url'				=> trim($_POST['form']['base_url']),

			'enable_intro'			=> 0,
			'site_intro'			=> '',

			'enable_news'			=> 0,
			'forum_news'			=> '',
			'nb_news_home'			=> 10,
			'nb_news_page'			=> 10,

			'enable_res'			=> 0,
			'nb_res_home'			=> 10,
			'styles_per_page'		=> 12,
			'hacks_per_page'		=> 12,

			'enable_tuts'			=> 0,
			'nb_tuts_home'			=> 10,
			'tuts_per_page'			=> 10,

			'enable_lastposts'		=> 0,
			'nb_lastposts'			=> 10,

			'enable_social'			=> 0,
			'social_links'			=> '',

			'enable_footer_links'	=> 0,
			'footer_sitelinks'		=> '',
			'footer_affiliates'		=> '',

			'enable_ads'			=> 0,
			'your_ads'				=> 'Saisissez votre publicité ici.'
		);
//		exit(print_r($form));

		$message = '';
		if($form['forum_url'] == '')
			$message = '<p>Vous devez saisir le chemin vers votre forum pour que votre site puisse fonctionner correctement !</p>';
		elseif(!file_exists($form['forum_url'].'viewforum.php'))
			$message = '<p>Le chemin saisi n\'est pas bon !</p>';
		else
		{
			define ('PUN_ROOT',$form['forum_url']);
			require PUN_ROOT.'include/common.php';
		}
		$base_to_check = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$my_base = $form['base_url'].'install_site.php?act=install';
		if($form['base_url'] == '')
			$message = '<p>Vous devez saisir l\'URL de base de votre site pour que votre site puisse fonctionner correctement !</p>';
//		elseif(!file_exists($form['base_url'].'admin.php'))
		elseif($base_to_check != $my_base)
			$message = '<p>Le chemin saisi n\'est pas bon ou un problème est survenu lors de l\'upload des fichiers !<br />La base de référence : '.$base_to_check.'<br />Ma base : '.$my_base.'</p>';
		else
		{
			define ('BASE_URL',$form['base_url']);
		}
		if(!file_exists('./site_config.php'))
			$message = '<p>Le fichier site_config.php est inexistant !  Veuillez vous assurez qu\'un fichier vide portant co nom se trouve bien à la racine de votre site, avec les permissions d\'écriture avant de continuer l\'installation !</p>';
		elseif(!is_writable('./site_config.php'))
			$message = '<p>Le fichier site_config.php n\'est pas autorisé en écriture !  Veuillez régler les permission en CHMOD 777 pour que le script puisse écrire dans ce fichier !</p>';

		if($message == '')
		{
			//Écriture dans le fichier site_config.php
			$fh = @fopen('./site_config.php', 'wb');
			if (!$fh)
				error('Unable to write site config data', __FILE__, __LINE__);
			$output = '<?php'."\n\n".'define(\'PUN_ROOT\', \''.$form['forum_url'].'\');'."\n".'define(\'BASE_URL\', \''.$form['base_url'].'\');'."\n\n".'define(\'PUN_SITE\',1);';
			fwrite($fh, $output);

			//Avec le fichier site_config.php, pas besoin de stocker ces infos dans la BDD
			unset($form['forum_url']);
			unset($form['base_url']);

			// Création des tables

			//Catégories de ressources
			$schema = array(
				'FIELDS'				=> array(
					'rcat_id'				=> array(
						'datatype'				=> 'SERIAL',
						'allow_null'    		=> false
					),
					'rcat_name'			=> array(
						'datatype'				=> 'VARCHAR(64)',
						'allow_null'			=> false
					),
					'rcat_clearname'		=> array(
						'datatype'				=> 'VARCHAR(64)',
						'allow_null'			=> false
					),
					'rcat_icon'				=> array(
						'datatype'				=> 'VARCHAR(64)',
						'allow_null'			=> false
					),
					'rcat_desc'			=> array(
						'datatype'				=> 'MEDIUMTEXT',
						'allow_null'			=> false
					),
					'rcat_order'			=> array(
						'datatype'				=> 'TINYINT(4) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'rcat_lang'				=> array(
						'datatype'				=> 'SET(\'fr\',\'en\')',
						'allow_null'			=> false
					),
				),
				'PRIMARY KEY'			=> array('rcat_id'),
				'INDEXES'				=> array(
					'catname_idx'		=> array('rcat_name')
				)
			);
			$db->create_table('res_cat', $schema,true) or error('Unable to create res_cat table', __FILE__, __LINE__, $db->error());

			//Sous-catégories de ressources
			$schema = array(
				'FIELDS'				=> array(
					'rsub_id'				=> array(
						'datatype'				=> 'SERIAL',
						'allow_null'    		=> false
					),
					'rsub_name'			=> array(
						'datatype'				=> 'VARCHAR(32)',
						'allow_null'			=> false
					),
					'rsub_clearname'		=> array(
						'datatype'				=> 'VARCHAR(32)',
						'allow_null'			=> false
					),
					'rsub_type'				=> array(
						'datatype'				=> 'ENUM(\'hack\',\'style\')',
						'allow_null'			=> false
					),
					'rsub_catid'			=>array(
						'datatype'				=> 'TINYINT(4) UNSIGNED',
						'allow_null'			=> false
					),
					'rsub_desc'			=> array(
						'datatype'				=> 'MEDIUMTEXT',
						'allow_null'			=> false
					),
					'rsub_lang'				=> array(
						'datatype'				=> 'SET(\'fr\',\'en\')',
						'allow_null'			=> false
					),
				),
				'PRIMARY KEY'			=> array('rsub_id'),
				'INDEXES'				=> array(
					'subname_idx'		=> array('rsub_name')
				)
			);
			$db->create_table('res_subcat', $schema,true) or error('Unable to create res_subcat table', __FILE__, __LINE__, $db->error());

			//Ressources
			$schema = array(
				'FIELDS'				=> array(
					'rentry_id'				=> array(
						'datatype'				=> 'SERIAL',
						'allow_null'    		=> false
					),
					'rentry_name'		=> array(
						'datatype'				=> 'VARCHAR(128)',
						'allow_null'			=> false
					),
					'rentry_shortdesc'	=> array(
						'datatype'				=> 'VARCHAR(512)',
						'allow_null'			=> false
					),
					'rentry_screen_main'	=> array(
						'datatype'				=> 'INT(11) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'rentry_desc'		=> array(
						'datatype'				=> 'MEDIUMTEXT',
						'allow_null'			=> false
					),
					'rentry_authornotes'	=> array(
						'datatype'				=> 'MEDIUMTEXT',
						'allow_null'			=> false
					),
					'rentry_subcatid'		=> array(
						'datatype'				=> 'SMALLINT(6) UNSIGNED',
						'allow_null'			=> false
					),
					'rentry_catid'			=> array(
						'datatype'				=> 'TINYINT(4) UNSIGNED',
						'allow_null'			=> false
					),
					'rentry_download'		=> array(
						'datatype'				=> 'VARCHAR(256)',
						'allow_null'			=> false
					),
					'rentry_publish'		=> array(
						'datatype'				=> 'TINYINT(1) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'rentry_lang'			=> array(
						'datatype'				=> 'SET(\'fr\',\'en\')',
						'allow_null'			=> false
					),
					'rentry_publishdate'	=> array(
						'datatype'				=> 'INT(11) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'rentry_lastupdate'		=> array(
						'datatype'				=> 'INT(11) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					)
				),
				'PRIMARY KEY'			=> array('rentry_id'),
				'INDEXES'				=> array(
					'resname_idx'		=> array('rentry_name')
				)
			);
			$db->create_table('res_entries', $schema,true) or error('Unable to create res_entries table', __FILE__, __LINE__, $db->error());

			//Captures d'écran de ressources
			$schema = array(
				'FIELDS'				=> array(
					'rscreen_id'			=> array(
						'datatype'				=> 'SERIAL',
						'allow_null'    		=> false
					),
					'rscreen_legend'		=> array(
						'datatype'				=> 'VARCHAR(256)',
						'allow_null'			=> false
					),
					'rscreen_url_full'		=> array(
						'datatype'				=> 'VARCHAR(64)',
						'allow_null'			=> false
					),
					'rscreen_url_small'		=> array(
						'datatype'				=> 'VARCHAR(64)',
						'allow_null'			=> false
					),
					'rscreen_entryid'		=> array(
						'datatype'				=> 'MEDIUMINT(9) UNSIGNED',
						'allow_null'			=> false
					)
				),
				'PRIMARY KEY'			=> array('rscreen_id'),
				'INDEXES'				=> array(
					'screen_idx'			=> array('rscreen_legend')
				)
			);
			$db->create_table('res_screens', $schema,true) or error('Unable to create res_screens table', __FILE__, __LINE__, $db->error());

			//Catégories de tutoriels
			$schema = array(
				'FIELDS'				=> array(
					'tcat_id'				=> array(
						'datatype'				=> 'SERIAL',
						'allow_null'	    	=> false
					),
					'tcat_name'			=> array(
						'datatype'				=> 'VARCHAR(64)',
						'allow_null'			=> false
					),
					'tcat_clearname'		=> array(
						'datatype'				=> 'VARCHAR(64)',
						'allow_null'			=> false
					),
					'tcat_icon'				=> array(
						'datatype'				=> 'VARCHAR(64)',
						'allow_null'			=> false
					),
					'tcat_desc'			=> array(
						'datatype'				=> 'MEDIUMTEXT',
						'allow_null'			=> false
					),
					'tcat_order'			=> array(
						'datatype'				=> 'TINYINT(4) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'tcat_lang'				=> array(
						'datatype'				=> 'SET(\'fr\',\'en\')',
						'allow_null'			=> false
					),
					'tcat_hasversions'		=> array(
						'datatype'				=> 'TINYINT(1) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					)
				),
				'PRIMARY KEY'			=> array('tcat_id'),
				'INDEXES'				=> array(
					'catname_idx'		=> array('tcat_name')
				)
			);
			$db->create_table('tuts_cat', $schema,true) or error('Unable to create tuts_cat table', __FILE__, __LINE__, $db->error());

			//Versions pour tutoriels
			$schema = array(
				'FIELDS'				=> array(
					'version_id'			=> array(
						'datatype'				=> 'SERIAL',
						'allow_null'	    	=> false
					),
					'version_name'			=> array(
						'datatype'				=> 'VARCHAR(16)',
						'allow_null'			=> false
					),
					'version_cat'			=> array(
						'datatype'				=> 'TINYINT(4) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					)
				),
				'PRIMARY KEY'			=> array('version_id'),
				'INDEXES'				=> array(
					'version_idx'			=> array('version_name')
				)
			);
			$db->create_table('tuts_versions', $schema,true) or error('Unable to create tuts_versions table', __FILE__, __LINE__, $db->error());

			//Types de tutoriels
			$schema = array(
				'FIELDS'				=> array(
					'type_id'				=> array(
						'datatype'				=> 'SERIAL',
						'allow_null'    		=> false
					),
					'type_name'			=> array(
						'datatype'				=> 'VARCHAR(64)',
						'allow_null'			=> false,
					)
				),
				'PRIMARY KEY'			=> array('type_id'),
				'INDEXES'				=> array(
					'typename_idx'		=> array('type_name')
				)
			);
			$db->create_table('tuts_type', $schema,true) or error('Unable to create tuts_type table', __FILE__, __LINE__, $db->error());

			//Tutoriels
			$schema = array(
				'FIELDS'				=> array(
					'tentry_id'				=> array(
						'datatype'				=> 'SERIAL',
						'allow_null'    		=> false
					),
					'tentry_name'			=> array(
						'datatype'				=> 'VARCHAR(128)',
						'allow_null'			=> false
					),
					'tentry_icon'			=> array(
						'datatype'				=> 'VARCHAR(64)',
						'allow_null'			=> false
					),
					'tentry_desc'			=> array(
						'datatype'				=> 'MEDIUMTEXT',
						'allow_null'			=> false
					),
					'tentry_level'			=> array(
						'datatype'				=> 'TINYINT(1) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'tentry_lang'			=> array(
						'datatype'				=> 'ENUM(\'fr\',\'en\')',
						'allow_null'			=> false
					),
					'tentry_author'			=> array(
						'datatype'				=> 'MEDIUMINT(9) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'tentry_type'			=> array(
						'datatype'				=> 'TINYINT(4) UNSIGNED',
						'allow_null'			=> false
					),
					'tentry_publish'		=> array(
						'datatype'				=> 'TINYINT(1) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'tentry_publishdate'	=> array(
						'datatype'				=> 'INT(11) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'tentry_lastupdate'		=> array(
						'datatype'				=> 'INT(11) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'tentry_comments'		=> array(
						'datatype'				=> 'SMALLINT(6) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'tentry_catid'			=> array(
						'datatype'				=> 'TINYINT(4) UNSIGNED',
						'allow_null'			=> false
					),
					'tentry_version'		=> array(
						'datatype'				=> 'TINYINT(4) UNSIGNED',
						'allow_null'			=> true
					)
				),
				'PRIMARY KEY'			=> array('tentry_id'),
				'INDEXES'				=> array(
					'tutname_idx'			=> array('tentry_name'),
				)
			);
			$db->create_table('tuts_entries', $schema,true) or error('Unable to create tuts_entries table', __FILE__, __LINE__, $db->error());

			//Parties de tutoriel
			$schema = array(
				'FIELDS'				=> array(
					'text_id'				=> array(
						'datatype'				=> 'SERIAL',
						'allow_null'    		=> false
					),
					'text_name'				=> array(
						'datatype'				=> 'VARCHAR(128)',
						'allow_null'			=> false
					),
					'text_text'				=> array(
						'datatype'				=> 'TEXT',
						'allow_null'			=> false
					),
					'text_order'			=> array(
						'datatype'				=> 'TINYINT(4) UNSIGNED',
						'allow_null'			=> false
					),
					'text_entryid'			=> array(
						'datatype'				=> 'MEDIUMINT(9) UNSIGNED',
						'allow_null'			=> false
					),
					'text_publishdate'		=> array(
						'datatype'				=> 'INT(11) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'text_lastupdate'		=> array(
						'datatype'				=> 'INT(11) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					)
				),
				'PRIMARY KEY'			=> array('text_id'),
				'INDEXES'				=> array(
					'textname_idx'			=> array('text_name'),
				)
			);
			$db->create_table('tuts_texts', $schema,true) or error('Unable to create tuts_text table', __FILE__, __LINE__, $db->error());

			//Commentaires de tutoriels
			$schema = array(
				'FIELDS'				=> array(
					'comment_id'			=> array(
						'datatype'				=> 'SERIAL',
						'allow_null'    		=> false
					),
					'comment_entryid'		=> array(
						'datatype'				=> 'MEDIUMINT(9) UNSIGNED',
						'allow_null'			=> false
					),
					'comment_content'		=> array(
						'datatype'				=> 'MEDIUMTEXT',
						'allow_null'			=> false
					),
					'comment_author'		=> array(
						'datatype'				=> 'MEDIUMINT(9) UNSIGNED',
						'allow_null'			=> false
					),
					'comment_ip'			=> array(
						'datatype'				=> 'VARCHAR(39)',
						'allow_null'			=> false
					),
					'comment_publishdate'		=> array(
						'datatype'				=> 'INT(11) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'comment_lastupdate'		=> array(
						'datatype'				=> 'INT(11) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					)
				),
				'PRIMARY KEY'			=> array('comment_id')
			);
			$db->create_table('tuts_comments', $schema,true) or error('Unable to create tuts_comments table', __FILE__, __LINE__, $db->error());

			//Pages statiques
			$schema = array(
				'FIELDS'				=> array(
					'page_id'				=> array(
						'datatype'				=> 'SERIAL',
						'allow_null'	    	=> false
					),
					'page_title'			=> array(
						'datatype'				=> 'VARCHAR(128)',
						'allow_null'			=> false
					),
					'page_title_clean'		=> array(
						'datatype'				=> 'VARCHAR(32)',
						'allow_null'			=> false
					),
					'page_text'				=> array(
						'datatype'				=> 'TEXT',
						'allow_null'			=> false
					),
					'page_lang'				=> array(
						'datatype'				=> 'ENUM(\'fr\',\'en\')',
						'allow_null'			=> false
					),
					'page_publishdate'		=> array(
						'datatype'				=> 'INT(11) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					),
					'page_lastupdate'		=> array(
						'datatype'				=> 'INT(11) UNSIGNED',
						'allow_null'			=> false,
						'default'				=> 0
					)
				),
				'PRIMARY KEY'			=> array('page_id'),
				'INDEXES'				=> array(
					'pagename_idx'			=> array('page_title'),
				)
			);
			$db->create_table('site_pages', $schema,true) or error('Unable to create site_pages table', __FILE__, __LINE__, $db->error());

			//Configuration générale
			$schema = array(
				'FIELDS'				=> array(
					'conf_name'				=> array(
						'datatype'				=> 'VARCHAR(255)',
						'allow_null'			=> false
					),
					'conf_value'			=> array(
						'datatype'				=> 'TEXT',
						'allow_null'			=> true
					)
				),
				'PRIMARY KEY'			=> array('conf_name')
			);
			$db->create_table('site_config', $schema,true) or error('Unable to create site_config table', __FILE__, __LINE__, $db->error());

			foreach ($form as $key => $input)
			{
				if ($input != '' || !is_int($input))
					$value = '\''.$db->escape($input).'\'';

				$db->query('INSERT INTO site_config (conf_name,conf_value) VALUES(\'o_'.$key.'\','.$value.')') or error('Unable to insert site config', __FILE__, __LINE__, $db->error());
			}

			// Regenerate the config cache
			if (!defined('SITE_CACHE_FUNCTIONS_LOADED'))
				require './includes/cache.php';

			generate_site_config_cache();

			header('Location: install_site.php?act=done');
			exit;
		}
	}
	elseif($_GET['act'] == 'done')
	{
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Installation terminée !</title>
<style type="text/css">
*{margin:0;padding:0;}
body { background-color:#e0efe0; color:#333; font-family:'Trebuchet MS',Arial,sans-serif; font-size:100%; }
#wrap {width:900px; margin: 30px auto; background-color:#fafafa; border:#c0d0c0 1px solid;}
h1 {margin: 10px;}
#info {margin: 10px; font-size:0.8em;}
fieldset { margin: 10px; padding: 10px; border: #609060 1px dotted;}
legend {font-weight:bold; color:#609060;}
label {font-weight:bold; font-size: 0.9em;}
em {font-size:0.8em;}
fieldset p {margin: 10px 0 20px;}
textarea, input[type="text"] {width: 850px;}
p.send {text-align:center;}
#footer {margin:10px; padding:10px; text-align:center; font-size:0.7em;}
</style>
</head>
<body>
<div id="wrap">
<h1>Installation terminée</h1>
<p id="info">Votre site est maintenant installé !  Vous pouvez maintenant vous connecter à l'administration pour le configurer.<br /><a href="./index.php">Accéder au site</a></p>
<p id="footer">Site propulsé par FluxBB</p>
</div>
</body>
</html>
<?php
		exit;
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Installation du site</title>
<style type="text/css">
*{margin:0;padding:0;}
body { background-color:#e0efe0; color:#333; font-family:'Trebuchet MS',Arial,sans-serif; font-size:100%; }
#wrap {width:900px; margin: 30px auto; background-color:#fafafa; border:#c0d0c0 1px solid;}
h1 {margin: 10px;}
#info {margin: 10px; font-size:0.8em;}
fieldset { margin: 10px; padding: 10px; border: #609060 1px dotted;}
legend {font-weight:bold; color:#609060;}
label {font-weight:bold; font-size: 0.9em;}
em {font-size:0.8em;}
fieldset p {margin: 10px 0 20px;}
textarea, input[type="text"] {width: 850px;}
p.send {text-align:center;}
#footer {margin:10px; padding:10px; text-align:center; font-size:0.7em;}
</style>
</head>
<body>
<div id="wrap">
<h1>Installation du site</h1>
<p id="info">Saisissez les informations nécessaires pour l'installation du site</p>
<div id="body">
<form method="post" action="install_site.php?act=install">
	<fieldset>
		<legend>Informations générales</legend>
		<?php echo !empty($message) ? '<div class="error">'. $message .'</div>' : ''; ?>
		<p>
			<label for="site-desc">Description du site</label><br />
			<em>Utilisez ce format pour chaque langue, en séparant d'un retour chariot : {fr|La description de mon super site}</em><br />
			<textarea name="form[site_desc]" id="site-desc" cols="60" rows="5"><?php echo !empty($form['site_desc']) ? pun_htmlspecialchars($form['site_desc']) : '{fr|exemple de description}'."\n".'{en|Sample site description}'; ?></textarea>
		</p>
		<p>
			<label for="forum-url">Chemin relatif du forum</label><br />
			<em>Pour assurer le bon fonctionnement du site, vous devez saisir le chemin relatif de votre forum FluxBB à partir de votre site.  Par exemple, si votre site est à la racine et que votre forum FluxBB se trouve dans un dossier nommé « fluxbb », vous devez saisir « ./fluxbb/ » dans le champs</em><br />
			<input type="text" size="30" name="form[forum_url]" id="forum-url" value="<?php echo !empty($form['forum_url']) ? $form['forum_url'] : './fluxbb/'; ?>" />
		</p>
		<p>
			<label for="base-url">URL de base de votre site</label><br />
			<em>Certaines fonctionnalités requièrent l'URL absolue du site pour son bon fonctionnement.  Si par exemple l'adresse de votre site est « http://www.pouet.com » et que votre site se trouve à la racine, saisissez « http://www.pouet.com/ ».</em><br />
			<input type="text" size="30" name="form[base_url]" id="base-url" value="<?php echo !empty($form['base_url']) ? $form['base_url'] : 'http://exemple.com/'; ?>" />
		</p>
	</fieldset>
	<p class="send"><input type="submit" value="Installer" /></p>
</form>
</div>
<p id="footer">Site propulsé par FluxBB</p>
</div>
</body>
</html>
