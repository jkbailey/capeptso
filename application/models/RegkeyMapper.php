<?php

class Application_Model_RegkeyMapper
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
            $this->setDbTable('Application_Model_DbTable_Regkey');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Regkey $regkey)
    {
        $data = array(
            'regkey' => $regkey->getRegkey(),
        );
 
        if (null === ($id = $regkey->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_Regkey $regkey)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $regkey->setId($row->id)
                  ->setEkey($row->regkey);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Regkey();
            $entry->setId($row->id)
                  ->setRegkey($row->regkey);
            $entries[] = $entry;
        }
        return $entries;
    }
 
    public function getKey()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        $row = $resultSet[0];
        return $row->regkey;
    }

}

