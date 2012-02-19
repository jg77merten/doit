<?php

class Admin_Form_Ads extends Zend_Form
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

    	$element = new Zend_Form_Element_Select('status');
        $element
            ->setLabel('Status')
            ->addMultiOptions(array(0=>'Inactive',1=>'Active'))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);
        
        
        $element = new Zend_Form_Element_Textarea('description');
        $element
            ->setLabel('Description')
            ->setRequired()
            ;
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('file');
        $element
            ->setLabel('File')
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