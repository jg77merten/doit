<?php

class User_MessagesController extends FinalView_Controller_Action
{

    public function indexAction()
    {
        $messagesPager = Doctrine::getTable('Topic')->findPageByParams(array(
            'messages' => array('viewer' => $this->_helper->user->authorized)
        ), $this->getRequest()->getQuery('page', 1), Config::get('user', 'messages.perPage', 10) );

        $messages = $messagesPager->execute();
        
        $this->view->messages = $messages;
        $this->view->pager = $messagesPager;

        Doctrine::getTable('TopicPrivate')->updateByParams(array(
            'user_id'       =>  $this->_helper->user->authorized->id,
            'topics'        =>  array_values($messages->toKeyValueArray('id', 'id'))
        ), array('is_viewed' => true) );
    }

    public function postTopicAction()
    {
        $form = new User_Form_Message_Topic($this->_helper->user->authorized);

        $this->_postTopic($form);
    }

    public function postStatementAction()
    {
        $form = new User_Form_Message_Statement($this->_helper->user->authorized);

        $this->_postTopic($form);
    }

    public function postPictureAction()
    {
        $form = new User_Form_Message_Picture($this->_helper->user->authorized);

        $this->_postTopic($form);
    }

    /**
     * Topic posting.
     * 
     * @param User_Form_Message_Abstract $form 
     */
    protected function _postTopic(User_Form_Message_Abstract $form)
    {
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $topicData = $form->getValues();
                if (isset($topicData['picture_path'])) {
                    $fFilter = new FinalView_Filter_File_SetUniqueName();
                    $topicData['picture_path'] = basename($fFilter->filter($topicData['picture_path']));
                }
                $topicData['user_id'] = $this->_helper->user->authorized->id;
                $topic = new Topic();
                $topic->merge($topicData);
                $topic->save();

                if ($form->getValue('friends')) {
                    $collection = new Doctrine_Collection('TopicPrivate');
                    foreach ($form->getValue('friends') as $user) {
                        $record = Doctrine::getTable('TopicPrivate')->create(array(
                            'topic_id' => $topic->id,
                            'user_id' => $user
                                ));
                        $collection->add($record);
                    }
                    $collection->save();
                }
                
                $this->view->redirect = true; return;
//                 $this->_helper->redirector->gotoRoute(array(), 'UserMypageIndex'); exit;
            }
            $this->view->errors = $form->getMessages();
        }
    }

}