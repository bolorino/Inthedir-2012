<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    
    public $CI;
    
    public function __construct($config)
	{
		parent::__construct($config);
		
		$this->CI =& get_instance();
	}
    
    public function unique($email) 
    {
        
        $this->CI->form_validation->set_message('unique', _('The %s is already being used.'));

        if ($this->CI->ion_auth->get_user_by_email($email))
        {
            return FALSE;
        }

        return TRUE;
    }
    
    public function valid_country($country_code)
    {
        
        $this->CI->form_validation->set_message('valid_country', _('You must select a valid Country'));
        
        if ( ! $this->CI->country->is_valid_country($country_code))
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function valid_spanish_community($spanish_community)
    {
        
        $this->CI->form_validation->set_message('valid_spanish_community', _('Debes seleccionar una Comunidad Autónoma'));
        
        $spanish_communities = spanish_communities();
        
        if (! in_array($spanish_community, $spanish_communities))
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function valid_entity_type($id)
    {
        
        $this->CI->form_validation->set_message('valid_entity_type', _('Must select a valid Entity type'));
        
        if ( ! $this->CI->entity_type->get_by_id($id, $this->CI->itdlang))
        {
            return FALSE;
        }
        
        return TRUE;
    }

    public function valid_role($value)
    {
        $this->CI->form_validation->set_message('valid_role', _('You must select one option'));

        if ($value == '0')
        {
            return FALSE;
        }

        return TRUE;
    }
    
    public function valid_program_type($id)
    {
        
        $this->CI->form_validation->set_message('valid_program_type', _('Must select a valid program type'));
        
        if ( ! $this->CI->program_type->get_by_id($id, $this->CI->itdlang))
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function valid_audience($id)
    {
        
        $this->CI->form_validation->set_message('valid_audience', _('Must select a valid audience'));
        
        if ( ! $this->CI->audience->get_by_id($id, $this->CI->itdlang))
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function valid_company_category($id)
    {
        
        $this->CI->form_validation->set_message('valid_company_category', _('The Company category is missing'));
        
        if ( ! $this->CI->category->get_by_id($id, $this->CI->itdlang))
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function valid_company_show($id)
    {
        $this->CI->form_validation->set_message('valid_company_show', _('Must select one show'));
        
        if (intval($id == 0))
        {
            return FALSE;
        }
        
        $spectacle = $this->CI->spectacle->get_by_id($id);
        
        if ($spectacle->company_id != $this->CI->user->company_id)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function field_empty($value)
    {
        
        $this->CI->form_validation->set_message('field_empty', '');
        
        if ( ! empty($value))
        {
            return FALSE;
        }
        
        return TRUE;
    }

    public function accept_terms($value = FALSE)
    {
        
        $this->CI->form_validation->set_message('Must accept terms', '');
        
        if (empty($value))
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function valid_website($value)
    {
        
        $this->CI->form_validation->set_message('valid_website', _('Invalid website URL.'));
        
        $str_pattern = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
        
        if ( ! preg_match($str_pattern, $value))
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function valid_month($value)
    {
        $this->CI->form_validation->set_message('valid_month', _('Must select a valid month'));
        
        if (intval($value) < 1 OR intval($value) > 12)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function valid_date($value)
    {
        $this->CI->form_validation->set_message('valid_date', _('Invalid date'));
        
        // Replace slashes with dashes to assume european format dd/mm/yy
        $value = str_replace('/', '-', $value);
        
        $stamp = strtotime($value);
        
        if (! is_numeric($stamp))
        {
            return FALSE;
        }
        
        $month = date('m', $stamp);
        $day   = date('d', $stamp);
        $year  = date('Y', $stamp);
        
        if (checkdate($month, $day, $year))
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    public function strong_pass($value, $params)
    {
        
        $this->CI->form_validation->set_message('strong_pass', _('The password is too weak.'));
        
        $score = 0;
        
        if (preg_match('!\d!', $value))
        {
            $score++;
        }
        
        if (preg_match('![A-z]!', $value))
        {
            $score++;
        }
        
        if (preg_match('!\W!', $value))
        {
            $score++;
        }
        
        if (strlen($value) >= 8)
        {
            $score++;
        }
        
        if ($score < $params)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
}