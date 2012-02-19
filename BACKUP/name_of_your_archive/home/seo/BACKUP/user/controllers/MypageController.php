<?php

class User_MypageController extends FinalView_Controller_Action
{

    public function indexAction()
    {
        $user = $this->_helper->user->authorized;
        
        $this->view->wordOfMy = $this->_helper->user->authorized->getMyNetworkTags();
        
        $this->view->formPostTopic = $formTopic = new User_Form_Message_Topic();
        $this->view->formPostStatement = $formStatement = new User_Form_Message_Statement();
        $this->view->formPostPicture = $formPicture = new User_Form_Message_Picture();

        $this->view->topicTrail = $user->searchTopics(array(
                    'viewer' => $user,
                    'comments' => true
                ))->execute();
        // TODO: comments!
//        var_dump($this->view->topicTrail->toArray()); exit;
    }

    public function friendPopupAction()
    {
        $this->_helper->layout->disableLayout();
        $user = $this->_helper->user->authorized;
        $this->view->friends = $user->getFriends();
    }
    
    public function uploadPictureTopicAction()
    {
        require_once LIBRARY_PATH . "/AjaxUpload/php.php";

        $uploader = new qqFileUploader();
        $result = $uploader->upload();

        if (@$result['success']) {
            $filter = new User_Filter_UploadPictureTopic($uploader->getFile());

            if ($filter->isValid()) {
                $this->_helper->json(array(
                    'success'    =>  true,
                    'img_src'    =>  $this->view->src($this->_helper->thumbnail->thumb(array(
                        'url'       =>  FinalView_Config::get('user', 'uploadPictureForTopicPath') . '/' . basename($filter->file),
                        'width'     =>  165,
                        'height'    =>  165,
                        'crop'      =>  'C'
                    )))
                ));
                return;
            }

            $errors = $filter->getMessages();
        }

        $this->_helper->json(array(
            'success'   =>  false,
            'errors'    =>  $errors ? $errors : $result['error']
        ));
    }

}