<?php

	if ( ! defined ( 'DATALIFEENGINE' ))
	{  		   die ( "Hacking Attemp!" );
	}


        $_TITLE = 'Замовити торт, торти на замовлення';
        $_keys = 'тест';
	$buff_last_images = showLastImages( 6 );

	$where = " WHERE approve = '1'";
	$order_by = " ORDER BY " . PREFIX . "_portfolio.add_date DESC LIMIT 5";

	$db->query ( "SELECT " . PREFIX . "_portfolio.* FROM " . PREFIX . "_portfolio" . $where . $order_by );

	if ( $db->num_rows () == 0 )
	{         msgbox ( "Ой!", "В цій області ще жоден кондитер не запропонував послуги. Бажаєте <a href=\"/?do=portfolio&act=add\">запропонувати свої</a>?" );
	}
	else
	{   	 $tpl->load_template ( 'portfolio/top_panel.tpl' );
		 $tpl->set ( '{title}',	$_TITLE );
		 $tpl->set ( '{country}',	getOptions ( getCountries () ));
		 $tpl->copy_template = <<<HTML
<script type="text/javascript" src="/engine/modules/portfolio/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="/engine/inc/portfolio/js/town.js"></script>
<script type="text/javascript" src="/engine/inc/portfolio/js/search.js"></script>
{$tpl->copy_template}
<div id="content_field">
HTML;
		 $tpl->compile ( 'content' );

		 $tpl->load_template ( 'portfolio/show_last_images.tpl' );
	            $tpl->set( '{last_images}', $buff_last_images );
		 $tpl->compile ( 'content' );

		 $tpl->load_template ( 'portfolio/portfolio.tpl' );
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
			 $tpl->compile ( 'content' );
		    }
		    $tpl->copy_template = '</div>';
		    $tpl->compile ( 'content' );
	}
?>