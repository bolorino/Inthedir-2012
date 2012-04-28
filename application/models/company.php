<?php
class Company extends Doctrine_Record {
    
    public function setTableDefinition () 
    {
        $this->hasColumn('category_id', 'integer', 4, array('unsigned' => true));
        $this->hasColumn('country', 'string', 3, array('unsigned' => true));
        $this->hasColumn('company_name', 'string', 125);
        $this->hasColumn('normalized_name', 'string', 60);
        $this->hasColumn('image', 'string', 150);
        $this->hasColumn('logo', 'string', 50);
        $this->hasColumn('founded', 'integer', 4);
        $this->hasColumn('address', 'string', 255);
        $this->hasColumn('city', 'string', 65);
        $this->hasColumn('state', 'string', 60);
        $this->hasColumn('spanish_community', 'string', 22);
        $this->hasColumn('postal_code', 'string', 12);
        $this->hasColumn('phone', 'string', 18);
        $this->hasColumn('mobile', 'string', 18);
        $this->hasColumn('email', 'string', 65);
        $this->hasColumn('website', 'string', 90);
        $this->hasColumn('members', 'integer', 4);
        $this->hasColumn('contact_person', 'string', 60);
        $this->hasColumn('short_description', 'string', 255);
        $this->hasColumn('about', 'text', 1500);
        $this->hasColumn('inthedir', 'integer', 1);
        $this->hasColumn('status', 'string', 10);
        $this->hasColumn('auth', 'integer', 1);
        $this->hasColumn('counter', 'integer', 4);
        
    }
    
    public function setUp() 
    {
        
        $this->setTableName('companies');
        
        $this->actAs('Timestampable');
        
        $this->hasMany('Spectacle as Spectacles', array(
            'local' => 'id', 
            'foreign' => 'company_id'
        ));
        
        $this->hasMany('Manager_agenda as Manager_companies', array(
            'local' => 'id', 
            'foreign' => 'company_id'
        ));
        
        $this->hasOne('Category', array(
            'local' => 'category_id', 
            'foreign' => 'id'
        ));
        
        $this->hasOne('Country', array(
            'local' => 'country', 
            'foreign' => 'id_country'
        ));
    }
    
    public function get_total_companies($params)
    {        
        $where = 'auth = 1 ';
        
        if ($params['category_id'])
        {
            $category_id = intval($params['category_id']);
            $where .= ' AND category_id = ' . $category_id;
        }
        
        if ($params['country'])
        {
            $where .= ' AND country = "' . $params['country'] . ' " ';
        }
        
        if (isset($params['letter']))
        {
            $where .= ' AND company_name LIKE "' . $params['letter'] . '%" ';
        }
        
        $result = Doctrine_Query::create()
            ->select('COUNT(*) AS total_companies')
            ->from('Company')
            ->where($where)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();
        
        return $result['total_companies'];
        
    }
    
    public function get_companies_array($params) 
    {
        // Authorized companies by default.
        // Passing the parameter auth most likely be a query for unauthorized companies (like in registration)

        if (! isset($params['auth']))
        {
            $where = 'auth = 1 ';
            $order = 'inthedir DESC';
        }
        else
        {
            $where = 'auth = ' . ($params['auth'] == 0 ? 0 : 1);
            $order = 'company_name ASC';
        }
        
        if ($params['orderby'] && $this->_is_valid_orderby($params['orderby']))
        {
            $order .= ', ' . $params['orderby'] . ' ' . $params['order'];
        }
        
        if ($params['country'])
        {
            if (is_array($params['country']))
            {
                // Multi Country selection
                $where .= ' AND country IN (';
                
                foreach ($params['country'] as $country)
                {
                    $where .= "'$country', ";
                }
                
                // Strip last comma and whitespace 
                $where = substr($where, 0, -2);
                
                $where .= ')';
            }
            else
            {
                $where .= " AND country = '" . $params['country'] ."' ";
            }
        }
        
        if (isset($params['spanish_community']) && $params['spanish_community'])
        {
            if (is_array($params['spanish_community']))
            {
                $where .= " AND (country = 'ES' AND spanish_community IN (";
                
                foreach ($params['spanish_community'] as $community)
                {
                    $where .= "'$community', ";
                }
                
                // Strip last comma and whitespace 
                $where = substr($where, 0, -2);
                
                $where .= '))';
            }
            else 
            {
                $where .= " AND (country = 'es' AND spanish_community = '" . $params['spanish_community'] . "')";
            }
        }
        
        if ($params['category'])
        {
            if (is_array($params['category']))
            {
                // Multi category selection
                $where .= ' AND category_id IN (';
                
                foreach ($params['category'] as $category)
                {
                    $where .= intval($category) . ', ';
                }
                
                // Strip last comma and whitespace 
                $where = substr($where, 0, -2);
                
                $where .= ')';
            }
            else
            {
                $category = intval($params['category']);
                $where .= ' AND category_id = ' . $category;
            }
        }
        
        if (isset($params['letter']))
        {
            $where .= ' AND company_name LIKE "' . $params['letter'] . '%" ';
        }
        
        if (isset($params['status']))
        {
            $where .= ' AND status = \'' . $params['status'] . '\' ';
        }
        
        if (isset($params['inthedir']))
        {
            $where .= ' AND inthedir = 1';
        }
        
        $companies = Doctrine_Query::create()
            ->select('id, category_id, city, state, spanish_community, country, company_name, normalized_name, image, founded, contact_person, short_description, inthedir, status, auth, created_at, updated_at')
            ->from('Company')
            ->where($where)
            ->orderBy($order)
            ->limit($params['limit'])
            ->offset($params['offset'])
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
        
        return $companies;
    }
    
