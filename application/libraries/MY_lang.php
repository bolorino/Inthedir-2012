<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* URI Language Identifier
* 
* Adds a language identifier prefix to all site_url links
* 
* @copyright     Copyright (c) Wiredesignz 2009-12-20
* @version         0.23
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*/
class MY_Lang extends CI_Lang
{
    
    public $gettext_language;
    public $gettext_domain = 'messages';
    public $gettext_path;
    public $active_lang;
    
    public function __construct() {
        parent::__construct();
        
        global $RTR;

        $index_page    = $RTR->config->item('index_page');
        $lang_uri_abbr = $RTR->config->item('lang_uri_abbr');
        
        /* get the lang_abbr from uri segments */
        $lang_abbr = current($RTR->uri->segments);
        
        /* check for invalid abbreviation */
        if ( ! isset($lang_uri_abbr[$lang_abbr])) 
        {
            $deft_abbr = $RTR->config->item('language_abbr');
            
            log_message('debug', '* lang_uri_abbr NOT set. itdlang set to: ' . $deft_abbr);
            
            $this->active_lang = $deft_abbr;
            
            $RTR->config->set_item('itdlang', $deft_abbr);  
            
            /* check for abbreviation to be ignored */
            if ($deft_abbr != $RTR->config->item('lang_ignore')) 
            {
                log_message('debug', '* $deft_abbr != lang_ignore');
                
                /* check and set the default uri identifier */
                $index_page .= empty($index_page) ? $deft_abbr : "/$deft_abbr";
            
                /* redirect after inserting language id */
                header('Location: ' . $RTR->config->item('base_url') . $index_page . $RTR->uri->uri_string);
            }
            
            /* get the language name */
            $user_lang = $lang_uri_abbr[$deft_abbr];
            
            log_message('debug', '* active_lang: ' . $this->active_lang);
            
            $RTR->config->set_item('itdlang', $this->active_lang);  
        
        } 
        else 
        {
            /* get the language name */
            $user_lang = $lang_uri_abbr[$lang_abbr];
            
            log_message('debug', '* 2nd user_lang: ' . $user_lang);
            
            /* reset config language to match the user language */
            $RTR->config->set_item('language', $user_lang);
            $RTR->config->set_item('language_abbr', $lang_abbr);
            
            $this->active_lang = $lang_abbr;
            $RTR->config->set_item('itdlang', $lang_abbr);
            
            log_message('debug', '* active_lang: ' . $this->active_lang);
        
            /* check for abbreviation to be ignored */
            if ($lang_abbr != $RTR->config->item('lang_ignore')) 
            {
                /* check and set the user uri identifier */
                $index_page .= empty($index_page) ? $lang_abbr : "/$lang_abbr";
                
                /* reset uri segments and uri string */
                $RTR->uri->_reindex_segments(array_shift($RTR->uri->segments));
                $RTR->uri->uri_string = str_replace("/$lang_abbr/", '/', $RTR->uri->uri_string);
            }
        }
        
        $gettext_lang = 'en_IN';
        
        if ($RTR->config->item('language_abbr') == 'es') 
        {
            $gettext_lang = 'es_ES.utf8';
        }
        
        $this->load_gettext($gettext_lang);
        
        /* reset the index_page value */
        $RTR->config->set_item('index_page', $index_page);
        log_message('debug', "MY_Lang Class Initialized");
    }
    
    /**
     * This method overides the original load method. Its duty is loading the domain files by config or by default internal settings.
     *
     */
    private function load_gettext ($userlang = false) 
    {
        if ($userlang ) 
        {
            $this->gettext_language = $userlang;
        } 
        else 
        { 
            $this->gettext_language = 'en_IN';
        }
        
        log_message('debug', 'Gettext Class gettext_language was set by parameter:' . $this->gettext_language );

        putenv("LANG=$this->gettext_language");
        setlocale(LC_ALL, $this->gettext_language);

        /* Let's set the path of .po files */
        $this->gettext_path = APPPATH . 'language/locale/';
        log_message('debug', 'Gettext Class path chosen is: '.$this->gettext_path);

        bindtextdomain($this->gettext_domain, $this->gettext_path);
        textdomain($this->gettext_domain);
        
        log_message('debug', 'Gettext Class the domain chosen is: '. $this->gettext_domain);

        return TRUE;
    }

}
