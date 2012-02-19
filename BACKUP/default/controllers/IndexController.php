<?php

class IndexController extends FinalView_Controller_Action
{
    
    public function indexAction()
    {
		$this->_helper->redirector->gotoRoute(array(),'AdminAuthLogin');
    }    
}