    public function get_featured_companies(array $companies_id) 
    {
        
        $where = 'auth = 1 AND ';
        $last_item = count($companies_id);
        $item = 0;
        
        foreach ($companies_id as $cid)
        {
            $item++;
            $where .= 'id = ' . $cid;
            
            if ($item < $last_item)
            {
                $where .= ' OR ';
            }
        }
        
        $companies = Doctrine_Query::create()
            ->select('id, category_id, country, company_name, normalized_name, image, founded, short_description, inthedir, status, auth')
            ->from('Company')
            ->where($where)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
        
        return $companies;
    }
    
    public function get_by_id($id, $simple = TRUE)
    {
        if ($simple)
        {
            // Must be Object. Used in global controllers
        
            $company = Doctrine::getTable('Company')->find($id);
        }
        else 
        {
            // Used to send calls
            
            $company = Doctrine_Query::create()
                ->select('id, company_name, contact_person, inthedir, status, auth')
                ->from('Company')
                ->where('id = ?', $id)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                ->fetchOne();
        }
        
        return $company;
    }
    
    public function get_basic_info($id)
    {
        $company = Doctrine_Query::create()
            ->select('id, category_id, country, company_name, normalized_name')
            ->from('Company')
            ->where('id = ?', $id)
            ->addWhere('auth = 1 AND inthedir =1')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();
            
            return $company;
    }
    
    public function get_by_category($cat_id)
    {
        $companies = Doctrine::getTable('Company')->findBy('category_id', $cat_id, Doctrine_Core::HYDRATE_ARRAY);
        
        return $companies;
    }
    
    public function get_by_normalized_name($name)
    {
        $company = Doctrine::getTable('Company')->findOneBy('normalized_name', $name);
        
        return $company;
    }
    
    public function get_by_name($name, $params = FALSE)
    {
        $add_where = 1;

        if (isset($params['exclude_id']))
        {
            $add_where = 'id != ' . (int) $params['exclude_id'];
            $add_where .= ' AND inthedir = 0';
        }

        $company = Doctrine_Query::create()
            ->select('id, category_id, country, company_name, normalized_name, email, status')
            ->from('Company')
            ->where('company_name LIKE ?', "%$name%")
            ->andWhere($add_where)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        if ( ! $company)
        {
            return FALSE;
        }
        
        return $company;
    }

    public function get_company_background($company_id)
    {
        $company = Doctrine_Query::create()
            ->select('id, about')
            ->from('Company')
            ->where('id = ?', $company_id)
            ->limit(1)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();

        if ( ! $company)
        {
            return FALSE;
        }

        return $company['about'];
    }
    
    /**
     * Gets the last similar normalized name. 
     * Normalized names are clean strings from names that could be not unique.
     * If normalized name already exists -N is added to string, where N is an incremental number.
     * @author Jose Bolorino
     * 
     * @param string $name
     * 
     * @return string
     **/
    public function get_last_normalized($name)
    {
        $last_normalized = Doctrine_Query::create()
            ->select('normalized_name')
            ->from('Company')
            ->where('normalized_name LIKE ?', $name)
            ->orderBy('normalized_name DESC')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();

        if ( ! $last_normalized)
        {
            return FALSE;
        }

        return $last_normalized;        
    }
    
