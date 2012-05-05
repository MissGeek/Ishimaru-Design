<?php
/*
*
* Fonctions pour la section des catégories
*
*/

/*function get_res_lang($id)
{
	global $db;
	$result = $db->query('SELECT rentry_publish FROM res_entries WHERE rentry_id=\''.$id.'\'');
	$lang = $db->fetch_row($result);
	return $lang['rentry_publish'];
}*/

function prune_res_subcat($subcatid)
{
	global $db;

//	$extra_sql = ($prune_date != -1) ? ' AND last_post<'.$prune_date : '';

//	if (!$prune_sticky)
//		$extra_sql .= ' AND sticky=\'0\'';

	// Fetch topics to prune
	$result = $db->query('SELECT rentry_id FROM res_entries WHERE rentry_subcatid='.$subcatid) or error('Unable to fetch topics', __FILE__, __LINE__, $db->error());

	$res_ids = '';
	while ($row = $db->fetch_row($result))
		$res_ids .= (($res_ids != '') ? ',' : '').$row[0];


	if ($res_ids != '')
	{
		// Fetch screenshots to prune
		$result = $db->query('SELECT rscreen_id,rscreen_url_full FROM res_screens WHERE rscreen_entryid IN('.$res_ids.')', true) or error('Unable to fetch posts', __FILE__, __LINE__, $db->error());

		$screen_ids = '';
		$img_te_delete = array();
		while ($row = $db->fetch_assoc($result))
		{
			$screen_ids .= (($screen_ids != '') ? ',' : '').$row['rscreen_id'];
			$img_to_delete[] = $row['rscreen_url_full'];
		}

		if ($screen_ids != '')
		{
			//on supprime les images avant les requêtes, car sinon on ne pourra plus récupérer les URLs relatifs
			foreach($img_to_delete as $del_file)
			{
				remove_file($del_file,'res');
			}
			// Delete topics
			$db->query('DELETE FROM res_entries WHERE rentry_id IN('.$res_ids.')') or error('Unable to prune resources', __FILE__, __LINE__, $db->error());

			// Delete posts
			$db->query('DELETE FROM res_screens WHERE rscreen_id IN('.$screen_ids.')') or error('Unable to prune screenshots', __FILE__, __LINE__, $db->error());
		}
	}
}


function prune_versions($catid)
{
	global $db;

	$ver_res = $db->query('SELECT version_id FROM tuts_versions WHERE version_cat='.$catid) or error('Unable to fetch versions', __FILE__, __LINE__, $db->error());

	$ver_ids = '';
	while ($verrow = $db->fetch_row($ver_res))
		$ver_ids .= (($ver_ids != '') ? ',' : '').$verrow[0];

	if ($ver_ids != '')
	{
		$db->query('DELETE FROM tuts_versions WHERE version_id IN('.$ver_ids.')') or error('Unable to prune versions', __FILE__, __LINE__, $db->error());
	}
}

function prune_tutorials($id,$type)
{
	global $db;
	// Fetch topics to prune
	if($type == 'cat')	
		$sql = 'SELECT tentry_id FROM tuts_entries WHERE tentry_catid='.$id;
	elseif($type == 'ver')
		$sql = 'SELECT tentry_id FROM tuts_entries WHERE tentry_version='.$id;

	$tut_res = $db->query($sql) or error('Unable to fetch tutorials', __FILE__, __LINE__, $db->error());

	$tut_ids = '';
	while ($tutrow = $db->fetch_row($tut_res))
		$tut_ids .= (($tut_ids != '') ? ',' : '').$tutrow[0];

	if ($tut_ids != '')
	{
		$t = $db->query('SELECT tentry_icon FROM tuts_entries WHERE tentry_id IN('.$tut_ids.')');
		while($icon = $db->fetch_row($t))
			remove_file($icon,'tuts');

		// Delete topics
		$db->query('DELETE FROM tuts_entries WHERE tentry_id IN('.$res_ids.')') or error('Unable to prune resources', __FILE__, __LINE__, $db->error());

	}
}
