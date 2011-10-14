<?php

	if ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )
	{
		 define ( 'DATALIFEENGINE', true );

		 define ( 'MOD_DIR', dirname ( __FILE__ ));

		 define ( 'ENGINE_DIR', dirname ( dirname ( MOD_DIR )));

		 define ( 'ROOT_DIR', dirname ( ENGINE_DIR ));

		 include ENGINE_DIR . '/data/config.php';
		 require_once ENGINE_DIR . '/classes/mysql.php';
		 require_once ENGINE_DIR . '/classes/templates.class.php';
		 require_once ENGINE_DIR . '/data/dbconfig.php';
		 require_once ENGINE_DIR . '/modules/functions.php';

		 require_once ENGINE_DIR . '/inc/portfolio/func.php';
		$tpl = new dle_template;
		$tpl->dir = ROOT_DIR .  "/templates/" . $config['skin'];
		define('TEMPLATE_DIR', $tpl->dir);
		 @header( "Content-type: text/css; charset=" . $config['charset'] );
         switch ( $_REQUEST[ 'act' ] )
           {         		case 'get_regions' :

         			$country_id = intval ( $_POST[ 'country_id' ] );

         			if ( $country_id != 0 )
         			{
         				 echo '<option value="0"></option>';
	         			 echo getOptions ( getRegions( $country_id ));
	         		}
					break;

				case 'get_towns' :

					$region_id = intval ( $_POST[ 'region_id' ] );

					if ( $region_id != 0 )
					{                         echo '<option value="0"></option>';
		                            echo getOptions( getTowns( $region_id ));
					}
					break;
				case 'search' :
					    require_once ENGINE_DIR . '/inc/portfolio/search.php';
					break;
         }

         die();
	}

?>