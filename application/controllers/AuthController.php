<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
    
        /* Initialize action controller here */
        if ( $this->getRequest()->getActionName() != 'logout' ) {
            if ( Zend_Auth::getInstance()->hasIdentity() )
            {
            
                $this->_helper->redirector->gotoRoute(array(
                   'controller'=> 'directory',
                   'action' =>'index'));
                
            }
        }
        
    }

    public function indexAction()
    {
        if ( $this->getRequest()->isPost() ) {
            $data = Array();
            $data['username'] = $this->getRequest()->getParam('username');
            $data['password'] = $this->getRequest()->getParam('password');
     
            if ($this->_process($data)) {
                // We're authenticated! Redirect to the home page
                $this->_helper->redirector->gotoRoute(array(
                       'controller'=> 'directory',
                       'action' =>'index'));
            } else {
                $this->view->message = 'Incorrect login and password.';
            }
        }
    }
    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector->gotoRoute(array(
                       'controller'=> 'auth',
                       'action' =>'index'));
    }

    public function registerAction()
    {
    
        $preauthMapper = new Application_Model_PreAuthMapper();
        $authMapper = new Application_Model_AuthMapper();
        $regkeyMapper = new Application_Model_RegkeyMapper();
        $this->regkey = $regkeyMapper->getKey();
        
        if ( $this->getRequest()->isPost() ) {
            if ( strtolower($this->regkey) == strtolower($this->_request->getPost('regkey')) ) {
                $data = $this->_request->getPost();
                
                $result = $preauthMapper->checkUsername($data['username']);
                if ($result) {
                    $this->view->message = 'That username already exists';
                    return;
                }
                $result = $authMapper->checkUsername($data['username']);
                if ($result) {
                    $this->view->message = 'That username already exists';
                    return;
                }
                
                unset($data['regkey']);
                
                // create random string for email confirmation
                $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
                $string = '';
                for ($i = 0; $i < 16; $i++) {
                  $string .= $characters[rand(0, strlen($characters) - 1)];
                }
                $data['ekey'] = $string;
                
                $preauth = new Application_Model_PreAuth($data);
                $preauthMapper->save($preauth);
                
                //send e-mail
                $mail = new Zend_Mail();
                $mail->setBodyHtml('Thank you for creating your Cape online account.<br /><br /><a href="http://www.capeptso.org/auth/confirm/ekey/'.$data['ekey'].'">Please click here to confirm your e-mail</a><br /><br />If the link above does not work, copy and paste this into your browser http://www.capeptso.org/auth/confirm/ekey/'.$data['ekey']);
                $mail->setFrom('admin@capeptso.org', 'Cape PTSO');
                $mail->addTo($data['email'], $data['username']);
                $mail->setSubject('Cape Online Account Confirmation');
                $mail->send();
                //send e-mail
                $mail = new Zend_Mail();
                $mail->setBodyHtml('Thank you for creating your Cape online account.<br /><br /><a href="http://www.capeptso.org/auth/confirm/ekey/'.$data['ekey'].'">Please click here to confirm your e-mail</a><br /><br />If the link above does not work, copy and paste this into your browser http://www.capeptso.org/auth/confirm/ekey/'.$data['ekey']);
                $mail->setFrom('admin@capeptso.org', 'Cape PTSO');
                $mail->addTo('jkbailey@gmail.com');
                $mail->setSubject('Cape Online Account Confirmation');
                $mail->send();
                
                $this->view->message = 'A confirmation e-mail has been sent to '.$data['email'].'.';
            } else {
                $this->view->message = 'You have entered an incorrect auth key';
            }
        }
        
        $this->view->entries = $preauthMapper->fetchAll(); //for testing only... make sure to remove
        
    }
    
    public function confirmAction()
    {
        
        $preauthMapper = new Application_Model_PreAuthMapper();
        $preauth = new Application_Model_PreAuth();
        $ekey = $this->getRequest()->getParam('ekey');
        $result = $preauthMapper->findAuth($ekey);
        
        if ($result) {
        
            $data = Array(
                'username'=>$result['username'],
                'password'=>$result['password'],
                'email'=>$result['email'],
            );
            
            $authMapper = new Application_Model_AuthMapper();
            $auth = new Application_Model_Auth($data);
            $authMapper->save($auth);
            
            $preauthMapper->removeAuth($ekey);
            
            $this->view->success = true;
            
        } else {
            
            //bad ekey
            $this->view->success = false;
            
        }
        
    }
    
    public function forgotPasswordAction()
    {
        // forgot password
    }
    
    protected function _getAuthAdapter()
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $authAdapter->setTableName('auth')
            ->setIdentityColumn('username')
            ->setCredentialColumn('password');
        return $authAdapter;
    }
    
    protected function _process($values)
    {
        // Get our authentication adapter and check credentials
        $adapter = $this->_getAuthAdapter();
        $adapter->setIdentity($values['username']); 
        $adapter->setCredential($values['password']);

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $user = $adapter->getResultRowObject();
            $auth->getStorage()->write($user);
            return true;
        }
        return false;
    }

}

