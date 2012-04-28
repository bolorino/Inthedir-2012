<?php
$config = array(
    'spectacle_step_1' => array(
        array(
            'field'      => 'spectacle_name', 
            'label'     => _('Spectacle Title'), 
            'rules'		=> 'required|xss_clean'
        ), 
        array(
            'field'      => 'audience', 
            'label'     => _('Type of audience'), 
            'rules'		=> 'required|valid_audience'
        ),
        array(
            'field'      => 'premiere', 
            'label'     => _('Year of Premiere'), 
            'rules'		=> 'required|is_natural_no_zero'
        ),
        array(
            'field'      => 'director', 
            'label'     => _('Director'), 
            'rules'		=> 'xss_clean'
        ),
        array(
            'field'      => 'length', 
            'label'     => _('Duration'), 
            'rules'		=> 'required|is_natural_no_zero'
        ),
        array(
            'field'      => 'ages_from', 
            'label'     => _('Ages from'), 
            'rules'		=> 'is_natural'
        ),
        array(
            'field'      => 'ages_to', 
            'label'     => _('To ages'), 
            'rules'		=> 'is_natural_no_zero'
        ), 
        array(
            'field'      => 'short_description', 
            'label'     => _('Short description'), 
            'rules'		=> 'required|max_length[350]|xss_clean'
        )
    ), 
    'spectacle_step_2' => array(
        array(
            'field'      => 'sinopsis', 
            'label'     => _('Sinopsis'), 
            'rules'		=> 'required|max_legnth[1500]|xss_clean'
        )
    ),
    'spectacle_step_3' => array( 
        array(
            'field'      => 'credit_titles', 
            'label'     => _('Credit Titles'), 
            'rules'		=> 'required|max_length[1500]|xss_clean'
        ), 
        array(
            'field'      => 'sheet', 
            'label'     => _('Sheet'), 
            'rules'		=> 'max_length[1500]|xss_clean'
        )
    )
);