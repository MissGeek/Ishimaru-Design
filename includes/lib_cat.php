<?php
/*
*
* Functions for categories sections
*
*/

/*Function to prune subcategories and its content*/
function prune_res_subcat($subcatid)
{
	global $db;

	// Fetch resources to prune
	$r = $db->query('SELECT rentry_id FROM res_entries WHERE rentry_subcatid='.$subcatid) or error('Unable to fetch topics', __FILE__, __LINE__, $db->error());

	if($db->num_rows($r) > 0)
	{
		$res_ids = '';
		while ($resrow = $db->fetch_assoc($r))
		{
			$res_ids .= (($res_ids != '') ? ',' : '').$resrow['rentry_id'];

			if(file_exists('./img/res-'.$resrow['rentry_id']))
			{
				clear_dir('./img/res-'.$resrow['rentry_id']);
			}
		}
		if ($res_ids != '')
		{
			// Fetch screenshots to prune
			$img = $db->query('SELECT rscreen_id,rscreen_url_full FROM res_screens WHERE rscreen_entryid IN('.$res_ids.')') or error('Unable to fetch posts', __FILE__, __LINE__, $db->error());

			$screen_ids = '';
			while ($imgrow = $db->fetch_assoc($img))
			{
				$screen_ids .= (($screen_ids != '') ? ',' : '').$imgrow['rscreen_id'];
			}

			if ($screen_ids != '')
			{
				// Delete resources
				$db->query('DELETE FROM res_entries WHERE rentry_id IN('.$res_ids.')') or error('Unable to prune resources', __FILE__, __LINE__, $db->error());

				// Delete screenshots
				$db->query('DELETE FROM res_screens WHERE rscreen_id IN('.$screen_ids.')') or error('Unable to prune screenshots', __FILE__, __LINE__, $db->error());
			}
			else //Some resources might have no screenshot
			{
				// Delete resources
				$db->query('DELETE FROM res_entries WHERE rentry_id IN('.$res_ids.')') or error('Unable to prune resources', __FILE__, __LINE__, $db->error());
			}
		}
	}
}

/*Versions pruning*/
function prune_versions($catid)
{
	global $db;

	$ver_res = $db->query('SELECT version_id FROM tuts_versions WHERE version_cat='.$catid) or error('Unable to fetch versions', __FILE__, __LINE__, $db->error());

	$ver_ids = '';
	while ($verrow = $db->fetch_row($ver_res))
		$ver_ids .= (($ver_ids != '') ? ',' : '').$verrow[0];

	if ($ver_ids != '')
	{
		$db->query('DELETE FROM tuts_versions WHERE version_id IN('.$ver_ids.')') or error('Unable to prune versions', __FILE__, __LINE__, $db->error());
	}
}

/*Tutorials pruning*/
function prune_tutorials($id,$type)
{
	global $db;
	// Fetch tutorials to prune
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
		$txt = $db->query('SELECT text_id, text_name FROM tuts_texts WHERE text_entryid IN('.$tut_ids.')') or error('Unable to get tutorial parts', __FILE__, __LINE__, $db->error());
		
		$part_ids = '';
		while ($row = $db->fetch_assoc($txt))
			$part_ids .= (($part_ids != '') ? ',' : '').$row['text_id'];

		if ($part_ids != '')
		{
			//Delete tutorial parts
			$db->query('DELETE FROM tuts_texts WHERE text_id IN('.$part_ids.')') or error('Unable to prune tutorial parts', __FILE__, __LINE__, $db->error());
		}
		
		$t = $db->query('SELECT tentry_icon FROM tuts_entries WHERE tentry_id IN('.$tut_ids.')') or error('Unable to get tutorial icon', __FILE__, __LINE__, $db->error());
		while($icon = $db->fetch_assoc($t))
		{
			remove_file($icon['tentry_icon'],'tut');
		}

		// Delete tutorials
		$db->query('DELETE FROM tuts_entries WHERE tentry_id IN('.$tut_ids.')') or error('Unable to prune tutorials', __FILE__, __LINE__, $db->error());
	}
}

