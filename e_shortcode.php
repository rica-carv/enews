<?php
/*
* Copyright (c) e107 Inc e107.org, Licensed under GNU GPL (http://www.gnu.org/licenses/gpl.txt)
*
* Featurebox shortcode batch class - shortcodes available site-wide. ie. equivalent to multiple .sc files.
*/

if (!defined('e107_INIT')) { exit; }

//e107::lan('eforum');  // English_menu.php or {LANGUAGE}_menu.php
include_once(e_PLUGIN . "euser/includes/euser_trait.php");
////e107::includeLan(e_PLUGIN.'eforum/languages/'.e_LANGUAGE);


class enews_shortcodes extends e_shortcode// must match the plugin's folder name. ie. [PLUGIN_FOLDER]_shortcodes
{	
  use Euser_global_info;
	public $override = true; // when set to true, existing core/plugin shortcodes matching methods below will be overridden. 
/*
	protected $forumObj;
	protected $viewforum_sc;
*/
//protected $news_sc;
protected $tp;
protected $sql_enews;

	function __construct(){
		/*
			parent::__construct();
				$this->e107 = e107::getInstance();
		*/
/*			include_once(e_PLUGIN . "forum/forum_class.php");
			$this->forumObj = new e107forum();
*/

//			$this->viewforum_sc = e107::getScBatch('view', 'forum');   // Isto não funciona aqui, estoura....

//      $this->init();
		//    $this->scf = e107::getScBatch('forum');
			$this->tp = e107::getParser();
		//    $this->forumsc = e107::getScBatch('forum',TRUE);
		//    $this->menu['foruminfo'] = e107::getmenu()->isLoaded("foruminfo");
    $this->sql_enews = e107::getDb();

		// O breadcrumb passou para o e_parse do eforum
		  }
		

// ###########################################
// ##### NEWS PLUGIN OVERRIDE SHORTCODES #####
// ###########################################

function sc_news_body($parm=null)
    {
      $sc = e107::getScBatch('news');
//      $tp = e107::getParser();
      e107::getBB()->setClass("news"); // For automatic bbcode image resizing. 
    /*
      $action = isset($this->param['current_action']) ? $this->param['current_action'] : '';
    
      $news_body = '';
    
      if($parm != 'extended')
      {
        $news_body = $this->tp->toHTML($this->news_item['news_body'], true, 'BODY, fromadmin', $this->news_item['news_author']);
      }
      
      if($this->news_item['news_extended'] && (isset($_POST['preview']) || $action === 'extend') && ($parm !== 'noextend' && $parm !== 'body'))
      {
        $news_body .= $this->tp->toHTML($this->news_item['news_extended'], true, 'BODY, fromadmin', $this->news_item['news_author']);
      }
    */
    //  var_dump ($parm);
    
      $action = isset($sc->param['current_action']) ? $sc->param['current_action'] : '';
    
      $news_body = '';
    
      if(!$parm['extended'])
      {
//        $news_body = $this->tp->toHTML($sc->news_item['news_body'], true, 'BODY, fromadmin', $sc->news_item['news_author']);
        $news_body = $this->tp->toHTML($sc->news_item['news_body'], true, 'BODY, fromadmin', $sc->news_item['news_author']);
      }
      
      if($sc->news_item['news_extended'] && (isset($_POST['preview']) || $action === 'extend') && (!$parm['noextend'] && !$parm['body']))
      {
//        $news_body .= $this->tp->toHTML($sc->news_item['news_extended'], true, 'BODY, fromadmin', $sc->news_item['news_author']);
        $news_body .= $this->tp->toHTML($sc->news_item['news_extended'], true, 'BODY, fromadmin', $sc->news_item['news_author']);
      }
    
    //  var_dump ($news_body);
      e107::getBB()->clearClass();
    
//      return ($parm['limit']?$this->tp->text_truncate($news_body, $parm['limit']):$news_body);
      return ($parm['limit']?$this->tp->truncate($news_body, $parm['limit']):$news_body);
    }
    
    function sc_newsnavlink($parm=null) //TODO add more options.
    {
    //          $url = $this->sc_news_nav_url($parm);
    
      $url = e107::getUrl()->create('news/list/items'); // default for now.
    
      if(varset($parm['list']) == 'all') // A list of all items - usually headings and thumbnails
      {
        $url = e107::getUrl()->create('news/list/all');
      }
      elseif(varset($parm['list']) == 'category')
      {
        $url = e107::getUrl()->create('news/list/short', $this->news_item);  //default for now.
      }
      elseif(varset($parm['items']) == 'category')
      {
        $url = e107::getUrl()->create('news/list/category', $this->news_item);
      }
    
      $caption = vartrue($parm['text'], $this->tp->toGlyph('fa-backward').LAN_BACK);
      
        $text = '<a class="pager-button btn hidden-print align-self-center mb-0" href="'.$url.'">'.e107::getParser()->toHTML($caption,false,'defs').'</a>';
      
      return $text;
    }

