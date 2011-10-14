<?php

   /* ---------------------------------------------------------------------- *
	*  Created                                                               *
	*  by nick-on © Copyright 2009. All Rights Reserved!					 *
	* ---------------------------------------------------------------------- *
	*  This file may no be redistributed in whole or significant part.	 	 *
	* ---------------------------------------------------------------------- */

	class class_image
  	{		  	var $image				=	false;

   	 		var $allow_watermark	=	false;

     		var $error				=	false;

     		var $types				=	array ( '1' => 'GIF', '2' => 'JPG', '3' => 'PNG' );

   	 		var $data				=	array ();

   	 		var $gd_version			=	2;

   	 		var $watermark_light	=	'';

   	 		var $watermark_dark		=	'';

   	 		var $quality			=	100;

   	 		public function __construct ( $file_name = "" )
   	 		{
	    	      $gd_functions = array
    	    	  (
	    	      		'imagecreate',
		    	      	'imagecreatefromgif',
	    	    	  	'imagecreatefromjpeg',
        	  			'imagecreatefrompng',
	        		  	'imagegif',
	    		      	'imagejpeg',
        		    	'imagepng',
    		      );

		          foreach ( $gd_functions as $function_name )
		          {
        			  	 if ( !function_exists ( $function_name ))
        			  	 {
				               $this->error = GD_ERROR;
          	 				   break;
          	 			}
		          }

        		  if ( $this->error )
		          {
        			   return false;
          		  }

          		  if ( $file_name != '' )
          		  {           	   		   $this->open_image ( $file_name );
          		  }
   	 		}

		  /* ---------------------------------------------------------------- *
		   *    Init image params
		   * ---------------------------------------------------------------- */
 	 	   function open_image ( $file_name )
 	 	   {
 	 	  		  if ( $this->error == GD_ERROR )
 	 	  		  { 	 	      		   return false;
 	 	  		  }

 	 	  		  $this->image 		  = false;

 	 	  		  $this->error 		  = false;

 	 	  		  $this->data  		  = array ();

 	 	  		  $this->data['file'] = $file_name;

		          if ( !file_exists ( $this->data['file'] ))
        		  {          				$this->error = NO_IMG_FILE;
          		 		return false;
          		  }

	        	 /* --------------------------------------------------------- *
    		      *    Get file type & size
		          * --------------------------------------------------------- */

		          if ( !$image_info = getimagesize ( $this->data['file'] ) )
        		  {            			$this->error = NO_IMAGE;
						return false;
		          }

		          if ( trim ( $this->data['type'] = $this->types [ $image_info[2] ] ) == '' )
        		  {                	    $this->error = UNKNOWN_TYPE;
                 		return false;
          		  }

		          switch ( $this->data['type'] )
        		  {           				case 'GIF' :
		           			$this->image = @imagecreatefromgif ( $this->data['file'] );
        		   			break;

           				case 'PNG' :
		           			$this->image = @imagecreatefrompng ( $this->data['file'] );
        		   			break;

           	   			case 'JPG' :
           	   				$this->image = @imagecreatefromjpeg ( $this->data['file'] );
		           	   		break;
        		  }

		          $this->data['width']  = imagesx ( $this->image );
        		  $this->data['height'] = imagesy ( $this->image );

		          if ( $this->data['width'] == 0 OR $this->data['height'] == 0 )
        		  {		          	   $this->error = SIZE_ERROR;
        		  	   return false;
		          }
		   }

		  /* --------------------------------------------------------------- *
		   *    Generate thumbnail
	       * --------------------------------------------------------------- */

     	   function thumbnail ( $size, $crop_image = false )
     	   {
		         if ( $this->error )
        		 {		         	  return false;
        		 }

         		 if ( is_numeric ( $size ) AND intval ( $size ) != 0 )
         		 {
		             $size_num = intval ( $size );
        		     $size	   = array ();

		             if ( $this->data['width'] >= $this->data['height'] )
        		     {                		  $size['width']  = $size_num;
			              $size['height'] = ceil ( ( $size['width'] / $this->data['width'] ) * $this->data['height'] );
		             }
        		     else
		             {
        		     	  $size['height'] = $size_num;
              	 		  $size['width']  = ceil ( ( $size['height'] / $this->data['height'] ) * $this->data['width'] );
             		 }
		         }
        		 elseif ( !is_array ( $size ) OR intval ( $size['width'] ) == 0 OR intval ( $size['height'] ) == 0 )
		         {
			       	 $this->error = NO_THUMB_SIZE;
         			 return false;
		         }

        		 if ( $this->image )
		         {
        		     /* ----------------------------------------------------- *
		              *   Need to scale image ?
        		      * ----------------------------------------------------- */

		             if ( $this->data['width'] > $size['width'] OR $this->data['height'] > $size['height'] )
        		     {

                	  /* ---------------------------------------------------- *
	                   *   It's imposible to scale animated images
    	               * ---------------------------------------------------- */

                  		if ( $this->data['type'] == 'GIF' and $this->gif_animated () )
		                {
        					 $this->error = ANIMATED_GIF;                       		 return false;
                  		}
		               	else
        		        {
		                       if ( $this->gd_version == 1 )
        		               {                		       		$_thumb = imagecreate ( $size['width'], $size['height'] );
                       		   }
                       		   else
                       		   {                       				$_thumb = imagecreatetruecolor ( $size['width'], $size['height'] );
                       		   }

                       		   if ( $size['width'] == $size['height'] OR $crop_image )
                       		   {
                            		if ( $this->data['width'] > $this->data['height'] )
                            		{
                               	 		 $this->scale_image ( $_thumb, $this->image, 0, 0, round((max($this->data['width'], $this->data['height']) - min($this->data['width'], $this->data['height']))/2), 0, $size['width'], $size['width'], min($this->data['width'], $this->data['height']), min($this->data['width'], $this->data['height']));
	                        		}

                            		if ( $this->data['width'] < $this->data['height'] )
                            		{                                 		 $this->scale_image ( $_thumb, $this->image, 0, 0, 0, 0, $size['width'], $size['width'], min ( $this->data['width'], $this->data['height'] ), min ( $this->data['width'], $this->data['height'] ));
                            		}

                            		if ( $this->data['width'] == $this->data['height'] )
                            		{                                 		 $this->scale_image ( $_thumb, $this->image, 0, 0, 0, 0, $size['width'], $size['width'], $this->data['width'], $this->data['height'] );
                            		}
		                       }
        			           else
                     		   {
                            		$this->scale_image ( $_thumb, $this->image, 0, 0, 0, 0, $size['width'], $size['height'], $this->data['width'], $this->data['height'] );
                       		   }

                       		   $this->image = $_thumb;
                  		}
             		 }
         		 }
         		 else
         		 {         	 			$this->error = IMG_CREATE_ERROR;
         	 			return $this->error;
         		 }
     	   }

	      /* --------------------------------------------------------------- *
    	   *   Resize image
		   * --------------------------------------------------------------- */

		   function scale_image ( $dist, $source, $x1, $y1, $x2, $y2, $width_1, $height_1, $width_2, $height_2 )
     	   {
     	 		if ( $this->gd_version == 1 )
     	 		{    		  		imagecopyresized   ( $dist, $source, $x1, $y1, $x2, $y2, $width_1, $height_1, $width_2, $height_2 );
		    	}
     	 		else
     	 		{	          		imagecopyresampled ( $dist, $source, $x1, $y1, $x2, $y2, $width_1, $height_1, $width_2, $height_2 );
		     	}
     	   }

		  /* --------------------------------------------------------------- *
		   *    Maybe it's gif animated image ?
		   * --------------------------------------------------------------- */

		   function gif_animated ()
		   {         		$content = @file_get_contents ( $this->data['file'] );

		        if ( trim ( $content ) == '' )
        		{         	  		 $this->error = READ_FILE_ERROR;
         	  		 return false;
         		}

         		$count = 0;
         		$pos	= 0;

         		while ( $count < 2 )
         		{              		$frame_one = strpos ( $content, "\x00\x21\xF9\x04", $pos );

              		if ( $frame_one === false )
              		{
               	   		break;
              		}
              		else
              		{                   		$pos		  = $frame_one + 1;
                   		$frame_two = strpos ( $content, "\x00\x2C", $pos );

                   		if ( $frame_two === false )
                   		{                   			break;
                   		}
                   		else
                   		{                    		if ( $frame_one + 8 == $frame_two )
                    		{                         		 $count++;
                    		}

                    		$pos = $frame_two + 1;
                   		}
              		}
         		}

         		return $count > 1;
     	   }

   		  /* --------------------------------------------------------------- *
		   * 	Saving ?
		   * --------------------------------------------------------------- */

		   function save ( $file_name = '', $source = false )
     	   {
	     		if ( $this->error )
         		{
         	  		 return false;
         		}

         		if ( trim ( $file_name ) == '' )
         		{         	  		 $file_name = $this->data['file'];
         		}

		        if ( !$source )
        		{         	   		  $source = $this->image;
         		}

         		$file_name = preg_replace( "/^(.*)\..+?$/", "\\1", $file_name ) . "." . strtolower ( $this->data['type'] );

		 		switch ( $this->data['type'] )
		 		{	 				case 'GIF' :
	 					@imagegif  ( $source, $file_name );
	 					break;

			 		case 'PNG' :
	 					@imagepng  ( $source, $file_name );
	 					break;

			 		case 'JPG' :
	 					@imagejpeg ( $source, $file_name, $this->quality );
	 					break;
		 		}

         		if ( !file_exists ( $file_name ))
         		{
         	   		  $this->error = SAVE_ERROR;         	   		  return false;
         		}

         		@imagedestroy ( $source );
         		@chmod 	   ( $file_name, 0666 );

         		return $file_name;
     	   }

    	  /* ---------------------------------------------------------------- *
		   *	  Insert watermark!
		   * ---------------------------------------------------------------- */

		   function watermark ( $position = BOTTOM_RIGHT, $source = false, $margin = 7 )
     	   {
			    if ( $this->error )
        		{
		         	  return false;
        		}

		     	if ( !$this->allow_watermark )
     			{        		 	  $this->error = NOT_ALLOWED;
		     		  return false;
		     	}

     			if ( $this->data['type'] == 'GIF' AND $this->gif_animated() )
		     	{     				 $this->error = ANIMATED_GIF;
		     		 return false;
     			}

		     	if ( !$source )
     			{
		     		  $source = $this->image;
     			}

		     	if ( !file_exists ( $this->watermark_light ) OR !file_exists ( $this->watermark_dark )){
		         	$this->error = NO_WATERMARK_FILE;
        		 	return false;
		     	}

        		$image_width  	= imagesx ( $this->image );
		        $image_height 	= imagesy ( $this->image );

        		$watermark_size = getimagesize ( $this->watermark_light );

		     	switch ( $position )
     			{
		     		case TOP_RIGHT :
        		  		$pos_x = $image_width - $margin - $watermark_size[0];
          				$pos_y = $margin;
		    			break;

     				case TOP_LEFT :
     					$pos_x = $margin;
		     			$pos_y = $margin;
     					break;

		     		case BOTTOM_RIGHT :
     				    $pos_x = $image_width  - $margin - $watermark_size[0];
     		    		$pos_y = $image_height - $margin - $watermark_size[1];
		     			break;

     				case BOTTOM_LEFT :
        				$pos_x = $margin;
		          		$pos_y = $image_height - $margin - $watermark_size[1];
     					break;

		     		case CENTER:
     				    $pos_x = ceil ( $image_width  / 2 ) - ceil ( $watermark_size[0] / 2 );
     		    		$pos_y = ceil ( $image_height / 2 ) - ceil ( $watermark_size[1] / 2 );
		     			break;

     				case TOP :
						$pos_x = ceil ( $image_width  / 2 ) - ceil ( $watermark_size[0] / 2 );
		     			$pos_y = $margin;
     					break;

		       		case BOTTOM :
       					$pos_x = ceil ( $image_width  / 2 ) - ceil ( $watermark_size[0] / 2 );
       					$pos_y = $image_height - $margin;
		       			break;

        		 	case LEFT :
         	    		$pos_x = $margin;
		         	    $pos_y = ceil ( $image_height / 2 ) - ceil ( $watermark_size[1] / 2 );
        		 		break;

		         	case RIGHT :
        		 		$pos_x = $image_width - $margin - $watermark_size[0];
         	    		$pos_y = ceil ( $image_height / 2 ) - ceil ( $watermark_size[1] / 2 );
		         		break;

        		 	default :
           				$pos_x = $image_width  - $margin - $watermark_size[0];
		           		$pos_y = $image_height - $margin - $watermark_size[1];
        		 		break;
			   	}

		     	if ( $pos_x < 0 OR $pos_y < 0 OR $watermark_size[0] + $margin > $image_width OR $watermark_size[1] + $margin > $image_height )
     			{
		     		 return false;
     			}

		     	$pixel = imagecreatetruecolor ( 1, 1 );

      			imagecopyresampled ( $pixel, $source, 0, 0, $pos_x, $pos_y, 1, 1, $watermark_size[0], $watermark_size[1] );

		      	$RGB   = imagecolorat ( $pixel, 0, 0 );

      			$red 	= ( $RGB >> 16 ) & 0xFF;
		      	$green 	= ( $RGB >> 8  ) & 0xFF;
      			$blue	= $RGB & 0xFF;

		      	$min 	= min ( $red, $green, $blue );
      			$max 	= max ( $red, $green, $blue );

		      	$light 	= (double)(( $min + $max ) / 510.0 );

		      	@imagedestroy ( $pixel );

				$watermark_image = $light < 0.5 ? $this->watermark_light : $this->watermark_dark;

		        $watermark = @imagecreatefrompng ( $watermark_image );

        		imagealphablending ( $this->image, 	true );
		        imagealphablending ( $watermark, 	true );

		        imagecopy ( $this->image, $watermark, $pos_x, $pos_y, 0, 0, $watermark_size[0], $watermark_size[1] );

		        imagedestroy ( $watermark );
		   }


		 /* ---------------------------------------------------------------- *
    	  *	  Show GD image
	      * ---------------------------------------------------------------- */

	      function show ( $source = false )
	      {		   	 	if ( !$source )
     		 	{     	 		   	  $source = $this->image;
     	 	 	}

	   	 	 	flush();

     	 	 	switch ( $this->data['type'] )
     	 	 	{     	 			case 'GIF' :
		     	 	    @header('Content-type: image/gif');
     			 		break;

		     	 	case 'PNG' :
     			 	    @header('Content-Type: image/png' );
     	 				break;

		     	 	case 'JPG' :
     			 	    @header('Content-Type: image/jpeg' );
     	 				break;
	     	 	}

    	 	 	print_r ( $this->image );
          		exit ();
	      }
  	}

?>