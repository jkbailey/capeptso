<?php

class Application_Model_PreAuthMapper
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
            $this->setDbTable('Application_Model_DbTable_PreAuth');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_PreAuth $preauth)
    {
        $data = array(
            'username' => $preauth->getUsername(),
            'password' => $preauth->getPassword(),
            'email' => $preauth->getEmail(),
            'ekey' => $preauth->getEkey(),
        );
 
        if (null === ($id = $preauth->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_PreAuth $preauth)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $preauth->setId($row->id)
                  ->setUsername($row->username)
                  ->setPassword($row->password)
                  ->setEmail($row->email)
                  ->setEkey($row->ekey);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_PreAuth();
            $entry->setId($row->id)
                  ->setUsername($row->username)
                  ->setPassword($row->password)
                  ->setEmail($row->email)
                  ->setEkey($row->ekey);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    public function findAuth($ekey)
    {
        $table = $this->getDbTable();
        $select = $table->select();
        $select->where('ekey = ?', $ekey);
        $row = $table->fetchRow($select);
        return $row;
    }
    
    public function removeAuth($ekey)
    {
        $table = $this->getDbTable();
        $where = $table->getAdapter()->quoteInto('ekey = ?', $ekey);
        $table->delete($where);
    }
    
    public function checkUsername($username)
    {
        $table = $this->getDbTable();
        $select = $table->select();
        $select->where('username = ?', $username);
        $row = $table->fetchRow($select);
        return $row;
    }

}

