<?php

class Admin_IndexController extends FinalView_Controller_Action
{ 
    private $_categoryForm;
    private $_adsForm;
    private $_howtoForm;
	private $_homeForm;
	private $_iconForm;
    private $_splashForm;
    private $_companyForm;
    private $secret_key = 'DfoUig5rduZbgm';
	
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
    
    public function adsAction() 
    {
        if ($this->getRequest()->isPost()) {
            switch (true) {
                case $this->getRequest()->has('newads'):
                     $this->_helper->redirector->gotoRoute(array(),'AdminIndexAdsnew');
                break;
            }
            
            $this->_helper->redirector->gotoUrl($this->getRequest()->getRequestUri());
        }
        
        $this->view->grid = new Admin_Grid_Ads();
    }
    
    
    public function adsviewAction() 
    {
        $this->view->ads =  Doctrine::getTable('Ads')->findOneByParams(array(
            'id'   =>  $this->_getParam('id')
        ));
    }
    
    public function adsnewAction()
	{
        if ($this->getRequest()->isPost()) {
        		$ads = $this->_saveads();
            	$ads->save();
            	$adsdir = 'packs/ads';
				$fullFilePath = $_SERVER['DOCUMENT_ROOT'].$this->_getParam('file');
				$newfilename = 'ads'.$ads->id.'_72.jpg';
				$newdest = $adsdir.'/'.$newfilename;
				rename($fullFilePath,$newdest);
				system ("mogrify -resize 72x72 $newdest");
    	        $this->_helper->redirector->gotoRoute(array(),'AdminIndexAds');
         }
		$ads = $this->_getadsForm();
     	$this->view->form = $ads;    
	}
    
    public function adseditAction()
	{
	    $id = $this->_getParam('id');
        $ads =  Doctrine::getTable('Ads')->findOneByParams(array(
            'id'   =>  $id
        ));
        $this->view->ads = $ads;
       // $this->_getcategoryForm();
        if (!$this->getRequest()->isPost()) {
            $this->_getadsForm($ads)->populate(
                $ads->toArray()
            );
        }
        if ($ads = $this->_saveads($ads)) {
        	$ads->save();
            $this->_helper->redirector->gotoRoute(array(),'AdminIndexAds');
        }
	}
	
    public function splashscreenAction()
	{
        $splash =  Doctrine::getTable('Splashscreen')->findOneByParams(array(
            'id'   =>  1
        ));
        $this->view->splash = $splash;
       // $this->_getcategoryForm();
        if (!$this->getRequest()->isPost()) {
            $this->_getsplashForm($splash)->populate(
                $splash->toArray()
            );
        }
        if ($splash = $this->_savesplash($splash)) {
        	
       
				$fil = $this->_getParam('file');
				$fil2 = $this->_getParam('file2');
				 		print_r($fil2);
        	
        		if (!empty($fil)) {
            	$splashdir = 'packs/splash';
				$fullFilePath = $_SERVER['DOCUMENT_ROOT'].$this->_getParam('file');
				$newfilename = 'splash_1024.jpg';
				$newdest = $splashdir.'/'.$newfilename;

				copy($fullFilePath,$newdest);
				system ("mogrify -resize 1024x768 $newdest");
				$splash->img1024 = $newfilename;
        		}
                if (!empty($fil2)) {
            	$splashdir = 'packs/splash';
				$fullFilePath = $_SERVER['DOCUMENT_ROOT'].$this->_getParam('file');
				$newfilename = 'splash_960.jpg';
				$newImagename480 = 'splash_480.jpg';
				$newdest = $splashdir.'/'.$newfilename;
				$newImagedest480 = $splashdir.'/'.$newImagename480;
				copy($fullFilePath,$newdest);
				copy($newdest,$newImagedest480);
				echo "$newdest,$newImagedest480 = $fullFilePath,$newdest"; 
				
				system ("mogrify -resize 960x640 $newdest");
				system ("mogrify -resize 480x $newImagedest480");
				$splash->img960 = $newfilename;
				$splash->img480 = $newImagename480;
				//exit();
        		}
        	$splash->save();
        	        $this->view->splash = $splash;
            $this->_helper->redirector->gotoRoute(array(),'AdminIndexSplashscreen');
        }
	}
	
