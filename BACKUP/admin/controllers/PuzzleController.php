<?php

class Admin_PuzzleController extends FinalView_Controller_Action
{ 
    private $_puzzleForm;
	
    public function indexAction() 
    {
        $pack_id = $this->_getParam('id');
    	if ($this->getRequest()->isPost()) {
            switch (true) {
                case $this->getRequest()->has('delete'):
                    $this->delete();
                break;
                case $this->getRequest()->has('newpuzzle'):
                     $this->_helper->redirector->gotoRoute(array(),'AdminPuzzleNewpuzzle');
                break;
            }
            
            $this->_helper->redirector->gotoUrl($this->getRequest()->getRequestUri());
        }
        $pack = 
        
        $this->view->grid = new Admin_Grid_Puzzles($pack_id);
        $this->view->pack =  Doctrine::getTable('Puzzlepack')->findOneByParams(array(
            'id'   =>  $pack_id
        ));
    
    }
    
    public function editpuzzleAction()
	{
			
		$id = $this->_getParam('id');
        $puzzle =  Doctrine::getTable('Puzzle')->findOneByParams(array(
            'id'   =>  $id
        ));
        $this->view->puzzle = $puzzle;
        if (!$this->getRequest()->isPost()) {
            $this->getpuzzleForm($puzzle)->populate(
                $puzzle->toArray()
            );
        }
        
        if ($puzzle = $this->savepuzzle($puzzle)) {

                $packdir = 'packs/'.$puzzle->pack_id;
                $pack_id = $puzzle->pack_id;
            	if(!is_dir($packdir)) {mkdir($packdir,0777);}
            	$puzzledir = $packdir.'/'.$puzzle->id;
            	if(!is_dir($puzzledir)) {mkdir($puzzledir,0777);}
				
            	//$uploadedData = $this->getpuzzleForm()->getValues();
				//=============MAIN IMAGE       
				$fil = $this->_getParam('file');     	
				$fullImagePath = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$fil;
				
				if (!empty($fil)) {
					
				$newImagename = 'puzzle'.$puzzle->id.'_960.jpg';
				$newImagename480 = 'puzzle'.$puzzle->id.'_480.jpg';
				$newImagename246b = 'puzzle'.$puzzle->id.'_246b.jpg';
				$newImagename123b = 'puzzle'.$puzzle->id.'_123b.jpg';
				$newImagedest = $puzzledir.'/'.$newImagename;
				$newImagedest480 = $puzzledir.'/'.$newImagename480;
				$newImagedest246b = $puzzledir.'/'.$newImagename246b;
				$newImagedest123b = $puzzledir.'/'.$newImagename123b;
				rename($fullImagePath,$newImagedest);
				copy($newImagedest,$newImagedest480);
				copy($newImagedest,$newImagedest246b);
				copy($newImagedest,$newImagedest123b);
			
				system ("mogrify -resize 960x $newImagedest");
				system ("mogrify -resize 480x $newImagedest480");
				system ("mogrify -resize x246 $newImagedest246b");
				system ("mogrify -resize x123 $newImagedest123b");
				$puzzle->image960 = $newImagename;
				

				//=============Thumb
				$fullThumbPath = $_SERVER['DOCUMENT_ROOT'].'/uploads/thumb_'.$this->_getParam('file');
				
				$newThumbname = 'puzzle'.$puzzle->id.'_246.jpg';
				$newThumbname123 = 'puzzle'.$puzzle->id.'_123.jpg';
				$newThumbdest = $puzzledir.'/'.$newThumbname;
				$newThumbdest123 = $puzzledir.'/'.$newThumbname123;
				
				
				rename($fullThumbPath,$newThumbdest);
				copy($newThumbdest,$newThumbdest123);
			
				system ("mogrify -resize 246x $newThumbdest");
				system ("mogrify -resize 123x $newThumbdest123");
				$puzzle->image246 = $newThumbname;

				//=============videos

				}
				$start_video = $this->_getParam('vid');
            	$end_video = $this->_getParam('vid2');
				
           	
            	if (!empty($start_video)) {
            	$fullVidPath = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$start_video;
            	$ext =  end(explode(".", $start_video));            	
				$newVidname = 'puzzle'.$puzzle->id.'_1.'.$ext;
				$newsmallVidname = 'puzzle'.$puzzle->id.'s_1.mp4';
				$newViddest = $puzzledir.'/'.$newVidname;
				$smallnewViddest = $puzzledir.'/'.$newsmallVidname;
				system("mv -f $fullVidPath $newViddest");
				$output = shell_exec("ffmpeg -y -i {$_SERVER['DOCUMENT_ROOT']}/$newViddest -vcodec libx264 -vpre hq -vpre ipod640 -b 500k -bt 50k -acodec libfaac -ab 56k -ac 1 -s 720x416 {$_SERVER['DOCUMENT_ROOT']}/$smallnewViddest");
				$puzzle->Video1 = $newVidname;
            	}
            	
                if (!empty($end_video)) {
            	$fullVid2Path = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$end_video;
            	$ext =  end(explode(".", $end_video));
				$newVid2name = 'puzzle'.$puzzle->id.'_2.'.$ext;
				$newsmallVid2name = 'puzzle'.$puzzle->id.'s_2.mp4';
				$newVid2dest = $puzzledir.'/'.$newVid2name;
				$smallnewVid2dest = $puzzledir.'/'.$newsmallVid2name;
				system("mv -f $fullVid2Path $newVid2dest");
				$output = shell_exec("ffmpeg -y -i {$_SERVER['DOCUMENT_ROOT']}/$newVid2dest -vcodec libx264 -vpre hq -vpre ipod640 -b 500k -bt 50k -acodec libfaac -ab 56k -ac 1 -s 720x416 {$_SERVER['DOCUMENT_ROOT']}/$smallnewVid2dest");
				$puzzle->Video2 = $newVid2name;
            	}

			
				
        	//print_r($puzzle->toArray());
        	//exit();
        	//unset($puzzle->pack_id);

        	$puzzle->save();
            $this->_helper->redirector->gotoRoute(array('id' =>$puzzle->pack_id),'AdminPuzzleIndex');
        }

	}
	
	
    public function newpuzzleAction()
	{
        
	        if ($this->getRequest()->isPost()) {
	        	
	        //	echo $this->_getParam('pack');
	        //	print_r($this->getRequest()->getPost());
	        	//exit();
	        	
            if ($this->getpuzzleForm($this->_getParam('pack'))->isValid($this->getRequest()->getPost())) {
				//=============videos
            	$puzzle = $this->savepuzzle();
            	$puzzle->save();
                $packdir = 'packs/'.$puzzle->pack_id;
            	if(!is_dir($packdir)) {mkdir($packdir,0777);}
            	$puzzledir = $packdir.'/'.$puzzle->id;
            	if(!is_dir($puzzledir)) {mkdir($puzzledir,0777);}         	
            	$start_video = $this->_getParam('vid');
            	$end_video = $this->_getParam('vid2');
            	if (!empty($start_video)) {
            	$fullVidPath = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$start_video;
				$fullVid2Path = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$end_video;
            	$ext1 =  end(explode(".", $start_video));            	
            	$ext2 =  end(explode(".", $end_video));
				$newVidname = 'puzzle'.$puzzle->id.'_1.'.$ext1;
				$newsmallVidname = 'puzzle'.$puzzle->id.'s_1.mp4';
				$newViddest = $puzzledir.'/'.$newVidname;
				$smallnewViddest = $puzzledir.'/'.$newsmallVidname;
				$newVid2name = 'puzzle'.$puzzle->id.'_2.'.$ext2;
				$newsmallVid2name = 'puzzle'.$puzzle->id.'s_2.mp4';
				$newVid2dest = $puzzledir.'/'.$newVid2name;
				$smallnewVid2dest = $puzzledir.'/'.$newsmallVid2name;
				rename($fullVidPath,$newViddest);
				rename($fullVid2Path,$newVid2dest);
				$output = shell_exec("ffmpeg -y -i {$_SERVER['DOCUMENT_ROOT']}/$newViddest -vcodec libx264 -vpre hq -vpre ipod640 -b 500k -bt 50k -acodec libfaac -ab 56k -ac 1 -s 720x416 {$_SERVER['DOCUMENT_ROOT']}/$smallnewViddest");
				$output = shell_exec("ffmpeg -y -i {$_SERVER['DOCUMENT_ROOT']}/$newVid2dest -vcodec libx264 -vpre hq -vpre ipod640 -b 500k -bt 50k -acodec libfaac -ab 56k -ac 1 -s 720x416 {$_SERVER['DOCUMENT_ROOT']}/$smallnewVid2dest");
				$puzzle['Video1'] = $newVidname;
				$puzzle['Video2'] = $newVid2name;
				
            	}
            	//$uploadedData = $this->getpuzzleForm()->getValues();
				//=============MAIN IMAGE        
				$fil = $this->_getParam('file');    	
				$fullImagePath = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$this->_getParam('file');
				//$Imageinfo = $this->getpuzzleForm()->file->getFileinfo();
				
				
				$newImagename = 'puzzle'.$puzzle->id.'_960.jpg';
				$newImagename480 = 'puzzle'.$puzzle->id.'_480.jpg';
				$newImagename246b = 'puzzle'.$puzzle->id.'_246b.jpg';
				$newImagename123b = 'puzzle'.$puzzle->id.'_123b.jpg';
				$newImagedest = $puzzledir.'/'.$newImagename;
				$newImagedest480 = $puzzledir.'/'.$newImagename480;
				$newImagedest246b = $puzzledir.'/'.$newImagename246b;
				$newImagedest123b = $puzzledir.'/'.$newImagename123b;
				rename($fullImagePath,$newImagedest);
				copy($newImagedest,$newImagedest480);
				copy($newImagedest,$newImagedest246b);
				copy($newImagedest,$newImagedest123b);
			
				system ("mogrify -resize 960x $newImagedest");
				system ("mogrify -resize 480x $newImagedest480");
				system ("mogrify -resize x246 $newImagedest246b");
				system ("mogrify -resize x123 $newImagedest123b");
				$puzzle['image960'] = $newImagename;
            	//=============Thumb
				$fullThumbPath = $_SERVER['DOCUMENT_ROOT'].'/uploads/thumb_'.$this->_getParam('file');
				
				$newThumbname = 'puzzle'.$puzzle->id.'_246.jpg';
				$newThumbname123 = 'puzzle'.$puzzle->id.'_123.jpg';
				$newThumbdest = $puzzledir.'/'.$newThumbname;
				$newThumbdest123 = $puzzledir.'/'.$newThumbname123;
				rename($fullThumbPath,$newThumbdest);
				copy($newThumbdest,$newThumbdest123);
				system ("mogrify -resize 246x $newThumbdest");
				system ("mogrify -resize 123x $newThumbdest123");
				$puzzle['image246'] = $newThumbname;				
				
            	$puzzle->save();
            	$this->_helper->redirector->gotoRoute(array('id'=>$puzzle->id),'AdminPuzzleConfirmpuzzle');
            } else {
            	echo "1errors<BR>";
            	print_r($this->_puzzleForm->getErrors('asdad'));
            	echo "2errors<BR>";
            	print_r($this->_puzzleForm->getMessages());
            	echo "3errors<BR>";
            	print_r($this->_puzzleForm->getErrorMessages());
            	echo "4errors<BR>";
            	print_r($this->_puzzleForm->getValidValues($this->getRequest()->getPost()));
            	
            	exit();
            }
        }
       // echo $this->_getParam('pack_id');exit();	
        $puzzle = $this->getpuzzleForm($this->_getParam('pack'));
     	$this->view->form = $puzzle;    
	}
	
