<?php

class Admin_Form_Category extends Zend_Form
{
    
    /**
     * Initialize form (used by extending classes)
     * 
     * @return void
     */
    public function init()
    {
        $element = new Zend_Form_Element_Text('title');
        $element
            ->setLabel('Title')
            ->setRequired()
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('titlehead');
        $element
            ->setLabel('TitleHead')
            ->setRequired()
            ;
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Textarea('keywordshead');
        $element
            ->setLabel('Keywords')
            ->setRequired()
            ;
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Textarea('descriptionhead');
        $element
            ->setLabel('Description')
            ->setRequired()
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