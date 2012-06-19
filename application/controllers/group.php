<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Company Public Controller
 * Displays company information
 * 
 * @package Inthedir
 * @category Controller
 * @author Jose Bolorino
 */

class Group extends Public_Controller {
    
    public $ion_auth;
    
    /**
     * The Company object
     * 
     * @var object
     */
    private $_view_company;
    /**
     * Company in or outside the dir flag
     * 
     * @var bool
     */
    private $_company_inthedir;
    
    private $_is_admin = FALSE;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->load->config('MY_config');
        $this->load->model('company');
        $this->load->model('spectacle');
        
        $this->load->helper('seo_helper');
        
        if ($this->session->userdata('admin') === TRUE)
        {
            $this->_is_admin = TRUE;
            
            $this->vars['admin'] = TRUE;
        }
        
        // Set the object scope in template
        $this->vars['place'] = 'companies';

        // Set the categories corresponding to this object (Companies) for templates
        $this->vars['categories'] = $this->category->get_all_by_lang($this->config->item('language_abbr'));
    }
    
    public function view($normalized_name, $simple = FALSE)
    {
        // @todo header keywords
        
        // Determine if the company exists and if it is in or outside the dir. 
        
        if ( ! $this->_is_inthedir($normalized_name))
        {
            show_error(_('Company not found'), 404);
            exit;
        }
        
        $view_spectacles = FALSE;
        
        $category_normalized_name = ($this->config->item('language_abbr') == 'en' ? $this->_view_company->Category->normalized_name_en : $this->_view_company->Category->normalized_name_es);
        
        if ($this->_company_inthedir == 'inside')
        {
            // Company Inthedir
            
            // Logged in Company not viewing itself: add visit to company counter
            if ( ! $this->session->userdata('ucid') OR $this->_view_company->id != $this->session->userdata('ucid')) 
            { 
                $this->_view_company->add_visit($this->_view_company->id);
            }
            
            $display = 'company';
        }
        else 
        {
            // Company outside the dir
               
            $display = 'company_outside';
            
            // Get similar companies
            $params = array(
                'country'     => $this->_view_company->country, 
                'category_id' => $this->_view_company->category_id, 
                'exclude_id'  => $this->_view_company->id
            );
            
            $related_companies = $this->company->get_similar_companies($params, $limit = 5);
        }
        
        if (isset($this->role) && $this->role == 'manager')
        {
            // User is a manager. Check if the company is in her list
            $this->load->model('manager_agenda');

            // $display is named with the item type
            $item_id = $this->manager_agenda->has_item('company', $this->_view_company->id);
            
            if ($item_id)
            {
                $this->vars['inlist']  = TRUE;
                $this->vars['item_id'] = $item_id; 
            }
        }
        
        // Header SEO
        $header_description = $this->_view_company['company_name'] . '. ' . $this->_view_company['short_description'];
        
        // Set vars for view
        $this->vars['view_company'] = $this->_view_company;
        $this->vars['title']        = $this->_view_company['company_name'];
        
        if ($simple === FALSE)
        {
            // Regular view

            // Get shows
            $view_spectacles = $this->spectacle->get_company_spectacles($this->_view_company->id);
            
            $this->vars['include_js'] = TRUE;
            // Set galleryView plugin
            $this->vars['gallery_plugin'] = TRUE;
            
            $this->vars['header_description']       = set_header_description($header_description);
            $this->vars['category_normalized_name'] = $category_normalized_name;
            $this->vars['content_view']             = 'company';
            
            $this->vars['cid'] = $this->_view_company->id;
            
            if ($this->_company_inthedir == 'outside' && $related_companies)
            {
                $this->vars['related_companies'] = $related_companies;
            }
        }
        else 
        {
            // Get shows
            $view_spectacles = $this->spectacle->get_company_spectacles_simple($this->_view_company->id);
            
            if (isset($item_id) && ! empty($item_id))
            {
                // Company in notebook
                $this->vars['company_menu'] = TRUE;
            }
            
            if ($this->_is_admin === TRUE)
            {
                $company_actions = array(
        	        array(
        	            'text'	    => _('Edit'), 
        	            'action'	=> '/regidor/admin/update_company/' . $this->_view_company->id . '/1', 
        	            'class'		=> 'button'
        	        ), 
        	        array(
        	            'text'		=> _('Add show'), 
        	            'action'	=> '/regidor/admin/add_spectacle/' . $this->_view_company->id, 
        	            'class'		=> 'button'
        	        )
        	    );
        	    
        	    $this->vars['company_actions'] = $company_actions;
            }
            
            $display .= '_simple';
        }
        
        $display .= '.tpl';
        
        $this->vars['view_spectacles'] = $view_spectacles;
        
        $this->load->view($display, $this->vars);
    }
    
    private function _is_inthedir($normalized_name)
    {
        $this->_view_company = $this->company->get_by_normalized_name($normalized_name);
        
        if ( ! $this->_view_company)
        {
            // No company inside nor outside the dir: 404
            return FALSE;
        }
        elseif ($this->_view_company->inthedir == 0)
        {
            // It is a company outside the dir
            $this->_company_inthedir = 'outside';
        }
        else
        {
            // It is inside the dir
            $this->_company_inthedir = 'inside';
        }
        
        return TRUE;
    }
}