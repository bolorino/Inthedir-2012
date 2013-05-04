<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Festivals extends Public_Controller
{
    private $_categories_array = array(1, 3, 9);

    public function __construct()
    {
        parent::__construct();

        $this->load->model('entity');
        $this->load->model('program_type');

        $this->load->library('pagination');

        // Set the object scope in template
        $this->vars['place'] = 'festivals';
    }

    public function index()
    {
        redirect('festivals/All');
    }

    public function view($cat='All')
    {
        // Get the program type of the festival
        $category_id = FALSE;
        $category_normalized_name = FALSE;

        if ($cat && $cat != 'All')
        {
            $category = $this->program_type->get_by_name($cat, $this->config->item('language_abbr'), 'festivals');

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
                $category_normalized_name = $category['normalized_name'];
            }

        }

        // Total festivals
        $selected_country = FALSE;

        $count_festivals_params = array(
            'entity_type_id'	=> $this->_categories_array,
            'program_type_id'	=> $category_id,
            'country'		    => $selected_country
        );

        $total_festivals = $this->entity->get_total_entities($count_festivals_params);

        // Initialize pagination
        $config['total_rows']      = $total_festivals;
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

        $festivals_params = array(
            'orderby'            => 'entity_name',
        	'order'              => 'ASC',
            'entity_type_id'     => $this->_categories_array,
	        'program_type_id'	 => $category_id,
            'search_query'		 => FALSE,
	        'country'			 => FALSE,
            'offset'             => $this->pagination->offset,
            'limit'              => $this->config->item('per_page')
        );

        $festivals = $this->entity->get_entities_array($festivals_params);

        // Set the categories corresponding to this object (Festival)
        $this->vars['festivals_categories'] = $this->program_type->get_all_as_categories($this->config->item('language_abbr'));
        $this->vars['category_normalized_name'] = $category_normalized_name;

        // Build the result's message: (%n||no $category festivals)
        // @todo Country

        $results_message = _('Theatre festivals list');

        if (isset($category))
        {
            $category_name = $category['cat_name'];

            if (empty($festivals))
            {
                $results_message = sprintf(_('No %s festivals yet'), $category_name);
            }
            else
            {
                $results_message = sprintf(_('%s %s festivals'), $total_festivals, $category_name);
            }
        }
        else
        {
            // No categoy
            if (empty($festivals))
            {
                $results_message = _('No Performing Arts festivals found');
            }
            else {
                $results_message = sprintf(_('%s Performing Arts festivals'), $total_festivals);
            }
        }

        // Set title
        $title = _('Theatre festivals list');

        if (isset($category_name))
        {
            $title = sprintf(_('%s festivals'), $category_name);
        }


        $this->vars['title'] = $title;
        $this->vars['header_description'] = _('List of theatre, dance, puppets and other Performing Arts festivals.');

        // Activate infinite (sort of) scroll
        $this->vars['scroll'] = TRUE;

        $this->vars['results_message'] = $results_message;

        $this->vars['total_entities'] = $total_festivals;
        $this->vars['entities'] = $festivals;

        $this->load->view('entities_list.tpl', $this->vars);
    }

}
