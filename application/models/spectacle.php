<?php
class Spectacle extends Doctrine_Record 
{
    
    public function setTableDefinition() {
        $this->hasColumn('company_id', 'integer', 4, array('unsigned' => true));
        $this->hasColumn('audience_id', 'integer', 4, array('unsigned' => true));
        $this->hasColumn('spectacle_name', 'string', 90);
        $this->hasColumn('normalized_name', 'string', 60);
        $this->hasColumn('premiere', 'integer', 4);
        $this->hasColumn('short_description', 'string', 350);
        $this->hasColumn('sinopsis', 'string', 1500);
        $this->hasColumn('director', 'string', 50);
        $this->hasColumn('length', 'integer', 4);
        $this->hasColumn('ages_from', 'integer', 4, array('notnull' => false));
        $this->hasColumn('ages_to', 'integer', 4, array('notnull' => false));
        $this->hasColumn('credit_titles', 'string', 1000);
        $this->hasColumn('sheet', 'string', 1000);
        $this->hasColumn('inthedir', 'integer', 1);
        $this->hasColumn('auth', 'integer', 1);
        $this->hasColumn('status', 'string', 10);
        $this->hasColumn('counter', 'integer', 8);
        
    }
    
    public function setUp() {
        
        $this->setTableName('spectacles');
        
        $this->actAs('Timestampable');
        
        $this->hasOne('Company', array(
            'local' => 'company_id', 
            'foreign' => 'id'
        ));
        
        $this->hasOne('Audience', array(
            'local' => 'audience_id', 
            'foreign' => 'id'
        ));
        
        $this->hasMany('Spectacle_media as Media', array(
            'local' => 'id',
            'foreign' => 'spectacle_id'
        ));
        
        $this->hasMany('Spectacle_category as Spectacle_categories', array(
            'local' => 'id', 
            'foreign' => 'spectacle_id'
        ));
    }
    
    public function get_spectacles_array($params) 
    {   
        $where      = 'auth = 1 ';
        $left_join  = FALSE;
        $order      = FALSE;
        
        if ($params['category_id'])
        {
            $left_join = ', s.Spectacle_categories c ';
            $where .= ' AND c.category_id = ' . intval($params['category_id']);
        }
        
        if ($params['audience_id'])
        {
            $where .= ' AND s.audience_id = ' . intval($params['audience_id']);
        }
        
        if ($params['orderby'] && $this->_is_valid_orderby($params['orderby']))
        {
            $order = $params['orderby'] . ' ' . $params['order'];
        }
        
        $spectacles = Doctrine::getTable('Spectacle')
            ->createQuery('s')
            ->leftJoin('s.Media m'.$left_join)
            ->innerJoin('s.Company')
            ->addWhere("m.media_type = 'image' AND m.main = 1")
            ->addWhere($where)
            ->orderBy($order)
            ->limit($params['limit'])
            ->offset($params['offset'])
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        return $spectacles;
        
    }
    
    public function get_audience_images($audience, $limit)
    {
        $where = 's.audience_id ';
        
        if (! is_array($audience))
        {
            $where .= ' = ' . intval($audience);
        }
        else
        {
            $where .= ' IN (';
            
            foreach ($audience as $id)
            {
                $where .= $id . ', ';
            }
            
            // Strip last comma and whitespace 
            $where = substr($where, 0, -2);
            
            $where .= ')';
        }
        
        $images = Doctrine::getTable('Spectacle')
            ->createQuery('s')
            ->leftJoin('s.Media m')
            ->select('s.id, s.spectacle_name, s.normalized_name AS normalized_name, m.media AS image')
            ->where($where)
            ->andWhere('m.media_type = "image" AND m.main = 1')
            ->orderBy('RAND()')
            ->limit($limit)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        return $images;
    }
    
    public function get_total_spectacles($category = FALSE, $audience = FALSE)
    {
        // no leftjoin if not category 
        $where = 'auth = 1 ';
        $left_join = FALSE;
        
        if (isset($category) && $category != FALSE) 
        {
            $left_join = 's.Spectacle_categories c '; // @todo check
            $where .= ' AND c.category_id = ' . intval($category);
        }
        
        if (isset($audience) && $audience != FALSE) 
        {
            $audience = intval($audience);
            $where .= ' AND s.audience_id = ' . $audience;
        }
        
        // There must be a better way to do this. Not found yet.
        if ( ! $category)
        {
            $result = Doctrine_Query::create()
                ->select('COUNT(*) AS total_spectacles')
                ->from('Spectacle s')
                ->addWhere($where)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                ->fetchOne();
        }
        else 
        {
            $result = Doctrine_Query::create()
                ->select('COUNT(*) AS total_spectacles')
                ->from('Spectacle s')
                ->leftJoin($left_join)
                ->addWhere($where)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                ->fetchOne();
        }
        
            
        return $result['total_spectacles'];
    }
    
