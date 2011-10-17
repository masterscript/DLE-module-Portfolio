<?php

  	function getCountries ()
  	{
     		global $db;

     		$country = get_vars ( 'portfolio_country' );

     		if ( ! $country )
     		{
		     	   $db->query ( "SELECT * FROM " . PREFIX . "_portfolio_geo WHERE is_country = 'YES'" );

		     	   $country = array ();

		     	   while ( $row = $db->get_row () )
     			   {
     			    	   $country[ $row[ 'id' ]] = $row;
     			   }

     			   set_vars( 'portfolio_country', $country );
     		}

     		return $country;
  	}

  	function getRegions ( $country_id )
  	{
  			global $db;

  			$db->query ( "SELECT * FROM " . PREFIX . "_portfolio_geo WHERE is_region = 'YES' AND country_id = '{$country_id}'" );

  			$region = array ();

  			while ( $row = $db->get_row () )
  			{
  					$region[ $row[ 'id' ]] = $row;
  			}

  			return $region;
  	}

  	function getTowns( $region_id )
  	{
    		global $db;

    		$db->query ( "SELECT * FROM " . PREFIX . "_portfolio_geo WHERE is_town = 'YES' AND region_id = '{$region_id}'" );

    		$towns = array ();

    		while ( $row = $db->get_row () )
    		{
    			    $towns[ $row[ 'id' ]] = $row;
    		}
    		return $towns;
  	}

  	function getTowns_marked( $region_id )
  	{
    		global $db;
    		$db->query ( "SELECT * FROM " . PREFIX . "_portfolio_geo WHERE is_town = 'YES' AND region_id = '{$region_id}'" );
    		$towns = array ();
    		while ( $row = $db->get_row () )
    		{    			    $towns[ $row[ 'id' ]] = $row;
    		}
		$towns['add_new_city']['name'] = " - add new city - ";
		$towns['blank_string']['name'] = "";
    		return $towns;
  	}



  	function getOptions ( $values, $sel = 0 )
  	{
  			if ( ! is_array ( $values ))
  			{
             	   return "";
  			}

  			$options = array ();

  			foreach ( $values as $id => $value )
  			{
  					$selected = $id == $sel ? ' selected' : '';

               		$options[] = "<option value=\"" . $id . "\" {$selected}>" . stripslashes ( $value[ 'name' ] ) . "</option>";
  			}

  			return implode ( "\n", $options );
  	}

  	function clearGeoCache()
  	{
  			@unlink ( ENGINE_DIR . '/cache/system/portfolio_country.php' );
  			@unlink ( ENGINE_DIR . '/cache/system/portfolio_services.php' );
  	}

  	function getServices ()
  	{
  			global $db;

  			$buffer = get_vars ( 'portfolio_services' );

  			if ( ! $buffer )
  			{
	  			   $db->query ( "SELECT * FROM " . PREFIX . "_portfolio_services" );

  				   while ( $row = $db->get_row () )
  				   {
  						$buffer[ $row['id' ]] = $row;
  				   }

  				   set_vars ( 'portfolio_services', $buffer );
  			}

  			return $buffer;
  	}

  	function showImages ( $user_id )
  	{
  			$full_temp_folder = ROOT_DIR . '/uploads/portfolio/sample/' . $user_id . '/';
			$files 			  = @scandir ( $full_temp_folder );
			$allowed_ext 	  = array ( 'png', 'jpg', 'jpeg', 'gif' );

			$buffer = <<<HTML
<table cellpadding="4" cellspacing="0">
HTML;

		    $count = 0;

		    if ( ! is_array ( $files ))
		    {
		    	   return "";
		    }

			foreach ( $files as $file_name )
			{
 		 			if ( $file_name != '.' and $file_name != '..' )
 		 			{
 		 				 $file_ext = strtolower ( end ( explode ( ".", $file_name )));

                     	 if ( in_array ( $file_ext, $allowed_ext ))
                     	 {
                           	  $count ++;

                           	  if ( $count == 1 )
                           	  {
                           	   	   $buffer .= "<tr>";
                           	  }

                           	  $buffer .= <<<HTML
<td style="padding:6px">
<a href="/uploads/portfolio/sample/{$user_id}/{$file_name}" onclick="return hs.expand(this); return false;"><img src="/uploads/portfolio/sample/{$user_id}/mini/{$file_name}" border="0" /></a>
</td>
HTML;
							  if ( $count > 2 )
							  {
							  	   $count = 0;
							  	   $buffer .= "</tr>";
							  }

                     	 }
                    }
         	}

         	$buffer .= "</table>";


         	return $buffer;


  	}



