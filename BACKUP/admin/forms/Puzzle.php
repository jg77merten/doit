<?php

class Admin_Form_Puzzle extends Zend_Form
{
    
    /**
     * Initialize form (used by extending classes)
     * 
     * @return void
     */
	public $pack_id;
	
	public function setPack($val){
		$this->pack_id = $val;
	}
	
    public function init()
    {
    	$p = Doctrine::getTable('Puzzlepack')->findByParams(array(
        ));
        

        
        foreach ($p as $key=>$value) {
        	$p_list[$value->id] = $value->id.' '.$value->name; 
        } 
        unset($p);

    	$element = new Zend_Form_Element_Select('pack');
        $element
            ->setLabel('Puzzle Pack')
            ->addMultiOptions(array($p_list))
            ->addFilters(array('StringTrim'))
            ->setValue($this->pack_id)
            ;
        $this->addElement($element);
    	
    	$element = new Zend_Form_Element_Select('status');
        $element
            ->setLabel('Status')
            ->addMultiOptions(array(1=>'Active',0=>'Inactive'))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);
        
        
    	$element = new Zend_Form_Element_Text('name');
        $element
            ->setLabel('Name')
          //  ->setRequired()
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);

//		$max_size = '3MB';
//        $validator = new Zend_Validate_File_Size(array(
//            'max' => $max_size,
//            'bytestring' => false));
//        $validator->setMessage('Given file size more than ' . $max_size,
//            Zend_Validate_File_Size::TOO_BIG);
        
        $element = new Zend_Form_Element_Text('file');
        $element
            ->setLabel('File')
            ->setAttribs(array('readonly'=>''))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);
		
		
		$max_size = '30MB';
        $validator = new Zend_Validate_File_Size(array(
            'max' => $max_size,
            'bytestring' => false));
        $validator->setMessage('Given file size more than ' . $max_size,
            Zend_Validate_File_Size::TOO_BIG);
		
        $element = new Zend_Form_Element_Text('vid');
        $element
            ->setLabel('Start video')
            ->setAttribs(array('readonly'=>''))
            ->addFilters(array('StringTrim'))
            ;
        $this->addElement($element);
            // /var/www/www/uploads
           
        $element = new Zend_Form_Element_Text('vid2');
        $element
            ->setLabel('End video')
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