<?php 
//usb_script for auto recognizing camera
exec ("php usb_script");

//take the picture and save it as img.jpg
exec ("sudo gphoto2 --capture-image-and-download --filename=\"img.jpg\"  --force-overwrite", $output);

echo json_encode(array(
		'success' => true
	));

 ?>
