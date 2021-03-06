<?php
exec ("sudo php usb_script");

$action = '';

if (isset($_GET['action'])){
	$action = $_GET['action'];
}


$returnObj;

try{
	switch($action){

		case "takePicture":
			$directory = "/var/www/html/Hochzeit/";
			$name = date("d-m-Yh:i:s"); 
			$fullPath = $directory.$name.'.jpg';
			exec ("sudo gphoto2 --capture-image-and-download --filename=$fullPath  --force-overwrite", $output);
			echo json_encode($name);
			break;

		case "takePreview":
			exec ("sudo gphoto2 --capture-preview --filename=\"preview.jpg\"  --force-overwrite", $output);
			echo json_encode($output);
			break;

		case "getImage":
			$file = $_GET['file'];
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.$file.'"');
			header('Content-Length: '.filesize('images/'.$file));
			$fp = fopen('images/'.$file, 'rb');
			fpassthru($fp);
			exit;
			break;


		case "getCamera":

			exec ("gphoto2 --auto-detect", $output);
			$returnObj->camera = trim(explode("usb", $output[count($output) - 1])[0]);
			header('Content-Type: application/json');
			echo json_encode($returnObj);

			break;

		case "getImages":

			$files = array();
			$imageDir = opendir('images');
			while (($file = readdir($imageDir)) !== false) {
				if(!is_dir('images/'.$file)){
					$path_parts = pathinfo('images/'.$file);
					if (!file_exists('images/thumbs/'.$path_parts['basename'].'.jpg')){
						try { //try to extract the preview image from the RAW
							CameraRaw::extractPreview('images/'.$file, 'images/thumbs/'.$path_parts['basename'].'.jpg');
						} catch (Exception $e) { //else resize the image...
							$im = new Imagick('images/'.$file);
							$im->setImageFormat('jpg');
							$im->scaleImage(1024,0);
							$im->writeImage('images/thumbs/'.$path_parts['basename'].'jpg');
							$im->clear();
							$im->destroy();
						}
					}
					$returnFile;
					$returnFile->name = $path_parts['basename'];
					$returnFile->sourcePath = 'images/'.$file;
					$returnFile->thumbPath = 'images/thumbs/'.$path_parts['basename'].'.jpg';

					array_push($files,$returnFile);

					unset($returnFile);
				}
			}
			closedir($imageDir);
			$returnObj = $files;
			header('Content-Type: application/json');
			echo json_encode($returnObj);
			break;
		default:
			break;
	}
} catch (Exception $e) { //else resize the image...

}

?>