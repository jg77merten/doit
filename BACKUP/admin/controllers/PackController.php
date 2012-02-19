<?php

class Admin_PackController extends FinalView_Controller_Action
{ 
    private $_packForm;
	
    public function indexAction() 
    {
    	$category_id = $this->_getParam('category_id');
		//if (!empty($category_id)) {$category_id = $this->_getParam('category_id');} else {$category_id = null;}
    	if ($this->getRequest()->isPost()) {
            
        	
        	
            switch (true) {
                case $this->getRequest()->has('delete'):
                    $this->delete();
                break;
                case $this->getRequest()->has('newpack'):
                     $this->_helper->redirector->gotoRoute(array(),'AdminPackNewpack');
                break;
            }
            
            $this->_helper->redirector->gotoUrl($this->getRequest()->getRequestUri());
        }
        
        $this->view->grid = new Admin_Grid_Packs($category_id);
    
    }
    
    public function editpackAction()
	{
		$id = $this->_getParam('id');
        $pack =  Doctrine::getTable('Puzzlepack')->findOneByParams(array(
            'id'   =>  $id
        ));
        $this->view->pack = $pack;
        if (!$this->getRequest()->isPost()) {
            $this->getpackForm($pack)->populate(
                $pack->toArray()
            );
        }
        if ($pack = $this->savepack($pack)) {
        		$uploadedData = $this->getpackForm()->getValues();	
        		//$fullFilePath = $this->getpackForm()->file->getFileName();
				//$fileinfo = $this->getpackForm()->file->getFileinfo();
				$fil = $this->_getParam('file');
        		if (!empty($fil)) {
        	    $packdir = 'packs/'.$pack->id;
            	if(!is_dir($packdir)) {mkdir($packdir);}
            	$fullFilePath = $_SERVER['DOCUMENT_ROOT'].$this->_getParam('file');
            	$newfilename = 'pack'.$pack->id.'_246.jpg';
				$newfilename123 = 'pack'.$pack->id.'_123.jpg';
				//$newfilename = 'pack'.$pack->id.'_246.'.pathinfo($fileinfo['file']['name'],PATHINFO_EXTENSION);
				//$newfilename123 = 'pack'.$pack->id.'_123.'.pathinfo($fileinfo['file']['name'],PATHINFO_EXTENSION);
				$newdest = $packdir.'/'.$newfilename;
				$newdest123 = $packdir.'/'.$newfilename123;
				
				rename($fullFilePath,$newdest);
				copy($newdest,$newdest123);
				system ("mogrify -resize 246x246 $newdest");
				system ("mogrify -resize 123x123 $newdest123");
				$pack->image246 = $newfilename;
            	$pack->image123 = $newfilename123;
        		}
        	$pack->save();
            $this->_helper->redirector->gotoRoute(array(),'AdminPackEdit');
        }
		
	}
	
    public function generateAction()
	{
		$id = $this->_getParam('id');
        $pack =  Doctrine::getTable('Puzzlepack')->findOneByParams(array(
            'id'   =>  $id
        ));
        ini_set('max_execution_time', 300);
//echo "cd ".PUBLIC_PATH .'/packs/'.$id."; zip -r -0 ".PUBLIC_PATH.'/packs/'.$id." *";
//exit();
		//system("cd ".PUBLIC_PATH .'/packs/'.$id."; zip -r -0 ".PUBLIC_PATH.'/packs/'.$id." *");
//		system("cd ".PUBLIC_PATH .'/packs/'.$id."; zip -r -0 ".PUBLIC_PATH.'/packs/'.$id." * > /dev/null");
		$this->_helper->redirector->gotoRoute(array('id' =>$id),'AdminPuzzleIndex');
	}
	
    public function givepackAction()
	{
		$id = $this->_getParam('id');
        $pack =  Doctrine::getTable('Puzzlepack')->findOneByParams(array(
            'id'   =>  $id
        ));
	  ini_set('memory_limit','200M');		
	  $fileName     = $id.".zip";
      $fileFullName = PUBLIC_PATH .'/packs/'.$id.".zip";
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', 'application/octet-stream', true)
            ->setHeader('Content-Length', filesize($fileFullName))
            ->setHeader('Content-Disposition', 'attachment; filename='.$fileName)
            ->clearBody();
        $this->getResponse()
            ->sendHeaders();

	  readfile($fileFullName);
      
		$this->_helper->layout()->disableLayout();
	}
	
    public function newpackAction()
	{
        
	        if ($this->getRequest()->isPost()) {
            if ($this->getpackForm()->isValid($this->getRequest()->getPost())) {
            	$pack = $this->savepack();
            	$pack->save();
            	$packdir = 'packs/'.$pack->id;
            	if(!is_dir($packdir)) {mkdir($packdir,0777);}
				//$uploadedData = $this->getpackForm()->getValues();
				//$fullFilePath = $this->getpackForm()->file->getFileName();
				$fullFilePath = $_SERVER['DOCUMENT_ROOT'].$this->_getParam('file');
				$newfilename = 'pack'.$pack->id.'_246.jpg';
				$newfilename123 = 'pack'.$pack->id.'_123.jpg';
				$newdest = $packdir.'/'.$newfilename;
				$newdest123 = $packdir.'/'.$newfilename123;
				
				rename($fullFilePath,$newdest);
				copy($newdest,$newdest123);
				system ("mogrify -resize 246x246 $newdest");
				system ("mogrify -resize 123x123 $newdest123");
				$pack->image246 = $newfilename;
            	$pack->image123 = $newfilename123;
            	$pack->save();
            	$this->_helper->redirector->gotoRoute(array('id'=>$pack->id),'AdminPackEdit');
            }
        }
        $pack = $this->getpackForm();
     	$this->view->form = $pack;    
	}
	
    public function delpackAction()
	{
			
		$id = $this->_getParam('id');
		$puzlies =  Doctrine::getTable('Puzzle')->findByParams(array(
            'pack_id'   =>  $id
        ));
        $pack =  Doctrine::getTable('Puzzlepack')->findOneByParams(array(
            'id'   =>  $id
        ));
        if (!empty($pack->id)) {
        	$puzlies->delete();
        	system ("rm -f -r /var/www/www/packs/$pack->id");
        	//echo "rm -f -r /var/www/www/packs/$pack->id";
        	$pack->delete();
        	//exit();	
        }
         $this->_helper->redirector->gotoRoute(array(),'AdminPackIndex');
        //system ()
        
	}
	
    private function getpackForm()
    {
        if ($this->_packForm === null) {
        $this->_packForm = new Admin_Form_Pack();
        }
        return $this->_packForm;
    }
    
    private function savepack($cat = null)
    {
        if ($this->getRequest()->isPost()) {
        		if ($this->getpackForm()->isValid($this->getRequest()->getPost())) {
                	if (is_null($cat) ) {
                		$cat = Doctrine::getTable('Puzzlepack')->create();
                	}
                $cat->merge($this->getpackForm()->getValues());
                return $cat;
            }
        }
     $this->view->form = $this->getpackForm();
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
		$record =  Doctrine::getTable('Puzzlepack')->findOneByParams(array(
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
        $this->_helper->redirector->gotoRoute(array(), 'AdminPackIndex');
    } 
    
	
}



