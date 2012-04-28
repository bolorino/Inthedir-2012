<?php
class Spectacle_media extends Doctrine_Record {
    
    public function setTableDefinition() {
        $this->hasColumn('spectacle_id', 'integer', 4, array('unsigned' => true));
        $this->hasColumn('media_type', 'string', 12);
        $this->hasColumn('media', 'string', 255);
        $this->hasColumn('main', 'boolean', 1);
        $this->hasColumn('description_en', 'string', 255);
        $this->hasColumn('description_es', 'string', 255);
        
    }
    
    public function setUp() {
        
        $this->setTableName('spectacles_media');
        $this->actAs('Timestampable');
        
        $this->hasOne('Spectacle', array(
            'local' => 'spectacle_id', 
            'foreign' => 'id'
        ));
        
    }
    
    public function get_spectacle_media($sid, array $params)
    {
        $order       = FALSE;
        $media_query = FALSE;
        
        if ($params['orderby'] && $this->_is_valid_orderby($params['orderby']))
        {
            $order = $params['orderby'] . ' ' . $params['order'];
        }
        
        if ($params['media_type'] && $this->_is_valid_media_type($params['media_type']))
        {
            $media_query = "media_type = '" . $params['media_type'] . "'";
        }
        
        $media = Doctrine_Query::create()
            ->select('spectacle_id, media, main, description_en, description_es')
            ->from('Spectacle_media')
            ->where('spectacle_id = ?', $sid)
            ->addWhere($media_query)
            ->orderBy($order)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        if ( ! $media)
        {
            return FALSE;
        }
        
        return $media;
        
    }
    
    // @todo refactorize the following two methods to get_spectacle_media($id, $type)
    
    public function get_spectacle_images($id, array $params = NULL)
    {
        $order = FALSE;
        
        if ($params['orderby'] && $this->_is_valid_orderby($params['orderby']))
        {
            $order = $params['orderby'] . ' ' . $params['order'];
        }
        
        // @todo add orderby
        $images = Doctrine_Query::create()
            ->select('spectacle_id, media, main, description_en, description_es')
            ->from('Spectacle_media')
            ->where("spectacle_id = $id")
            ->addWhere("media_type = 'image'")
            ->orderBy($order)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
        
        if ( ! $images)
        {
            return FALSE;
        }
       
        return $images;
    }
    
    public function get_spectacle_videos($id)
    {
        // @todo add orderby
        $videos = Doctrine_Query::create()
            ->select('spectacle_id, media, main, description_en, description_es')
            ->from('Spectacle_media')
            ->where('spectacle_id = ?', $id)
            ->addWhere("media_type = 'video'")
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
        
        if ( ! $videos)
        {
            return FALSE;
        }
       
        return $videos;
    }
    
    public function get_main_spectacle_image($id)
    {
        $image = Doctrine_Query::create()
            ->select('spectacle_id, media, main, description_en, description_es')
            ->from('Spectacle_media')
            ->where("spectacle_id = $id")
            ->addWhere('main = 1')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();
            
        return $image;
    }
    
    public function get_by_id($id)
    {
        $result = Doctrine_Query::create()
            ->select('id, spectacle_id, media_type, media, main, description_en, description_es')
            ->from('Spectacle_media')
            ->where('id = ?', $id)
            ->fetchOne();
            
        return $result;
    }
    
    public function get_by_name($sid, $media)
    {
        $result = Doctrine_Query::create()
            ->select('id, spectacle_id, media_type, media')
            ->from('Spectacle_media')
            ->where('spectacle_id = ?', $sid)
            ->addWhere('media = ?', $media)
            ->fetchOne();
            
        return $result;
    }
    
    public function add_spectacle_media($sid, $type, $fields)
    {
        if ( ! $this->_is_valid_media_type($type))
        {
            return FALSE;
        }
        
        $media = new Spectacle_media();
        
        foreach ($fields as $key => $value) 
        {
            $media->$key = $value;
        }
        
        $media->save();
        
        return TRUE;
        
    }
    
    public function spectacle_has_item($sid, $iid)
    {
        
        $sid = intval($sid);
        $iid = intval($iid);
        
        $result = Doctrine_Query::CREATE()
            ->select('id')
            ->from('Spectacle_media')
            ->where('id = ?', $iid)
            ->addWhere('spectacle_id = ?', $sid)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();
        
        if ( ! $result)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    // Mark the default image for show. Unset previous default image. $iid (Image ID) $sid (Spectacle ID)
    public function set_default_spectacle_image($id)
    {
        
        $id = intval($id);
        
        // Get the image info
        $image = Doctrine::getTable('Spectacle_media')->findOneBy('id', $id);
        
        if ( ! $image)
        {
            return FALSE;
        }
        
        // Get the spectacle ID
        $sid = $image->spectacle_id;
        
        // Unset previous default image
        $where_unset = 'spectacle_id = ' . $sid . ' AND media_type = "image" AND main = 1';
        
        $unset_main = Doctrine_Query::CREATE()
            ->update('Spectacle_media')
            ->set('main', 0)
            ->where($where_unset)
            ->execute();
            
        // New default image for Show
        $image->main = 1;
        
        $image->save();
        
        return TRUE;
    }
    
    private function _is_valid_media_type($media_type)
    {
        $valid_media_types = array('image', 'video');
        
        if ( ! in_array($media_type, $valid_media_types))
        {
            return FALSE;
        }

        return TRUE;
    }
    
	/**
     * Checks if param is a valid order field
     * 
     * @param string $field
     * 
     * @return bool
     */
    private function _is_valid_orderby($field)
    {
        $valid_fields = array('main', 'created_at');
        
        if ( ! in_array($field, $valid_fields))
        {
            return FALSE;
        }

        return TRUE;
    }
}
