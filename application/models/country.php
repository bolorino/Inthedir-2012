<?php
class Country extends Doctrine_Record 
{
    
    public function setTableDefinition () 
    {
        $this->hasColumn('id_country', 'string', 2, array('unsigned' => true));
        $this->hasColumn('name_es', 'string', 20);
        $this->hasColumn('name_en', 'string', 20);
        $this->hasColumn('iso3', 'string', 3);
        $this->hasColumn('isonum', 'string', 3);
        $this->hasColumn('phone_prefix', 'string', 10);
        $this->hasColumn('country_group', 'string', 3);
    }
    
    public function setUp() 
    {
        $this->setTableName('countries');
    }
    
    public function get_countries_array($group=FALSE)
    {        
        $where = 'ISNULL(country_group) OR country_group = \'\'';
        
        if ( ! empty($group)) 
        {
            $where = 'country_group = ' . '\'' . $group . '\'';
        }
        
        $countries = Doctrine_Query::create()
            ->select('id_country, country_group')
            ->from('Country')
            ->where($where)
            ->orderBy('id_country')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
        
        if ( ! $countries) 
        {
            return false;
        }
        
        return $countries;
    }
    
    public function get_all_countries()
    {
        $CI =& get_instance();
        
        $countries = Doctrine_Query::create()
            ->select('id_country, name_' . $CI->config->item('language_abbr') . ' AS country')
            ->from('Country')
            ->orderBy('name_' . $CI->config->item('language_abbr') . ' ASC')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        return $countries;
            
    }
    
    public function get_country_name($country_code) 
    {
        $this->ci =& get_instance();
        
        $allowed_languages = array('en', 'es');
        
        if (strlen($country_code) != 2) 
        {
            return FALSE;
        } 
        
        if ( ! in_array($this->ci->config->item('language_abbr'), $allowed_languages)) 
        {
            return FALSE;
        }
        
        $country = Doctrine_Query::create()
            ->select('id_country, name_' . $this->ci->config->item('language_abbr') . ' AS country')
            ->from('Country')
            ->where('id_country = ?', $country_code)
            ->limit(1)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();
        
        if ( ! $country) 
        {
            return FALSE;
        }
        
        return $country['country'];
    }
    
    public function get_phone_prefix($country_code)
    {
        $country = Doctrine_Query::create()
            ->select('phone_prefix')
            ->from('Country')
            ->where('id_country = ?', $country_code)
            ->limit(1)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();
            
        if ( ! $country)
        {
            return FALSE;
        }
        
        return $country['phone_prefix'];
    }
    
    public function is_valid_country($country_code)
    {
        $result = Doctrine::getTable('Country')->findBy('id_country', $country_code, Doctrine_Core::HYDRATE_ARRAY);
        
        if ( ! $result)
        {
            return FALSE;
        }

        return $result;
    }
}