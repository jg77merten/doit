<?php
class Admin_Grid_Puzzles extends Admin_Grid_Base
{
    public function __construct($pack_id)
    {
        parent::__construct(array(
            'model' => 'Puzzle',
            'filter'    =>  array(
        		'pack_id' => $pack_id,
        		'order_by' => array('field'=>'position','direction'=>'asc')
            ),
            
        
        ));

        $router = Zend_Controller_Front::getInstance()->getRouter();
        
        
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'up',
                'Up',
                $router->getRoute('AdminPuzzleUp'),
                array('id' => 'id')
            )
        );

        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'down',
                'Down',
                $router->getRoute('AdminPuzzleDown'),
                array('id' => 'id')
            )
        );

        $this->getColumns()->removeColumn('ids');
        $this->getColumns()->removeColumn('image246');
        $this->getColumns()->removeColumn('image960');
        $this->getColumns()->removeColumn('video1');
        $this->getColumns()->removeColumn('video2');
        $this->getColumns()->removeColumn('pack_id');
        $this->getColumns()->removeColumn('created_at');
        $this->getColumns()->removeColumn('position');
        $this->getColumns()->removeColumn('name');
        
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'name', 
                '',
                $router->getRoute('AdminPuzzleEditpuzzle'), 
                array('id' => 'id')
            )
        ); 

        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'Del', 
                'Delete',
                $router->getRoute('AdminPuzzleDelpuzzle'), 
                array('id' => 'id')
            )
        ); 
      
        
        $this->addPlugin(new FinalView_Grid_Plugin_Sortable(array(
            'id'
        )));
        
        
        $this->getColumns()->setColumnsOrder(array('up','down','name','status','id','updated_at','Del'));
        
        $this->addPlugin(new FinalView_Grid_Plugin_Colspan(array('up'=>3)));
        
        $url = Zend_Controller_Action_HelperBroker::getStaticHelper('Url');
        
        $this->addPlugin(new FinalView_Grid_Plugin_Gridactions(array(
            array('type'    =>  'link', 'label' => 'New Puzzle', 
            'href' => $url->url(array('pack'=>$pack_id), 'AdminPuzzleNewpuzzle')) 
            //$router->getRoute(array('pack_id'=>$pack_id),'AdminPuzzleEditpuzzle')),
        )));
        
        
		$this->getRenderer()->addScriptPath(APPLICATION_PATH . "/" . "modules/admin/views/grid");
       
    }
    
    
    public function upHandler($params, $view) {
    	$this->getColumns()->up->handler($params, $view);
	    $view->setScript("up.phtml");
    }
    public function downHandler($params, $view) {
	    $this->getColumns()->down->handler($params, $view);
    	$view->setScript("down.phtml");
    }
    
    public function statusHandler($params, FinalView_Grid_Renderer $view)
    {
        if ($params->status) { $view->value = 'Active';}
        else {$view->value = 'Inactive';}
    }
    
    public function nameHandler($params, $view) {
    	$this->getColumns()->name->handler($params, $view);
    	$view->label = $params->name;
    }
    

}
