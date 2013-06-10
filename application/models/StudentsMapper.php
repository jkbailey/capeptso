<?php

class Application_Model_StudentsMapper
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
            $this->setDbTable('Application_Model_DbTable_Students');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Students $students)
    {
        $data = array(
            'id' => $students->getId(),
            'authid' => $students->getAuthid(),
            'fname' => $students->getFname(),
            'lname' => $students->getLname(),
            'teacher' => $students->getTeacher(),
            'grade' => $students->getGrade(),
        );
 
        if (null === ($id = $students->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    public function find($id, Application_Model_Students $students)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $students->setId($row->id)
              ->setAuthid($row->authid)
              ->setFname($row->fname)
              ->setLname($row->lname)
              ->setTeacher($row->teacher)
              ->setGrade($row->grade);
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Students();
            $entry->setId($row->id)
                  ->setAuthid($row->authid)
                  ->setFname($row->fname)
                  ->setLname($row->lname)
                  ->setTeacher($row->teacher)
                  ->setGrade($row->grade);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    public function listStudents($authid)
    {
        $table = $this->getDbTable();
        $select = $table->select();
        $select->where('authid = ?', $authid);
        $resultSet = $table->fetchAll($select);
        
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Students();
            $entry->setId($row->id)
                  ->setAuthid($row->authid)
                  ->setFname($row->fname)
                  ->setLname($row->lname)
                  ->setTeacher($row->teacher)
                  ->setGrade($row->grade);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    public function findStudent($id)
    {
        $table = $this->getDbTable();
        $select = $table->select();
        $select->where('id = ?', $id);
        $row = $table->fetchRow($select);
        return $row;
    }
    
}

