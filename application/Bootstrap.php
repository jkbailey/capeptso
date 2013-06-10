<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initDoctype()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('XHTML1_STRICT');
	}
	
	protected function _initSession()
    {
        # set up the session as per the config.
        $options = $this->getOptions();
        $sessionOptions = array(
            'gc_probability'    =>    $options['resources']['session']['gc_probability'],
            'gc_divisor'        =>    $options['resources']['session']['gc_divisor'],
            'gc_maxlifetime'    =>    $options['resources']['session']['gc_maxlifetime']
        );
         
        $idleTimeout = $options['resources']['session']['idle_timeout'];
         
        Zend_Session::setOptions($sessionOptions);
        Zend_Session::start();
         
        # now check for idle timeout.
        if(isset($_SESSION['timeout_idle']) && $_SESSION['timeout_idle'] < time()) {
            Zend_Session::destroy();
            Zend_Session::regenerateId();
            header('Location: /auth');
            exit();
        }
     
        $_SESSION['timeout_idle'] = time() + $idleTimeout;
    }
	
	protected function _initUser()
	{
    	if ( Zend_Auth::getInstance()->hasIdentity() ) {
            $userInfo = Zend_Auth::getInstance()->getStorage()->read();
            $this->view->username = $userInfo->username;
    	}
	}

}

