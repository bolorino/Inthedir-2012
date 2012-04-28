<?php
/*
 * Indexes Spectacles and Categories
 */

class Spectacle_category extends Doctrine_Record {
    
    public function setTableDefinition() {
        $this->hasColumn('spectacle_id', 'integer', 4, array('unsigned' => true));
        $this->hasColumn('category_id', 'integer', 4, array('unsigned' => true, 'null' => true));
    }
    
    public function setUp() {
        
        $this->setTableName('spectacles_categories');
        
        $this->hasOne('Spectacle', array(
            'local' => 'spectacle_id', 
            'foreign' => 'id'
        ));
    }
    
    public function get_spectacle_categories($sid)
    {
        $sid = intval($sid);
        
        $spectacle_categories = Doctrine_Query::create()
            ->select('id, spectacle_id, category_id')
            ->from('Spectacle_category')
            ->where('spectacle_id = ?', $sid)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        return $spectacle_categories;
        
    }
    
    public function get_by_id($id)
    {
        // Must be an object to delete a category from a show
        
        $category = Doctrine::getTable('Spectacle_category')->findBy('id', $id);
        
        return $category;
    }
    
    
}
