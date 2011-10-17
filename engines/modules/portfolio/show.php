<?php

  	if ( ! defined ( 'DATALIFEENGINE' ))
  	{  		   die ( 'Hacking Attemp!' );
  	}

  	$user_name = trim ( $_REQUEST[ 'user_name' ] );
	
	if(strstr($user_name,'%')!=false) $user_name = urldecode ( $user_name );
	else $user_name = iconv ( 'utf-8', 'windows-1251', $user_name );
	
  	$user_name = $db->safesql ( $user_name );

  	if ( $user_name == '' )
  	{     	 msgbox( $lang['all_info'], "В цього користувача немає анкети!" );
  	}
  	else
  	{
  		 $_TITLE = 'Перегляд портфоліо кондитера "' . $user_name . '"';
  		 $db->query ( "SELECT
  		 		" . PREFIX . "_portfolio.*,
  		 		" . PREFIX . "_users.reg_date,
  		 		" . PREFIX . "_users.lastdate
  		  FROM " . PREFIX . "_portfolio

  		 	INNER JOIN " . PREFIX . "_users ON ( " . PREFIX . "_users.user_id = " . PREFIX . "_portfolio.user_id )
  		  WHERE " . PREFIX . "_portfolio.user_name = '{$user_name}'" );

  		 if ( $db->num_rows () == 0 )
  		 {  		 	  msgbox( $lang['all_info'], "В цього користувача немає анкети!" );
  		 }
  		 else
  		 {
  		 	  $portfolio_info = $db->get_row ();


  		 	  $metatags = create_metatags ( $portfolio_info[ 'comment' ]. $portfolio_info[ 'address'] );

              if ( trim ( $portfolio_info[ 'foto' ] ) != '' AND file_exists ( ROOT_DIR . '/uploads/portfolio/foto/' . $portfolio_info[ 'foto' ] ))
              {              	   $portfolio_info[ 'foto' ] = "/uploads/portfolio/foto/" . $portfolio_info[ 'foto' ];
              }
              else
              {              	   $portfolio_info[ 'foto' ] = "/templates/" . $config['skin']. "/images/noavatar.png";
              }

              $portfolio_info[ 'fotos' ] = showImages ( $portfolio_info[ 'user_id' ] );

  		 	  $portfolio_info[ 'reg_date' ]  = getLangDate ( $portfolio_info[ 'reg_date' ] );
  		 	  $portfolio_info[ 'lastdate' ]  = getLangDate ( $portfolio_info[ 'lastdate' ] );

  		 	  // Отримання списку послуг
  		 	  $services = getServices ();
  		 	  $portfolio_info[ 'services' ] = $services[ $portfolio_info[ 'services' ]][ 'name' ];

			  // Визначення країни
  		 	  $countries = getCountries ();
  		 	  $country_id = $portfolio_info[ 'country' ];
  		 	  $portfolio_info[ 'country' ] = $countries[ $country_id ][ 'name' ];

			  // Визначення області
  		 	  $regions = getRegions ( $country_id );
  		 	  $region_id = $portfolio_info[ 'region' ];
  		 	  $portfolio_info[ 'region' ] = $regions[ $region_id ][ 'name' ];

			  // Визначення міста
  		 	  $towns = getTowns ( $region_id );
  		 	  $town_id = $portfolio_info[ 'town' ];
  		 	  $portfolio_info[ 'town' ] = $towns[ $town_id ][ 'name' ];

			  $tpl->load_template ( 'portfolio/show.tpl' );

			  foreach ( $portfolio_info as $name => $value )
			  {			  		$tpl->set ( '{' . $name . '}', stripslashes ( $value ));

			  		if ( trim ( $value ) == '' )
			  		{			  			 $tpl->set_block ( "#\\[{$name}\\](.*)\\[/{$name}\\]#Usi", "" );
			  		}
			  		else $tpl->set_block ( "#\\[{$name}\\](.*)\\[/{$name}\\]#Usi", "\\1" );
			  }

  			  $tpl->compile ( 'content' );

			  // --------------------------------------------------------------
  			  //	Перегляд комментарів
  			  // --------------------------------------------------------------

  			  $portfolio_id = $portfolio_info[ 'id' ];
  			  $news_id = $portfolio_id;

  			  $db->query ( "SELECT COUNT(*) as count FROM " . PREFIX . "_portfolio_comments WHERE portfolio_id = '{$portfolio_info['id']}'" );
  			  $total_comments = $db->get_row ();
  			  $total_comments = $total_comments[ 'count' ];

  			  require_once ENGINE_DIR . '/modules/portfolio/classes/comments.class.php';
  			  $comments = new DLE_Comments ( $db, $total_comments, $config['comm_nummers'] );

  			  if( $config['comm_msort'] == "" ) $config['comm_msort'] = "ASC";

  			  $comments->query = "SELECT
  			  		" . PREFIX . "_portfolio_comments.id, portfolio_id as post_id,
  			  		" . PREFIX . "_portfolio_comments.user_id, date, autor as gast_name,
  			  		" . PREFIX . "_portfolio_comments.email as gast_email, text, ip, is_register, name,
  			  		" . USERPREFIX . "_users.email, news_num, comm_num, user_group, reg_date, signature, foto, fullname, land, icq, xfields
  			  	FROM
  			  		" . PREFIX . "_portfolio_comments
  			  	LEFT JOIN " . USERPREFIX . "_users ON " . PREFIX . "_portfolio_comments.user_id=" . USERPREFIX . "_users.user_id
  			  	WHERE " . PREFIX . "_portfolio_comments.portfolio_id = '{$portfolio_info['id']}'
  			  	ORDER BY date " . $config['comm_msort'];

  			  $comments->build_comments( 'portfolio/comments.tpl', 'news' );

  			  // --------------------------------------------------------------
  			  //	Добавление нового комментария
  			  // --------------------------------------------------------------

  			  $tpl->load_template( 'portfolio/addcomments.tpl' );

  			  	if ($config['allow_subscribe'] AND $user_group[$member_id['user_group']]['allow_subscribe']) $allow_subscribe = true; else $allow_subscribe = false;

  			  	$allow_subscribe = false;

		if( $config['allow_comments_wysiwyg'] == "yes" ) {
			include_once ENGINE_DIR . '/editor/comments.php';
			$bb_code = "";
			$allow_comments_ajax = true;
		} else
			include_once ENGINE_DIR . '/modules/bbcode.php';

		if( $user_group[$member_id['user_group']]['captcha'] ) {

			if ( $config['allow_recaptcha'] ) {

				$tpl->set( '[recaptcha]', "" );
				$tpl->set( '[/recaptcha]', "" );

				$tpl->set( '{recaptcha}', '<div id="dle_recaptcha"></div>' );

				$tpl->set_block( "'\\[sec_code\\](.*?)\\[/sec_code\\]'si", "" );
				$tpl->set( '{reg_code}', "" );

			} else {

				$tpl->set( '[sec_code]', "" );
				$tpl->set( '[/sec_code]', "" );
				$path = parse_url( $config['http_home_url'] );
				$tpl->set( '{sec_code}', "<span id=\"dle-captcha\"><img src=\"" . $path['path'] . "engine/modules/antibot.php\" alt=\"${lang['sec_image']}\" border=\"0\" alt=\"\" /><br /><a onclick=\"reload(); return false;\" href=\"#\">{$lang['reload_code']}</a></span>" );
				$tpl->set_block( "'\\[recaptcha\\](.*?)\\[/recaptcha\\]'si", "" );
				$tpl->set( '{recaptcha}', "" );
			}

		} else {
			$tpl->set( '{sec_code}', "" );
			$tpl->set( '{recaptcha}', "" );
			$tpl->set_block( "'\\[recaptcha\\](.*?)\\[/recaptcha\\]'si", "" );
			$tpl->set_block( "'\\[sec_code\\](.*?)\\[/sec_code\\]'si", "" );
		}

		if( $config['allow_comments_wysiwyg'] == "yes" ) {

			$tpl->set( '{editor}', $wysiwyg );

		} else {
			$tpl->set( '{editor}', $bb_code );

		}

		$tpl->set( '{text}', '' );
		$tpl->set( '{title}', $lang['news_addcom'] );

		if( ! $is_logged ) {
			$tpl->set( '[not-logged]', '' );
			$tpl->set( '[/not-logged]', '' );
		} else
			$tpl->set_block( "'\\[not-logged\\](.*?)\\[/not-logged\\]'si", "" );

		if( $is_logged ) $hidden = "<input type=\"hidden\" name=\"name\" id=\"name\" value=\"{$member_id['name']}\" /><input type=\"hidden\" name=\"mail\" id=\"mail\" value=\"\" />";
		else $hidden = "";

		$tpl->copy_template = "

<script type=\"text/javascript\" src=\"/engine/modules/portfolio/js/main.js\"></script>
<form  method=\"post\" name=\"dle-comments-form\" id=\"dle-comments-form\" action=\"{$_SESSION['referrer']}\">" . $tpl->copy_template . "
		<input type=\"hidden\" name=\"subaction\" value=\"addcomment\" />{$hidden}
		<input type=\"hidden\" name=\"post_id\" id=\"post_id\" value=\"$news_id\" /></form>";

		if (!isset($path['path'])) $path['path'] = "/";

		$tpl->copy_template .= <<<HTML
<script language="javascript" type="text/javascript">
<!--
$(function(){

	$('#dle-comments-form').submit(function() {
	  //doAddComments();
   	  portfolioAddComment();
	  return false;
	});

});

function reload () {

	var rndval = new Date().getTime();

	document.getElementById('dle-captcha').innerHTML = '<img src="{$path['path']}engine/modules/antibot.php?rndval=' + rndval + '" border="0" width="120" height="50" alt="" /><br /><a onclick="reload(); return false;" href="#">{$lang['reload_code']}</a>';

};
//-->
</script>
HTML;

		if ( $config['allow_recaptcha'] ) {

		$tpl->copy_template .= <<<HTML
<script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
<script language="javascript" type="text/javascript">
<!--
$(function(){
	Recaptcha.create("{$config['recaptcha_public_key']}",
     "dle_recaptcha",
     {
       theme: "{$config['recaptcha_theme']}",
       lang:  "{$lang['wysiwyg_language']}"
     }
   );
});
//-->
</script>
HTML;

		}

	/*
			  if( $config['allow_comments_wysiwyg'] == "yes" ) {
					include_once ENGINE_DIR . '/editor/comments.php';
					$bb_code = "";
					$allow_comments_ajax = true;
			  } else
					include_once ENGINE_DIR . '/modules/bbcode.php';

			  if( $config['allow_comments_wysiwyg'] != "yes" ) {
					$tpl->set( '[not-wysywyg]', "" );
					$tpl->set( '[/not-wysywyg]', "" );
			  } else
					$tpl->set_block( "'\\[not-wysywyg\\](.*?)\\[/not-wysywyg\\]'si", "" );

			  if( $user_group[$member_id['user_group']]['captcha'] ) {
					$tpl->set( '[sec_code]', "" );
					$tpl->set( '[/sec_code]', "" );
					$path = parse_url( $config['http_home_url'] );
					$tpl->set( '{sec_code}', "<span id=\"dle-captcha\"><img src=\"" . $path['path'] . "engine/modules/antibot.php\" border=\"0\" alt=\"${lang['sec_image']}\" /><br /><a onclick=\"reload(); return false;\" href=\"#\">{$lang['reload_code']}</a></span>" );
			  }
			  else
			  {
					$tpl->set( '{sec_code}', "" );
					$tpl->set_block( "'\\[sec_code\\](.*?)\\[/sec_code\\]'si", "" );
			  }

			  if( $config['allow_comments_wysiwyg'] == "yes" )
			  {
					$tpl->set( '{wysiwyg}', $wysiwyg );
			  }
			  else  $tpl->set( '{wysiwyg}', '' );

			  $tpl->set( '{text}', '' );
			  $tpl->set( '{bbcode}', $bb_code );
			  $tpl->set( '{title}', $lang['news_addcom'] );

			  if( ! $is_logged )
			  {
					$tpl->set( '[not-logged]', '' );
					$tpl->set( '[/not-logged]', '' );
			  } else
					$tpl->set_block( "'\\[not-logged\\](.*?)\\[/not-logged\\]'si", "" );

			  if( $is_logged ) $hidden = "<input type=\"hidden\" name=\"name\" id=\"name\" value=\"{$member_id['name']}\" /><input type=\"hidden\" name=\"mail\" id=\"mail\" value=\"\" />";
			  else $hidden = "";

			  $tpl->copy_template = <<<HTML
<script type="text/javascript" src="/engine/modules/portfolio/js/main.js"></script>
{$tpl->copy_template}
HTML;


			  $tpl->copy_template = "<form  method=\"post\" name=\"dle-comments-form\" id=\"dle-comments-form\" action=\"{$_SESSION['referrer']}\">" . $tpl->copy_template . "
		<input type=\"hidden\" name=\"subaction\" value=\"addcomment\" />{$hidden}
		<input type=\"hidden\" name=\"post_id\" id=\"post_id\" value=\"$news_id\" /></form>";

			  $tpl->copy_template .= <<<HTML
<script language="javascript" type="text/javascript">
<!--
function reload () {

	var rndval = new Date().getTime();

	document.getElementById('dle-captcha').innerHTML = '<img src="{$path['path']}engine/modules/antibot.php?rndval=' + rndval + '" border="0" width="120" height="50" alt="" /><br /><a onclick="reload(); return false;" href="#">{$lang['reload_code']}</a>';

};
//-->
</script>
HTML;

				$tpl->set( '{text}', '' );
*/

 				$tpl->compile( 'content' );



				$tpl->clear();
  		 }
  	}

?>