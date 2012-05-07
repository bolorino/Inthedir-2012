<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Companies Controller
 * Arranges the lists of companies
 * 
 * @package Inthedir
 * @category Controller
 * @author Jose Bolorino
 */
class Companies extends My_Controller 
{
    
    public $ion_auth;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('company');
        $this->load->model('country');
        $this->load->library('pagination');
        
        // Set the object scope in template
        $this->vars['place'] = 'companies';
        
        // Set the categories corresponding to this object (Companies)
        $this->vars['categories'] = $this->category->get_all_by_lang($this->config->item('language_abbr'));
    }
    
    public function index()
    {
        redirect('companies/All');
        
    }
    
    /**
     * Gets array of companies by valid category
     * @param string $cat category
     * @param string $letter 
     * @return void
     */
    public function view($cat='All', $letter=FALSE)companies
    {
        /*
         * @todo: Get records by Country
         * Right now Country is set by post.
         * 
         */ 
        
        $selected_country      = FALSE;
        $selected_country_name = FALSE;
        $category_id           = FALSE;

        if (strlen($letter) > 1)
        {
            $letter = FALSE;
        }
        
        if ($this->input->post('country'))
        {
            // Check if a valid Country code is provided
            if ($selected_country_name = Country::get_country_name($this->input->post('country')))
            {
                $selected_country = $this->input->post('country');
            }
        }
        
        if (strlen($cat) > 1 && $cat != 'All') 
        {
            $category = $this->category->get_by_name($cat, $this->config->item('language_abbr'), 'companies');

            if ( ! $category) 
            {
                show_error(_('The selected category does not exists'), 404);
                exit;
            }
            elseif ( ! is_array($category)) 
            { // Is a valid category in other language. 
                $new_language_abbr = ($this->config->item('language_abbr') == 'en' ? 'es' : 'en');
                $new_language = ($this->config->item('language') == 'english' ? 'spanish' : 'english');
                
                $this->config->set_item('language_abbr', $new_language_abbr);
                $this->config->set_item('language', $new_language);
                
                $redirect = base_url() . $category;
                
                redirect($redirect);
                exit;
            } 
            else 
            { // Valid category
                $category_id = $category['id'];
            }   
        }

        // Begins with character
        if (strlen($cat) == 1)
        {
            $letter = $cat;
        }
        
        // Total companies
        $count_companies_params = array(
            'category_id'	=> $category_id, 
            'country'		=> $selected_country, 
            'letter'        => $letter
        );
        
        $total_companies = $this->company->get_total_companies($count_companies_params);
        
        // Initialize pagination
        $config['total_rows']      = $total_companies;
        $config['per_page']        = $this->config->item('per_page');
        $config['anchor_class']    = 'class="page"';
        $config['first_link']      = _('First');
        $config['last_link']       = _('Last');
        $config['next_link']       = _('Next') . ' &gt;';
        $config['prev_link']       = '&lt; ' . _('Previous');
        $config['first_tag_open']  = '<span id="first">';
        $config['first_tag_close'] = '</span>';
        $config['last_tag_open']   = '<span id="last">';
        $config['last_tag_close']  = '</span>';
        $config['cur_tag_open']    = '<span class="current">';
        $config['cur_tag_close']   = '</span>';
        $config['next_tag_open']   = '<span id="next">';
        $config['next_tag_close']  = '</span>';
        $config['prev_tag_open']   = '<span id="prev">';
        $config['prev_tag_close']  = '</span>';
        
        $this->pagination->initialize($config);
        $this->vars['pagination'] = $this->pagination->create_links();
        
        // Get paginated results
        $params = array(
            'category' => $category_id, 
            'country'  => $selected_country, 
            'letter'   => $letter, 
            'orderby'  => 'company_name', 
            'order'    => 'ASC', 
            'offset'   => $this->pagination->offset, 
            'limit'    => $this->config->item('per_page')
        );
        
        $companies = $this->company->get_companies_array($params);
        
        // Build the result's message: (%n||no $category companies)
        // @todo Country
        
        if (isset($category))
        {
            $category_name = $category['cat_name'];
            
            if (empty($companies))
            {
                $results_message = sprintf(_('No %s companies yet'), $category_name);
            }
            else
            {
                $results_message = sprintf(_('%s %s companies'), $total_companies, $category_name);
            }
        }
        else 
        {
            // No categoy
            if (empty($companies))
            {
                $results_message = _('No Performing Arts companies found');
            }
            else 
            {
                $results_message = sprintf(_('%s Performing Arts companies'), $total_companies);
            }
        }
        
        // Set title
        $title = _('Performing Arts companies');
        
        if (isset($category_name)) 
        {
            $title = sprintf(_('%s companies'), $category_name);
        }

        if ($letter)
        {
            $letter_result   = '“' . strtoupper($letter) . '” ';
            $results_message .=  ' ' . sprintf(_('that start with %s'), $letter_result);
            $title           .= ': ' . sprintf(_('Letter %s'), strtoupper($letter));
        }
        
        // Get AlphaBet
        $letters_params = array(
            'category'  => $category_id
        );

        $available_letters = $this->company->get_available_alphabet($letters_params);

        $this->vars['title'] = $title;
        
        // Activate infinite (sort of) scroll
        $this->vars['scroll'] = TRUE;
        
        $this->vars['form_attributes']          = array('id' => 'selectcountry'); 
        
        $this->vars['available_countries']      = $this->company->get_available_countries($category_id);
        $this->vars['alphabet']                 = $available_letters;
        $this->vars['current_letter']           = $letter;
        
        $this->vars['category_normalized_name'] = (isset($category) ? $category['normalized_name'] : 'All');
        $this->vars['category_name']            = (isset($category) ? $category['cat_name'] : FALSE);
        $this->vars['selected_country']         = $selected_country;
        $this->vars['selected_country_name']    = $selected_country_name;
        
        $this->vars['companies']                = $companies;
        $this->vars['total_companies']          = $total_companies;
        $this->vars['results_message']          = (isset($results_message) ? $results_message : FALSE);
        $this->vars['message']                  = (isset($message) ? $message : '');
        
        $this->load->view('companies_list.tpl', $this->vars);
    }

}