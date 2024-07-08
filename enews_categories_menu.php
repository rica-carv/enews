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
	
    $news   = e107::getObject('e_news_category_tree');  // get news class.
    $sc     = e107::getScBatch('news'); // get news shortcodes.
    $tp     = e107::getParser(); // get parser.
//    var_dump($sc);

    // load active news categories. ie. the correct userclass etc.
    $data = $news->loadActive(false)->toArray();  // false to utilize the built-in cache.

	$parms['tmpl']      = 'news_menu';
	$parms['tmpl_key']  = 'category';

	$template = e107::getTemplate('news', $parms['tmpl'], $parms['tmpl_key'], true, true);

//	$TEMPLATE = "<li>{NEWS_CATEGORY_NAME: link=1}</li>";

//    $text = $tp->toText(LAN_NEWSCAT_MENU_TITLE);
    $text = $tp->parseTemplate($template['start'], true, $sc);;

    foreach($data as $row){
//      var_dump($row);
      $sc->setScVar('news_item', $row); // send $row values to shortcodes.
      $text .= $tp->parseTemplate($template['item'], true, $sc); // parse news shortcodes.
    }
    $text .= $tp->parseTemplate($template['end'], true, $sc);;

    echo e107::getRender()->tablerender(LAN_NEWSCAT_MENU_TITLE, $text, 'default', true);

//    echo $text;