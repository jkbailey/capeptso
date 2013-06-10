<?php

class Application_Model_FamilyMapper
{

    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Family');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Family $family)
    {
        $data = array(
            'id' => $family->getId(),
            'authid' => $family->getAuthid(),
            'optout' => $family->getOptout(),
            'lastname' => $family->getlastname(),
            'phone' => $family->getPhone(),
            'street' => $family->getStreet(),
            'city' => $family->getCity(),
            'state' => $family->getState(),
            'zip' => $family->getZip(),
            'parent1fname' => $family->getParent1fname(),
            'parent1lname' => $family->getParent1lname(),
            'parent1email' => $family->getParent1email(),
            'parent1phone' => $family->getParent1phone(),
            'parent2fname' => $family->getParent2fname(),
            'parent2lname' => $family->getParent2lname(),
            'parent2email' => $family->getParent2email(),
            'parent2phone' => $family->getParent2phone(),
        );
 
        if (null === ($id = $family->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_Family $family)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $family->setId($row->id)
                  ->setAuthid($row->authid)
                  ->setOptout($row->optout)
                  ->setLastname($row->lastname)
                  ->setPhone($row->phone)
                  ->setStreet($row->street)
                  ->setCity($row->city)
                  ->setState($row->state)
                  ->setZip($row->zip)
                  ->setParent1fname($row->parent1fname)
                  ->setParent1lname($row->parent1lname)
                  ->setParent1email($row->parent1email)
                  ->setParent1phone($row->parent1phone)
                  ->setParent2fname($row->parent2fname)
                  ->setParent2lname($row->parent2lname)
                  ->setParent2email($row->parent2email)
                  ->setParent2phone($row->parent2phone);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Family();
            $entry->setId($row->id)
                  ->setAuthid($row->authid)
                  ->setOptout($row->optout)
                  ->setLastname($row->lastname)
                  ->setPhone($row->phone)
                  ->setStreet($row->street)
                  ->setCity($row->city)
                  ->setState($row->state)
                  ->setZip($row->zip)
                  ->setParent1fname($row->parent1fname)
                  ->setParent1lname($row->parent1lname)
                  ->setParent1email($row->parent1email)
                  ->setParent1phone($row->parent1phone)
                  ->setParent2fname($row->parent2fname)
                  ->setParent2lname($row->parent2lname)
                  ->setParent2email($row->parent2email)
                  ->setParent2phone($row->parent2phone);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    public function findFamily($authid)
    {
        $table = $this->getDbTable();
        $select = $table->select();
        $select->where('authid = ?', $authid);
        $row = $table->fetchRow($select);
        return $row;
    }

}

