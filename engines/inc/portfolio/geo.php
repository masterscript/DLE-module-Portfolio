<?php

  	if ( ! defined ( 'DATALIFEENGINE' ))
  	{
  		   die ( 'Hacking Attemp!' );
  	}

  	switch ( $_REQUEST[ 'act' ] )
  	{
  			// Видалити запис
  			case 'del' :
  				$id = intval ( $_REQUEST[ 'id' ] );

  				if ( $id != 0 )
  				{  					 $db->query ( "DELETE FROM " . PREFIX . "_portfolio_geo WHERE id = '{$id}'" );

  					 clearGeoCache ();

                 	 header ( "Location: {$PHP_SELF}?mod=portfolio&page=geo" );
                 	 die();
  				}

  				die();
  				break;

  			// Додати країну     		case 'add_country' :

     			if ( trim ( $_POST[ 'country' ] ) != '' )
     			{
     				 $country = $db->safesql ( $_POST[ 'country' ] );                 	 $db->query ( "INSERT INTO " . PREFIX . "_portfolio_geo ( name, is_country ) VALUES ( '{$country}', 'YES' )" );

                   	 clearGeoCache ();

                 	 header ( "Location: {$PHP_SELF}?mod=portfolio&page=geo" );
                 	 die();
     			}
     			break;

			// Додати область
     		case 'add_region' :

     			$country_id = intval ( $_POST['country' ] );
				if ( trim ( $_POST[ 'region' ] ) != '' AND $country_id != 0 )
				{
					 $region = $db->safesql ( $_POST[ 'region' ] );
					 $db->query ( "INSERT INTO " . PREFIX . "_portfolio_geo ( name, is_region, country_id ) VALUES ( '{$region}', 'YES', '{$country_id}' )" );

					 clearGeoCache ();

					 header ( "Location: {$PHP_SELF}?mod=portfolio&page=geo" );
                 	 die();
				}
     			break;

			// Додати місто
     		case 'add_town' :

     			$country_id = intval ( $_POST[ 'country' ] );
     			$region_id  = intval ( $_POST[ 'region' ] );

     			if ( trim ( $_POST[ 'town' ] ) != '' AND $country_id != 0 AND $region_id != 0 )
     			{     				 $town = $db->safesql ( $_POST[ 'town' ] );
     				 $db->query ( "INSERT INTO " . PREFIX . "_portfolio_geo ( name, is_town, country_id, region_id ) VALUES ( '{$town}', 'YES', '{$country_id}', '{$region_id}' )" );

                     clearGeoCache ();

					 header ( "Location: {$PHP_SELF}?mod=portfolio&page=geo" );
                 	 die();
     			}
     			break;
  	}


  	echoheader ( '', '' );

	echo <<<HTML
<script type="text/javascript" src="/engine/inc/plugins/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/engine/inc/portfolio/js/town.js"></script>
HTML;

	opentable ( 'Додати країну' );

	echo <<<HTML
<form method="POST">
<input type="hidden" name="act" value="add_country" />

<div style="padding:6px">
Назва країни: <input type="text" name="country" class="edit" />
<input type="submit" class="bbcodes" value="Додати" />
</div>
</form>
HTML;

	closetable ();

	opentable ( 'Додати область' );


	$country = getOptions ( getCountries ());

	echo <<<HTML
<form method="POST">
	<input type="hidden" name="act" value="add_region" />

	<div style="padding:6px">
		Виберіть країну:
		<select name="country">
			<option value="0"></option>
			{$country}
		</select>
	</div>
	<div style="padding:6px">
		Вкажіть назву міста:
		<input type="text" name="region" class="edit" value="" />
		<input type="submit" class="bbcodes" value="Додати" />
	</div>
</form>
HTML;

	closetable ();

	opentable ( 'Додати місто' );

	echo <<<HTML
<form method="POST">
	<input type="hidden" name="act" value="add_town" />

	<div style="padding:6px">
		Выберите страну:
		<select name="country" onChange="getRegions(this.value, 'region');" >
			<option value="0"></option>
			{$country}
		</select>
	</div>

	<div style="padding:6px">
		Виберіть область:
		<select name="region" id="region"></select>
	</div>

	<div style="padding:6px">
		Вкажіть назву міста:
		<input type="text" name="town" class="edit" value="" />
		<input type="submit" class="bbcodes" value=" Добавить " />
	</div>



</form>
HTML;

	closetable ();

	opentable ( 'Список' );

    $geo = array ();

	$db->query ( "SELECT * FROM " . PREFIX . "_portfolio_geo" );

	if ( $db->num_rows() == 0 )
	{
	 	 echo <<<HTML
<div style="padding:10px" align="center" valign="middle">
Нема даних :(
</div>
HTML;
	}
	else
	{
	    $geo_data = array ();

		while ( $row = $db->get_row () )
		{
				if ( $row[ 'is_country' ] == 'YES' )
				{					 $geo_data[ $row[ 'id' ]] = $row;
				}

				if ( $row[ 'country_id' ] != 0 AND $row[ 'is_region' ] == 'YES' )
				{                 	 $geo_data[ $row[ 'country_id' ]][ 'regions' ][ $row[ 'id' ]] = $row;
				}

				if ( $row[ 'country_id' ] != 0 AND $row[ 'region_id' ] != 0 AND $row[ 'is_town' ] == 'YES' )
				{                     $geo_data[ $row[ 'country_id' ]][ 'regions' ][ $row[ 'region_id' ]][ 'towns' ][ $row[ 'id' ]] = $row;
				}
		}

		echo <<<HTML
<table cellpadding="4" cellspacing="0" width="100%">
HTML;

		function drawRow ( $geo_info, $sub_str = "", $bg_color = "" )
		{				global $PHP_SELF;

				if ( $bg_color != '' )
				{					 $bg_color = "background-color: {$bg_color};";
				}

     			echo <<<HTML
<tr>
	<td style="padding:4px; {$bg_color}">{$sub_str}{$geo_info['name']}</td>
	<td style="padding:4px; {$bg_color} width:100px"><a href="{$PHP_SELF}?mod=portfolio&page=geo&act=del&id={$geo_info['id']}">Видалити</a></td>
</tr>
<tr><td background="/engine/skins/images/mline.gif" height=1 colspan=7></td></tr>
HTML;
		}

		if ( $db->num_rows () == 0 )
		{			 echo <<<HTML
<tr>
	<td colspan="12" height="40" align="center" valign="middle" class="navigation">Нема даних :(</td>
</tr>
HTML;
		}
		else
		{			foreach ( $geo_data as $country )
			{            	drawRow ( $country, "", "#fafafa" );

            	if ( is_array ( $country[ 'regions' ] ))
            	{
	            	foreach ( $country[ 'regions' ] as $region )
    	        	{                 		drawRow ( $region, " -- " );

						if ( is_array ( $region[ 'towns' ] ))
						{
                 			foreach ( $region[ 'towns' ] as $town )
                 			{                 					drawRow( $town, " ---- " );
	                 		}
	                	}
        	    	}
        	    }


			}
		}


    	echo <<<HTML
</table>
HTML;
    }

	closetable ();

  	echofooter ();

?>