<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/news/users_news_menu.php,v $
 * $Revision$
 * $Date$
 * $Author$
 */

if (!defined('e107_INIT')) { exit; }
if (!e107::isInstalled('news'))
{
	return;
}
// Load Data
/*
if($cacheData = e107::getCache()->retrieve("nq_usersnews"))
{
	echo $cacheData;
	return;
}
*/
require_once(e_HANDLER."news_class.php");
unset($text);
/////global $usersnews_STYLE;
$ix = new news;

//$caption = TD_MENU_L1;

if(!empty($parm))
{
	if(is_string($parm))
	{
		parse_str($parm, $parms);
	}
	else
	{
		$parms = $parm;
	}
}

e107::css('enews', 'usersnews_menu.css');

e107::plugLan('enews', null);
//e107::css('news', 'news_carousel.css');
//if(!$usersnews_STYLE)
//{
/*
	if(THEME_LEGACY !== true) // v2.x
	{
*/
/*
		if(!defined("usersnews_COLS"))
		{
			define("usersnews_COLS",false);
		}
*/
		$template = e107::getTemplate('enews', 'enews_menu', 'users', true, true);
//		var_dump ($template);
/*		
		$item_selector = '<div class="btn-group pull-right float-right float-end"><a class="btn btn-mini btn-sm btn-xs btn-default btn-secondary" href="#usersnews" data-slide="prev" data-bs-slide="prev">‹</a>  
 		<a class="btn btn-sm btn-mini btn-xs btn-default btn-secondary" href="#usersnews" data-slide="next" data-bs-slide="next">›</a></div>';
*/
/*
		if(!empty($parms['caption']))
		{
			$template['caption'] =  e107::getParser()->toHTML($parms['caption'],true,'TITLE');
		}

		$caption = "<div class='inline-text'>".$template['caption']." ".$item_selector."</div>";		
*/				
		$usersnews_STYLE = $template['item']; 
/*
	}
	else //v1.x
	{

		if(!empty($parms['caption']))
		{
			$caption =  e107::getParser()->toHTML($parms['caption'], true,'TITLE');
		}
			
		$template['start'] = '';
		$template['end'] = '';	
			
		$usersnews_STYLE = "
		<div style='padding:3px;width:100%'>
		<table style='border-bottom:1px solid black;width:100%' cellpadding='0' cellspacing='0'>
		<tr>
		<td style='vertical-align:top;padding:3px;width:20px'>
		{NEWSCATICON}
		</td><td style='text-align:left;padding:3px;vertical-align:top'>
		{NEWSTITLELINK}
		</td></tr></table>
		</div>\n";
	 
	}
*/	

//}


if(!defined("usersnews_LIMIT")){
	define("usersnews_LIMIT",3);
}
/*
if(!defined("usersnews_ITEMLINK")){
	define("usersnews_ITEMLINK","");
}

if(!defined("usersnews_CATLINK")){
	define("usersnews_CATLINK","");
}
if(!defined("usersnews_THUMB")){
	define("usersnews_THUMB","border:0px");
}
if(!defined("usersnews_CATICON")){
	define("usersnews_CATICON","border:0px");
}

if(!defined("usersnews_COLS")){
	define("usersnews_COLS","1");
}

if(!defined("usersnews_CELL")){
	define("usersnews_CELL","padding:0px;vertical-align:top");
}

if(!defined("usersnews_SPACING")){
	define("usersnews_SPACING","0");
}
*/
if(!isset($param))
{
	$param = array();
}
/*
$param['itemlink'] 		= defset('usersnews_ITEMLINK');
$param['thumbnail'] 	= defset('usersnews_THUMB');
$param['catlink'] 		= defset('usersnews_CATLINK');
$param['caticon'] 		= defset('usersnews_CATICON');
$param['template_key']  = 'news_menu/other/item';
*/
/*
$style 					= defset('usersnews_CELL');
$nbr_cols 				= defset('usersnews_COLS');

$_t = time();
*/
/*
$query = "SELECT n.*, u.user_id, u.user_name, u.user_customtitle, nc.category_id, nc.category_name, nc.category_sef, nc.category_icon FROM #news AS n
LEFT JOIN #user AS u ON n.news_author = u.user_id
LEFT JOIN #news_category AS nc ON n.news_category = nc.category_id
WHERE n.news_class IN (".USERCLASS_LIST.") AND n.news_start < ".$_t." AND (n.news_end=0 || n.news_end>".$_t.") AND FIND_IN_SET(2, n.news_render_type)  ORDER BY n.news_datestamp DESC LIMIT 0,".usersnews_LIMIT;
*/
$query = "SELECT u.user_id, u.user_name, u.user_customtitle, r.*, n.* 
    FROM #user AS u 
    LEFT JOIN (SELECT n.news_author, COUNT(n.news_id) AS totalnews, MAX(n.news_id) as latestnews FROM #news AS n
      GROUP BY n.news_author) AS r ON u.user_id = news_author
      LEFT JOIN #news as n ON r.latestnews = n.news_id
      WHERE n.news_id <> '' AND n.news_class IN (".USERCLASS_LIST.")
	  ORDER BY n.news_datestamp DESC LIMIT 0,".usersnews_LIMIT;

if ($sql->gen($query))
{
	$text = $tp->parseTemplate($template['start'],true);
/*		
	if(usersnews_COLS !== false)
	{
		$text .= "<table style='width:100%' cellpadding='0' cellspacing='".usersnews_SPACING."'>";
		$t = 0;		
		
		$wid = floor(100/$nbr_cols);
		while ($row = $sql->fetch()) 
		{
			$text .= ($t % $nbr_cols == 0) ? "<tr>" : "";
			$text .= "\n<td style='$style ; width:$wid%;'>\n";
			$text .= $ix->render_newsitem($row, 'return', '', $usersnews_STYLE, $param);
	
			$text .= "\n</td>\n";
			if (($t+1) % $nbr_cols == 0) {
				$text .= "</tr>";
				$t++;
			}
			else {
				$t++;
			}
		}
	
		while ($t % $nbr_cols != 0)
		{
			$text .= "<td style='width:$wid'>&nbsp;</td>\n";
			$text .= (($t+1) % $nbr_cols == 0) ? "</tr>" : "";
			$t++;
	
		}
		
		$text .= "</table>";		
	}
	else // perfect for divs. 
	{
*/
//		$active = 'active';		
		while ($row = $sql->fetch()) 
		{
//			var_dump ($template);
//			var_dump ($usersnews_STYLE);
//			var_dump ($row);
//			$active = ($loop == 0) ? 'active' : '';		
			$TMPL = str_replace("{NEWS_AUTHOR_COUNT}", $row['totalnews'], $usersnews_STYLE);	
			$text .= $ix->render_newsitem($row, 'return', '', $TMPL, $param);
//			$active = null;
		}				
//	}

	$text .= $tp->parseTemplate($template['end'], true);

	// Save Data
	ob_start();

	$ns->tablerender($caption, $text, 'users_news');

	$cache_data = ob_get_flush();
	e107::getCache()->set("nq_usersnews", $cache_data);
}