<?php

  	if ( ! defined ( 'DATALIFEENGINE' ))
  	{  		   die ( 'Hacking Attemp!' );
  	}

  	function create_metatags($story) {
	global $config, $db;

	$keyword_count = 20;
	$newarr = array ();
	$headers = array ();
	$quotes = array ("\x22", "\x60", "\t", '\n', '\r', "\n", "\r", '\\', ",", ".", "/", "¬", "#", ";", ":", "@", "~", "[", "]", "{", "}", "=", "-", "+", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"' );
	$fastquotes = array ("\x22", "\x60", "\t", "\n", "\r", '"', '\r', '\n', "/", "\\", "{", "}", "[", "]" );

	$story = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "", $story );
	$story = preg_replace( "'\[attachment=(.*?)\]'si", "", $story );
	$story = preg_replace( "'\[page=(.*?)\](.*?)\[/page\]'si", "", $story );
	$story = str_replace( "{PAGEBREAK}", "", $story );
	$story = str_replace( "&nbsp;", " ", $story );
	$story = str_replace( '<br />', ' ', $story );
	$story = trim( strip_tags( $story ) );

	if( trim( $_REQUEST['meta_title'] ) != "" ) {

		$headers['title'] = trim( $db->safesql( htmlspecialchars( $_REQUEST['meta_title'] ) ) );

	} else $headers['title'] = "";

	if( trim( $_REQUEST['descr'] ) != "" ) {

		$headers['description'] = $db->safesql( substr( strip_tags( stripslashes( $_REQUEST['descr'] ) ), 0, 190 ) );

	} else {

		$story = str_replace( $fastquotes, '', $story );

		$headers['description'] = $db->safesql( substr( $story, 0, 190 ) );
	}

	if( trim( $_REQUEST['keywords'] ) != "" ) {

		$headers['keywords'] = $db->safesql( str_replace( $fastquotes, " ", strip_tags( stripslashes( $_REQUEST['keywords'] ) ) ) );

	} else {

		$story = str_replace( $quotes, '', $story );

		$arr = explode( " ", $story );

		foreach ( $arr as $word ) {
			if( strlen( $word ) > 4 ) $newarr[] = $word;
		}

		$arr = array_count_values( $newarr );
		arsort( $arr );

		$arr = array_keys( $arr );

		$total = count( $arr );

		$offset = 0;

		$arr = array_slice( $arr, $offset, $keyword_count );

		$headers['keywords'] = $db->safesql( implode( ", ", $arr ) );
	}

	return $headers;
}

  	require_once ENGINE_DIR . '/inc/portfolio/func.php';

  	$fields = array
  	(
  	  		'services',
  	  		'price',
  	  		'minimum_order',
  	  		'foto',
  	  		'country',
  	  		'region',
  	  		'town',
  	  		'address',
  	  		'icq',
  	  		'skype',
  	  		'email',
  	  		'phone',
       		'contact_time',
       		'comment',
  	);

  	switch ( $_REQUEST[ 'act' ] )
  	{
  			case 'show' :

                $_TITLE = 'Перегляд портфоліо';
  				require_once ENGINE_DIR . '/modules/portfolio/show.php';
  				break;

  			case 'add' :

  				$_TITLE = 'Додавання нового портфоліо';
  				require_once ENGINE_DIR . '/modules/portfolio/add.php';
  				break;

  			case 'edit' :
  				$_TITLE = 'Редагування портфоліо';
  				require_once ENGINE_DIR . '/modules/portfolio/add.php';
  				break;

  			case 'comments' :
  				require_once ENGINE_DIR . '/modules/portfolio/comments.php';
  				break;

  			case 'ajax' :

  				switch ( $_REQUEST[ 'sub_act' ] )
  				{  						case 'del_foto' :
  							@unlink ( ROOT_DIR . '/uploads/portfolio/sample/' . $member_id[ 'user_id' ] . '/' . $_REQUEST[ 'foto' ] );
  							@unlink ( ROOT_DIR . '/uploads/portfolio/sample/' . $member_id[ 'user_id' ] . '/mini/' . $_REQUEST[ 'foto' ] );
							$db->query ( "DELETE FROM  " . PREFIX . "_portfolio_images WHERE image_name = '{$_REQUEST[ 'foto' ]}'" );
  							break;
  				}

  				echo getImages ( $member_id[ 'user_id' ] );
  				die();
  				break;
  	 		default :
  	 				require_once ENGINE_DIR . '/modules/portfolio/main.php';
  	 			break;
  	}


	$metatags[ 'title' ] = $_TITLE;

	/*
	$metatags[ 'keywords' ] = $_KEYWORDS;
	$metatags[ 'description' ] = $_DESCRIPTION;
	*/

?>