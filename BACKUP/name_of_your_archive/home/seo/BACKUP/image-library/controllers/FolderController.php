<?php

class ImageLibrary_FolderController extends Zend_Controller_Action
{

	private $_sitegift_id;

	public function init()
	{
		Zend_Layout::getMvcInstance()->disableLayout();

		$this->_sitegift_id = $this->getRequest()->getParam('sitegift_id');
	}

	public function addAction()
	{
		$pathname
			= ImageLibrary_Utils::getDir($this->_sitegift_id, true)
			. DIRECTORY_SEPARATOR
			. $this->getRequest()->getParam('folder');
		
		$this->_helper->json(array('result' => mkdir($pathname, 0777)));
	}

	public function getAction()
	{
		$directory = ImageLibrary_Utils::getDir($this->_sitegift_id, true);

		$this->view->assign(array(
			'folders' => $this->_helper->directory->getDirs($directory,
				$this->getRequest()->getQuery('current_folder')),
		));
	}

}