<?php

class Admin_Form_Company extends Zend_Form
{
    
    /**
     * Initialize form (used by extending classes)
     * 
     * @return void
     */
    public function init()
    {
    	
        $element = new Zend_Form_Element_Text('url');
        $element
            ->setLabel('url')
            ->setRequired()
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