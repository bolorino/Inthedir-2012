<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Shows extends Public_Controller 
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->config('MY_config');
        $this->load->model('company');
        $this->load->model('spectacle');
        $this->load->model('spectacle_media');
        $this->load->model('audience');
        $this->load->helper('seo_helper');
        $this->load->library('pagination');
        
        // $this->output->enable_profiler(true);
        
        // Set the object scope in template
        $this->vars['place'] = 'shows';
        
        // Set the categories corresponding to this object (Shows)
        $this->vars['categories']    = $this->category->get_all_by_lang($this->config->item('language_abbr'));
        $this->vars['subcategories'] = $this->audience->get_all_as_category($this->config->item('language_abbr'));
        
        // Set the category and subcategory taglines
        $this->vars['category_tagline']    = _('Type of Show');
        $this->vars['subcategory_tagline'] = _('Audience');
    }
    
    public function view($cat='All', $subcat='All')
    {
        $category_id    = FALSE;
        $subcategory_id = FALSE;
        
        if ($cat && $cat != 'All') 
        {
            // There is category
            $category = $this->category->get_by_name($cat, $this->config->item('language_abbr'), 'shows');
            
            if ( ! $category) 
            {
                show_error(_('The selected category does not exists'), 404);
                exit;
            } 
            elseif ( ! is_array($category)) 
            { // Is a valid category in other language. 
                $new_language_abbr = ($this->config->item('language_abbr') == 'en' ? 'es' : 'en');
                $new_language      = ($this->config->item('language') == 'english' ? 'spanish' : 'english');
                
                $this->config->set_item('language_abbr', $new_language_abbr);
                $this->config->set_item('language', $new_language);
                
                $redirect = base_url() . $category;
                
                redirect($redirect);
                exit;
            }
            else 
            { // Valid category
                // Get shows by category
                $category_id = $category['id'];
            }
        }
        
        // Check subcategory
        if ($subcat && $subcat != 'All') 
        {
            $subcategory    = $this->audience->get_by_name($subcat, $this->config->item('language_abbr'));
            $subcategory_id = $subcategory['id'];
        }
        
        $total_spectacles = $this->spectacle->get_total_spectacles($category_id, $subcategory_id);
        
        // Initialize pagination
        $config['total_rows']     = $total_spectacles;
        $config['per_page']       = $this->config->item('per_page');
        $config['anchor_class']   = 'class="page"';
        $config['cur_tag_open']   = '<span class="current">';
        $config['cur_tag_close']  = '</span>';
        $config['next_tag_open']  = '<span id="next">';
        $config['next_tag_close'] = '</span>';
        $config['prev_tag_open']  = '<span id="prev">';
        $config['prev_tag_close'] = '</span>';
        
        $this->pagination->initialize($config);
        $this->vars['pagination'] = $this->pagination->create_links();
        
        // Get paginated and filtered results
        $spectacles_params = array(
            'category_id' => $category_id, 
            'audience_id' => $subcategory_id, 
            'country'     => FALSE, 
            'orderby'     => 'spectacle_name', 
            'order'       => 'ASC', 
            'offset'      => $this->pagination->offset, 
            'limit'       => $this->config->item('per_page')
        );
        
        $spectacles = $this->spectacle->get_spectacles_array($spectacles_params);
        
        // Build the result's message: (%n||no $category shows for $subcategory)

        if (isset($category))
        {
            $category_name = $category['cat_name'];
        }

        if (isset($subcategory))
        {
            $subcategory_name = $subcategory['audience_name'];
        }

        if (isset($category_name) && isset($subcategory_name))
        {
            $title = sprintf(_('%s shows for %s audience'), $category_name, $subcategory_name);
            
            if (empty($spectacles))
            {
                $results_message = sprintf(_('No %s shows for %s audience yet'), $category_name, $subcategory_name);
            }
            else
            {
                $results_message = sprintf(_('%s %s shows for %s audience'), $total_spectacles, $category_name, $subcategory_name);
            }
        }
        elseif (isset($category_name))
        {
            $title = sprintf(_('%s shows'), $category_name);
            
            if (empty($spectacles))
            {
                $results_message = sprintf(_('No %s shows yet'), $category_name);
            }
            else
            {
                $results_message = sprintf(_('%s %s shows'), $total_spectacles, $category_name);
            }
        }
        elseif (isset($subcategory_name))
        {
            $title = sprintf(_('Shows for %s audience'), $subcategory_name);
            
            if (empty($spectacles))
            {
                $results_message = sprintf(_('No shows for %s audience yet'), $subcategory_name);
            }
            else
            {
                $results_message = sprintf(_('%s shows for %s audience'), $total_spectacles, $subcategory_name);
            }
        }
        else 
        {
            $title = _('Performing Arts shows');
            
            // No category neither subcategory
            if (empty($spectacles))
            {
                $results_message = _('No Performing Arts shows found');
            }
            else {
                $results_message = sprintf(_('%s Performing Arts shows'), $total_spectacles);
            }
        }
        
        $this->vars['title'] = $title;
        
        // Activate infinite (sort of) scroll
        $this->vars['scroll'] = TRUE;
        
        $this->vars['audience']                 = (isset($audience) ? $audience['audience_name'] : 'All');
        $this->vars['category_normalized_name'] = (isset($category) ? $category['normalized_name'] : 'All');
        $this->vars['subcategory']              = (isset($subcategory) ? $subcategory['normalized_name'] : 'All');
        $this->vars['spectacles']               = $spectacles;
        $this->vars['results_message']          = (isset($results_message) ? $results_message : FALSE);
        
        $this->vars['total_spectacles'] = $total_spectacles;
        $this->vars['message']   = (isset($message) ? $message : '');
        
        $this->load->view('spectacles_list.tpl', $this->vars);
    }
}