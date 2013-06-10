<?php

class Application_Model_Family
{

    protected $_id;
    protected $_authid;
    protected $_optout;
    protected $_lastname;
    protected $_phone;
    protected $_street;
    protected $_city;
    protected $_state;
    protected $_zip;
    protected $_parent1fname;
    protected $_parent1lname;
    protected $_parent1email;
    protected $_parent1phone;
    protected $_parent2fname;
    protected $_parent2lname;
    protected $_parent2email;
    protected $_parent2phone;
    
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
            throw new Exception('Invalid family property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid family property');
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
            } else {
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
 
    public function setOptout($optout)
    {
        $this->_optout = (int) $optout;
        return $this;
    }
 
    public function getOptout()
    {
        return $this->_optout;
    }
 
    public function setLastname($lastname)
    {
        $this->_lastname = (string) $lastname;
        return $this;
    }
 
    public function getLastname()
    {
        return $this->_lastname;
    }
 
    public function setPhone($phone)
    {
        $this->_phone = (string) $phone;
        return $this;
    }
 
    public function getPhone()
    {
        return $this->_phone;
    }
 
    public function setStreet($street)
    {
        $this->_street = (string) $street;
        return $this;
    }
 
    public function getStreet()
    {
        return $this->_street;
    }
 
    public function setCity($city)
    {
        $this->_city = (string) $city;
        return $this;
    }
 
    public function getCity()
    {
        return $this->_city;
    }
 
    public function setState($state)
    {
        $this->_state = (string) $state;
        return $this;
    }
 
    public function getState()
    {
        return $this->_state;
    }
 
    public function setZip($zip)
    {
        $this->_zip = (string) $zip;
        return $this;
    }
 
    public function getZip()
    {
        return $this->_zip;
    }
 
    public function setParent1fname($parent1fname)
    {
        $this->_parent1fname = (string) $parent1fname;
        return $this;
    }
 
    public function getParent1fname()
    {
        return $this->_parent1fname;
    }
 
    public function setParent1lname($parent1lname)
    {
        $this->_parent1lname = (string) $parent1lname;
        return $this;
    }
 
    public function getParent1lname()
    {
        return $this->_parent1lname;
    }
 
    public function setParent1email($parent1email)
    {
        $this->_parent1email = (string) $parent1email;
        return $this;
    }
 
    public function getParent1email()
    {
        return $this->_parent1email;
    }
 
    public function setParent1phone($parent1phone)
    {
        $this->_parent1phone = (string) $parent1phone;
        return $this;
    }
 
    public function getParent1phone()
    {
        return $this->_parent1phone;
    }
 
    public function setParent2fname($parent2fname)
    {
        $this->_parent2fname = (string) $parent2fname;
        return $this;
    }
 
    public function getParent2fname()
    {
        return $this->_parent2fname;
    }
 
    public function setParent2lname($parent2lname)
    {
        $this->_parent2lname = (string) $parent2lname;
        return $this;
    }
 
    public function getParent2lname()
    {
        return $this->_parent2lname;
    }
 
    public function setParent2email($parent2email)
    {
        $this->_parent2email = (string) $parent2email;
        return $this;
    }
 
    public function getParent2email()
    {
        return $this->_parent2email;
    }
 
    public function setParent2phone($parent2phone)
    {
        $this->_parent2phone = (string) $parent2phone;
        return $this;
    }
 
    public function getParent2phone()
    {
        return $this->_parent2phone;
    }

}

