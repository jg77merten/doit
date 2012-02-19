<?php

class ImageLibrary_FileController extends Zend_Controller_Action
{

	private $_sitegift_id;

	public function init()
	{
		Zend_Layout::getMvcInstance()->disableLayout();

		$this->_sitegift_id = $this->getRequest()->getParam('sitegift_id');
	}

	public function getAction()
	{
		if ($this->_hasParam('occurance')) {
			$form = new ImageLibrary_Form_Search;
			$form->isValid($this->getRequest()->getQuery());

			if ($folder = $form->getValue('folder')) {
				$directory
					= ImageLibrary_Utils::getDir($this->_sitegift_id)
					. pcgbasename($this->getRequest()->getParam('folder'))
					;
				$files = $this->_helper->directory->getFilesOnly($directory);
			} else {
				$directory = ImageLibrary_Utils::getDir($this->_sitegift_id);
				$files = $this->_helper->directory->getFilesOnly($directory, true);
			}

			$this->view->files = $this->_search($form, $files);
		} else {
            $filter = new ImageLibrary_Filter_EmptyFolder;
            $folder = $filter->filter(pcgbasename($this->getRequest()->getParam('folder')));
            
			$directory
				= ImageLibrary_Utils::getDir($this->_sitegift_id)
				. $folder
				;
			
			$this->view->files = $this->_helper->directory($directory);
		}

        $this->view->cache = $this->getRequest()->getParam('cache');
	}

	private function _search(ImageLibrary_Form_Search $form, array $files)
	{
		$filter = new ImageLibrary_Filter_Search($form->getElement('occurance')->getAttrib('placeholder'));
		
		if (!($needle = $filter->filter($form->getValue('occurance')))) {
			return $files;
		}


//		$files = array_filter($files, function ($file) use ($needle)
//		{
//			return (bool)stristr(pcgbasename($file), $needle);
//		});
//
//		return $files;

		// FFFFFFFFFUUUUUUUUUUUUUUUUU.......
		
		$_files = array();

		foreach ($files as $file) {
			if (false !== stristr(pcgbasename($file), $needle)) {
				array_push($_files, $file);
			}
		}

		return $_files;
	}

	public function deleteAction()
	{
		$file
			= ImageLibrary_Utils::getDir($this->_sitegift_id, true)
			. pcgbasename(urldecode($this->getRequest()->getPost('folder')))
			. DIRECTORY_SEPARATOR
			. pcgbasename($this->getRequest()->getPost('file'))
			;

        runlink($file);

		$this->_helper->json(array(
			'result' => true, 
		));
	}

	public function renameAction()
	{
		$oldname
			= ImageLibrary_Utils::getDir($this->_sitegift_id, true)
			. pcgbasename(urldecode($this->getRequest()->getPost('folder')))
			. DIRECTORY_SEPARATOR
			. pcgbasename($this->getRequest()->getPost('filename_old'))
			;
		$newname
			= ImageLibrary_Utils::getDir($this->_sitegift_id, true)
			. pcgbasename(urldecode($this->getRequest()->getPost('folder')))
			. DIRECTORY_SEPARATOR
			. pcgbasename($this->getRequest()->getPost('filename_new'))
			;

		$this->_helper->json(array(
			'result' => rename($oldname, $newname),
		));
	}

	public function uploadAction()
	{
		$errors = array();
		$form = new ImageLibrary_Form_UploadFile;

		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$filename = $this->_uploadFile($form->file);
                $result = (bool)$filename;
			} else {
				$result = false;
				$errors = $form->file->getMessages();
			}
		}

		$this->view->assign(array(
			'result'    => $result,
			'errors'    => $errors,
            'file'      => ImageLibrary_Utils::getDir($this->_sitegift_id, false) . $filename,
		));
	}

	private function _uploadFile(Zend_Form_Element_File $file)
	{
		$directory
			= ImageLibrary_Utils::getDir($this->_sitegift_id, true)
			. pcgbasename(urldecode($this->getRequest()->getParam('folder')))
			. DIRECTORY_SEPARATOR
			;
        $filename = Utils::incrementFilename($directory, $file->getValue());
        //$filename = $this->_helper->file->incrementName($directory, $file->getvalue());
        
		return copy($file->getFileName(), $directory . $filename) ? $filename : false;
	}

    private function _generateFileName($directory, $filename)
    {
        if (!file_exists($directory . $filename)) {
            return $filename;
        }

        
    }

}