<?php

class ImageLibrary_Form_Search extends Zend_Form
{

	/**
     * Initialize form (used by extending classes)
     *
     * @return void
     */
    public function init()
    {
		$this->setMethod(self::METHOD_GET);


		$element = new Zend_Form_Element_Text('occurance');
		$this->addElement($element);
        

		$element = new Zend_Form_Element_Select('folder');
		$this->addElement($element->setMultiOptions($this->_getFoldersMultiOptions()));
    }

	private function _getFoldersMultiOptions()
	{
		// load helper first :)
		Zend_Controller_Action_HelperBroker::getStaticHelper('directory');
		
		return
			array(
				''														=> 'All Files',
				ImageLibrary_Controller_Helper_Directory::ROOT_DIR_URL	=> 'This Folder'
			);
	}

}