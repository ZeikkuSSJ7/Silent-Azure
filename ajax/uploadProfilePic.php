<?php
include '../config.php';

if (isset($_FILES['image'])) {
	
	$file_name = getUserToken();
	$file_tmp = $_FILES['image']['tmp_name'];
	$file_ext = explode('.', $_FILES['image']['name']);
	$file_ext = 'png';

	$file_name = $file_name . '.' . $file_ext;

	// $extensions = array("jpeg", "jpg", "png");

	// if (in_array($file_ext, $extensions) === false) {
	// 	$errors[] = "extension not allowed, please choose a JPEG or PNG file.";
	// }
	$location = IMAGES_ROOT_LOCAL . USER_IMAGE_DIR . '/' . $file_name;
	$src =  USER_IMAGE_DIR . '/' . $file_name . '?nocache=' . rand();


    move_uploaded_file($file_tmp, $location);
	
	echo $src;
}
?>