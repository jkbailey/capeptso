<?php

class Application_Model_AuthMapper
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
            $this->setDbTable('Application_Model_DbTable_Auth');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Auth $auth)
    {
        $data = array(
            'username' => $auth->getUsername(),
            'password' => $auth->getPassword(),
            'email' => $auth->getEmail(),
        );
 
        if (null === ($id = $auth->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_Auth $auth)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $auth->setId($row->id)
                  ->setUsername($row->username)
                  ->setPassword($row->password)
                  ->setEmail($row->email);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Auth();
            $entry->setId($row->id)
                  ->setUsername($row->username)
                  ->setPassword($row->password)
                  ->setEmail($row->email);
            $entries[] = $entry;
        }
        return $entries;
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

