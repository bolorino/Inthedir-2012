<?php
/**
 * CodeIgniter/Doctrine Offer Model
 *
 * Model for spectacles offers
 *
 * @author          Jose Bolorino
 * @license         http://
 * @link            http://
 */
class Offer extends Doctrine_Record {
    
    private $_open_status = 'open';
    
    public function setTableDefinition() {
        $this->hasColumn('user_id', 'integer', 4, array('unsigned' => true));
        $this->hasColumn('spectacle_id', 'integer', 4, array('unsigned' => true));
        $this->hasColumn('normalized_location', 'string', 255);
        $this->hasColumn('cache', 'int', 6);
        $this->hasColumn('time_scope', 'string', 5);
        $this->hasColumn('location_scope', 'string', 8);
        $this->hasColumn('from_date', 'datetime');
        $this->hasColumn('to_date', 'datetime');
        $this->hasColumn('location', 'string', 150);
        $this->hasColumn('city', 'string', 65);
        $this->hasColumn('state', 'string', 60);
        $this->hasColumn('spanish_community', 'string', 23);
        $this->hasColumn('country', 'string', 3);
        $this->hasColumn('description', 'string', 255);
        $this->hasColumn('status', 'string', 12);
    }
    
    public function setUp() {
        
        $this->setTableName('offers');
        
        $this->actAs('Timestampable');
        
        $this->hasOne('Spectacle', array(
            'local'   => 'spectacle_id', 
            'foreign' => 'id'
        ));
    }
    
    public function get_by_id($id)
    {
        $offer = Doctrine::getTable('Offer')->find($id);
        
        return $offer;
    }
    
    public function add_offer(array $params)
    {
        $offer = new Offer();
        
        foreach ($params as $key => $value)
        {
            $offer->$key = $value;
        }
        
        $offer->save();
        
        return $offer->id;
    }
    
    public function get_total_offers($params)
    {        
        $where = "status = 'open' ";
        
        if ($params['location'])
        {
            $where .= ' AND normalized_location = \'' . mysql_real_escape_string($params['location']) . '\' ';
        }
        
        if (isset($params['user']))
        {
            $where .= ' AND user_id = ' . (int) $params['user'];
        }
        
        $result = Doctrine_Query::create()
            ->select('COUNT(*) AS total_offers')
            ->from('Offer')
            ->where($where)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();

        return $result['total_offers'];
    }
    
    public function get_open_offers($params)
    {
        $lang  = $params['language'];
        $where = "status = 'open' ";
        
        if (isset($params['location']) && $params['location'])
        {
            $where .= ' AND normalized_location = \'' . mysql_real_escape_string($params['location']) . '\' ';
        }
        
        if ($params['orderby'] && $this->_is_valid_orderby($params['orderby']))
        {
            $order = $params['orderby'] . ' ' . $params['order'];
        }
        
        if (! isset($params['simple']))
        {
            $offers = Doctrine_Query::create()
                ->select('id, spectacle_id, normalized_location, cache, time_scope, location_scope, from_date, to_date, location, 
                	city, state, spanish_community, country, description, status')
                ->addSelect('s.company_id, s.audience_id, s.spectacle_name, s.normalized_name, s.premiere, s.short_description')
                ->addSelect('m.media AS media')
                ->addSelect("a.id AS audience_id, a.audience_name_$lang AS audience")
                ->from('Offer o')
                ->innerJoin('o.Spectacle s')
                ->innerJoin('s.Audience a')
                ->innerJoin('s.Media m')
                ->where($where)
                ->andWhere('s.auth = 1')
                ->andWhere("m.media_type = 'image' AND main = 1")
                ->orderBy($order)
                ->limit($params['limit'])
                ->offset($params['offset'])
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                ->execute();
        }
        else
        {
            if (isset($params['expiration']))
            {
                $where .= ' AND to_date <= \'' . $params['expiration'] . '\' OR (ISNULL(to_date) AND from_date <= \'' . $params['expiration'] .'\')';
            }
            
            $offers = Doctrine_Query::create()
                ->select('id, spectacle_id, normalized_location, cache, time_scope, location_scope, from_date, to_date, location, 
                	city, state, spanish_community, country, description, status')
                ->from('Offer')
                ->where($where)
                ->orderBy($order)
                ->limit($params['limit'])
                ->offset($params['offset'])
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                ->execute();
        }
        
        return $offers;
    }
    
    public function get_open_locations()
    {
        $locations = Doctrine_Query::create()
            ->select('DISTINCT location AS location_name, normalized_location AS normalized_location')
            ->from('Offer')
            ->where('status = ?', $this->_open_status)
            ->orderBy('location ASC')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchArray();
            
        return $locations;
    }
    
    public function get_user_offers($params)
    {
        $CI = get_instance();
        
        $where = 'user_id = ' . (int) $CI->user_id;
        $order = FALSE;
        
        if ($params['status'] && $this->_is_valid_status($params['status']))
        {
            $where .= ' AND status = \'' . $params['status'] . '\' ';
        }
        
        if ($params['orderby'] && $this->_is_valid_orderby($params['orderby']))
        {
            $order = $params['orderby'] . ' ' . $params['order'];
        }
        
        $offers = Doctrine_Query::create()
            ->select('id, user_id, spectacle_id, normalized_location, cache, time_scope, location_scope, from_date, to_date, location, 
            	city, state, spanish_community, country, description, status')
            ->addSelect('s.company_id, s.spectacle_name, s.normalized_name')
            ->addSelect('m.media AS media')
            ->from('Offer o')
            ->innerJoin('o.Spectacle s')
            ->innerJoin('s.Media m')
            ->where($where)
            ->andWhere('s.auth = 1')
            ->andWhere("m.media_type = 'image' AND main = 1")
            ->orderBy($order)
            ->limit($params['limit'])
            ->offset($params['offset'])
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        return $offers;
    }
    
    public function get_user_offer($offer_id)
    {
        $CI = get_instance();
        
        $user_offer = Doctrine_Query::create()
            ->select('id, user_id, spectacle_id, normalized_location, cache, time_scope, location_scope, from_date, to_date, location, 
            	city, state, spanish_community, country, description, status')
            ->from('Offer')
            ->where('id = ?', $offer_id)
            ->andWhere('user_id = ?', $CI->user_id)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();
                        
        return $user_offer;
    }
    
    public function get_spectacle_open_offers($spectacle_id)
    {
        $offer = Doctrine_Query::create()
            ->select('id, user_id, spectacle_id, normalized_location, cache, time_scope, location_scope, from_date, to_date, location, 
            	city, state, spanish_community, country, description, status')
            ->from('Offer')
            ->where('spectacle_id = ?', $spectacle_id)
            ->andWhere('status = ?', $this->_open_status)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        return $offer;
    }
    
    public function change_status($offer_id, $status)
    {
        if (! $this->_is_valid_status($status))
        {
            return FALSE;
        }
        
        $offer = $this->get_by_id($offer_id);
        
        $offer->status = $status;
        $offer->save();
        
        return TRUE;
    }
    
    private function _is_valid_orderby($field)
    {
        $valid_fields = array('spectacle_id', 'id', 'status', 'from_date', 'to_date', 'RAND()');
        
        if (in_array($field, $valid_fields))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    private function _is_valid_status($status)
    {
        $valid_statuses = array('open', 'review', 'closed');
        
        if (in_array($status, $valid_statuses))
        {
            return TRUE;
        }

        return FALSE;
    }
}