<?php

class User_ProfileController extends FinalView_Controller_Action
{

//    const TOPICS_COUNT_PER_PAGE=1;
//      const PAGER_CHUNK=5;

    protected $_formUserProfile = null;
    protected $_formUserProfileFirstLogin = null;
    protected $_formUploadAvatar = null;
    protected $_formChangePswd = null;
    protected $_formSettings = null;
    protected $_authorizedUser = null;

    public function indexAction()
    {
        $user = $this->_getAuthorizedUser();
        $this->view->user = $user;

        $this->view->topicTrail = $user->searchTopics(array(
                    'viewer' => $user,
                    'owner' => $user
                ))->execute();
    }

    public function firstloginAction()
    {
        $user = $this->_getAuthorizedUser();
        $form = $this->_getFormUserProfileFirstLogin($user);

        $this->view->imageSrc = FinalView_Config::get('user', 'avatar_url') . '/' . 'default.jpg';

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $formValues = $form->getValues();
                $user->firstlogin = 0;
                $user->merge($formValues);
                $user->save();
                $this->_helper->redirector->gotoRoute(array(), 'DefaultIndexIndex');
            }
        } else {
            $this->_getFormUserProfileFirstLogin()->populate($user->toArray());
        }

        $this->view->formEditProfile = $form;
    }

    /**
     * Edit authorized user profile
     */
    public function editAction()
    {
        $this->_edit();
        $this->view->form = $this->_getFormUserProfile();
    }

    public function stateAutocompliteAction()
    {
        $post = $this->getRequest()->getPost();
        $this->view->source = array_values(
                Doctrine::getTable('Network')->findByStateCode($post['state'])->toKeyValueArray('id', 'title')
        );
    }

    /**
     * Edit authorized user profile
     */
    public function avatarCropAction()
    {
        $thumb = $this->_helper->getHelper('Thumbnail');

        $avatarStandart = FinalView_Config::get('user', 'avatar');

        $user = $this->_getAuthorizedUser();

        $post = $this->getRequest()->getPost();

        $user->deleteAvatarFile();

        $newAvName = uniqid() . '.' . pathinfo($post['src'], PATHINFO_EXTENSION);
        $originFile = FinalView_Config::get('user', 'upload_avatar_path') . '/' . basename($post['src']);

        $croppedPath = $thumb->thumb(array(
            'url' => $originFile,
            'width' => $post['w'],
            'height' => $post['h'],
            'coords' => array('x' => $post['x'], 'y' => $post['y']),
            'crop' => 'CC',
            'name' => $newAvName,
            'destination' => sys_get_temp_dir()
                ));

        foreach ($avatarStandart['thumb'] as $k => $v) {
            $thumb->thumb(array(
                'url' => $croppedPath,
                'crop' => 'PF',
                'width' => $v['width'],
                'height' => $v['height'],
                'destination' => FinalView_Config::get('user', 'upload_avatar_path'),
            ));
        }

        $thumb->thumb(array(
            'url' => $originFile,
            'width' => $avatarStandart['width'],
            'height' => 0,
            'destination' => FinalView_Config::get('user', 'upload_avatar_path'),
            'name' => $newAvName,
            'crop' => 'P'
        ));

        $user->setAvatarFile($newAvName);

        $user->save();
        copy($originFile, FinalView_Config::get('user', 'upload_avatar_path') . '/' . $user->getOriginAvatarSrc());

        $this->view->src = $this->view->src($user->getAvatarPath());
    }

    protected function _edit()
    {
        if ($this->_request->isPost()) {
            if ($this->_getFormUserProfile()->isValid($this->_request->getPost())) {
                $formValues = $this->_getFormUserProfile()->getValues();
                $this->_getAuthorizedUser()->merge($formValues);
                $this->_getAuthorizedUser()->save();
            }
        } else {
            $this->_getFormUserProfile()->populate($this->_getAuthorizedUser()->toArray());
        }
    }

    public function uploadAvatarAction()
    {
        require_once LIBRARY_PATH . "/AjaxUpload/php.php";

        $uploader = new qqFileUploader();
        $result = $uploader->upload();

        if (@$result['success']) {
            $filter = new User_Filter_UploadAvatar($uploader->getFile());

            if ($filter->isValid()) {
                $this->_helper->json(array(
                    'success' => true,
                    'img_src' => Config::get('user', 'avatar_url') . '/' . basename($filter->file)
                ));
                return;
            }

            $errors = $filter->getMessages();
        }

        $this->_helper->json(array(
            'success' => false,
            'errors' => $errors ? $errors : $result['error']
        ));
    }

    public function changeAvatarAction()
    {
        $originFilename = $this->_helper->user->authorized->getOriginAvatarSrc();
        $dir = Config::get('user', 'upload_avatar_path');

        $filename = pathinfo($originFilename, PATHINFO_FILENAME) . 'temp';
        $ext = pathinfo($originFilename, PATHINFO_EXTENSION);

        while (file_exists($dir . '/' . $filename . '.' . $ext)) {
            $filename .= rand(10, 99);
        }

        copy(
                $dir . '/' . $originFilename, $dir . '/' . $filename . '.' . $ext
        );
        $this->view->imageSrc = Config::get('user', 'avatar_url') . '/' . $filename . '.' . $ext;
    }

    /**
     * change password action
     */
    public function changePasswordAction()
    {

        $this->view->form = $this->_getFormChangePswd();

        if ($this->getRequest()->isPost()) {
            if ($this->_getFormChangePswd()->isValid($this->getRequest()->getPost())) {
                $user = $this->_helper->user->authorized;
                $user->password = $this->_getFormChangePswd()->getValue('new_password');
                $user->save();
            }
        }
    }

    public function settingsAction()
    {
        $user = $this->_getAuthorizedUser();
        if ($this->_request->isPost()) {
            if ($this->_getFormSettings(array('user' => $user))->isValid($this->_request->getPost())) {
                $formValues = $this->_getFormSettings()->getValues();
                $user->merge($formValues);
                $user->save();
            }
        } else {
            $this->_getFormSettings(array('user' => $user))->populate($user->toArray());
        }

        $this->view->form = $this->_getFormSettings(array('user' => $user));
    }

    /**
     * get user's change password form
     * @return User_Form_ChangePswd
     */
    protected function _getFormChangePswd()
    {
        if (is_null($this->_formChangePswd)) {
            $this->_formChangePswd = new User_Form_Profile_ChangePswd(array('user' => $this->_helper->user->authorized));
        }

        return $this->_formChangePswd;
    }

    protected function _getFormUserProfile()
    {
        if ($this->_formUserProfile === null) {
            $this->_formUserProfile = new User_Form_Profile_Edit();
        }

        return $this->_formUserProfile;
    }

    protected function _getFormUserProfileFirstLogin($user = null)
    {
        if ($this->_formUserProfileFirstLogin === null) {
            $this->_formUserProfileFirstLogin = new User_Form_Profile_FirstLoginEdit($user);
        }

        return $this->_formUserProfileFirstLogin;
    }

    protected function _getFormUploadAvatar()
    {
        if ($this->_formUploadAvatar === null) {
            $this->_formUploadAvatar = new User_Form_Profile_UploadAvatar();
        }

        return $this->_formUploadAvatar;
    }

    protected function _getFormSettings($options)
    {
        if ($this->_formSettings === null) {
            $this->_formSettings = new User_Form_Profile_Settings($options);
        }
        return $this->_formSettings;
    }

    public function _getAuthorizedUser()
    {
        if ($this->_authorizedUser === null) {
            $this->_authorizedUser = $this->_helper->user->authorized;
        }
        return $this->_authorizedUser;
    }

    /**
     * view requested user profile
     */
    public function viewProfileAction()
    {
        $requestedUser = $this->_helper->user->current;
        $viewerUser = $this->_getAuthorizedUser();
        $viewerUserId = isset($viewerUser->id) ? $viewerUser->id : null;

        $this->view->topicTrail = null;

        if ($requestedUser->id == $viewerUserId) {
            // my profile
            $this->_helper->redirector->gotoRoute(array(), 'UserProfileIndex');
        } elseif ($requestedUser->access_type == User::USER_PROFILE_PUBLIC OR
                $requestedUser->isFriend($viewerUserId)) {
            // can view topics
            $this->view->topicTrail = $requestedUser->searchTopics(array(
                        'owner' => $requestedUser,
                        'viewer' => $viewerUser))->execute();

            if (isset($viewerUser->id)) {
                $requestedUser->setBlokker($viewerUser->id);
                $viewerUser->setBlokker($requestedUser->id);
            }
        } else {
            // can't view topics
        }

        $this->view->user = $requestedUser;
    }

}