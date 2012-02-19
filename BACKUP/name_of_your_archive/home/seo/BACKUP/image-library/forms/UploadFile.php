<?php

class ImageLibrary_Form_UploadFile extends Zend_Form
{

	/**
     * Initialize form (used by extending classes)
     *
     * @return void
     */
    public function init()
    {
		$this->setMethod(self::METHOD_POST);

		$validator = new Zend_Validate_File_Size(array(
            'max' => $file_max_size = Config::get('image-library', 'image_max_size'),
            'bytestring' => false));
        $validator->setMessage('Given file size more than ' . $file_max_size,
            Zend_Validate_File_Size::TOO_BIG);

		$element = new Zend_Form_Element_File('file');
		$element
			->setRequired()
			->addValidator('Count', false, 1)
            ->addValidator('MimeType', false, 
				Zend_Controller_Action_HelperBroker::getStaticHelper('config')
					->get('image_allowed_mime_types'))
            ->addValidator($validator)
			;
		$this->addElement($element);
    }

}