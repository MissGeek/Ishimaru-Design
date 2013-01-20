<?php
//Fichier pour le traitement des images pour les catégories
//On vérifie si un fichier a été envoyé

$img_url = isset($oldfile) ? $oldfile : '';
$img_value = '';
$up_errors = array(); //Initialisation des erreurs

if(isset($_FILES[$icon]))
{
	//On vérifie si une nouvelle image a été envoyée
//	if($_FILES[$icon]['error'] > 0)
//		site_msg($lang_site['Admin bad transfer'].'rrrwaw');
//	else
	if($_FILES[$icon]['error'] == 0)
	{
		//Un vérifie sa taille
		if($_FILES[$icon]['size'] > $size)
			$up_errors[] = $lang_site['Admin file too big'];

		$extensions_valide = array('jpg','jpeg','gif','png');
//		$allowed_types = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png');
		$extension_upload = strtolower(substr(strrchr($_FILES[$icon]['name'], '.'),1));

		if(!in_array($extension_upload,$extensions_valide)/* || !in_array($new_file['icon'],$allowed_types)*/)
			$up_errors[] = $lang_site['Admin bad extension'];

		if(!file_exists('./img/'.$folder))
			$up_errors[] = sprintf($lang_site['Admin no folder'],$folder);
		elseif(!is_writable('./img/'.$folder))
			$up_errors[] = $lang_site['Admin bad chmod'];

		if(count($up_errors) == 0)
		{
			//Tous les critères sont corrects, donc on continue le traitement
			$nom = rename_file($folder,$_FILES[$icon]['name']);
			$resultat = @move_uploaded_file($_FILES[$icon]['tmp_name'],$nom);
			if($resultat)
			{
				$img_value = $nom;
				if($folder == 'tut')
				{
					list($width,$height,$imgtype,$attr) = getimagesize($nom);
					if($width > 150 || $height > 150)
					{
						$src = create_thumb($nom);
	
						//Petite miniature, pour la galerie
						$l_src = imagesx($src);
						$h_src = imagesy($src);
						$dest = new_thumb($l_src,$h_src,'tut');
						$l_dest = imagesx($src);
						$h_dest = imagesy($src);
						//On crée l'image
						imagecopyresampled($dest, $src, 0, 0, 0, 0, $l_dest, $h_dest, $l_src, $h_src);
						//Enregistrement de la miniature
						save_thumb($dest,$nom,'tut');
						//Récupération de l'url de la miniature pour le stocker dans la base de données
					}
				}
			}
			else
			{
				$img_url = $oldfile;
				$img_value = '';
				$list_errors = $lang_site['Admin error while uploading'].'<br />';
				foreach($up_errors as $error)
				{
					$list_errors .= $error.'<br />';
				}
				exit(print_r($up_errors));
			}
		}
		else
		{
			$img_url = $oldfile;
			$img_value = '';
			$list_errors = $lang_site['Admin upload failed'].'<br />';
			foreach($up_errors as $error)
			{
				$list_errors .= $error.'<br />';
			}
			site_msg($list_errors);
		}
	}
}
/*else
{
	//Rien n'a été envoyé.  On n'affiche le message d'erreur que pour les ajouts d'entrée
	$img_url = $oldfile;
	$img_value = '';
	$img_error = 'pwet'.$lang_site['Admin no pic'];
	if(empty($oldfile))
		site_msg($img_error);
}*/
?>
