<?php
/* Smarty globals */
$config['web_root']              = '/';
$config['static_content']        = ''; // http://bucketname.s3.amazonaws.com/ OR http://cname.domain.tld/
$config['img_path']              = $config['web_root'] . 'images/';
$config['icon_path']             = $config['img_path'] . 'icons/';
$config['user_image']            = $config['static_content'] . 'photos/';
$config['user_image_medium']     = $config['user_image'] . 'medium/';
$config['user_image_high']       = $config['user_image'] . 'high/';
$config['user_thumbnail']        = $config['user_image'] . 'thumbnails/';
$config['user_thumbnail_square'] = $config['user_thumbnail'] . 'square/';
$config['flags_path']            = $config['img_path'] . 'flags/';

$config['lang_selector'] = alt_site_url();

/* Upload */
/* Absolute files path from constants*/
$config['upload_path']       = IMG_UPLOAD_PATH;
$config['image_path']        = IMG_USER_PATH;
$config['image_medium_path'] = IMG_MEDIUM_PATH;
$config['image_high_path']   = IMG_HIGH_PATH;
$config['image_thumb_path']  = IMG_THUMB_PATH;
$config['image_square_path'] = IMG_SQUARE_PATH;

$config['allowed_types'] = 'jpg|jpeg';

// Images settings
$config['max_size']         = 4096; //4 MB
$config['quality']          = 85;
$config['quality_high']     = 95;
$config['maintain_ratio']   = TRUE;
// Main image
$config['itd_width']        = 400;
$config['itd_height']       = 300;
// Medium image
$config['itd_med_width']    = 800;
$config['itd_med_height']   = 600;
// Thumbnail image
$config['itd_thumb_width']  = 175;
$config['itd_thumb_height'] = 125;
// Square image
$config['itd_square_side']  = 90;

/* Social Media */
$config['available_social_media'] = array('Twitter');

// Pagination 
$config['per_page'] = 10;

// Normalized names length
$config['user_normalized_name_max_length']      = 15;
$config['spectacle_normalized_name_max_length'] = 60;
$config['company_normalized_name_max_length']   = 60;

// Email
$config['itd_contact_email'] = '';

// Controllers for informative email URLs
$config['forgot_password_controller']     = 'access/forgot_password'; 
$config['contact_controller']             = 'page/contact';
$config['cancel_registration_controller'] = 'access/cancel_registration/';