    public function homescreenAction()
	{
        $home =  Doctrine::getTable('Homescreen')->findOneByParams(array(
            'id'   =>  1
        ));
        $this->view->home = $home;
       // $this->_getcategoryForm();
        if (!$this->getRequest()->isPost()) {
            $this->_gethomeForm($home)->populate(
                $home->toArray()
            );
        }
        if ($home = $this->_savehome($home)) {
        	
       
				$fil = $this->_getParam('file');
				$fil2 = $this->_getParam('file2');
				 		print_r($fil2);
        	
        		if (!empty($fil)) {
            	$homedir = 'packs/home';
				$fullFilePath = $_SERVER['DOCUMENT_ROOT'].$this->_getParam('file');
				$newfilename = 'home_1024.jpg';
				$newdest = $homedir.'/'.$newfilename;

				copy($fullFilePath,$newdest);
				system ("mogrify -resize 1024x768 $newdest");
				$home->img1024 = $newfilename;
        		}
                if (!empty($fil2)) {
            	$homedir = 'packs/home';
				$fullFilePath = $_SERVER['DOCUMENT_ROOT'].$this->_getParam('file');
				$newfilename = 'home_960.jpg';
				$newImagename480 = 'home_480.jpg';
				$newdest = $homedir.'/'.$newfilename;
				$newImagedest480 = $homedir.'/'.$newImagename480;
				copy($fullFilePath,$newdest);
				copy($newdest,$newImagedest480);
				echo "$newdest,$newImagedest480 = $fullFilePath,$newdest"; 
				
				system ("mogrify -resize 960x640 $newdest");
				system ("mogrify -resize 480x $newImagedest480");
				$home->img960 = $newfilename;
				$home->img480 = $newImagename480;
				//exit();
        		}
        	$home->save();
        	        $this->view->home = $home;
            $this->_helper->redirector->gotoRoute(array(),'AdminIndexHomescreen');
        }
	}
	
    public function adsdeleteAction()
	{
	$id = $this->_getParam('id');
        $cat =  Doctrine::getTable('Ads')->findOneByParams(array(
            'id'   =>  $id
        ));
        $cat->delete();
        //$messanger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $this->_helper->redirector->gotoRoute(array(),'AdminIndexAds');
		
	}
	
