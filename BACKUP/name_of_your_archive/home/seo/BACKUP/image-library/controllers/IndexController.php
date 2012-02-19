<?php

class ImageLibrary_IndexController extends Zend_Controller_Action
{

	private $_sitegift_id;

    private $_is_upload_enabled;

	public function init()
	{
		Zend_Layout::getMvcInstance()->disableLayout();

		$this->view->sitegift_id = $this->_sitegift_id =
			$this->getRequest()->getParam('sitegift_id');

        $site = Doctrine::getTable('Sitegift')->find($this->_sitegift_id);
        $this->view->is_upload_enabled = $this->_is_upload_enabled = $site->isUploadEnabled();
	}

	public function indexAction()
	{
		// always perfom this check cause user directory may not exists
		ImageLibrary_Utils::getDir($this->_sitegift_id, true);
		
		$directory = ImageLibrary_Utils::getDir($this->_sitegift_id);
		
		$this->view->assign(array(
			'directory' => $directory,
			'files' => $this->_helper->directory($directory),
			'folders' => $this->_helper->directory->getDirs($directory),

			'cache' => $this->getRequest()->getParam('cache'), 

			'form' => array(
				'upload' => new ImageLibrary_Form_UploadFile,
				'search' => new ImageLibrary_Form_Search,
			),
		));
	}

    public function prePopupAction()
    {
        $files = $this->_helper->directory->getFiles(
            ImageLibrary_Utils::getDir($this->_sitegift_id, true));
    
        $this->view->assign(array(
            'form' => new ImageLibrary_Form_UploadFile,
            'is_image_lib_empty' => !(count($files) > 0),
        ));
    }

    public function reCalculateOccupiedSpaceAction()
	{
		$directory = ImageLibrary_Utils::getDir($this->_sitegift_id);

		$this->view->space = $this->_helper->space($directory);
	}

}