    public function get_company_spectacles($cid, $exclude=FALSE, $limit=0)
    {
        $add_where = 'auth = 1 ';
        
        if ($exclude)
        {
            $add_where .= ' AND id != ' . intval($exclude);
        }
        
        $spectacles = Doctrine::getTable('Spectacle')
            ->createQuery('s')
            ->leftJoin('s.Media m')
            ->innerJoin('s.Company')
            ->where('s.company_id = ?', $cid)
            ->addWhere("m.media_type = 'image' AND m.main = 1")
            ->andWhere($add_where)
            ->orderBy('premiere DESC')
            ->limit($limit)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        if ( ! $spectacles)
        {
            return FALSE;
        }
        
        return $spectacles;
    }
    
    public function get_company_spectacles_simple($cid, $limit=0)
    {
        $spectacles =  Doctrine_Query::create()
            ->select('id, spectacle_name, normalized_name, premiere, short_description')
            ->from('Spectacle')
            ->addWhere('auth = 1 AND company_id = ?', $cid)
            ->orderBy('premiere DESC')
            ->limit($limit)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
        
        if ( ! $spectacles)
        {
            return FALSE;
        }
        
        return $spectacles;
    }
    
    /**
     * Get shows without images
     *
     * @return array $spectacles
     * @author Jose Bolorino
     **/
    public function get_noimage_spectacles($params)
    {
        $where = 'id NOT IN (SELECT m.spectacle_id FROM Spectacle_media m)';
        $and_where = 'auth = 1 ';
        
        if (isset($params['company_id']))
        {
            $and_where .= ' AND company_id = ' . intval($params['company_id']);
        }
        
        $spectacles =  Doctrine_Query::create()
            ->select('id, spectacle_name, normalized_name, premiere, short_description')
            ->from('Spectacle')
            ->where($where)
            ->andWhere($and_where)
            ->orderBy('premiere DESC')
            ->limit($params['limit'])
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
        
        if ( ! $spectacles)
        {
            return FALSE;
        }
        
        return $spectacles;
    }
    
    public function get_total_company_spectacles($cid)
    {
        $result = Doctrine_Query::create()
            ->select('COUNT(*) AS total_spectacles')
            ->from('Spectacle')
            ->where('company_id = ? AND auth = 1', $cid)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();
            
        return $result['total_spectacles'];
    }
    
    public function get_by_id($id)
    {
        // Must be Object. 
        
        $spectacle = Doctrine::getTable('Spectacle')->find($id);
        
        return $spectacle;
    }
    
    public function get_by_normalized_name($name)
    {
        $spectacle = Doctrine::getTable('Spectacle')->findOneBy('normalized_name', $name);
        
        if ( ! $spectacle)
        {
            return FALSE;
        }
        
        return $spectacle;
    }
    
 	/**
     * Gets the last similar normalized name. 
     * Normalized names are clean strings from names that could be not unique.
     * If normalized name already exists -N is added to string, where N is an incremental number.
     *
     * @return string
     * @author Jose Bolorino
     **/
    public function get_last_normalized($name)
    {
        $last_normalized = Doctrine_Query::create()
            ->select('normalized_name')
            ->from('Spectacle')
            ->where('normalized_name LIKE ?', $name)
            ->orderBy('normalized_name DESC')
            ->limit(1)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();

        if ( ! $last_normalized['normalized_name'])
        {
            return FALSE;
        }
        
        return $last_normalized['normalized_name'];
    }
    
    public function add($fields)
    {
        $spectacle = new Spectacle();
        
        foreach ($fields as $key => $value) 
        {
            $spectacle->$key = $value;
        }
        
        $spectacle->save();
        
        return $spectacle->id;
        
    }
    
    public function update(Spectacle $spectacle, $fields)
    {
        
        foreach ($fields as $key => $value) 
        {
            $spectacle->$key = $value;
        }
        
        $spectacle->save();
        
        return TRUE;
        
    }
    
    public function add_visit($sid)
    {
        $add_visit = Doctrine_Query::create()
            ->update('Spectacle')
            ->set('counter', 'counter + 1')
            ->where('id = ?', $sid)
            ->execute();
        
    }
    
    private function _is_valid_orderby($field)
    {
        $valid_fields = array('spectacle_name', 'premiere', 'created_at', 'updated_at');
        
        if (in_array($field, $valid_fields))
        {
            return TRUE;
        }
        
        return FALSE;
    }
}
