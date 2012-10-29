<?php

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('inc/fineuploader.php');

$allowedExtensions = explode(',', ALLOWED_EXTENSIONS);
$sizeLimit = MAX_FILE_SIZE;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);

$result = $uploader->handleUpload( plugin_dir_path( __FILE__ ). 'uploads', TRUE );

echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

/****************************************
Example of how to use this uploader class...
You can uncomment the following lines (minus the require) to use these as your defaults.

// list of valid extensions, ex. array("jpeg", "xml", "bmp")

// max file size in bytes

require('valums-file-uploader/server/php.php');
$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);

// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
$result = $uploader->handleUpload('uploads/');

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

/******************************************/

?>
