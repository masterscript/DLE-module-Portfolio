<?php

	@session_start ();
	@ob_start ();
	@ob_implicit_flush ( 0 );
	error_reporting ( E_ALL ^ E_NOTICE );
	@ini_set ( 'display_errors', true );
	@ini_set ( 'html_errors', false );
	@ini_set ( 'error_reporting', E_ALL ^ E_NOTICE );
	define ( "DATALIFEENGINE",	true );
	define ( "ENGINE_DIR", 		dirname ( dirname ( dirname ( __FILE__ ))));
	define ( "ROOT_DIR",		dirname ( ENGINE_DIR ));
	require_once ENGINE_DIR . '/modules/portfolio/classes/class_image.php';
	require_once ENGINE_DIR . '/classes/mysql.php';
	require_once ENGINE_DIR . '/data/dbconfig.php';
	$allowed_ext = array ( 'png', 'jpg', 'jpeg', 'gif' );
	require_once ENGINE_DIR . "/modules/functions.php";
 	$user_id = intval ( $_REQUEST [ 'user_id' ] );

 	if ( $user_id == 0 )
 	{ 		 die ();
 	}
if ( ! empty ( $_FILES )){
	    $array_length = count($_FILES['Filedata']['name']);
	    $array_index = 0;

    do {
			if	 ( $array_length == 1 ){
		  		    $file_name = strtolower ( totranslit ( $_FILES['Filedata']['name'] ));
		  		    $temp_name = $_FILES['Filedata']['tmp_name'];
				    $next_loop = false;
			} elseif ( $array_length > 1 ){
				    $file_name = strtolower ( totranslit ( $_FILES['Filedata']['name'][$array_index] ));
		  		    $temp_name = $_FILES['Filedata']['tmp_name'][$array_index];
				    $next_loop = true;
			} else	 {
				    echo "-1";
				    die();
			}
  		 $file_ext  = strtolower( end ( explode ( ".", $file_name )));

  		 $temp_folder = ROOT_DIR . '/uploads/portfolio/sample/' . $user_id . '/';
  		 $mini_folder = $temp_folder . 'mini/';

  		 if ( ! file_exists ( $temp_folder )){
  			    @mkdir ( $temp_folder, 0777 );
  		 	    @chmod ( $temp_folder, 0777 );
  		 }

  		 if ( ! file_exists ( $mini_folder )){
  			@mkdir ( $mini_folder, 0777 );
  		 	@chmod ( $mini_folder, 0777 );
  		 }

  		 if ( in_array ( $file_ext, $allowed_ext )){
	      	    @move_uploaded_file( $temp_name, $temp_folder . $file_name );
		    if ( file_exists ( $temp_folder. $file_name )){
	      		$image = new class_image ( $temp_folder . $file_name );
	                $image->thumbnail ( 150 );
                	$image->save ( $mini_folder . $file_name );
			$db->query ( "INSERT INTO " . PREFIX . "_portfolio_images  ( user_id, image_name ) VALUES ( '{$user_id}','{$file_name}' )");
	      	    }
	    	}
		$array_index += 1;
		if ($array_index >= $array_length-1 )$next_loop = false;
    } while ( $next_loop );
}
  	echo "1";
  	die();

?>