function showLastImages ( $numbers_images ){
	global $db;
	$query_id_lastimg = $db->query ( "SELECT * FROM " . PREFIX . "_portfolio_images ORDER BY " . PREFIX . "_portfolio_images.added_date DESC LIMIT {$numbers_images} " );
	$count = 0;
	$buffer = <<<HTML
<table cellpadding="4" cellspacing="0">
HTML;
	while ( $row = $db->get_row ( $query_id_lastimg ) ){
		$user_row = $db->super_query ( "SELECT user_name FROM " . PREFIX . "_portfolio WHERE user_id = '{$row['user_id']}'" );
		
		$count ++;
		if ( $count == 1 ){
			    $buffer .= "<tr>";
		}
	$buffer = $buffer . <<<HTML
<td style="padding:20px">
<a "href="/uploads/portfolio/sample/{$row['user_id']}/{$row['image_name']}" onclick="return hs.expand(this); return false;"><img src="/uploads/portfolio/sample/{$row['user_id']}/mini/{$row['image_name']}" border="0" /></a>
<div>додав кулiнар</div>
<a href="/portfolio/{$user_row['user_name']}/">{$user_row['user_name']}</a>
</td>
HTML;
		if ( $count > 2 ){
			$count = 0;
			$buffer = $buffer . "</tr>";
		}
	}
	$buffer = $buffer . "</table>";
	return $buffer;
}


function getImages ( $user_id )
  	{
			$full_temp_folder = ROOT_DIR . '/uploads/portfolio/sample/' . $user_id . '/';
			$files 			  = @scandir ( $full_temp_folder );
			$allowed_ext 	  = array ( 'png', 'jpg', 'jpeg', 'gif' );

 			if ( is_array ( $files ))
		 	{
 	 			 $images = <<<HTML
<script type="text/javascript">
	function del_img ( file_name )
	{
            $('#fotos').html('<img src="/engine/modules/portfolio/img/loading.gif" border="0" />');

            $.post( '/index.php', { do: 'portfolio', act: 'ajax', sub_act: 'del_foto', foto: file_name, user_id: '{$user_id}' }, function ( data ) {
               		$('#fotos').html( data );
    		});
	}
</script>
<table cellpadding="4" cellspacing="0" border="0">
HTML;

 		 		$flag = 0;

 		 		foreach ( $files as $file_name )
				{
 		 			if ( $file_name != '.' and $file_name != '..' )
 		 			{
 		 				 $file_ext = strtolower ( end ( explode ( ".", $file_name )));

                     	 if ( in_array ( $file_ext, $allowed_ext ))
                     	 {
                         	  $flag ++;

                         	  if ( $flag == 1 )
                         	  {
                         	  	   $images .= "<tr>\n";
                         	  }

                         	  $images .= <<<HTML
<td style="padding:6px; border:1px dotted #c4c4c4; width:160px;" align="center">
	<img src="/uploads/portfolio/sample/{$user_id}/mini/{$file_name}" border="0" />
	<div style="padding-top:10px;"><img src="/engine/modules/portfolio/img/cancel.png" style="cursor:pointer;" title="Видалити" onClick="del_img('{$file_name}');" /></div>
</td>
HTML;
                              if ( $flag == 3 )
                              {
                              	   $flag = 0;

                              	   $images .= "</tr>\n";
                              }
                     	 }
	 		 		}
 			    }

 		 	    $images .= "</table>";
		 	}

		 	return $images;
  	}

  	function getLangDate ( $timestamp )
  	{
  			global $lang, $config;

  			$_TIME = time ();

  			if( date( Ymd, $timestamp ) == date( Ymd, $_TIME ) )
  			{
					return $lang['time_heute'] . langdate( ", H:i", $timestamp );
			}
			elseif( date( Ymd, $timestamp ) == date( Ymd, ($_TIME - 86400) ) )
			{
					return $lang['time_gestern'] . langdate( ", H:i", $timestamp );
			}
			else
			{
					return langdate( "j F Y H:i", $timestamp );
			}
  	}


?>