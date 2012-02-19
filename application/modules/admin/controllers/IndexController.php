<?php

class Admin_IndexController extends FinalView_Controller_Action
{ 
    private $_categoryForm;
	
    public function indexAction() 
    {
        if ($this->getRequest()->isPost()) {
            
        	
        	
            switch (true) {
                case $this->getRequest()->has('delete'):
                    $this->delete();
                break;
                case $this->getRequest()->has('newcategory'):
                     $this->_helper->redirector->gotoRoute(array(),'AdminIndexNewcategory');
                break;
            }
            
            $this->_helper->redirector->gotoUrl($this->getRequest()->getRequestUri());
        }
        
        $this->view->grid = new Admin_Grid_Categories();
    
    }
    
    public function delcatAction()
	{
		$id = $this->_getParam('id');			
		if (!empty($id)) {
        $category =  Doctrine::getTable('Category')->findOneByParams(array(
            'id'   =>  $this->_getParam('id')
        ));
        $category->delete();
		}
         $this->_helper->redirector->gotoRoute(array(),'AdminIndexIndex');
        //system ()
        
	}
    
   public function editcategoryAction()
	{
	$c_id = $this->_getParam('id');
        $cat =  Doctrine::getTable('Category')->findOneByParams(array(
            'id'   =>  $c_id
        ));
       // $this->_getcategoryForm();
        if (!$this->getRequest()->isPost()) {
            $this->_getcategoryForm($cat)->populate(
                $cat->toArray()
            );
        }
        if ($cat = $this->_savecategory($cat)) {
        	$cat->save();
            $this->_helper->redirector->gotoRoute(array(),'AdminIndexIndex');
        }

	}
	
	//===============FORMS
    public function newcategoryAction()
	{
        $cat = $this->_getcategoryForm();
        if ($this->getRequest()->isPost()) {
        	if ($cat = $this->_savecategory()) {
	           	$cat->save();
    	        $this->_helper->redirector->gotoRoute(array(),'AdminIndexIndex');
        	}
         }

     	$this->view->form = $cat;    
	}
	
    private function _getcategoryForm()
    {
        if ($this->_categoryForm === null) {
        $this->_categoryForm = new Admin_Form_Category();
        }
        return $this->_categoryForm;
    }
    
    private function _savecategory($cat = null)
    {
        if ($this->getRequest()->isPost()) {
        		if ($this->_getcategoryForm()->isValid($this->getRequest()->getPost())) {
                	if (is_null($cat) ) {
                		$cat = Doctrine::getTable('Category')->create();
                	}
                $cat->merge($this->_getcategoryForm()->getValues());
                return $cat;
            }
        }
     $this->view->form = $this->_getcategoryForm();
    }

   public function upAction()
    {
        $this->_move('up');
    }

    public function downAction()
    {
        $this->_move('down');
    }

    private function _move($direction)
    {
		$record =  Doctrine::getTable('Category')->findOneByParams(array(
            'id'   =>  $this->_getParam('id')
        ));
        

        if (!$record) {$this->_helper->error->notFound();}
        
        switch ($direction) {
            case 'up' :
                $record->moveUp();
                break;
            case 'down' :
                $record->moveDown();
                break;
            default : trigger_error('Unknown direction', E_USER_ERROR);
        }
//        print_r($record->toArray());
//        exit();
        $this->_helper->redirector->gotoRoute(array(), 'AdminIndexIndex');
    }
	
}



