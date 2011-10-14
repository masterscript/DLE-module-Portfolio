<?php


	if ( ! defined ( 'DATALIFEENGINE' ))
	{  die( 'Hacking Attemp!' );
	}
	
	$country_id = intval ( $_REQUEST[ 'country' ] );
	$region_id  = intval ( $_REQUEST[ 'region' ] );
	$town_id	= intval ( $_REQUEST[ 'town' ] );
        $_TITLE = 'Замовити торт, торти на замовлення';
        $_keys = 'тест'; 

	if ( $country_id != 0 )
	{
			$where[] = "country = '" . $country_id . "'";

			$countries = getCountries ();
			$_TITLE = 'Всі кондитери, якими багата ' . $countries[ $country_id ][ 'name' ] . '';
$_KEYWORDS = 'торт, тортик, замовлення, в Україні, кондитери, вартість, торти, ціна, смачні';
	}

	if ( $region_id  != 0 )
	{			$where[] = "region = '" . $region_id . "'";

			$regions = getRegions( $country_id );
			$_TITLE = 'Кондитери в області ' . $regions[ $region_id ][ 'name' ] . '';
	}

	if ( $town_id	 != 0 )
	{			$where[] = "town = '" . $town_id . "'";

			$towns = getTowns ( $region_id );
			$_TITLE = 'Кондитери в місті ' . $towns[ $town_id ][ 'name' ] . '';
	}

	$order = $_REQUEST[ 'order' ] == 'desc' ? 'DESC' : 'ASC';

	$order_by = false;

	switch ( $_REQUEST[ 'sort' ] )
	{     case 'cost' :
     			$order_by = PREFIX . "_portfolio.price " . $order;
     			break;

     		case 'add_date' :
     			$order_by = PREFIX . "_portfolio.add_date " . $order;
     			break;
     		default :
     			$order_by = PREFIX . "_portfolio.user_name " . $order;
     			break;
	}

	if ( $order_by )
	{		 $order_by = " ORDER BY " . $order_by;
	}

	$where[] = "approve = '1'";

	if ( $where )
	{		 $where = " WHERE " . implode ( " AND ", $where );
	}

  	$db->query ( "SELECT " . PREFIX . "_portfolio.* FROM " . PREFIX . "_portfolio" . $where . $order_by );

  	if ( $db->num_rows () == 0 )
  	{         msgbox ("Ой!", "В цій області ще жоден кондитер не запропонував послуги. Бажаєте <a href=\"/?do=portfolio&act=add\">запропонувати свої</a>?");
  	}
  	else
  	{
  		 $tpl->load_template ( '/portfolio/portfolio.tpl' );
  		 while ( $row = $db->get_row () )
  		 {
  		 		 foreach ( $row as $name => $value )
  		 		 {
  		 		 		if ( $name == 'foto' )
  		 		 		{
	  		 		 		if ( trim ( $value ) != '' AND file_exists ( ROOT_DIR . '/uploads/portfolio/foto/' . $value ))
    	          			{
						       	   $value = "/uploads/portfolio/foto/" . $value;
            				}
			    	        else
            				{
			            	  	   $value = "/templates/" . $config['skin']. "/images/noavatar.png";
	              			}
	              		}
  		 		 		$tpl->set ( '{' . $name . '}', stripslashes ( $value ));
  		 		 		if ( trim ( $value ) == '' )
  		 		 		{
  		 		 			$tpl->set_block ( "#\\[{$name}\\](.*)\\[/{$name}\\]#Usi", "" );
  		 		 		}
  		 		 		else $tpl->set_block ( "#\\[{$name}\\](.*)\\[/{$name}\\]#Usi", "\\1" );
  		 		 }
      			 $tpl->compile ( 'search_content' );
      		}
	echo $tpl->result['search_content'];
	echo "OK";
  	}
?>