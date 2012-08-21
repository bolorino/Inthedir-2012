<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter Oembed Class
 *
 * Simple class to embed video from YouTube and Vimeo
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Jose Bolorino
 * @license         http://
 * @link            http://
 */
class Oembed {
    private $_ci;                          // CodeIgniter instance
    
    private $_services = array();          // Available services with oembed base URLs
	private $_services_base_url = array(); // Fetch URLs
	
	public $width = 500;                   // Default object width
	
	private $_service;                     // Selected service
	
	public function __construct()
	{
	    $this->_ci =& get_instance();
        log_message('debug', 'Oembed Class Initialized');

        // Set available services with oembed base URLs
        $this->_services = array(
            'vimeo'     => 'http://vimeo.com/api/oembed.json?url=',
            'youtube'   => 'http://www.youtube.com/oembed?url='
        );

        // Set the base URL for each service
        $this->_services_base_url = array(
            'vimeo'   => 'http://vimeo.com/', 
            'youtube' => 'http://www.youtube.com/watch?v='
        );
	}
	
    /**
     * Gets the oembed code
     * 
     * @param string $url
     * 
     * @return string
     */

    public function get_oembed($url)
    {
        $this->_is_valid_service($url);
        
        if ( ! $this->_service)
        {
            return FALSE;
        }
        
        $video_url = $this->get_clean_url($url); 
        
        $embed_url = $this->_services[$this->_service] . urlencode($video_url) . '&width=' . $this->width;
        
        $petition = new Curl();
	    
	    $result = $petition->_simple_call('get', $embed_url);
	    
	    if (! $result)
	    {
	        return FALSE;
	    }
        
        return json_decode($result);
    }
    
    public function get_clean_url($url)
    {
        $video_url = $this->_services_base_url[$this->_service] . $this->_get_video_code($url);
        
        return $video_url;
    }
    
    private function _get_video_code($url) 
    {
        switch ($this->_service) {
            case 'youtube':
                $to_explode = '?v=';
                //$base_url = 'http://www.youtube.com/watch?v=';
            break;
            
            case 'vimeo':
                $to_explode = '.com/';
                //$base_url = 'http://vimeo.com/';
            break;
        }
        
    	$parts = explode($to_explode, $url);
    	
    	if (count($parts) == 2) 
    	{
    		$tmp = explode('&',$parts[1]);
    		
    		if (count($tmp) > 1) 
    		{
    			$video_code = $tmp[0];
    		} 
    		else 
    		{
    			$video_code = $parts[1];
    		}
    	}
    	else 
    	{
    		return FALSE;
    	}
    	
    	return $video_code;
    }
    
    private function _is_valid_service($url)
    {
        
        if (preg_match('/vimeo\.com/', $url)) 
        {
            $this->_service = 'vimeo';
        }
        elseif (preg_match('/youtube\./', $url))
        {
            $this->_service = 'youtube';
        }
        
    }
}