    function sc_adminoptions($parm=array())
    {
//      $tp = e107::getParser();
      if (ADMIN && getperms('H') && (e_PAGE == 'news'))
        {

        $sc = e107::getScBatch('news');
        
        //TODO - discuss - a pref for 'new browser window' loading, or a parm or leave 'new browser window' as default?
        $default = (deftrue('BOOTSTRAP')) ? $this->tp->toGlyph('fa-edit',false) :  "<img src='".e_IMAGE_ABS."admin_images/edit_16.png' alt=\"".LAN_EDIT."\" class='icon' />";
  
        
        $adop_icon = (file_exists(THEME."images/newsedit.png") ? "<img src='".THEME_ABS."images/newsedit.png' alt=\"".LAN_EDIT."\" class='icon' />" : $default);
        
        $class = varset($parm['class'], 'btn btn-default btn-secondary');

/*
        echo "<pre>";
        var_dump ($this);
        echo "</pre>";
*/

///        return "<a class='e-tip ".$class." hidden-print' rel='external' href='".e_ADMIN_ABS."newspost.php?action=edit&amp;id=".$this->news_item['news_id']."' title=\"".LAN_EDIT."\">".$adop_icon."&nbsp;".LAN_EDIT."</a>\n";
        return "<a class='spt_mod-btn e-tip ".$class." hidden-print' rel='external' href='".e_ADMIN_ABS."newspost.php?action=edit&amp;id=".$sc->news_item['news_id']."' title=\"".LAN_EDIT."\">".$adop_icon."&nbsp;".LAN_EDIT."</a>\n";
        }
        else
        {
          return '';
        }
    }



function sc_printicon($parm=array())
	{
//		require_once(e_HANDLER.'emailprint_class.php');
//		return emailprint::render_emailprint('news', $this->news_item['news_id'], 2, $parm);
$sc = e107::getScBatch('news');
e107::coreLan('print');
//return "<a class='btn btn-default text-nowrap' role='button' href='" . e_HTTP . "print.php?news." . $sc->news_item['news_id'] . "'>" . BTN_print . "</a>";
return "<a class='btn btn-default text-nowrap' role='button' href='" . e_HTTP . "print.php?news." . $sc->news_item['news_id'] . "'>".$this->tp->toGlyph('fa-print').LAN_PRINT_307."</a>";
//  return "---------------------";
	}

/*
	static function render_emailprint($mode, $id, $look = 0, $parm=array())
	{
		// $look = 0  --->display all icons
		// $look = 1  --->display email icon only
		// $look = 2  --->display print icon only
		$tp = e107::getParser();

		$text_emailprint = "";

		//new method emailprint_class : (only news is core, rest is plugin: searched for e_emailprint.php which should hold $email and $print values)
		if($mode == "news")
		{
			$email = "news";
			$print = "news";
		}
		else
		{
			//load the others from plugins
			$handle = opendir(e_PLUGIN);
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != ".." && is_dir(e_PLUGIN.$file)) 
				{
					$plugin_handle = opendir(e_PLUGIN.$file."/");
					while (false !== ($file2 = readdir($plugin_handle))) 
					{
						if ($file2 == "e_emailprint.php") 
						{
							require_once(e_PLUGIN.$file."/".$file2);
						}
					}
				}
			}
		}
		

		
		if(deftrue('BOOTSTRAP'))
		{
			$genericMail = $tp->toGlyph('fa-envelope',false); // "<i class='icon-envelope'></i>";
			$genericPrint = $tp->toGlyph('fa-print',false); // "<i class='icon-print'></i>"; 
			$class = !empty($parm['class']) ? $parm['class'] : "btn btn-default btn-secondary";
		}
		else // BC
		{
			$genericMail = "<img src='".e_IMAGE_ABS."generic/email.png'  alt='".LAN_EMAIL_7."'  />";
			$genericPrint = "<img src='".e_IMAGE_ABS."generic/printer.png'  alt='".LAN_PRINT_1."'  />";	
			$class = "";
		}
		

		if ($look == 0 || $look == 1) 
		{
			$ico_mail = (defined("ICONMAIL") && file_exists(THEME."images/".ICONMAIL) ? "<img src='".THEME_ABS."images/".ICONMAIL."'  alt='".LAN_EMAIL_7."'  />" : $genericMail);
			//TDOD CSS class
			$text_emailprint .= "<a class='e-tip hidden-print ".$class."' href='".e_HTTP."email.php?".$email.".".$id."' title='".LAN_EMAIL_7."'>".$ico_mail."</a> ";
		}
		if ($look == 0 || $look == 2) 
		{
			$ico_print = (defined("ICONPRINT") && file_exists(THEME."images/".ICONPRINT) ? "<img src='".THEME_ABS."images/".ICONPRINT."' alt='".LAN_PRINT_1."'  />" : $genericPrint);
			//TODO CSS class
			$text_emailprint .= "<a rel='alternate' class='e-tip ".$class." hidden-print' href='".e_HTTP."print.php?".$print.".".$id."' title='".LAN_PRINT_1."'>".$ico_print."</a>";
		}
		return $text_emailprint;
	}
*/

function sc_news_author($parms=null)
{
  $sc = e107::getScBatch('news');

//  var_dump ($parms);
//  var_dump ($sc->news_item['user_name']);
//  var_dump ($sc->news_item['user_id']);
  if(!empty($sc->news_item['user_id']))
  {
//    var_dump (isset($parms['nolink']));
//    var_dump (isset($parms['link']));
    //    return ($parms['nolink'])?$sc->news_item['user_name']:(($parms['link'])?e107::getUrl()->create('user/profile/view', $sc->news_item):"<a href='".e107::getUrl()->create('user/profile/view', $sc->news_item)."'>".$sc->news_item['user_name']."</a>");
    $link=e107::getUrl()->create('user/profile/view', $sc->news_item);
    $user_name=$sc->news_item['user_name'];
    $text=$parms['link']==-1?$user_name:($parms['link']==1?$link:"<a href='{$link}' class='e-tip'>{$user_name}</a>");

    return $text;
  }
//  return "<a href='https://e107.org'>e107</a>";
  return false;
}

// ########## END OF REWRITEN ORIGINAL NEWS SHORTCODES ##############
// ####################################
// ##### PLUGIN GLOBAL SHORTCODES #####
// ####################################

// Antigo, fica aqui por referencia. Não é usado. O news no core também não tem este shortcode....
function sc_enews_author_count($parms=null)
{
/*
  echo "<pre>";
  var_dump (e_PAGE);
//  var_dump (strpos(e_PAGE, "forum"));
  var_dump (strpos(e_PAGE, "forum") !== false);
  echo "</pre>";
*/
/*
echo "<pre>";
var_dump ($this->userinfo());
echo "</pre>";
*/
/*
  if (strpos(e_PAGE, "forum") !== false) {
    $sc = e107::getScBatch('view', 'forum');
    $uid = $sc->var['post_user'];
  } else {
    $sc = e107::getScBatch('news');
    $uid = $sc->news_item['news_author'];
  }
  */
  $uid=$this->userinfo();
  /*
  echo "<pre>";
  var_dump ($uid);
//  var_dump (strpos(e_PAGE, "forum"));
  echo "</pre>";
  echo "<hr><hr><hr>";
 */
    /*
  echo "<pre>";
  var_dump ($sc->news_item);
  echo "</pre>";
*/
//  $sql = e107::getDb();
/*
if(empty($uid = $sc->news_item['news_author'])){
  $sc = e107::getScBatch('view', 'forum');
  echo "<pre>";
  var_dump ($uid = $sc->var['post_user']);
  echo "</pre>";
  }
*/
/*
  if(!empty($nuid = $sc->news_item['user_id']))
  {
    $query = "SELECT n.news_author, COUNT(n.news_id) AS totalnews FROM #news AS n
    WHERE n.news_author = ".$nuid;

    if ($sql->gen($query))
    {
      while ($row = $sql->fetch()) 
      {
        return $row['totalnews'];
      }		
    }
  }
*/
/*
echo "<pre>";
var_dump ($sc);
echo "</pre>";
*/
  if(!empty($uid))
  {
    $row = $this->sql_enews->retrieve("SELECT n.news_author, COUNT(n.news_id) AS totalnews FROM #news AS n
    WHERE n.news_author = ".$uid);
/*
echo "<pre>";
var_dump (empty($row['totalnews']));
echo "</pre>";
*/
    return empty($row['totalnews'])?null:$row['totalnews'];
  }
}

}