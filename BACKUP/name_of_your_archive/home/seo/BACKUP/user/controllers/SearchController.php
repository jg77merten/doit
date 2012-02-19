<?php
class User_SearchController extends FinalView_Controller_Action
{
    const SEARCH_FOR_ALL = 'all';
    const SEARCH_FOR_USERS = 'users';
    const SEARCH_FOR_TOPICS = 'topics';
    
    
    public function indexAction()
    {
        $this->view->keyword = $this->getRequest()->getQuery('keyword');

        $search_for = $this->getRequest()->getQuery('search_for', User_SearchController::SEARCH_FOR_ALL);
        $this->view->search_for = $search_for;
        
        if (empty($search_for) ) {
            $this->_helper->redirector->gotoRoute(array(), 'DefaultIndexIndex' );
        }

        if (in_array($search_for, array(User_SearchController::SEARCH_FOR_USERS, User_SearchController::SEARCH_FOR_ALL))) {
            $this->_searchUsers( (int)$this->_getParam('page', 1));
        }
        
        if (in_array($search_for, array(User_SearchController::SEARCH_FOR_TOPICS, User_SearchController::SEARCH_FOR_ALL))) {
            $sels = array(
                'search'            =>  array(
                    'keyword'   =>  $this->getRequest()->getQuery('keyword')
                )
            );
            
            if ($this->_helper->user->isAuthorized()) {
                $sels['search']['viewer'] = $this->_helper->_user->authorized;
            }

            $this->view->topics = Doctrine::getTable('Topic')->findPageByParams(
                $sels, (int)$this->_getParam('page', 1), Config::get('user', 'search.entities_per_page', 10)
            );
        }
    }
    
    public function searchUsersAction()
    {
        $this->_searchUsers((int)$this->_getParam('page', 1));
    }
    
    protected function _searchUsers($page)
    {
        $this->view->users = Doctrine::getTable('User')->findPageByParams(array(
            'search'            =>  array('keyword' => $this->getRequest()->getQuery('keyword'))
        ), $page, Config::get('user', 'search.entities_per_page', 10));
    }
    
    public function searchTopicsAction()
    {
        $this->view->topics = Doctrine::getTable('Topic')->findPageByParams(array(
            'search'            =>  array(
                'keyword'   =>  $this->getRequest()->getQuery('keyword'),
                'viewer'    =>  $this->_helper->_user->authorized
            )
        ), (int)$this->_getParam('page', 1), Config::get('user', 'search.entities_per_page', 10));
    }

    protected function _searchTopics($keyword, $page, $perPage, $additionalSelectors = array() )
    {
        $this->view->topics = Doctrine::getTable('Topic')->findPageByParams(array(
            'search'            =>  array('keyword' => $keyword)
        ) + $additionalSelectors, $page, $perPage);
    }
    
    public function tagAction()
    {
        $this->view->keyword = $this->_getParam('keyword');

        $this->view->topics = Doctrine::getTable('Topic')->findPageByParams(array(
            'search'            =>  array(
                'keyword'   =>  $this->_getParam('keyword'),
                'viewer'    =>  $this->_helper->_user->authorized
            )
        ), $page, $perPage);
    }
    
    public function networkTagAction()
    {
        $this->view->keyword = $this->_getParam('keyword');

        $this->view->topics = Doctrine::getTable('Topic')->findPageByParams(array(
            'search'            =>  array(
                'keyword'           =>  $this->_getParam('keyword'),
                'viewer'            =>  $this->_helper->_user->authorized,
                'viewer_network'    =>  true
            )
        ), $page, $perPage);

        $this->view->keyword = $this->_getParam('keyword');
    }
 
  
}