<?php
	/**
	 * e107 website system
	 *
	 * Copyright (C) 2008-2017 e107 Inc (e107.org)
	 * Released under the terms and conditions of the
	 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
	 *
	 */
	if (!defined('e107_INIT')) { exit; }
/*
	$cacheString = 'nq_news_categories_menu_'.md5(serialize($parm).USERCLASS_LIST.e_LANGUAGE);
	$cached = e107::getCache()->retrieve($cacheString);
	if(false === $cached)
	{
		e107::plugLan('news');
	
		if(is_string($parm))
		{
			parse_str($parm, $parms);
		}
		else
		{
			$parms = $parm;
		}
*/	
		/** @var e_news_category_tree $ctree */
/*
		$ctree = e107::getObject('e_news_category_tree', null, e_HANDLER.'news_class.php');
	
		$parms['tmpl']      = 'news_menu';
		$parms['tmpl_key']  = 'category';
	
		$template = e107::getTemplate('news', $parms['tmpl'], $parms['tmpl_key'], true, true);
	
		$cached = $ctree->loadActive()->render($template, $parms, true);
	
		e107::getCache()->set($cacheString, $cached);
	}
	
	echo $cached;
*/
	
    $news   = e107::getObject('e_news_category_tree');  // get news class.
    $sc     = e107::getScBatch('news'); // get news shortcodes.
    $tp     = e107::getParser(); // get parser.

    // load active news categories. ie. the correct userclass etc.
    $data = $news->loadActive(false)->toArray();  // false to utilize the built-in cache.

	$parms['tmpl']      = 'news_menu';
	$parms['tmpl_key']  = 'category';

	$template = e107::getTemplate('news', $parms['tmpl'], $parms['tmpl_key'], true, true);

//	$TEMPLATE = "<li>{NEWS_CATEGORY_NAME: link=1}</li>";

    $text = $tp->parseTemplate($template['start'], true, $sc);;

    foreach($data as $row){
      $sc->setScVar('news_item', $row); // send $row values to shortcodes.
      $text .= $tp->parseTemplate($template['item'], true, $sc); // parse news shortcodes.
    }
    $text = $tp->parseTemplate($template['end'], true, $sc);;
    return $text;
