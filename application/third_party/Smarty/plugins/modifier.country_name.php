<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File: modifier.country_name.php
* Type: Modifier
* Name: country_name
* Purpose: return translated country name from DB ISO2 code
* Usage: {$country_iso2|country_name}
*
* @link 
* @copyright 2006 Jose Bolorino
* @author Jose Bolorino <bolorino@dmachina.com>
* @param string $iso2 string $lang
* @version: 0.2 codeigniter
*/

function smarty_modifier_country_name ($string, $lang) 
{
	$ci =& get_instance();
  
    $allowedLanguages = array('en', 'es');
  
    if (strlen($string) != 2) {
        return false;
        exit;
    } elseif (!in_array($lang, $allowedLanguages)) {
        return false;
        exit;
    }
    
	$country = Doctrine_Query::create()
        ->select('id_country')
        ->addSelect('name_' . $lang . ' AS country')
        ->from('Country')
        ->where('id_country = ?', $string)
        ->limit(1)
        ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
        ->execute();
    
    if (!$country) {
        return FALSE;
    } else {
        return $country[0]['country'];
    }
}
