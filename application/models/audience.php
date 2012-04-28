<?php
class Audience extends Doctrine_Record {
    
    public function setTableDefinition() {
        $this->hasColumn('audience', 'string', 35);
        $this->hasColumn('audience_name_en', 'string', 35);
        $this->hasColumn('normalized_name_en', 'string', 60);
        $this->hasColumn('audience_name_es', 'string', 35);
        $this->hasColumn('normalized_name_es', 'string', 60);
    }
    
    public function setUp() {
        
        $this->setTableName('audiences');
        
    }
    
    public function get_all()
    {
        $audiences = Doctrine::getTable('Audience')->findAll( Doctrine_Core::HYDRATE_ARRAY);
        
        return $audiences;
    }
    
    public function get_all_by_lang($lang)
    {
        $audiences = Doctrine_Query::create()
            ->select("id, audience, audience_name_$lang AS audience_name, normalized_name_$lang AS normalized_name")
            ->from('Audience')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        return $audiences;
        
    }
    
    public function get_all_as_category($lang)
    {
        $audiences = Doctrine_Query::create()
            ->select("id, audience, audience_name_$lang AS cat_name, normalized_name_$lang AS normalized_name")
            ->from('Audience')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        return $audiences;
        
    }
    
	/**
     * Get an audience type by name. 
     * Returns FALSE if empty Audience, string for redirect if it is a valid Audience but in another language, array Audience if everything is OK
     *
     * @return array Audience
     * @author Jose Bolorino
     **/
    public function get_by_name($name, $lang)
    {
        $valid_audience = $this->_is_valid($name);
        
        if (!$valid_audience) {
            // Audience does not exists
            return FALSE;
        } 
        elseif ($valid_audience != $lang) 
        { // A valid category but in another language
            $lang = $valid_audience;

            // Return redirection to the correct URL.
            $redirect = ($lang != 'en' ? $lang . '/shows/' : 'shows/');
        }
        
        $audience = Doctrine_Query::create()
            ->select("id, audience, audience_name_$lang AS audience_name, normalized_name_$lang AS normalized_name")
            ->from('Audience')
            ->where("normalized_name_$lang = ?", $name)
            ->limit(1)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();
        
        
        if (!$audience) 
        {
            return FALSE;
        } 
        elseif (isset($redirect)) 
        {
            $redirect .= $audience['normalized_name']; // Add the audience to the redirection
            return $redirect;
        } 
        else 
        {
            return $audience;
        }
    }
    
    public function get_by_id($id, $lang) 
    {
        $audience = Doctrine_Query::create()
            ->select("id, audience_name_$lang AS audience_name")
            ->from('Audience')
            ->where('id = ?', $id)
            ->limit(1)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();
        
        if (!$audience) 
        {
            return FALSE;
        }

        return $audience;
    }
    
    /**
     * Check if an audience exists in any language. Returns the language code for the valid audience type
     *
     * @return string
     * @author Jose Bolorino
     **/
    private function _is_valid($name)
    {
        $valid = FALSE;
        
        $audiences = $this->get_all();
        
        foreach ($audiences as $audience) 
        {
            if ($name == $audience['normalized_name_es']) 
            {
                $valid = 'es';
                break;
            } 
            elseif ($name == $audience['normalized_name_en']) 
            {
                $valid = 'en';
                break;
            }
        }
        
        return $valid;
    }
}
