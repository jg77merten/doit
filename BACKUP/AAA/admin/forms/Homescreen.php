<?php

class Admin_Form_Homescreen extends Zend_Form
{
    
    /**
     * Initialize form (used by extending classes)
     * 
     * @return void
     */
    public function init()
    {
    	
        $element = new Zend_Form_Element_Text('file');
        $element
            ->setLabel('Home 1024x768')
            ->setAttribs(array('readonly'=>''))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);  

        $element = new Zend_Form_Element_Text('file2');
        $element
            ->setLabel('Home 960x640')
            ->setAttribs(array('readonly'=>''))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);
            
        
        $element = new Zend_Form_Element_Submit('submit');
        $element
            ->setLabel('Submit')
            ->setIgnore(true)
            ;
        $this->addElement($element);
    }
    
}