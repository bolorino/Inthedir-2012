<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Festival extends Public_Controller
{
    private $_categories_array = array(1, 3, 9);

    public function __construct()
    {
        parent::__construct();

        $this->load->model('entity');
        $this->load->helper('seo_helper');

        // Set the object scope in template
        $this->vars['place'] = 'festivals';

    }

    public function view($normalized_name)
    {
        $entity = $this->entity->get_by_normalized_name($normalized_name, FALSE);

        if (! $entity)
        {
            show_error(_('Festival not found'), 404);
            return FALSE;
        }

        $this->vars['title'] = $entity[0]['entity_name'];

        // Header SEO
        $header_description = $entity[0]['entity_name'] . '. ' . $entity[0]['city'] . '. ';

        if ($entity[0]['city'] != $entity[0]['state'])
        {
            $header_description .= $entity[0]['state'] . ' ';
        }

        if ($entity[0]['short_description'] && ! empty($entity[0]['short_description']))
        {
            $header_description .= $entity[0]['short_description'];
        }

        $this->vars['header_description'] = set_header_description($header_description);

        $this->vars['festival'] = $entity;

        $this->load->view('festival.tpl', $this->vars);
    }

}
