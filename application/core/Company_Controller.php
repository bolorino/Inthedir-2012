<?php
/**
 * Company global Controller
 * 
 * @package Inthedir
 * @category Controller
 * @author Jose Bolorino
 */

class Company_Controller extends MY_Controller {
    
	/**
     * Sets the Company role global vars 
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        
        if ( ! $this->ion_auth->logged_in()) 
        { // No user logged, nothing to set
            redirect('/access/login');

            exit;
        }
        
        if ($this->group->name != 'company')
        {
            // Not a company
            redirect('/account');

            exit;
        }

        if (isset($this->itdcompany) && intval($this->user->company_id) > 0)
        {
            // Store Company ID in session
            $session_data = array(
                'ucid'  => $this->user->company_id
            );
            
            $this->session->set_userdata($session_data);
        }
        else
        {
            // Something went wrong. A Company role without Company ID            
            $this->ion_auth->logout();
            
            redirect('/');
        }
    }
}