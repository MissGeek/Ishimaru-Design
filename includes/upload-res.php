<?php
//Fichier pour le traitement des images
//On vérifie si un fichier a été envoyé

//	$img_url = isset($oldfile) ? $oldfile : '';
//	$img_values = array();
	$up_errors = array(); //Initialisation des erreurs

	//$img_url = $oldfile;
	//$img_query = "img_url='".$img_url."'";
	if(isset($_FILES[$icon]))
	{
		//On vérifie si une nouvelle image a été envoyée
		if($_FILES[$icon]['error'] > 0)
			site_msg($lang_site['Admin bad transfer'].'-1-'.$icon);
		else
		{
			//Un vérifie sa taille
			if($_FILES[$icon]['size'] > $size)
				$up_errors[] = $lang_site['Admin file too big'].'-2';

			$extensions_valide = array('jpg','jpeg','gif','png');
//			$allowed_types = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png');
			$extension_upload = strtolower(substr(strrchr($_FILES[$icon]['name'], '.'),1));

			if(!in_array($extension_upload,$extensions_valide)/* || !in_array($new_file['icon'],$allowed_types)*/)
				$up_errors[] = $lang_site['Admin bad extension'].'-3';

			if(!file_exists('./img/'.$folder) || !file_exists('./img/'.$folder.'/thumbs'))
				$up_errors[] = sprintf($lang_site['Admin no folder'],$folder);
			elseif(!is_writable('./img/'.$folder) || !is_writable('./img/'.$folder.'/thumbs'))
				$up_errors[] = $lang_site['Admin bad chmod'].'-4';

			if(count($up_errors) == 0)
			{
				//Tous les critères sont corrects, donc on continue le traitement
				$nom = rename_file($folder,$_FILES[$icon]['name']);
				$resultat = move_uploaded_file($_FILES[$icon]['tmp_name'],$nom);
				echo $nom;
//				echo '<img src="'.$nom.'" />';
				if($resultat)
				{
//					message($lang_site['Adm_transfer_success'].'<br />'.$nom);
//					$img_url = get_image_link($nom,''); //Pour la requête SQL
					$img_url = $nom;
//					echo $img_url;
//					$img_values[$field] = $db->escape($img_url);
					//Ça s'est déroulé avec succès, donc on crée les miniatures.  Attachez vos tuques, cette prodédures est très lourde !
/*					if($thumb == true)
					{*/
//						$source = create_thumb($nom);
//						$source = create_thumb($img_url);
						$src = create_thumb($nom);
			
						//Petite miniature, pour la galerie
						$l_src = imagesx($src);
//						echo $largeur_source.'-célarge';
						$h_src = imagesy($src);
//						echo $h_src.'-céhaut';
						$dest = new_thumb($l_src,$h_src,'res');
//						$new_height = round(($h_src*200)/$l_src);
//						if(imageistruecolor($nom))
//							$dest = imagecreatetruecolor(200,$new_height);
//						else
//							$dest = imagecreate(200,$new_height);
						$l_dest = imagesx($dest);
						$h_dest = round(($l_dest*$h_src)/$l_src);
						//On crée l'image
						imagecopyresampled($dest, $src, 0, 0, 0, 0, $l_dest, $h_dest, $l_src, $h_src);
						//Enregistrement de la miniature
						save_thumb($dest,$nom,$folder);
						//Récupération de l'url de la miniature pour le stocker dans la base de données
						$img_url_mini = get_image_link($nom,'mini');
			
						//On regroupe les variables des urls pour la requête
//						$img_values[] = $db->escape($img_url_mini);
						if(isset($oldfile))
						{
	 						//c'est une édition, donc on update
							remove_file($oldfile,$folder);
							$db->query('UPDATE res_screens SET rscreen_legend=\''.$db->escape($legend).'\',rscreen_url_full=\''.$db->escape($img_url).'\',rscreen_url_small=\''.$db->escape($img_url_mini).'\' WHERE rscreen_id='.$edit_screen) or error('Unable to update screenshot', __FILE__, __LINE__, $db->error());
						}
						else
						{
							$db->query('INSERT INTO res_screens(rscreen_id,rscreen_legend,rscreen_url_full,rscreen_url_small,rscreen_entryid) VALUES(\'\',\''.$db->escape($legend).'\',\''.$db->escape($img_url).'\',\''.$db->escape($img_url_mini).'\','.$cur_id.')') or error('Unable to add screenshot data', __FILE__, __LINE__, $db->error());
						}
//					}
				}
				else
				{
//					$img_url = $oldfile;
//					$img_values = '';
					$list_errors = $lang_site['Admin error while uploading'].'<br />';
					foreach($up_errors as $error)
					{
						$list_errors .= $error.'<br />';
					}
					site_msg($list_errors);
//					exit(print_r($up_errors));
				}
			}
			else
			{
//				$img_url = $oldfile;
//				$img_values = '';
				$list_errors = $lang_site['Admin upload failed'].'<br />';
				foreach($up_errors as $error)
				{
					$list_errors .= $error.'<br />';
				}
				site_msg($list_errors);
			}
		}
	}
/*	else
	{
		//Rien n'a été envoyé.  On n'affiche le message d'erreur que pour les ajouts d'entrée
//		$img_url = $oldfile;
//		$img_values = '';
		site_msg($lang_site['Admin no pic']);
	}*/
?>
