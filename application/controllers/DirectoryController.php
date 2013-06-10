<?php

class DirectoryController extends Zend_Controller_Action
{

    public function init()
    {
    
        /* Initialize action controller here */
        if ( Zend_Auth::getInstance()->hasIdentity() )
        {
        
            $this->userData = Zend_Auth::getInstance()->getStorage()->read();
            
        } else {
        
            $this->_helper->redirector->gotoRoute(array(
               'controller'=> 'auth',
               'action' =>'index'));
        }
        
    }

    public function indexAction()
    {
    
        $familyMapper = new Application_Model_FamilyMapper();
        $studentsMapper = new Application_Model_StudentsMapper();
        $this->view->family = $familyMapper->findFamily($this->userData->id);
        
        if (!$this->view->family['lastname']) {
            $this->_helper->redirector->gotoRoute(array(
                'controller'=> 'directory',
                'action' =>'editfamily'));
        }
        
        $this->view->students = $studentsMapper->listStudents($this->userData->id);
        
    }

    public function editfamilyAction()
    {
    
        $familyMapper = new Application_Model_FamilyMapper();
        
        if ( $this->getRequest()->isPost() )
        {
        
            $data = $this->_request->getPost();
            $data['authid'] = $this->userData->id;
            if (isset($data['optout'])) {
                $data['optout'] = 1;
            } else {
                $data['optout'] = 0;
            }
            $family = new Application_Model_Family($data);
            $familyMapper->save($family);
            $this->_helper->redirector->gotoRoute(array(
               'controller'=> 'directory',
               'action' =>'index'));
            
        }
        
        $this->view->family = $familyMapper->findFamily($this->userData->id);
        
    }

    public function studentAction()
    {
    
        $studentsMapper = new Application_Model_StudentsMapper();
        
        if ( $this->getRequest()->getParam('id') )
        {
            
            $sid = $this->getRequest()->getParam('id');
            $this->view->student = $studentsMapper->findStudent($sid);
            
        }
        
        if ( $this->getRequest()->isPost() )
        {
        
            $data = $this->_request->getPost();
            $data['authid'] = $this->userData->id;
            $students = new Application_Model_Students($data);
            $studentsMapper->save($students);
            $this->_helper->redirector->gotoRoute(array(
               'controller'=> 'directory',
               'action' =>'index'));
            
        }
        
    }

    public function viewfamilyAction()
    {
    
        if ( $this->getRequest()->getParam('id') )
        {
            
            $authid = $this->getRequest()->getParam('id');
          
            $familyMapper = new Application_Model_FamilyMapper();
            $studentsMapper = new Application_Model_StudentsMapper();
            
            $this->view->family = $familyMapper->findFamily($authid);
            $this->view->students = $studentsMapper->listStudents($authid);
            
        }
        
    }
    
    public function searchAction()
    {
    
        $db = Zend_Db_Table::getDefaultAdapter();
        $s = $this->getRequest()->getParam('s');
        $this->view->term = $s;
        $p = explode(' ',$s);
        
        $sql = 'SELECT  family.id AS familyid, 
                        students.id AS studentid,
                        family.authid, 
                        family.optout, 
                        students.fname, 
                        students.lname, 
                        students.teacher, 
                        students.grade, 
                        family.lastname, 
                        family.street, 
                        family.city, 
                        family.state, 
                        family.zip, 
                        family.phone, 
                        family.parent1fname,  
                        family.parent1lname, 
                        family.parent1email,
                        family.parent1phone, 
                        family.parent2fname, 
                        family.parent2lname, 
                        family.parent2email,
                        family.parent2phone
                        FROM family LEFT JOIN students ON family.authid = students.authid WHERE family.optout = 0';
        
        foreach($p AS $v)
        {
            $sql = $sql . ' AND (
                        students.fname LIKE \'%'.$v.'%\' OR
                        students.lname LIKE \'%'.$v.'%\' OR
                        students.teacher LIKE \'%'.$v.'%\' OR
                        students.grade LIKE \'%'.$v.'%\' OR
                        family.lastname LIKE \'%'.$v.'%\' OR
                        family.street LIKE \'%'.$v.'%\' OR
                        family.city LIKE \'%'.$v.'%\' OR
                        family.state LIKE \'%'.$v.'%\' OR
                        family.zip LIKE \'%'.$v.'%\' OR
                        family.phone LIKE \'%'.$v.'%\' OR
                        family.parent1fname LIKE \'%'.$v.'%\' OR
                        family.parent1lname LIKE \'%'.$v.'%\' OR
                        family.parent1email LIKE \'%'.$v.'%\' OR
                        family.parent1phone LIKE \'%'.$v.'%\' OR
                        family.parent2fname LIKE \'%'.$v.'%\' OR
                        family.parent2lname LIKE \'%'.$v.'%\' OR
                        family.parent2email LIKE \'%'.$v.'%\' OR
                        family.parent2phone LIKE \'%'.$v.'%\'
                        )';
        }
                        
        //print '<pre>' . $sql . '<br /><br /><pre>';
        //exit;
        $stmt = $db->query($sql);
        $result = $stmt->fetchall();
        
        $r = Array();
        foreach ($result AS $row)
        {
            if ( $row['fname'] )
            {
                $r[$row['familyid']]['students'][$row['studentid']]['fname'] = $row['fname'];
                $r[$row['familyid']]['students'][$row['studentid']]['lname'] = $row['lname'];
                $r[$row['familyid']]['students'][$row['studentid']]['teacher'] = $row['teacher'];
                $r[$row['familyid']]['students'][$row['studentid']]['grade'] = $row['grade'];
            }
            
            $r[$row['familyid']]['lastname'] = $row['lastname'];
            $r[$row['familyid']]['street'] = $row['street'];
            $r[$row['familyid']]['city'] = $row['city'];
            $r[$row['familyid']]['state'] = $row['state'];
            $r[$row['familyid']]['zip'] = $row['zip'];
            $r[$row['familyid']]['phone'] = $row['phone'];
            $r[$row['familyid']]['parent1fname'] = $row['parent1fname'];
            $r[$row['familyid']]['parent1lname'] = $row['parent1lname'];
            $r[$row['familyid']]['parent1email'] = $row['parent1email'];
            $r[$row['familyid']]['parent1phone'] = $row['parent1phone'];
            $r[$row['familyid']]['parent2fname'] = $row['parent2fname'];
            $r[$row['familyid']]['parent2lname'] = $row['parent2lname'];
            $r[$row['familyid']]['parent2email'] = $row['parent2email'];
            $r[$row['familyid']]['parent2phone'] = $row['parent2phone'];
            $r[$row['familyid']]['authid'] = $row['authid'];
        }
        
        $this->view->results = $r;
        
    }

}

