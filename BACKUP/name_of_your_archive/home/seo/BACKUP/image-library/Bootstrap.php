<?php

class ImageLibrary_Bootstrap extends FinalView_Application_Module_Bootstrap
{

	/**
    * load helpers
    *
    */
    public function init()
    {
		require_once APPLICATION_PATH . '/modules/image-library/Utils.php';

        Zend_Controller_Action_HelperBroker::addPath(
            APPLICATION_PATH . '/modules/image-library/controllers/helpers',
            'ImageLibrary_Controller_Helper'
        );

		$this->getApplication()->getResource('view')->addHelperPath(
			APPLICATION_PATH . '/modules/image-library/views/helpers',
			'ImageLibrary_View_Helper');
    }

	protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'ImageLibrary',
            'basePath'  => APPLICATION_PATH . '/modules/image-library',
        ));
        $autoloader->addResourceTypes(array(
            'exceptions' => array
                (
                    'path' => 'exceptions',
                    'namespace' => 'Exception',
                ),
        ));

        return $autoloader;
    }
    
}
