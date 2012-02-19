<?php

class IndexController extends FinalView_Controller_Action
{

    public function indexAction()
    {
        
    }
    
    public function worksviewAction()
    {
        $category_id = $this->_getParam('category_id');
        $this->view->works = Doctrine::getTable('Work')->findByParams(array(
            'category_id'   =>  $category_id,
            'status'   =>  1,
            'orderBy' => array('field' => 'id', 'direction' => 'DESC'),
        ));
        
        $this->view->category = Doctrine::getTable('Category')->findOneByParams(array(
            'id'   =>  $category_id
        ));
    }
    
    public function workviewAction()
    {
        $work_id = $this->_getParam('work_id');
        $this->view->work = $work = Doctrine::getTable('Work')->findOneByParams(array(
            'id'   =>  $work_id
        ));
        
        //print_r($work);
        //exit();
    }
}