    public function confirmpuzzleAction()
	{
			
		$id = $this->_getParam('id');
        $puz =  Doctrine::getTable('Puzzle')->findOneByParams(array(
            'id'   =>  $id
        ));
       // $this->getpuzzleForm();
        $this->view->puzzle = $puz;
	}
	
    public function delpuzzleAction()
	{
			
		$id = $this->_getParam('id');	
        $puz =  Doctrine::getTable('Puzzle')->findOneByParams(array(
            'id'   =>  $id
        ));
        if ((!empty($puz->pack_id)) and  (!empty($puz->id))) {
        	system ("rm -f -r /var/www/www/packs/$puz->pack_id/$puz->id");
        	$puz->delete();	
        }
        $this->_helper->redirector->gotoRoute(array('id' =>$puz->pack_id),'AdminPuzzleIndex');
        //system ()
        
	}	
    private function getpuzzleForm($pack=null)
    {
        if ($this->_puzzleForm === null) {
	       	
    	    $this->_puzzleForm = new Admin_Form_Puzzle(array('pack'=>$pack));
        }
        return $this->_puzzleForm;
    }
    
    private function savepuzzle($cat = null)
    {
        if ($this->getRequest()->isPost()) {
        		if ($this->getpuzzleForm()->isValid($this->getRequest()->getPost())) {
                	if (is_null($cat) ) {
                		$cat = Doctrine::getTable('Puzzle')->create();
                		$cat->pack_id = $this->_getParam('pack');
                	}
                $cat->merge($this->getpuzzleForm()->getValues());
                //$cat->pack_id = $this->_getParam('pack');
                return $cat;
            }
        }
     $this->view->form = $this->getpuzzleForm();
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
		$record =  Doctrine::getTable('Puzzle')->findOneByParams(array(
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
        $this->_helper->redirector->gotoRoute(array('id'=>$record->pack_id), 'AdminPuzzleIndex');
    }
}



