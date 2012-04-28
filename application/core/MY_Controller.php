<?php 
/**
 * Global Controller
 * 
 * @package Inthedir
 * @category Controller
 * @author Jose Bolorino
 */

class MY_Controller extends CI_Controller
{
    public $user_id = FALSE;
    public $role;
    
    function __construct()
    {
        parent::__construct();
        
        $this->load->config('MY_config');
        
        $this->itdlang = $this->config->item('language_abbr'); //$lang is a reserved var
        
        // Set error delimiters
        $this->form_validation->set_error_delimiters('', '<br />');

        // Set global template vars
        // @todo check if all these vars should be here. 
        // Maybe some of them could be set in a particular controller (if they are only for 1 controller)

        $this->vars['web_root']              = $this->config->item('web_root');
        $this->vars['static_content']        = $this->config->item('static_content');
        $this->vars['img_path']              = $this->config->item('img_path');
        $this->vars['icon_path']             = $this->config->item('icon_path');
        $this->vars['user_image']            = $this->config->item('user_image');
        $this->vars['user_image_medium']     = $this->config->item('user_image_medium');
        $this->vars['user_image_high']       = $this->config->item('user_image_high');
        $this->vars['user_thumbnail']        = $this->config->item('user_thumbnail');
        $this->vars['user_thumbnail_square'] = $this->config->item('user_thumbnail_square');
        $this->vars['flags_path']            = $this->config->item('flags_path');
        $this->vars['user_document_path']    = $this->config->item('doc_path');
        $this->vars['itd_square_side']       = $this->config->item('itd_square_side');
        $this->vars ['itd_thumb_width']      = $this->config->item('itd_thumb_width');
        $this->vars ['itd_thumb_height']     = $this->config->item('itd_thumb_height');
        
        // Global search form
        $this->vars['notopsearch'] = TRUE;
        
        $this->vars['top_form_attributes'] = array('id' => 'top-searchform', 'method' => 'post');

        $this->vars['q'] = array('name' => 'q',
                'id' => 'q', 
                'type' => 'text'
        );
        
        $this->vars['top_form_submit'] = array('value' => _('Search'));
        
        $this->vars['logged'] = $this->ion_auth->logged_in();
        
        // Default
        $this->vars['admin']               = FALSE;
        $this->vars['fb_js']               = FALSE;
        $this->vars['local']               = LOCAL;
        $this->vars['tweet']               = FALSE;
        $this->vars['title']               = _('Inthedir. Performing Arts Directory');
        $this->vars['place']               = FALSE;
        $this->vars['categories']          = FALSE;
        $this->vars['subcategories']       = FALSE;
        $this->vars['category']            = FALSE;
        $this->vars['subcategory']         = FALSE;
        $this->vars['category_tagline']    = FALSE;
        $this->vars['subcategory_tagline'] = FALSE;
        $this->vars['header_description']  = FALSE;
        $this->vars['print']               = FALSE;
        $this->vars['include_js_trans']    = FALSE;
        $this->vars['include_js']          = TRUE;
        $this->vars['gallery_plugin']      = FALSE;
        $this->vars['opengraph']           = FALSE;
        $this->vars['social']              = FALSE;
        $this->vars['pagination']          = FALSE;
        $this->vars['form_attributes']     = FALSE;
        $this->vars['usermenu']            = FALSE;
        $this->vars['manager_status']      = FALSE;
        
        // Current URL
        $this->vars['current_url'] = $this->config->item('base_url') . $this->uri->uri_string();
        
        // User empty vars
        $this->vars['message'] = '';
        $this->vars['ucid']    = 0;
        $this->vars['role']    = FALSE;
        $this->vars['inlist']  = FALSE;
        
        $this->vars['lang'] = $this->itdlang;
        $this->vars['lang_selector'] = $this->config->item('lang_selector');
        
        if ($this->ion_auth->logged_in()) 
        {
            // If logged in user get basic info for userbar navigation and other global public needs
            $this->user               = $this->ion_auth->get_user();
            $this->vars['first_name'] = $this->user->first_name;
            $this->group              = $this->ion_auth->get_group($this->user->group_id);
            $this->role               = $this->group->name;
            $this->vars['role']       = $this->role;
            
            $this->user_id = $this->user->id;
    		
    		$this->vars['user_picture'] = FALSE;
    		
    	    // JS translations
            $this->vars['delete_item']             = _('Delete');
            $this->vars['cancel']                  = _('Cancel');
            $this->vars['save']                    = _('Save');
            $this->vars['add']                     = _('Add');
            $this->vars['primary_image']           = _('Main image');
            $this->vars['set_primary_image']       = _('Set as default');
            $this->vars['set_primary_image_title'] = _('Use this image as the default for this Show');

    	    $this->vars['company_dashboard_url']     = base_url() . $this->itdlang . '/dashboard';
    	    
            // Set specific role vars
            switch ($this->role)
		    {
		        case 'company':
		            
		            $this->vars['company'] = FALSE;
		            
		            // If the user has a registered company
                    if ($this->user->company_id) 
                    {
                        $this->load->model('company');
                        $this->itdcompany = $this->company->get_by_id($this->user->company_id);

                        $this->vars['ucid']            = $this->user->company_id; // User Company ID
                        $this->vars['company']         = $this->itdcompany;
                        $this->vars['normalized_name'] = $this->itdcompany->normalized_name;
                        $this->vars['company_status']  = $this->itdcompany->status;
                        
                        $this->vars['usermenu'] = 'dashboard/company_menu_new.tpl';
                    }
                    else 
                    {
                        // Something went wrong. A Company role without Company ID
                        $this->ion_auth->logout();
                        redirect();
                    }
                    break;
		        case 'manager':
		            $this->vars['manager_status'] = $this->user->status;
		            $this->vars['usermenu'] = 'manager/manager_menu.tpl';
		            break;
		        case 'admin':
		            $this->vars['usermenu'] = 'admin/admin_menu.tpl';
		            break;
		        default:
		            break;
		    }
        }

        // Benchmark
        /*
        $this->CI = &get_instance();
        $this->vars['elapsed_time'] = $this->CI->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end');
        */
    }
}