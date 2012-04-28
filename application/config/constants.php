<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* File uploads */
// Absolute path for images upload
define('IMG_UPLOAD_PATH', '/var/www/inthedir/images/upload/');
// Absolute path for user images
define('IMG_USER_PATH', '/var/www/inthedir/images/photos/');
// Absolute path for medium size images
define('IMG_MEDIUM_PATH', IMG_USER_PATH . 'medium/');
// Absolute path for full size images
define('IMG_HIGH_PATH', IMG_USER_PATH . 'high/');
// Absolute path for thumbnail images
define('IMG_THUMB_PATH', IMG_USER_PATH . 'thumbnails/');
// Absolute path for square thumbnail images
define('IMG_SQUARE_PATH', IMG_THUMB_PATH . 'square/');

/* S3 Amazon storage */
// Bucket name
define('S3_BUCKET', '');
// User images path
define('S3_IMG_USER_PATH', 'images/photos/');
// Medium size images
define('S3_IMG_MEDIUM_PATH', S3_IMG_USER_PATH . 'medium/');
// Full size images
define('S3_IMG_HIGH_PATH', S3_IMG_USER_PATH . 'high/');
// Thumbnail images
define('S3_IMG_THUMB_PATH', S3_IMG_USER_PATH . 'thumbnails/');
// Square thumbnails
define('S3_IMG_SQUARE_PATH', S3_IMG_THUMB_PATH . 'square/');

/* App notifications email */
define('MESSAGE_SENDER_EMAIL', '');

/* Devel. Set to FALSE in production */
define('LOCAL', TRUE);

/* End of file constants.php */
/* Location: ./application/config/constants.php */