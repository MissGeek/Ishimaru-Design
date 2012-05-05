<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang_site['Lang']; ?>" lang="<?php echo $lang_site['Lang']; ?>">
  <head>
	<title><?php echo $pun_config['o_board_title']; ?> - <?php echo $titre_page; ?></title>
	<meta name="description" content="<?php echo pun_htmlspecialchars(shorttext_lang($site_config['o_site_desc'],$lang,true)); ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="./style/style.css" media="screen" type="text/css" title="design v6" />
<?php
if(isset($wcomment))
{
?>
<!-- Une partie de ce script étant gérée par PHP, je ne peux pas faire de miracles pour l'externalisation... -->
<script type="text/javascript">
/* <![CDATA[ */
function process_form(the_form)
{
	var element_names = {
<?php
	// Output a JavaScript object with localised field names
	$tpl_temp = count($required_fields);
	foreach ($required_fields as $elem_orig => $elem_trans)
	{
		echo "\t\t\"".$elem_orig.'": "'.addslashes(str_replace('&#160;', ' ', $elem_trans));
		if (--$tpl_temp) echo "\",\n";
		else echo "\"\n\t};\n";
	}
?>
	if (document.all || document.getElementById)
	{
		for (var i = 0; i < the_form.length; ++i)
		{
			var elem = the_form.elements[i];
			if (elem.name && (/^req_/.test(elem.name)))
			{
				if (!elem.value && elem.type && (/^(?:text(?:area)?|password|file)$/i.test(elem.type)))
				{
					alert('"' + element_names[elem.name] + '" <?php echo $lang_common['required field'] ?>');
					elem.focus();
					return false;
				}
			}
		}
	}
	return true;
}
/* ]]> */
</script>
<?php
}
?>
  </head>
  <body>
	<div id="global">
	<!--img id="grid" src="style/980grid-16col2.png" alt="" width="980" height="1600" /-->
		<div id="header">
		  <div id="desc">
			<h1><?php echo $pun_config['o_board_title']; ?></h1>
			<p id="sitedesc"><?php echo pun_htmlspecialchars(shorttext_lang($site_config['o_site_desc'],$lang,true)); ?></p>
			<p id="access"><a href="#content"><?php echo $lang_site['Skip link']; ?></a></p>
		  </div>
		<ul id="quickaccess">
		  <li id="home"><a href="index.php"><?php echo $lang_site['Home']; ?></a></li>
		  <?php if ($site_config['o_enable_res'] == '1'): ?><li id="resources"><a href="resources.php"><?php echo $lang_site['Resources']; ?></a></li><?php endif; ?>
		  <?php if ($site_config['o_enable_tuts'] == '1'): ?><li id="tutorials"><a href="tutorials.php"><?php echo $lang_site['Tutorials']; ?></a></li><?php endif; ?>
		  <li id="forum"><a href="fluxbb14/index.php"><?php echo $lang_site['Forum']; ?></a></li>
		</ul>
	  </div>
	  <div id="menu">
		<ul id="mainmenu">
		  <?php include('./includes/submenu.php'); ?>
		</ul>
	  </div>
<div id="corps">
	<?php include('./includes/sidebar.php'); ?>
	<div id="main"><a name="content" id="content"></a>
