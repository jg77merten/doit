<?php
class Admin_Grid_Packs extends Admin_Grid_Base
{
    public function __construct($category_id)
    {
    	
    	if (!empty($category_id))
    	{
        parent::__construct(array(
            'model' => 'Puzzlepack',
            'filter'    =>  array(
        		'category_id' => $category_id,
        		'order_by' => array('field'=>'position','direction'=>'asc')
            ),
        ));
    	} else {
    	parent::__construct(array(
            'model' => 'Puzzlepack',
            'filter'    =>  array(
        		'order_by' => array('field'=>'position','direction'=>'asc')
            ),
        ));	
    	}
        $router = Zend_Controller_Front::getInstance()->getRouter();

        
          $this->addColumn(
            new FinalView_Grid_Column_Action(
                'up',
                'Up',
                $router->getRoute('AdminPackUp'),
                array('id' => 'id')
            )
        );
		
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'down',
                'Down',
                $router->getRoute('AdminPackDown'),
                array('id' => 'id')
            )
        );
        
        $this->getColumns()->removeColumn('image246');
        $this->getColumns()->removeColumn('image123');
        $this->getColumns()->removeColumn('description');
        $this->getColumns()->removeColumn('created_at');
        $this->getColumns()->removeColumn('name');
        
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'edit', 
                'Edit',
                $router->getRoute('AdminPackEdit'), 
                array('id' => 'id')
            )
        );        
	        $this->addColumn(
	            new FinalView_Grid_Column_Action(
	                'Del', 
	                'Delete',
	                $router->getRoute('AdminPackDelpack'), 
	                array('id' => 'id')
	            )
	        );               

        
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'name', 
                '',
                $router->getRoute('AdminPuzzleIndex'), 
                array('id' => 'id')
            ),
            FinalView_Grid_ColumnsCollection::APPEND_AFTER_COLUMN,
            'category_id'
        ); 
        
        $this->addPlugin(new FinalView_Grid_Plugin_Sortable(array(
            'id', 'status', 'category_id', 'name', 'price' 
        )));
        
        $this->addPlugin(new FinalView_Grid_Plugin_Gridactions(array(
            array('type'    =>  'submit', 'value' => 'New Pack', 'name' => 'newpack'),
        )));
        
        
        $this->getColumns()->setColumnsOrder(array('up','down','name','price','status','id','purchase_id','category_id','updated_at','edit','Del'));
        $this->addPlugin(new FinalView_Grid_Plugin_Colspan(array('up'=>3)));

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
    
    public function nameHandler($params, $view) {
    	$this->getColumns()->name->handler($params, $view);
    	$view->label = $params->name;
    }

    public function ColumnHandler($params, $view) {
    	parent::ColumnHandler($params, $view);
    	if($params['column']->getName() == 'Del') {
    		$view->td_attribs = array('class' => 'column deleteLink');

    	}    	
    }
    
    public function statusHandler($params, FinalView_Grid_Renderer $view)
    {
        if ($params->status) { $view->value = 'Active';}
        else {$view->value = 'Inactive';}
    }

    public function categoryIdHandler($params, FinalView_Grid_Renderer $view)
    {
    	$cat =  Doctrine::getTable('Category')->findOneByParams(array(
            'id' =>  $params->category_id
        ));
        $view->value = $cat->title;        
    }
    
    
}