    public function companyAction()
	{
        $company =  Doctrine::getTable('Company')->findOneByParams(array(
            'id'   =>  1
        ));
        $this->view->company = $company;
       // $this->_getcategoryForm();
        if (!$this->getRequest()->isPost()) {
            $this->_getcompanyForm($company)->populate(
                $company->toArray()
            );
        }
        if ($company = $this->_savecompany($company)) {
        	$company->save();
        	$flashMessenger = $this->_helper->getHelper('FlashMessenger');
 			$flashMessenger->addMessage('Data saved');
            $this->_helper->redirector->gotoRoute(array(),'AdminIndexCompany');
        }
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
	
    public function howtoplayAction()
	{
        $howto =  Doctrine::getTable('Howtoplay')->findOneByParams(array(
            'id'   =>  1
        ));
       $this->view->howto = $howto;
        if (!$this->getRequest()->isPost()) {
            $this->_gethowtoForm($howto)->populate(
                $howto->toArray()
            );
        }  
        if ($howto = $this->_savehowto($howto)) {
                $howtodir = 'packs/howto';
				$fil = $this->_getParam('video');     	
				$fullVidPath = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$fil;
				if (!empty($fil)) {
					$video = $this->_getParam('video');
            	
            		if (!empty($video)) {
            			$fullVidPath = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$video;
            			$ext =  end(explode(".", $video));            	
						$newVidname = 'howto_big.mov';//.$ext;
						$newsmallVidname = 'howto_small.mp4';
						$newViddest = $howtodir.'/'.$newVidname;
						$smallnewViddest = $howtodir.'/'.$newsmallVidname;
						//echo "<BR>aaa=$fullVidPath $newViddest<BR>";
						//exit();
						system("mv -f $fullVidPath $newViddest");
						$output = shell_exec("ffmpeg -y -i {$_SERVER['DOCUMENT_ROOT']}/$newViddest -vcodec libx264 -vpre hq -vpre ipod640 -b 500k -bt 50k -acodec libfaac -ab 56k -ac 1 {$_SERVER['DOCUMENT_ROOT']}/$smallnewViddest");
						$howto->video = $newVidname;
						$howto->save();
						$flashMessenger = $this->_helper->getHelper('FlashMessenger');
 						$flashMessenger->addMessage('Data saved');
						$this->_helper->redirector->gotoRoute(array(),'AdminIndexHowtoplay');
            	}
            	
				}
			
         } 
         
        
	}
	
	
    public function iconAction()
	{
		$this->view->form = $this->_geticonForm();
        if ($this->getRequest()->isPost()) {
                $icondir = 'packs/ads';
				$fil = $this->_getParam('file');     	
				if (!empty($fil)) {
            		   	$adsdir = 'packs/ads';
						$fullFilePath = $_SERVER['DOCUMENT_ROOT'].$this->_getParam('file');
						$newfilename = 'icon_72.jpg';
						$newdest = $adsdir.'/'.$newfilename;
						copy($fullFilePath,$newdest);
						system ("mogrify -resize 72x72 $newdest");
						$flashMessenger = $this->_helper->getHelper('FlashMessenger');
 						$flashMessenger->addMessage('Data saved');
						$this->_helper->redirector->gotoRoute(array(),'AdminIndexIcon');
            	
            	
				}
			
         } 
	}
	
//================JSON==========
    public function directoryAction()
	{
		$sign = $this->_getParam('sign');
		$type = $this->_getParam('size');
		$secret_key='DfoUig5rduZbgm';
 		//$path = $_GET["path"];
///directory?size=1&sign=53bfc75e2f0d3df427ea5246e7da
 		$request_uri = $_SERVER['REQUEST_URI'];
 		///echo"$request_uri\n<BR>";
 		$for_md5 =  parse_url($request_uri);
 		$for_md5 =  substr($for_md5['path'], 1);
 		
 		$path = parse_url($request_uri );
 		parse_str($path['query'],$output);
 		//print_r($output);
 		
 		$for_md5 = $for_md5."?size=".$output['size'];
 		//echo"$for_md5\n<BR>";
 		
 		
 		$path = substr($path['path'], 4);
		// echo "formd5=".$for_md5.$secret_key."\n<BR><BR>";
 		$md5 = md5($for_md5.$secret_key);
 		 
 		$md5 = strtoupper ($md5);
 		//echo "formd5=$md5";
		//if ($md5 != $output['sign']) {exit();}
		
		
		$output=array();
		$cats = Doctrine::getTable('Category')->findByParams(
        array(
			'status' => 1,
			'order_by_param' => array('by'=>'position','dir'=>'asc')
            )
        );  
        $cats=$cats->toArray();
         
        
        //$cats = json_encode($cats);
	    //print_r($output);
	    $outputcats=array();
	    $outputpacks=array();
        foreach ($cats as $key=>$value) {
			
        	$value['id_directory'] = $value['id'];
        	unset($value['id']);
        	unset($value['status']);
        	unset($value['position']);
        	$value['name'] = $value['title'];
        	unset($value['title']);
        	unset($value['ord']);

			$packs = Doctrine::getTable('Puzzlepack')->findByParams(
        	array(
				'status' => 1,
				'category_id' =>  $value['id_directory'],
        		'order_by_param' => array('by'=>'position','dir'=>'asc')
            	)
        	);
        	
       		$packs=$packs->toArray();
        	$outpuzzpack=array();
       		foreach ($packs as $key2=>$value2) {
        		$puzzpack['id_pack'] = $value2['id'];
        		//$puzzpack['category_id'] = $value2['category_id'];
        		//echo $value2['image246']."\n\n<BR>	";
        		
        		$puzzpack['image_url'] = ($type==1) ? $value2['image246'] : $value2['image123'] ;  
        		$puzzpack['image_url'] = "get/".$puzzpack['id_pack']."/".$puzzpack['image_url'];
        		$puzzpack['description'] = $value2['description'];
        		//$puzzpack['description'] = $value2['description'];
        		$puzzpack['name'] = $value2['name'];
        		$puzzpack['price'] = $value2['price'];
        		$puzzpack['position'] = $value2['position'];
        		$puzzpack['app_purchase_id'] = $value2['purchase_id'];
        		unset($value2);
        			$puzzles = Doctrine::getTable('Puzzle')->findByParams(
        				array(
							'status' => 1,
							'pack_id' =>  $puzzpack['id_pack'],
        					'order_by_param' => array('by'=>'position','dir'=>'asc')
            			)
		        	);
		        	$puzzles=$puzzles->toArray();
		        	$outputpuzzlies=array();
		        	foreach ($puzzles as $key4=>$value4) {
	    		        	$puzz['thumbnail_url'] = '';
		        		$puzz['id_puzzle'] = $value4['id'];
		        		if ($type==1) {	
		        			if (preg_match('/^(.+?)(_)(\d+)(\.)(.+?)$/ism',$value4['image246'],$matches)) {
		        				$puzz['thumbnail_url'] = $matches[1].$matches[2].'246b'.$matches[4].$matches[5];
		        			}
						}
		        		 else {
		        			if (preg_match('/^(.+?)(_)(\d+)(\.)(.+?)$/ism',$value4['image246'],$matches)) {
		        				$puzz['thumbnail_url'] = $matches[1].$matches[2].'123b'.$matches[4].$matches[5];
		        			}
		        		}
		        		
		        		$puzz['thumbnail_url'] = "get/".$puzzpack['id_pack']."/".$value4['id']."/".$puzz['thumbnail_url'];

		        	$outputpuzzlies[]=$puzz;
		        	unset($puzz);
		        	}
		        	$puzzpack['puzzle_thumbnails'] = $outputpuzzlies;
        		$outputpacks[]=$puzzpack;
        		
       		}
       		if (empty($outputpacks)) {$outputpacks=array(0=>'none');}
       		$value['puzzle_packs']=$outputpacks;
       		unset($outputpacks);		

       		$output[]=$value;
       		
        }
       //===================ADS
       $ads = Doctrine::getTable('Ads')->findByParams(
        	array(
				'status' => 1,
        		'order_by_param' => array('by'=>'id','dir'=>'asc')
            	)
       );
       $adsout=array();
	foreach ($ads as $key=>$value) {
		$array['ad_id'] = $value['id'];
  		$array['ad_text'] = $value['description'];
  		$array['ad_link'] = $value['url'];
  		$array['ad_image72'] = "get/ads/icon_72.jpg";
  		$adsout[] = $array;
  		unset($array);
	}
	       //===================splash
        $splash =  Doctrine::getTable('Splashscreen')->findOneByParams(array(
            'id'   =>  1
        ));
        //print_r($splash);
        $splashout=array();
  		$array['img1024'] = 'get/splash/'.$splash->img1024;
  		$array['img960'] = 'get/splash/'.$splash->img960;
  		$array['img480'] = 'get/splash/'.$splash->img480;
  		$array['updated_at'] =  strtotime ($splash->updated_at);
  		$splashout[] = $array;
  		unset($array);
       
       // $cats->
       $aaa=array();
	       //===================Home
        $home =  Doctrine::getTable('Homescreen')->findOneByParams(array(
            'id'   =>  1
        ));
        //print_r($home);
        $homeout=array();
  		$array['img1024'] = 'get/home/'.$home->img1024;
  		$array['img960'] = 'get/home/'.$home->img960;
  		$array['img480'] = 'get/home/'.$home->img480;
  		$array['updated_at'] = strtotime ($home->updated_at);
  		$homeout[] = $array;
  		unset($array);
  		//===================Company
        $company =  Doctrine::getTable('Company')->findOneByParams(array(
            'id'   =>  1
        ));
        $companyout=array();
  		$array['url'] = $company->url;
  		$array['updated_at'] = strtotime ($company->updated_at);
  		$companyout[] = $array;
  		unset($array);
	       //===================How to
        $howto =  Doctrine::getTable('Howtoplay')->findOneByParams(array(
            'id'   =>  1
        ));
        if ($type==0) {$image = 'howto_big.mp4';} else {$image = 'howto_small.mp4';}
        $howtoout=array();
  		$array['video'] = 'get/howto/'.$image;
  		$array['updated_at'] = strtotime ($howto->updated_at);
  		$howtoout[] = $array;
  		unset($array);       
       // $cats->
       $aaa=array();       
       
       
       $aaa['directories'] = $output;
       $aaa['ads'] = $adsout;
       $aaa['company'] = $companyout;
       $aaa['splash'] = $splashout;
       $aaa['home'] = $homeout;
       $aaa['howto'] = $howtoout;
       $this->_helper->layout()->disableLayout();
      // print_r($GLOBALS); 
       echo Zend_Json::encode($aaa);
	}
	
    public function packAction()
	{
		$id = $this->_getParam('id');
		$type = $this->_getParam('size');
		$sign = $this->_getParam('sign');
		
		$secret_key='DfoUig5rduZbgm';
 		//$path = $_GET["path"];
///http://artpuzzle.loc/pack?id=1&size=1&sign=sakdjasjdjad
 		$request_uri = $_SERVER['REQUEST_URI'];
 		//echo"$request_uri\n<BR>";
 		$for_md5 =  "pack?id=$id";
 		
 		$path = parse_url($request_uri );
 		parse_str($path['query'],$output);
 		//print_r($output);
 		
 		$for_md5 = $for_md5."&size=".$output['size'];
 		//echo"$for_md5\n<BR>";
 		
 		
 		//$path = substr($path['path'], 4);
		// echo "formd5=".$for_md5.$secret_key."\n<BR><BR>";
 		$md5 = md5($for_md5.$secret_key);
 		$md5 = strtoupper ($md5);
		//echo "formd5=$md5";
 		if ($md5 != $output['sign']) {exit();}
		
		$output=array();
		$pack = Doctrine::getTable('Puzzlepack')->findOneByParams(array(
            'id' => $id
        ));
		
        $pack=$pack->toArray();
        $puzzles = Doctrine::getTable('Puzzle')->findByParams(
        	array(
				'status' => 1,
				'pack_id' =>  $pack['id'],
        		'order_by_param' => array('by'=>'position','dir'=>'asc')
        		)
		);
//		print_r($puzzles->toArray()); 
		$puzzles=$puzzles->toArray();

		$puzz=array();
		
//		print_r($puzz);
		
		foreach ($puzzles as $key4=>$value4) {
			$puzz['id_puzzle'] = $value4['id'];
		    $puzz['name'] = $value4['name'];
		   // $puzz['description'] = $value4['description'];
		   
		    
		    
 		  if ($type==1) {	$puzz['full_image_url'] =	$value4['image960']; } else {
		    	if (preg_match('/^(.+?)(_)(\d+)(\.)(.+?)$/ism',$value4['image960'],$matches)) {
		        	$puzz['full_image_url'] = $matches[1].$matches[2].'480'.$matches[4].$matches[5];
		        }
		    }		    
		    
		    preg_match('/^(.+?)(_)(\d+)(\.)(.+?)$/ism',$value4['image246'],$matches);		                                                            
		    

		    if ($type==1) {
			$puzz['image_url'] = $matches[1].$matches[2].'246b'.$matches[4].$matches[5];
		    } else {
	        	$puzz['image_url'] = $matches[1].$matches[2].'123b'.$matches[4].$matches[5];
		    }
//		    print_r($puzz);
	
//		    $puzz['image_url']='';	    
//		    $puzz['full_image_url']='';
		    $puzz['image_url'] = "get/".$pack['id']."/".$puzz['id_puzzle']."/".$puzz['image_url'];
		    $puzz['full_image_url'] = "get/".$pack['id']."/".$puzz['id_puzzle']."/".$puzz['full_image_url'];
		    
			if ($type==1) {
				$puzz['start_video_url'] = "get/".$pack['id']."/".$puzz['id_puzzle']."/".$value4['Video1'];
		    	$puzz['finish_video_url'] = "get/".$pack['id']."/".$puzz['id_puzzle']."/".$value4['Video2'];
		    } else {
		    	if (preg_match('/^(.+?)(_)(\d+)(\.)(.+?)$/ism',$value4['Video1'],$matches)) {
	        		$puzz['start_video_url'] = "get/".$pack['id']."/".$puzz['id_puzzle']."/".$matches[1].'s_1.mp4';
					$puzz['finish_video_url'] = "get/".$pack['id']."/".$puzz['id_puzzle']."/".$matches[1].'s_2.mp4';
		        }

		    }
		    
		    
		  //  print_r($value4);
		   // exit();
		    $output[]=$puzz;
		    unset($puzz);
		        	//print_r($outputpuzzlies);	
		}
		
	
       $aaa=array();
       $aaa['puzzles'] = $output;  
       //$this->_helper->layout()->disableLayout();
      // print_r($GLOBALS); 
       $this->_helper->json($aaa);
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

    private function _getadsForm()
    {
        if ($this->_adsForm === null) {
        $this->_adsForm = new Admin_Form_Ads();
        }
        return $this->_adsForm;
    }
    
    private function _getsplashForm()
    {
        if ($this->_splashForm === null) {
        $this->_splashForm = new Admin_Form_Splashscreen();
        }
        return $this->_splashForm;
    }
    
    private function _gethomeForm()
    {
        if ($this->_homeForm === null) {
        $this->_homeForm = new Admin_Form_Homescreen();
        }
        return $this->_homeForm;
    }
    
    private function _getcompanyForm()
    {
        if ($this->_companyForm === null) {
        $this->_companyForm = new Admin_Form_Company();
        }
        return $this->_companyForm;
    }
    
    private function _geticonForm()
    {
        if ($this->_iconForm === null) {
        $this->_iconForm = new Admin_Form_Icon();
        }
        return $this->_iconForm;
    }
    
    private function _saveads($cat = null)
    {
        if ($this->getRequest()->isPost()) {
        		if ($this->_getadsForm()->isValid($this->getRequest()->getPost())) {
                	if (is_null($cat) ) {
                		$cat = Doctrine::getTable('Ads')->create();
                	}
                $cat->merge($this->_getadsForm()->getValues());
                return $cat;
            }
        }
     $this->view->form = $this->_getadsForm();
    }
    
    
    private function _savesplash($cat = null)
    {
        if ($this->getRequest()->isPost()) {
        		if ($this->_getsplashForm()->isValid($this->getRequest()->getPost())) {
                	if (is_null($cat) ) {
                		$cat = Doctrine::getTable('Splashscreen')->create();
                	}
                $cat->merge($this->_getsplashForm()->getValues());
                return $cat;
            }
        }
     $this->view->form = $this->_getsplashForm();
    }
    
    private function _savehome($cat = null)
    {
        if ($this->getRequest()->isPost()) {
        		if ($this->_gethomeForm()->isValid($this->getRequest()->getPost())) {
                	if (is_null($cat) ) {
                		$cat = Doctrine::getTable('Homescreen')->create();
                	}
                $cat->merge($this->_gethomeForm()->getValues());
                return $cat;
            }
        }
     $this->view->form = $this->_gethomeForm();
    }
    
    private function _savecompany($cat = null)
    {
        if ($this->getRequest()->isPost()) {
        		if ($this->_getcompanyForm()->isValid($this->getRequest()->getPost())) {
                	if (is_null($cat) ) {
                		$cat = Doctrine::getTable('Company')->create();
                	}
                $cat->merge($this->_getcompanyForm()->getValues());
                return $cat;
            }
        }
     $this->view->form = $this->_getcompanyForm();
    }
    
    private function _gethowtoForm()
    {
        if ($this->_howtoForm === null) {
        $this->_howtoForm = new Admin_Form_Howto();
        }
        return $this->_howtoForm;
    }
    
  
    
    private function _savehowto($cat = null)
    {
        if ($this->getRequest()->isPost()) {
        		if ($this->_gethowtoForm()->isValid($this->getRequest()->getPost())) {
                	if (is_null($cat) ) {
                		$cat = Doctrine::getTable('Howtoplay')->create();
                	}
                $cat->merge($this->_gethowtoForm()->getValues());
                return $cat;
            }
        } else {        }
     $this->view->form = $this->_gethowtoForm();
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



