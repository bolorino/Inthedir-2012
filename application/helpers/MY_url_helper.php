<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Alternative languages helper
*
* Returns a string with links to the content in alternative languages
*
* version 0.2
* @author Luis <luis@piezas.org.es>
* @modified by Ionut <contact@quasiperfect.eu>
* @modified by Bolorino <contacto@bolorino.net>
*/

function alt_site_url($uri = '')
{
    $CI =& get_instance();
    
    $current_lang = $CI->uri->segment(1);
    $current_lang = $CI->config->item('language_abbr');
    
    $languages        = $CI->config->item('lang_desc');
    $languages_useimg = $CI->config->item('lang_useimg');
    $ignore_lang      = $CI->config->item('lang_ignore');
    
    if (empty($current_lang)) 
    {
        $uri = $ignore_lang . $CI->uri->uri_string();
        $current_lang = $ignore_lang;
    }
    else 
    {
        if (! array_key_exists($current_lang,$languages)) 
        {
            $uri = $ignore_lang . $CI->uri->uri_string();
            $current_lang = $ignore_lang;
        }
        else 
        {
            $uri = $CI->uri->uri_string();
        }
    }
    
    $alt_url = '<ul class="languageselector">';

    foreach ($languages as $lang => $lang_desc)
    {
            $alt_url .= '<li';
            
            if ($current_lang == $lang)
            {
                $alt_url .= ' class="current"';
            }
            
            $alt_url .= '><a href="'.config_item('base_url');
            
            if ($lang == $ignore_lang)
            {
                $new_uri = preg_replace('/^'.$current_lang.'/','',$uri);
                $new_uri = substr_replace($new_uri,'',0,1);
            }
            else
            {
                $new_uri = preg_replace("/^".$current_lang.'/',$lang,$uri);

                if ($current_lang == $ignore_lang) 
                {
                    $new_uri = $lang . '/' . $uri;
                }
            }
            
            $alt_url .= $new_uri . '">';
            
            if ($languages_useimg)
            {
                $alt_url .= '<img src="' . base_url() . 'images/flags/' . $lang . '.png" alt="' . $lang_desc . '"'; 
                
                if ($lang == $current_lang)
                {
                    $alt_url .= ' class="selected"';
                }
                
                $alt_url .= 'title = "' . $lang_desc . '"';
                $alt_url .= ' /> </a></li>';
            }
            else
            {
                $alt_url .= $lang_desc . '</a></li>';
            }

    }
    $alt_url .= '</ul>';
    
    return $alt_url;
}