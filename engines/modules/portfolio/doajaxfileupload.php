<?php

@session_start ();
@ob_start ();
@ob_implicit_flush ( 0 );
error_reporting ( E_ALL ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_NOTICE );
define ( "DATALIFEENGINE",	true );
define ( "ENGINE_DIR",	dirname ( dirname ( dirname ( __FILE__ ))));
define ( "ROOT_DIR",	dirname ( ENGINE_DIR ));
require_once ENGINE_DIR . '/modules/portfolio/classes/class_image.php';
require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
$allowed_ext = array ( 'png', 'jpg', 'jpeg', 'gif' );
require_once ENGINE_DIR . "/modules/functions.php";
$error = "";
$msg = "";
$fileElementName = 'fileToUpload';
$allowed_ext = array ( 'png', 'jpg', 'jpeg', 'gif' );
$i = 0;

$user_id = intval ( $_REQUEST [ 'user_id' ] );
	if ( $user_id == 0 )
	{		die ();
	}



$files_count = sizeof($_FILES[$fileElementName]["name"]);

for ($i = 0; $i < $files_count-1; $i++) {
	if(!empty($_FILES[$fileElementName]['error'][$i]))
	{
		switch($_FILES[$fileElementName]['error'][$i])
		{

			case '1':
				$error = 'размер загруженного файла превышает размер установленный параметром upload_max_filesize  в php.ini ';
				break;
			case '2':
				$error = 'размер загруженного файла превышает размер установленный параметром MAX_FILE_SIZE в HTML форме. ';
				break;
			case '3':
				$error = 'загружена только часть файла ';
				break;
			case '4':
				$error = 'файл не был загружен (Пользователь в форме указал неверный путь к файлу). ';
				break;
			case '6':
				$error = 'неверная временная дирректория';
				break;
			case '7':
				$error = 'ошибка записи файла на диск';
				break;
			case '8':
				$error = 'загрузка файла прервана';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	}elseif(empty($_FILES[$fileElementName]['tmp_name'][$i]) || $_FILES[$fileElementName]['tmp_name'][$i] == 'none')
	{
		$error = 'No file was uploaded..';
	}else {
                        $file_name = strtolower ( totranslit ( $_FILES[$fileElementName]['name'][$i] ));
                        $temp_name = $_FILES[$fileElementName]['tmp_name'][$i];
                        $upload_folder = ROOT_DIR . '/uploads/portfolio/sample/' . $user_id . '/';
                        $mini_folder = $upload_folder . 'mini/';

                        if ( ! file_exists ( $upload_folder )){
                            @mkdir ( $upload_folder, 0777 );
                            @chmod ( $upload_folder, 0777 );
                        }
                        if ( ! file_exists ( $mini_folder )){
                            @mkdir ( $mini_folder, 0777 );
                            @chmod ( $mini_folder, 0777 );
                        }
                        if (file_exists($upload_folder . $file_name)){
                            $error = $file_name . " уже существует. ";
                        }else{

                                $msg .= " File Name: " . $file_name . "<br/>";
                                $msg .= " File Type: " . $_FILES[$fileElementName]['type'][$i] . "<br/>";
                                $msg .= " File Size: " . (@filesize($temp_name)/ 1024)."Kb";

                                $file_ext  = strtolower( end ( explode ( ".", $file_name )));

                                if ( in_array ( $file_ext, $allowed_ext )){
                                    @move_uploaded_file( $temp_name, $upload_folder . $file_name );
                                    if ( file_exists ( $upload_folder. $file_name )){
                                        $image = new class_image ( $upload_folder . $file_name );
                                        $image->thumbnail ( 150 );
                                        $image->save ( $mini_folder . $file_name );
                                        $db->query ( "INSERT INTO " . PREFIX . "_portfolio_images  ( user_id, image_name ) VALUES ( '{$user_id}','{$file_name}' )");
                                    } else { $error = $file_name . "not permission to write in upload folder"; }
                                } else { $error = $file_name . "ext. not allowed"; }
                        }
                        //for security reason, we force to remove all uploaded file
                        @unlink($_FILES[$fileElementName][$i]);
            }
        echo "{";
        echo				"error: '" . $error . "',\n";
        echo				"msg: '" . $msg . "'\n";
        echo "}";
}
?>