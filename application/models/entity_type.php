<?php
class Entity_type extends Doctrine_Record {

    public function setTableDefinition ()
    {
        $this->hasColumn('name_en', 'string', 50);
        $this->hasColumn('name_es', 'string', 50);
        $this->hasColumn('normalized_name_en', 'string', 60);
        $this->hasColumn('normalized_name_es', 'string', 60);

    }

    public function setUp()
    {
        $this->setTableName('entity_types');

    }

    public function get_all_by_lang($lang)
    {
        $entity_types = Doctrine_Query::create()
            ->select("id, name_$lang AS entity_type_name, normalized_name_$lang AS normalized_name")
            ->from('Entity_type')
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();

        return $entity_types;

    }

    public function get_by_id($id, $lang)
    {
        $entity_type = Doctrine_Query::create()
            ->select("id, name_$lang AS name")
            ->from('Entity_type')
            ->where('id = ?', $id)
            ->limit(1)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->fetchOne();

        if (!$entity_type) {
            return FALSE;
        }

        return $entity_type;
    }

}
