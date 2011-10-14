<?php

	if ( ! defined ( 'DATALIFEENGINE' ))
	{		   die ( 'Hacking Attemp!' );
	}

	$id = intval ( $_REQUEST[ 'id' ] );

  	switch ( $_REQUEST[ 'sub_act' ]  )
  	{
  			case 'del' :

  				if ( $id != 0 )
  				{  					 $db->query ( "DELETE FROM " . PREFIX . "_portfolio_comments WHERE id = '{$id}'" );

  					 header ( "Location: " . $_SERVER[ 'HTTP_REFERER' ] );
  					 die ();
  				}
  				break;
  	}

?>