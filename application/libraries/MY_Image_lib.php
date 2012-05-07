<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Image_lib extends CI_Image_lib
{
    private $CI;

    public function __construct()
    {
        parent::__construct();
        
        $this->CI =& get_instance();
        $this->CI->load->library('s3');
        
    }
    
    public function itd_process_images($uploaded, $new_image_name)
    {
        // Full resolution image
        $config['image_library'] = 'GD2';
        $config['source_image'] = $uploaded['full_path'];
        // High resolution (or at least original uploaded size) image
        $config['new_image'] = $this->CI->config->item('image_high_path') . $new_image_name;

        $this->CI->image_lib->clear();
        $this->CI->image_lib->initialize($config);
        
        if ( ! $this->CI->image_lib->resize())
        {
            return $this->CI->image_lib->display_errors();
        }
        
        // Move to Amazon S3
        if (! LOCAL) {
            $this->CI->s3->putObject($this->CI->s3->inputFile($config['new_image']), S3_BUCKET, S3_IMG_HIGH_PATH . $new_image_name);
        }

        // Medium image
        $config['image_library']  = 'GD2';
        $config['source_image']   = $uploaded['full_path'];
        $config['new_image']      = $this->CI->config->item('image_medium_path') . $new_image_name;
        $config['maintain_ratio'] = TRUE;
        $config['quality']        = $this->CI->config->item('quality');
        $config['width']          = $this->CI->config->item('itd_med_width');
        $config['height']         = $this->CI->config->item('itd_med_height');
        
        $this->CI->image_lib->clear();
        $this->CI->image_lib->initialize($config);
        
        if ( ! $this->CI->image_lib->resize())
        {
            return $this->CI->image_lib->display_errors();
        }
        
        // Move to Amazon S3
        if (! LOCAL) {
            $this->CI->s3->putObject($this->CI->s3->inputFile($config['new_image']), S3_BUCKET, S3_IMG_MEDIUM_PATH . $new_image_name);
        }
        
        //Main image
        $config['image_library']  = 'GD2';
        $config['source_image']   = $uploaded['full_path'];
        $config['new_image']      = $this->CI->config->item('image_path') . $new_image_name;
        $config['maintain_ratio'] = TRUE;
        $config['quality']        = $this->CI->config->item('quality');
        $config['width']          = $this->CI->config->item('itd_width');
        $config['height']         = $this->CI->config->item('itd_height');

        $this->CI->image_lib->clear();
        $this->CI->image_lib->initialize($config);
        
        if ( ! $this->CI->image_lib->resize())
        {
            return $this->CI->image_lib->display_errors();
        }
        
        // Move to Amazon S3
        if (! LOCAL) {
            $this->CI->s3->putObject($this->CI->s3->inputFile($config['new_image']), S3_BUCKET, S3_IMG_USER_PATH . $new_image_name);
        }

        /* Thumbnail */
        $config['image_library'] = 'GD2';
        // Work on the previous resized image instead of uploaded one for lower memory comsumption
        $config['source_image']   = $this->CI->config->item('image_path') . $new_image_name;
        $config['new_image']      = $this->CI->config->item('image_thumb_path') . $new_image_name;
        $config['maintain_ratio'] = FALSE;
        $config['quality']        = $this->CI->config->item('quality_high');
        
        // Make it square if portrait
        $cropped = FALSE;
        
        // Get original image data
        $img_data = $this->getSize($this->CI->config->item('image_path') . $new_image_name);
        
        $config['width']  = $this->CI->config->item('itd_thumb_width'); // $img_data['width']
        $config['height'] = $this->CI->config->item('itd_thumb_height'); // $img_data['width']  
        
        // Crop only if image is portrait
        if ($img_data['height'] > $img_data['width']) 
        {
            $config['y_axis'] = ($img_data['height'] - $config['height']) / 2;
            
            // Crop image
            $this->CI->image_lib->initialize($config);
            
            if (! $this->CI->image_lib->crop()) 
            {
                return $this->CI->image_lib->display_errors();
            }
            
            $cropped = TRUE;
        }
        else 
        {
            $this->CI->image_lib->initialize($config);
            
            if ( ! $this->CI->image_lib->resize())
            {
                return $this->CI->image_lib->display_errors();
            }
        }
        
        // Move to Amazon S3
        if (! LOCAL) {
            $this->CI->s3->putObject($this->CI->s3->inputFile($config['new_image']), S3_BUCKET, S3_IMG_THUMB_PATH . $new_image_name);
        }

        $this->CI->image_lib->clear();
        
        /* Square thumbnail */
        $config['image_library'] = 'GD2';
        // Work on the thumbnail instead of uploaded image for lower memory comsumption
        $config['source_image']   = $this->CI->config->item('image_path') . $new_image_name;
        $config['new_image']      = $this->CI->config->item('image_square_path') . $new_image_name;
        $config['maintain_ratio'] = FALSE;
        $config['quality']        = $this->CI->config->item('quality_high');
        
        // Get thumbnail data
        $img_data = $this->getSize($this->CI->config->item('image_path') . $new_image_name);
        
        // Crop only if image is not square yet
        if ($img_data['width'] != $img_data['height']) 
        {
            // Set x and y axis for cropping from the center of the image.
            if ($img_data['width'] > $img_data['height']) 
            { // Landscape, crop left & right
                $config['width']  = $img_data['height'];
                $config['height'] = $img_data['height'];
                $config['x_axis'] = ($img_data['width'] - $config['width']) / 2;
            }
            else 
            { // Portrait, crop top & bottom
                $config['width']  = $img_data['width'];
                $config['height'] = $img_data['width'];
                $config['y_axis'] = ($img_data['height'] - $config['height']) / 2;
            }
            
            // Crop image
            $this->CI->image_lib->initialize($config);
            
            if (! $this->CI->image_lib->crop()) 
            {
                return $this->CI->image_lib->display_errors();
            }
            
            $cropped = TRUE;
        }
        
        // Resize
        $config['image_library']  = 'GD2';
        $config['source_image']   = $this->CI->config->item('image_square_path') . $new_image_name;
        
        $config['new_image']      = $this->CI->config->item('image_square_path') . $new_image_name;
        $config['maintain_ratio'] = FALSE;
        $config['quality']        = $this->CI->config->item('quality_high');
        $config['width']          = $this->CI->config->item('itd_square_side');
        $config['height']         = $this->CI->config->item('itd_square_side');
        
        $this->CI->image_lib->initialize($config);
        
        if ( ! $this->CI->image_lib->resize())
        {
            return $this->CI->image_lib->display_errors();
        }
        
        // Move to Amazon S3
        if (! LOCAL) {
            $this->CI->s3->putObject($this->CI->s3->inputFile($config['new_image']), S3_BUCKET, S3_IMG_SQUARE_PATH . $new_image_name);
        }
        
        return TRUE;
    }
    
    public function getSize ($image)
    {
        $imgData = getimagesize($image);
        
        $retval['width']  = $imgData[0];
        $retval['height'] = $imgData[1];
        $retval['mime']   = $imgData['mime'];
        
        return $retval;
    }
}
