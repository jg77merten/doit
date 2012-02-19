<?php

class Admin_Form_Pack extends Zend_Form
{
    
    /**
     * Initialize form (used by extending classes)
     * 
     * @return void
     */
    public function init()
    {
    	
    	$cat = Doctrine::getTable('Category')->findByParams(array(
            'status' => 1
        ));
        foreach ($cat as $key=>$value) {
        	$category_list[$value->id] = $value->title; 
        } 
        unset($cat);
    	
        $element = new Zend_Form_Element_Text('name');
        $element
            ->setLabel('Name')
            ->setRequired()
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);

        
        $element = new Zend_Form_Element_Select('category_id');
        $element
            ->setLabel('Category')
            ->setRequired()
            ->addMultiOptions(array($category_list))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('price');
        $element
            ->setLabel('Price')
            ->setRequired()
            ->addFilters(array('StringTrim'))
            //->StringLength(array('max' => 1,'max' => 12))
           // ->addValidator('digits', false, array())
            ;
        $this->addElement($element);

    	$element = new Zend_Form_Element_Select('status');
        $element
            ->setLabel('Status')
            ->addMultiOptions(array(0=>'Inactive',1=>'Active'))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('purchase_id');
        $element
            ->setLabel('Purchase id')
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('file');
        $element
            ->setLabel('File')
            ->setAttribs(array('readonly'=>''))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);    
            
//        $element = new Zend_Form_Element_File('file');
//        $element
//            ->setDestination( PUBLIC_PATH . DIRECTORY_SEPARATOR . '/uploads');
//            //->setRequired(true);
//            //->addValidator('IsImage');
//		$this->addElement($element);
        
        $element = new Zend_Form_Element_Textarea('description');
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