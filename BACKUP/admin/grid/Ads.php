<?php
class Admin_Grid_Ads extends Admin_Grid_Base
{
    public function __construct()
    {
    	
        parent::__construct(array(
            'model' => 'Ads',
            'filter'    =>  array(
        		'order_by' => array('field'=>'id','direction'=>'asc')
            ),
        ));

        $router = Zend_Controller_Front::getInstance()->getRouter();

        
        
        $this->getColumns()->removeColumn('ids');
        $this->getColumns()->removeColumn('url');
        $this->getColumns()->removeColumn('updated_at');
        $this->getColumns()->removeColumn('description');
        
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'edit', 
                'Edit',
                $router->getRoute('AdminIndexAdsedit'), 
                array('id' => 'id')
            )
        );        

        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'delete', 
                'Delete',
                $router->getRoute('AdminIndexAdsdelete'), 
                array('id' => 'id')
            )
        );        

//        $this->addColumn(
//            new FinalView_Grid_Column_Action(
//                'view', 
//                'View',
//                $router->getRoute('AdminPuzzleIndex'), 
//                array('id' => 'id')
//            )
//        );                

        
        $this->addColumn(
            new FinalView_Grid_Column_Action(
                'url', 
                '',
                $router->getRoute('AdminIndexAdsview'), 
                array('id' => 'id')
            ),
            FinalView_Grid_ColumnsCollection::APPEND_AFTER_COLUMN,
            'id'
        ); 
        
        
        $this->addPlugin(new FinalView_Grid_Plugin_Gridactions(array(
            array('type'    =>  'submit', 'value' => 'New Ads', 'name' => 'newads'),
        )));
        
        
//        $this->getColumns()->setColumnsOrder(array('up','down','name','price','status','id','purchase_id','category_id','updated_at','edit'));
//        $this->addPlugin(new FinalView_Grid_Plugin_Colspan(array('up'=>3)));

//        $this->getRenderer()->addScriptPath(APPLICATION_PATH . "/" . "modules/admin/views/grid");
    }
    
    
    public function urlHandler($params, $view) {
    	$this->getColumns()->url->handler($params, $view);
    	$view->label = $params->url;
    }
    
    public function statusHandler($params, FinalView_Grid_Renderer $view)
    {
        if ($params->status) { $view->value = 'Active';}
        else {$view->value = 'Inactive';}
    }

//    public function statusHandler($params, FinalView_Grid_Renderer $view)
//    {
//        if ($params->status) { $view->value = 'Active';}
//        else {$view->value = 'Inactive';}
//    }
    
    
}
