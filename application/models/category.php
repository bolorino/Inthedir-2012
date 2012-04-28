<?php
class Category extends Doctrine_Record 
{
    
    public function setTableDefinition () 
    {
        $this->hasColumn('cat_name_en', 'string', 15);
        $this->hasColumn('normalized_name_en', 'string', 60);
        $this->hasColumn('cat_name_es', 'string', 15);
        $this->hasColumn('normalized_name_es', 'string', 60);
    }
    
    public function setUp() 
    {
        
        $this->setTableName('categories');

    }
    
    public function get_all()
    {
        $categories = Doctrine::getTable('Category')->findAll( Doctrine_Core::HYDRATE_ARRAY);
        
        return $categories;
    }
    
    public function get_all_by_lang($lang)
    {
        $categories = Doctrine_Query::create()
            ->select("id, cat_name_$lang AS cat_name, normalized_name_$lang AS normalized_name")
            ->from('Category')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        return $categories;
        
    }
    
    public function get_by_id($id, $lang) 
    {
        $category = Doctrine_Query::create()
            ->select("id, cat_name_$lang AS cat_name, normalized_name_$lang AS normalized_name")
            ->from('Category')
            ->where('id = ?', $id)
            ->limit(1)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();
        
        if (!$category) 
        {
            return FALSE;
        }

        return $category;
    }
    
    /**
     * Get a category by name. 
     * Returns FALSE if empty or not valid Category, 
     * string for redirect if it is a valid category but in another language, 
     * array Category if everything is OK
     *
     * @return array Category
     * @author Jose Bolorino
     **/
    public function get_by_name($name, $lang, $controller_name)
    { 
        // @todo Only redirects by category, not by subcategory
        
        $valid_category = $this->_is_valid($name);
        
        if ( ! $valid_category) 
        {
            return FALSE;
        } 
        
        if ($valid_category != $lang) 
        { // A valid category but in another language. @todo : Needs to add the translated category name

            $name = $this->_translate_category($lang, $name);
            
            $redirect = ($lang != 'en' ? $lang . '/' . $controller_name . '/'.$name : $controller_name . '/' . $name);
        }
        
        
        $category = Doctrine_Query::create()
            ->select("id, cat_name_$lang AS cat_name, normalized_name_$lang AS normalized_name")
            ->from('Category')
            ->where("normalized_name_$lang = ?", $name)
            ->limit(1)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();
        
        if ( ! $category) 
        {
            return FALSE;
        } 
        
        if (isset($redirect)) 
        {
            return $redirect;
        } 
        else 
        {
            return $category;
        }
        
    }

    /**
     * Check if a category exists in any language. 
     * Returns the language code for the valid category
     *
     * @return string
     * @author Jose Bolorino
     **/
    private function _is_valid($name)
    {
        $valid = FALSE;
        
        $categories = $this->get_all();
        
        foreach ($categories as $category) 
        {
            if ($name == $category['normalized_name_es']) 
            {
                $valid = 'es';
                break;
            } 
            elseif ($name == $category['normalized_name_en']) 
            {
                $valid = 'en';
                break;
            }
        }
        
        return $valid;
    }
    
    /**
     * Gets the given category in another language
     * Returns the normalized name
     *
     * @return string
     * @author Jose Bolorino
     **/
    private function _translate_category($lang, $name) 
    {
        $translated_name = FALSE;
        
        $categories = $this->get_all();
        
        foreach ($categories as $category) 
        {
            if ($name == $category['normalized_name_es'] OR $name == $category['normalized_name_en']) 
            {
                $translated_name = $category['normalized_name_'.$lang];
                break;
            }
        }
        
        return $translated_name;
    }
}
