<?php

class Admin_Form_Howto extends Zend_Form
{
    
    /**
     * Initialize form (used by extending classes)
     * 
     * @return void
     */
    public function init()
    {
       
		$max_size = '30MB';
        $validator = new Zend_Validate_File_Size(array(
            'max' => $max_size,
            'bytestring' => false));
        $validator->setMessage('Given file size more than ' . $max_size,
            Zend_Validate_File_Size::TOO_BIG);
		
        $element = new Zend_Form_Element_Text('video');
        $element
            ->setLabel('Video')
            ->setAttribs(array('readonly'=>''))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);
        
        
        $element = new Zend_Form_Element_Submit('submit');
        $element
            ->setLabel('Update')
            ->setIgnore(true)
            ;
        $this->addElement($element);
    }
    
}