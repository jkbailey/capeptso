<?php

class Application_Model_Students
{

    protected $_id;
    protected $_authid;
    protected $_fname;
    protected $_lname;
    protected $_teacher;
    protected $_grade;
    
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid students property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid students property');
        }
        return $this->$method();
    }
 
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
 
    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }
 
    public function getId()
    {
        return $this->_id;
    }
 
    public function setAuthid($authid)
    {
        $this->_authid = (int) $authid;
        return $this;
    }
 
    public function getAuthid()
    {
        return $this->_authid;
    }
 
    public function setFname($fname)
    {
        $this->_fname = (string) $fname;
        return $this;
    }
 
    public function getFname()
    {
        return $this->_fname;
    }
 
    public function setLname($lname)
    {
        $this->_lname = (string) $lname;
        return $this;
    }
 
    public function getLname()
    {
        return $this->_lname;
    }
 
    public function setTeacher($teacher)
    {
        $this->_teacher = (string) $teacher;
        return $this;
    }
 
    public function getTeacher()
    {
        return $this->_teacher;
    }
 
    public function setGrade($grade)
    {
        $this->_grade = (int) $grade;
        return $this;
    }
 
    public function getGrade()
    {
        return $this->_grade;
    }
    
}

