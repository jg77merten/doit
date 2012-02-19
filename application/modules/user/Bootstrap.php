<?php

class User_Bootstrap extends FinalView_Application_Module_Bootstrap
{

    /**
     * load helpers 
     * 
     */
    public function init()
    {
        Zend_Controller_Action_HelperBroker::addPath(
            APPLICATION_PATH . '/modules/user/controllers/helpers', 
            'User_Controller_Helper'
        );
    }

    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => 'User',
            'basePath'  => APPLICATION_PATH . '/modules/user',
                ));
        $autoloader->addResourceTypes(array(
            'auth' => array
                (
                'path' => 'auth',
                'namespace' => 'Auth',
            )
        ));
    }

    protected function _initRememberMePlugin()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new User_Plugin_RememberMe());
    }

}
