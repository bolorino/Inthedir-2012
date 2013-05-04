<?php
class Entity extends Doctrine_Record {

    public function setTableDefinition ()
    {
        $this->hasColumn('entity_type_id', 'integer', 4, array('unsigned' => true));
        $this->hasColumn('program_type_id', 'integer', 4, array('unsigned' => true));
        $this->hasColumn('entity_name', 'string', 150);
        $this->hasColumn('normalized_name', 'string', 60);
        $this->hasColumn('month', 'integer', 2);
        $this->hasColumn('address', 'string', 255);
        $this->hasColumn('city', 'string', 65);
        $this->hasColumn('state', 'string', 60);
        $this->hasColumn('spanish_community', 'string', 22);
        $this->hasColumn('postal_code', 'string', 12);
        $this->hasColumn('country', 'string', 3, array('unsigned' => true));
        $this->hasColumn('phone', 'string', 18);
        $this->hasColumn('mobile', 'string', 18);
        $this->hasColumn('email', 'string', 65);
        $this->hasColumn('website', 'string', 90);
        $this->hasColumn('founded', 'integer', 4);
        $this->hasColumn('image', 'string', 50);
        $this->hasColumn('logo', 'string', 50);
        $this->hasColumn('short_description', 'string', 255);
        $this->hasColumn('about', 'text', 1500);
        $this->hasColumn('status', 'string', 10);
        $this->hasColumn('auth', 'integer', 1);
        $this->hasColumn('counter', 'integer', 4);

    }

    public function setUp()
    {

        $this->setTableName('entities');

        $this->actAs('Timestampable');

        $this->hasOne('Entity_type as Type', array(
            'local' => 'entity_type_id',
            'foreign' => 'id'
        ));

        $this->hasOne('Program_type as Program', array(
            'local' => 'program_type_id',
            'foreign' => 'id'
        ));

        $this->hasMany('Entity_contact as Contact', array(
            'local' => 'id',
            'foreign' => 'entity_id'
        ));
    }

    public function get_total_entities($params)
    {
        $where = 'auth = 1 ';

        if ($params['entity_type_id'])
        {
            if (is_array($params['entity_type_id']))
            {
                // Multiple selection
                $where .= ' AND entity_type_id IN (';

                foreach ($params['entity_type_id'] as $type_id)
                {
                    (int) $type_id;
                    $where .= "'$type_id', ";
                }

                // Strip last comma and whitespace
                $where = substr($where, 0, -2);

                $where .= ')';
            }
            else
            {
                $where .= ' AND entity_type_id = ' . intval($params['entity_type_id']);
            }
        }

        if (isset($params['program_type_id']) && $params['program_type_id'])
        {
            $program_type_id = intval($params['program_type_id']);
            $where .= ' AND program_type_id = ' . $program_type_id;
        }

        if (isset($params['search_query']))
        {
            $where .= ' AND entity_name LIKE \'%' . mysql_real_escape_string($params['search_query']) . '%\' ';
        }

        if ($params['country'])
        {
            $where .= " AND country = '" . $params['country'] ."' ";
        }

        if (isset($params['letter']))
        {
            $where .= ' AND entity_name LIKE "' . $params['letter'] . '%" ';
        }

        $result = Doctrine_Query::create()
            ->select('COUNT(*) AS total_entities')
            ->from('Entity')
            ->where($where)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();

        return $result['total_entities'];

    }