    public function find_companies($q)
    {
        $result = Doctrine_Query::create()
            ->select('id, country, company_name, normalized_name, image, founded, city, state, postal_code, contact_person, short_description')
            ->from('Company')
            ->where('auth = 1')
            ->andWhere('company_name LIKE ?', $q)
            ->orWhere('short_description LIKE ?', $q)
            ->orWhere('city LIKE ?', $q)
            ->orWhere('contact_person LIKE ?', $q)
            ->orWhere('founded = ?', $q)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            // echo $result->getSqlQuery();
            
        if ( ! $result)
        {
            return FALSE;
        }

        return $result;
    }
    
    public function get_similar_companies($params, $limit = 5)
    {
        $limit = intval($limit);
        $where = 'auth = 1 ';
        
        if ($params['country'] && strlen($params['country']) == 2)
        {
            $where .= " AND country = '" . $params['country'] ."' ";
        }
        
        if ($params['category_id'])
        {
            $where .= ' AND category_id = ' . intval($params['category_id']);
        }
        
        if ($params['exclude_id'])
        {
            $where .= ' AND id != ' . intval($params['exclude_id']);
        }
        
        $result = Doctrine_Query::create()
            ->select('id, country, company_name, normalized_name, image, city, RANDOM() AS rand')
            ->from('Company')
            ->where($where)
            ->orderBy('rand')
            ->limit($limit)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        if ( ! $result)
        {
            return FALSE;
        }

        return $result;
    }
    
    public function add($fields)
    {
        $company = new Company();
        
        foreach ($fields as $key => $value) 
        {
            $company->$key = $value;
        }
        
        $company->save();
        
        return $company->id;
        
    }
    
    public function update($company_id, $fields)
    {
        $company = $this->get_by_id($company_id);
        
        foreach ($fields as $key => $value) 
        {
            $company->$key = $value;
        }
        
        $company->save();
        
        return TRUE;
        
    }
    
    /**
     * Gets the differents countries from authorized companies.
     * @author Jose Bolorino
     * 
     * @param int $cat 
     * 
     * @return array
     **/
    public function get_available_countries($cat=FALSE)
    {
        $where = 'auth = 1';
        
        if ($cat)
        {
            $where .= ' AND category_id = ' . $cat;
        }
        
        $countries = Doctrine_Query::create()
            ->select('DISTINCT (country) AS country')
            ->from('Company')
            ->where($where)
            ->orderBy('country ASC')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
        
        if ( ! $countries) 
        {
            return FALSE;
        } 
        
        return $countries;
    }
    
    public function get_available_alphabet($params = FALSE)
    {
        $where = 'auth = 1 ';
        $order = FALSE;
        
        if ($params['category'])
        {
            if (is_array($params['category']))
            {
                // Multi category selection
                $where .= ' AND category_id IN (';
                
                foreach ($params['category'] as $category)
                {
                    $where .= intval($category) . ', ';
                }
                
                // Strip last comma and whitespace 
                $where = substr($where, 0, -2);
                
                $where .= ')';
            }
            else
            {
                $category = intval($params['category']);
                $where .= ' AND category_id = ' . $category;
            }
        }
        
        if (isset($params['from_agenda']))
        {
            $where .= ' AND id IN (SELECT a.company_id FROM Manager_agenda a WHERE a.manager_id = ' . intval($params['from_agenda']) . ') ';
        }
        
        $alphabet = Doctrine_Query::create()
            ->select('DISTINCT UPPER(SUBSTRING(company_name, 1, 1)) AS letter')
            ->from('Company')
            ->where($where)
            ->orderBy('company_name ASC')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
            
        return $alphabet;
    }
    
    /**
     * Records a visit to the company file.
     * @author Jose Bolorino
     * 
     * @param int $cid Company ID
     * 
     * @return void
     **/
    public function add_visit($cid)
    {
        $add_visit = Doctrine_Query::create()
            ->update('Company')
            ->set('counter', 'counter + 1')
            ->where('id = ?', $cid)
            ->execute();
        
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
        $valid_fields = array('company_name', 'founded', 'created_at', 'updated_at');
        
        if ( ! in_array($field, $valid_fields))
        {
            return FALSE;
        }

        return TRUE;
    }
}
