<?php

//Librairie dédiée à la gestion du multi-langue

/*
 Dans le footer, on a affaire à un array multi-niveaux
 Préparons-le pour le stockage
*/
/*function prepare_footer($str,$lang)
{
	$text = array();
	$elem = explode("\n\n\n",$str);
	foreach($elem as $value) // fr, en
	{
		$lang = substr($elem,1,2);
		$elem = substr(substr($elem,4),0,-1);
		$lists = explode("\n\n",$str);
		$nb_list = 0;
		foreach($lists as $list) // les listes
		{
			$items = explode"\n",$list);
			$nb_link = 0;
			foreach($items as $links) // les liens
			{
				$chunks = explode('|',$link);
				$chunks[0] = substr($chunks[0],1);
				$chunks[1] = substr($chunks[1],0,-1);
				foreach($chunks as $part) // les bouts de liens
				{
					$text[$lang][$nb_list][$nb_link]['url'] = $chunk[0];
					$text[$lang][$nb_list][$nb_link]['text'] = $chunk[1];
				}
				$nb_link++;
			}
			$nb_list++;
		}
	}
	return serialize($text);
}

function parse_footer($str,$lang)
{
	$array = unserialize($str);
	$toprocess = $array[$lang];
	$text = '';
	foreach($toprocess as $lists)
	{
		$count = 0;
		$text .= '<ul>';
		foreach($lists as $links)
		{
			$text .= '<li><a href="'.$links['url'].'">'.$links['text'].'</a></li>';
		}
		$text .= '</ul>';
	}
}*/

/*
 Function for footer links management
 Using Wiki-like codes for links will be much simpler than using HTML
 The number of consecutive linebreaks determines if we're ending a list or not
*/
function footer_links($str,$lang)
{
	$elem = explode("\n\n\n",$str);
	foreach($elem as $value){
		if(preg_match('#\['.$lang.'\]#',$value)){
			$value = substr(trim($value),4);
			$lists = explode("\n\n",$value);
			$block = '';
			foreach($lists as $list)
			{
				$block .= '<ul>';
				$items = explode("\n",$list);
				foreach($items as $link)
				{
					$block .= '<li>';
					$link = str_replace('{','<a href="',$link);
					$link = str_replace('|','">',$link);
					$link = str_replace('}','</a>',$link);
					$block .= $link;
					$block .= '</li>';
				}
				$block .= '</ul>';
			}
		}
	}
	return $block;
}

/*
 Function for site description, category name or any short texts
*/
function shorttext_lang($key,$lang,$delimiter=false)
{
	//Delimiter is true only if we're processing data entered by a textarea.
	$delimiter = $delimiter==true ? "\n" : '||';
 	$item = explode($delimiter,$key);
	$str = array();
	foreach($item as $value)
	{
		$value = substr(substr(trim($value),0,-1),1); //enlève le premier et le dernier caractère
		$elem = explode('|',$value);
		$str[$elem[0]] = $elem[1];
	}
	if(!array_key_exists($lang,$str))
	{
		if($lang == 'en')
			return $str['fr'];
		else
			return $str['en'];
//		error('The requested key '.$name.' does not exists for the language '.$lang.' !', __FILE__, __LINE__);
	}
	else return $str[$lang];
}

/*
 Function for text blocks that use BBCodes
 Pour faciliter la cohabitation, on va sérialiser
*/
/*function prepare_longtext($text)
{
	$desclang = explode('}'."\n\n".'{',$text);
	$desclang[0] = substr($desclang[0],1);
	$last = count($desclang) - 1;
	$desclang[$last] = substr($desclang[$last],0,1);
	$keys = array();
	foreach($desclang as $value)
	{
		$chunks = explode('|',$value);
		$keys[$chunks[0] = $chunks[1];
	}
	return serialize($keys);
}*/

function longtext_lang($str,$lang)
{
	$str = substr(substr(trim($str),0,-1),1);
	$desclang = explode('}'."\n\n".'{',$str);
//	$desclang[0] = substr(trim($desclang[0]),1);
//	$last = count($desclang) - 1;
//	$desclang[$last] = substr(trim($desclang[$last]),0,-1);
	$keys = array();
	foreach($desclang as $value)
	{
		$chunks = explode('|',$value);
		if($chunks[0] == $lang)
			return $chunks[1];
	}
}

/*
 Instead of using one entry for each language, everything is stored in one entry in config table
 This function retrieves the corresponding data and parses it to generate a definition list
*/
function intro_module($text,$lang)
{
	$lists = explode("\n\n",$text);
	$block = '<dl>';
	foreach($lists as $value)
	{
		if(preg_match('#\['.$lang.'\|#',$value))
		{
			$value = substr(substr(trim($value),0,-1),4);
			$items = explode("\n",$value);
			foreach($items as $str)
			{
				$str = str_replace('{','<dt>',$str);
				$str = str_replace('|','</dt><dd>',$str);
				$str = str_replace('}','</dd>',$str);
				$block .= $str;
			}
		}
	}
	$block .= '</dl>';
	return $block;
}

/*
 For multilang support, news forum ID for each language are stored in a same entry.
 This function retrieves the one corresponding to the current language
*/
function forum_news($str,$lang)
{
	$items = explode('||',$str);
	foreach($items as $value)
	{
		if(preg_match('#\{'.$lang.'\|#',$value))
		{
			$id = substr(substr(trim($value),0,-1),4);
		}
	}
	return $id;
}

function social_links($str,$lang)
{
	global $db, $lang_site, $pun_config, $site_config;

	$elem = explode("\n\n",$str);
	foreach($elem as $value)
	{
		if(preg_match('#\['.$lang.'\|#',$value))
		{
			$value = substr(substr(trim($value),0,-1),4);

			$count = 0;
			$block = '<p>';
			$items = explode("\n",$value);
			foreach($items as $link)
			{
				++$count;
				$link = substr(substr(trim($link),0,-1),1);
				$chunks = explode('|',$link);
				$block .= '<a href="'.$chunks[0].'"><img src="'.$chunks[1].'" alt="'.$lang_site['Follow on'].$chunks[2].'" /></a>';
				if($count < count($items))
					$block .= '&nbsp;';
			}
			$block .= '</p>';
		}
	}
	return $block;	
}