    public function get_entities_array($params)
    {
        $CI = get_instance();

        $where = 'auth = 1 ';
        $order = FALSE;

        if ($params['orderby'] && $this->_is_valid_orderby($params['orderby']))
        {
            $order = $params['orderby'] . ' ' . $params['order'];
        }

        if ($params['entity_type_id'])
        {
            if (is_array($params['entity_type_id']))
            {
                // Multiple selection
                $where .= ' AND entity_type_id IN (';

                foreach ($params['entity_type_id'] as $type_id)
                {
                    (int) $type_id;
                    $where .= "'$type_id', ";
                }

                // Strip last comma and whitespace
                $where = substr($where, 0, -2);

                $where .= ')';
            }
            else
            {
                $where .= ' AND entity_type_id = ' . intval($params['entity_type_id']);
            }
        }

        if ($params['program_type_id'])
        {
            $program_type_id = intval($params['program_type_id']);
            $where .= ' AND program_type_id = ' . $program_type_id;
        }

        if ($params['search_query'])
        {
            $where .= ' AND entity_name LIKE \'%' . mysql_real_escape_string($params['search_query']) . '%\' ';
        }

        if ($params['country'])
        {
            $where .= " AND country = '" . $params['country'] ."' ";
        }

        if (isset($params['letter']))
        {
            $where .= ' AND entity_name LIKE "' . $params['letter'] . '%" ';
        }

        if (isset($params['exclude']))
        {
            $where .= ' AND ' . $params['exclude_field'] . ' != \'' . $params['exclude'] . '\' ';
        }

        $entities = Doctrine_Query::create()
            ->select('id, entity_type_id, program_type_id, entity_name, normalized_name, month, address, city, spanish_community, state, country,
            	phone, mobile, email, website, founded, image, logo, short_description, about, status, auth, created_at, updated_at')
            ->addSelect("t.name_$CI->itdlang AS category_name, p.name_$CI->itdlang AS program_name")
            ->from('Entity e')
            ->innerJoin('e.Type t, e.Program p')
            ->where($where)
            ->orderBy($order)
            ->limit($params['limit'])
            ->offset($params['offset'])
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();

        return $entities;
    }

    public function get_by_id($id, $simple = TRUE)
    {
        // Must be Object

        if ($simple === TRUE)
        {
            $entity = Doctrine::getTable('Entity')->findOneBy('id', intval($id));
        }
        else
        {
            $entity = Doctrine::getTable('Entity')
            ->createQuery('e')
            ->innerJoin('e.Type, e.Program')
            ->addWhere('id = ?', $id)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();
        }

        return $entity;
    }

    public function get_by_normalized_name($name, $simple = TRUE)
    {
        if ($simple === TRUE)
        {
            $entity = Doctrine::getTable('Entity')->findOneBy('normalized_name', $name, Doctrine_Core::HYDRATE_ARRAY);
        }
        else
        {
            $CI = get_instance();

            $entity = Doctrine::getTable('Entity')
                ->createQuery('e')
                ->select(
                	'entity_type_id, program_type_id, entity_name, normalized_name, month, address, city, state, spanish_community,
                	postal_code, country, phone, mobile, email, website, image, logo, short_description, about'
                )
                ->innerJoin('e.Type t, e.Program p')
                ->addSelect("t.name_$CI->itdlang AS type_name, p.name_$CI->itdlang AS program_name")
                ->addWhere('normalized_name = ?', $name)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                ->execute();
        }

        return $entity;
    }

    public function get_by_name($name)
    {
        $entity = Doctrine_Query::create()
            ->select('id, entity_type_id, program_type_id, month, city, state, spanish_community, country, entity_name, normalized_name, status')
            ->from('Entity')
            ->where('entity_name LIKE ?', "%$name%")
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();

        if ( ! $entity)
        {
            return FALSE;
        }

        return $entity;
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
            ->from('Entity')
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

    public function find_entities($q)
    {
        $result = Doctrine_Query::create()
            ->select('id, entity_type_id, program_type_id, month, city, state, spanish_community, country, entity_name, normalized_name, short_description')
            ->from('Entity')
            ->where('auth = 1')
            ->andWhere('entity_name LIKE ?', $q)
            ->orWhere('short_description LIKE ?', $q)
            ->orWhere('city LIKE ?', $q)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();

        if ( ! $result)
        {
            return FALSE;
        }

        return $result;
    }

    public function get_available_entity_types($lang)
    {
        $entity_types = Doctrine_Query::create()
            ->select("id, name_$lang AS type_name")
            ->from('Entity_type')
            ->where('id IN (SELECT DISTINCT e.entity_type_id FROM Entity e)')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();

        if (! $entity_types)
        {
            return FALSE;
        }

        return $entity_types;

    }

    /**
     * Gets the differents countries from filtered entities.
     * @author Jose Bolorino
     *
     * @param array $params
     *
     * @return array
     **/
    public function get_available_countries($params)
    {

        $where = 'auth = 1';

        if ($params['entity_type_id'])
        {
            $entity_type_id = intval($params['entity_type_id']);
            $where .= ' AND entity_type_id = ' . $entity_type_id;
        }

        if ($params['program_type_id'])
        {
            $program_type_id = intval($params['program_type_id']);
            $where .= ' AND program_type_id = ' . $program_type_id;
        }

        $countries = Doctrine_Query::create()
            ->select('DISTINCT (country) AS country')
            ->from('Entity')
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

    public function get_available_alphabet()
    {
        $alphabet = Doctrine_Query::create()
            ->select('DISTINCT SUBSTRING(entity_name, 1, 1) AS letter')
            ->from('Entity')
            ->orderBy('entity_name ASC')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();

        return $alphabet;
    }

    public function add_entity(array $params)
    {
        // $params : fields/values array

        $entity = new Entity();

        foreach ($params as $key => $value)
        {
            $entity->$key = $value;
        }

        $entity->save();

        return $entity->id;
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
        $valid_fields = array('entity_name', 'month', 'city', 'state', 'spanish_community', 'country', 'founded', 'created_at', 'updated_at');

        if ( ! in_array($field, $valid_fields))
        {
            return FALSE;
        }

        return TRUE;
    }
}
