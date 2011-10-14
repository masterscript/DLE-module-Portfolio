<?php

  	if ( ! defined ( 'DATALIFEENGINE' ))
  	{  		   die( 'Hacking Attemp!' );
  	}

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

  	$id = intval ( $_REQUEST[ 'id' ] );

  	switch ( $_REQUEST[ 'act' ] )
  	{
  			case 'do_edit' :

  				if ( $_POST )
  				{
  					 $fields = array ();
  					 foreach ( $_POST[ 'port' ] as $name => $value )
  					 {                        	$fields[] = $name . " = '" . $value . "'";
  					 }

  					 $fields = implode ( ", ", $fields );

  					 $db->query( "UPDATE " . PREFIX . "_portfolio SET {$fields} WHERE id = '{$id}'" );

  					 msg( "info", "Info", "Портфоліо успішно змінено!", "{$PHP_SELF}?mod=portfolio&page=main" );
  				}

  				break;

  			case 'edit' :

  				echoheader( '', '' );

  				opentable ( 'Редагування портфоліо' );

  				require_once ENGINE_DIR . '/classes/templates.class.php';
  				$tpl = new dle_template ();
  				$tpl->dir = ENGINE_DIR . '/inc/portfolio/templates/';

  				$row = $db->super_query ( "SELECT * FROM " . PREFIX . "_portfolio WHERE id = '{$id}'" );

  				$tpl->load_template( 'add.tpl' );

  				$tpl->set ( '{approve}', $row[ 'approve' ] == 1 ? 'checked' : '' );

  				$tpl->set ( '{id}', $id );

  				$tpl->set ( '{country}', 	getOptions ( getCountries (), $row[ 'country' ] ));
				$tpl->set ( '{services}',	getOptions ( getServices (), $row[ 'services' ] ));
				$tpl->set ( '{region}',		getOptions ( getRegions ( $row[ 'country' ] ), $row[ 'region' ] ));
				$tpl->set ( '{town}',		getOptions ( getTowns ( $row[ 'region' ] ), $row[ 'town' ] ));

				foreach ( $fields as $field )
				{
				  		if ( $field != 'country' AND $field != 'services' AND $field != 'region' AND $field != 'town' )
				  		{
				  			if ( isset ( $row [ $field ] ))
				  			{
				  				 $tpl->set ( '{' . $field . '}', $row[ $field ] );
				  			}
				  			else
				  			{
		  						 $tpl->set ( '{' . $field . '}', '' );
		  					}
		 				}
				  	}

  				$tpl->compile( 'content' );
  				echo $tpl->result[ 'content' ];


  				closetable ();
  				echofooter ();



  				die();

  				break;
  		 	case 'del' :

  		 		if ( $id != 0 )
  		 		{
  		 			 $db->query ( "DELETE FROM " . PREFIX . "_portfolio WHERE id = '{$id}'" );
  		 		}
  		 		break;
  	}


  	echoheader ( '', '' );

  	opentable ( 'Список портфоліо' );

  	echo <<<HTML
<table cellpadding="4" cellspacing="0" width="100%">
<tr>
	<td style="padding:4px">Користувач</td>
	<td style="padding:4px; width:140px;">Додано</td>
	<td style="padding:4px; width:100px;">Модерація</td>
	<td style="padding:4px; width:140px;">Дія</td>
</tr>
<tr>
	<td colspan="12">{$unterline}</td>
</tr>
HTML;

   	$db->query ( "SELECT * FROM " . PREFIX . "_portfolio ORDER BY approve ASC, add_date DESC" );

   	if ( $db->num_rows () == 0 )
   	{   		 echo <<<HTML
<tr>
	<td style="padding:6px; height:60px;" colspan="12" align="center" valign="middle" class="navigation">Немає інфи</td>
</tr>
HTML;
   	}
   	else
   	{   		 while ( $row = $db->get_row () )
   		 {
   		 		 $approve = $row[ 'approve' ] == '1' ? "Так" : "<font color=\"red\">Та ну його</font>";
   		 	 	 echo <<<HTML
<tr>
	<td style="padding:4px"><a href="/user/{$row['user_name']}/">{$row['user_name']}</a></td>
	<td style="padding:4px">{$row['add_date']}</td>
	<td style="padding:4px">{$approve}</td>
	<td style="padding:4px">
			<a href="{$PHP_SELF}?mod=portfolio&page=main&act=edit&id={$row['id']}">Редагувати</a> |
	     	<a href="{$PHP_SELF}?mod=portfolio&page=main&act=del&id={$row['id']}">Видалити</a>
	</td>
</tr>
<tr><td background="engine/skins/images/mline.gif" height=1 colspan=7></td></tr>
HTML;
   		 }
   	}

   	echo <<<HTML
</table>
HTML;

  	closetable ();

  	echofooter ();

?>