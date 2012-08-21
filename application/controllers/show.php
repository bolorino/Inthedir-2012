<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Show extends Public_Controller 
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->config('MY_config');
        $this->load->model('company');
        $this->load->model('spectacle');
        $this->load->model('spectacle_category');
        $this->load->model('spectacle_media');
        $this->load->model('audience');
        $this->load->model('offer');
        
        $this->load->library('curl');
        $this->load->library('oembed');
        
        $this->load->helper('seo_helper');
        
        // Set the object scope in template
        $this->vars['place'] = 'shows';
        
        // Set the categories corresponding to this object (Show)
        $this->vars['categories']    = $this->category->get_all_by_lang($this->config->item('language_abbr'));
        $this->vars['subcategories'] = $this->audience->get_all_as_category($this->config->item('language_abbr'));
        
        // Set the category and subcategory taglines
        $this->vars['category_tagline']    = _('Type of Show');
        $this->vars['subcategory_tagline'] = _('Audience');
    }
    
    public function view($normalized_name, $simple = FALSE)
    {
        $view_spectacle = $this->spectacle->get_by_normalized_name($normalized_name);
        
        if ( ! $view_spectacle)
        {
            show_error(_('Spectacle not found'), 404);
        }
        
        $view_company = $this->company->get_basic_info($view_spectacle->company_id);
        
        if ( ! $this->session->userdata('ucid') OR $view_spectacle->company_id != $this->session->userdata('ucid')) 
        { // Logged in Company not viewing itself: add visit to company counter
            $view_spectacle->add_visit($view_spectacle->id);
        }
        
        // Get show categories. 
        // @todo What to do with a multidisciplinary show? 
        $spectacle_categories_id = $this->spectacle_category->get_spectacle_categories($view_spectacle->id);
        
        if (count($spectacle_categories_id) > 1 )
        {
            foreach ($spectacle_categories_id as $spectacle_category)
            {
                $spectacle_categories       = $this->category->get_by_id($spectacle_category['category_id'], $this->config->item('language_abbr'));
                $category_normalized_name[] = $spectacle_categories['normalized_name'];
                $spectacle_categories       = NULL;
            }
        }
        else 
        {
            /* @todo this is just a test to retrieve one of the categories
             * master.tpl should check and mark multiple categories for one show
             */ 
            $spectacle_category       = $this->category->get_by_id($spectacle_categories_id[0]['category_id'], $this->config->item('language_abbr'));
            $spectacle_category_name  = $spectacle_category['cat_name'];
            $category_normalized_name = $spectacle_category['normalized_name'];
        }
        
        $subcategory   = ($this->config->item('language_abbr') == 'en' ? $view_spectacle->Audience->normalized_name_en : $view_spectacle->Audience->normalized_name_es);
        $audience_name = ($this->config->item('language_abbr') == 'en' ? $view_spectacle->Audience->audience_name_en : $view_spectacle->Audience->audience_name_es);
        
        // Show images
        $images_params = array(
            'media_type' => 'image', 
            'orderby'    => 'created_at', 
            'order'	     => 'DESC'
        );
        
        $view_spectacle_images = $this->spectacle_media->get_spectacle_media($view_spectacle->id, $images_params);
        
        // Main image
        $main_image = $this->spectacle_media->get_main_spectacle_image($view_spectacle->id);
        
        if (isset($this->role) && $this->role == 'manager') // User is a manager. Check if the show is in his list
        {
            $this->load->model('manager_agenda');
            
            if ($this->manager_agenda->has_item('spectacle', $view_spectacle->id))
            {
                $this->vars['inlist'] = TRUE;
            }
        }
        
        // Show videos
        $this->vars['view_spectacle_videos'] = FALSE; 
        
        $videos_params = array(
            'media_type' => 'video', 
            'orderby'    => 'created_at', 
            'order'	     => 'DESC'
        );
        
        $view_spectacle_videos = $this->spectacle_media->get_spectacle_media($view_spectacle->id, $videos_params);
        
        if ($view_spectacle_videos)
        {
            $videos = array();
		    
		    foreach ($view_spectacle_videos as $spectacle_video)  
		    {
		        $video_data = $this->oembed->get_oembed($spectacle_video['media']);
		        
		        if ($video_data)
		        {
		            if ($video_data->provider_name == 'YouTube')
    		        {
    		            // Get the tiny thumbnail instead the default big one provided (with hq prefix)
    		            $thumbnail = preg_replace('/hqdefault.jpg/', 'default.jpg', $video_data->thumbnail_url);
    		        }
    		        else
    		        {
    		            $thumbnail = $video_data->thumbnail_url;
    		        }
    		        
    		        $videos[] = array(
                        'id'        => $spectacle_video['id'], 
                        'thumbnail' => $thumbnail, 
                        'embed'     => $video_data->html
    		        );
		        }
		    }
		    
		    $this->vars['view_spectacle_videos'] = $videos;
        }
        
        $template = 'spectacle_simple.tpl';
        
        // Other shows
        if ($simple === FALSE)
        {
            $view_company_spectacles = $this->spectacle->get_company_spectacles($view_spectacle->company_id, $view_spectacle->id); // Exclude the current show
            $this->vars['view_company_spectacles'] = $view_company_spectacles;
            
            $template = 'spectacle.tpl';
        }

        // Open offers
        $spectacle_offers = FALSE;
        $spectacle_offers = $this->offer->get_spectacle_open_offers($view_spectacle->id);
        
        // Header SEO
        $header_description = $view_spectacle->spectacle_name . '. ' . $view_spectacle->short_description;
        
        // Set vars for view        
        $this->vars['include_js'] = TRUE;
        // Set galleryView plugin
        $this->vars['gallery_plugin'] = TRUE;
        
        $this->vars['title']              = $view_spectacle->spectacle_name;
        $this->vars['header_description'] = set_header_description($header_description);
        $this->vars['view_spectacle']     = $view_spectacle;
        $this->vars['view_company']       = $view_company;
        
        $this->vars['view_spectacle_images']    = $view_spectacle_images;
        $this->vars['main_image']               = $main_image;
        $this->vars['show_category']            = (isset($spectacle_category_name) ? $spectacle_category_name : FALSE);
        $this->vars['category_normalized_name'] = $category_normalized_name;
        $this->vars['subcategory']              = $subcategory;
        $this->vars['audience_name']            = $audience_name;
        
        $this->load->view($template, $this->vars);
    }
    
}