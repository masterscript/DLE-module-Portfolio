<?php

  	if ( ! defined ( 'DATALIFEENGINE' ))
  	{  		   die ( 'Hacking Attemp!' );
  	}

	define ( 'MOD_DIR',		ENGINE_DIR . '/inc/portfolio/' );

  	require_once ENGINE_DIR . '/inc/plugins/core.php';
  	require_once MOD_DIR . 'func.php';

  	switch ( $_REQUEST[ 'page' ] )
  	{  			case 'geo' :
  				require_once MOD_DIR . 'geo.php';
  				break;

  			case 'main' :
  				require_once MOD_DIR . 'main.php';
  				break;

  			default :

  				echoheader ( '', '' );

  				opentable ( 'Навигация' );

  				echo <<<HTML
<input type="button" class="bbcodes" value=" Управление городами " onClick="document.location.href='{$PHP_SELF}?mod=portfolio&page=geo';" />
<input type="button" class="bbcodes" value=" Список портфолио " onClick="document.location.href='{$PHP_SELF}?mod=portfolio&page=main';" />
HTML;
				closetable ();

  				echofooter ( );

  				break;
  	}

?>