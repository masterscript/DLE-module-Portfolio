<?php

	if ( ! defined ( 'DATALIFEENGINE' ))
	{		   die ( 'Hacking Attemp!' );
	}

	require_once ENGINE_DIR . '/inc/portfolio/func.php';

    $data = array ();

  	$db->query ( "SELECT country, town, region FROM " . PREFIX . "_portfolio WHERE approve = '1'" );

  	while ( $row = $db->get_row () )
  	{  			$country_id = $row[ 'country' ];

  			if ( isset ( $data[ 'country' ][ $country_id ] ))
  			{  				 $data[ 'country' ][ $country_id ] ++;
  			}
  			else $data[ 'country' ][ $country_id ] = 1;

  			$town_id = $row[ 'town' ] ;

  			if ( isset ( $data[ 'town' ][ $town_id ] ))
  			{  				 $data[ 'town' ][ $town_id ] ++;
  			}
  			else $data[ 'town' ][ $town_id ] = 1;

  			$region_id = $row[ 'region' ];

  			if ( isset ( $data[ 'region' ][ $region_id ] ))
  			{
  				 $data[ 'region' ][ $region_id ] ++;  			}
  			else $data[ 'region' ][ $region_id ] = 1;
  	}

    $countries = getCountries ();

    $buffer = <<<HTML
<ul>
HTML;

	if ( intval ( $_REQUEST[ 'country' ] ) != 0)
	{		 $sel_country = true;
		 $sel_region = false;
		 $sel_town = false;
	}

	if ( intval ( $_REQUEST[ 'region' ] ) != 0)
	{		 $sel_country = false;
		 $sel_region = true;
		 $sel_town = false;
	}

	if ( intval ( $_REQUEST[ 'town' ] ) != 0)
	{         $sel_country = false;
         $sel_region = false;
         $sel_town = true;
	}


    foreach ( $countries as $country )
    {    		$regions = getRegions ( $country[ 'id' ] );

    		$count = intval ( $data[ 'country' ][ $country[ 'id' ]] );
    		$count = $count != 0 ? " (" . $count . ")" : "";

			if ( intval ( $_REQUEST[ 'country' ] ) == $country[ 'id' ] AND $sel_country )
			{				 $buffer .= "<li><b>" . $country['name'] . "</b></li>";
			}
			else $buffer .= <<<HTML
<li><a href="/index.php?do=portfolio&country={$country['id']}">{$country['name']}{$count}</a></li>
HTML;

    		foreach ( $regions as $region )
    		{
    				$count = intval ( $data[ 'region' ][ $region[ 'id' ]] );
    				$count = $count != 0 ? " (" . $count . ")" : "";

    				if ( intval ( $_REQUEST[ 'region' ] ) == $region['id'] AND $sel_region )
    				{    					 $buffer .= "<li>&nbsp; &nbsp;<b>" . $region['name'] . "</b></li>";
    				}
    				else $buffer .= <<<HTML
<li>&nbsp; &nbsp;<a href="/index.php?do=portfolio&country={$country['id']}&region={$region['id']}">{$region['name']}{$count}</a></li>
HTML;

    				$towns = getTowns ( $region[ 'id' ] );
    				foreach ( $towns as $town )
    				{    						$count = intval ( $data[ 'town' ][ $town[ 'id' ]] );
    						$count = $count != 0 ? " (" . $count . ")" : "";

    						if ( intval ( $_REQUEST[ 'town' ] ) == $town[ 'id' ] AND $sel_town )
    						{    							 $buffer .= "<li>&nbsp; &nbsp; &nbsp; <b>" . $town[ 'name' ] . "</b></li>";
    						}
    						else $buffer .= <<<HTML
<li>&nbsp; &nbsp; &nbsp; <a href="/index.php?do=portfolio&country={$country['id']}&region={$region['id']}&town={$town['id']}">{$town['name']}{$count}</a></li>
HTML;
    				}
    		}
    }

    $buffer .= <<<HTML
</ul>
HTML;

?>