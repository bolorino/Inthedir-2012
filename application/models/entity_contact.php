<?php
class Entity_contact extends Doctrine_Record {

    public function setTableDefinition ()
    {
        $this->hasColumn('entity_id', 'integer', 4, array('unsigned' => true));
        $this->hasColumn('salutation_id', 'integer', 4, array('unsigned' => true));
        $this->hasColumn('contact_first_name', 'string', 35);
        $this->hasColumn('contact_last_name', 'string', 50);
        $this->hasColumn('contact_position', 'string', 50);
        $this->hasColumn('contact_department', 'string', 120);
        $this->hasColumn('contact_email', 'string', 65);
        $this->hasColumn('contact_phone', 'string', 18);
        $this->hasColumn('contact_mobile', 'string', 18);
        $this->hasColumn('contact_notes', 'string', 1500);
    }

    public function setUp()
    {
        $this->setTableName('entity_contacts');

        $this->actAs('Timestampable');

        $this->hasOne('Salutation as Salutation', array(
            'local' => 'salutation_id',
            'foreign' => 'id'
        ));

    }

    public function get_contacts_by_entity($eid)
    {
        $contacts = Doctrine::getTable('Entity_contact')->findBy('entity_id', $eid, Doctrine_Core::HYDRATE_ARRAY);

        return $contacts;
    }

    public function get_contact_by_id($cid)
    {
        $contact = Doctrine::getTable('Entity_contact')->findOneBy('id', intval($cid));

        return $contact;
    }

    public function add_entity_contact($eid, array $fields)
    {
        // $params : fields/values array

        $entity_contact = new Entity_contact();

        $entity_contact->entity_id = intval($eid);

        foreach ($fields as $key => $value)
        {
            $entity_contact->$key = $value;

        }

        $entity_contact->save();

        return TRUE;
    }

    public function update_entity_contact($cid, array $fields)
    {
        $entity_contact = $this->get_contact_by_id($cid);

        if ( ! $entity_contact)
        {
            return FALSE;
        }

        foreach ($fields as $key => $value)
        {
            $entity_contact->$key = $value;

        }

        $entity_contact->save();

        return TRUE;
    }

}
