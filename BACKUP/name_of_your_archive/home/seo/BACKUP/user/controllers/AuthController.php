<?php

/**
 * Authentication
 */

//TODO: if user account is temporarily suspended by admin then the message should pop up “Your account is temporarily suspended till <date and time>” with Ok button to close message. The system should not log in user on the site.

class User_AuthController extends FinalView_Controller_Action
{

    private $_loginForm;
    private $_forgotPswdForm;
    protected $_wrongLoginForm;
    public $storage_params = array(
        'id'
    );
    protected $_session = null;

    /**
     * Login Peer
     */
    public function loginAction()
    {
        /*
         * if user failed to login more than $maxWrongLoginAttempts times
         * -- show recaptcha form
         */
        $maxWrongLoginAttempts = FinalView_Config::get('user', 'max_wrong_login_attempts');

        if ($this->_getWrongLoginAttemptsCount() >= $maxWrongLoginAttempts) {
            $this->_helper->redirector->gotoRoute(array(), 'UserAuthWronglogin');
        }

        if ($this->_login() == Zend_Auth_Result::SUCCESS) {
            $user = $this->_helper->user->authorized;
            
            // if firstlogin -- redirect to firstlogin page
            if ($user->firstlogin){
                $url = $this->_helper->url->url(array(), 'UserProfileFirstlogin');
            } else {
                $url = $this->getRequest()
                        ->getParam('back_url', $this->view->url(array(), 'UserIndexIndex'));
            }
            $this->_redirect($url);
        }
    }

    public function wrongloginAction()
    {
        $maxWrongLoginAttempts = FinalView_Config::get('user', 'max_wrong_login_attempts');
        if ($this->_getWrongLoginAttemptsCount() >= $maxWrongLoginAttempts) {

            if ($this->getRequest()->isPost()) {
                if ($this->_getWrongLoginForm()->isValid($this->getRequest()->getPost())) {
                    $this->_resetWrongLoginAttemptsCount();
                }
            }
            $this->view->form = $this->_getWrongLoginForm();
        }

        if ($this->_getWrongLoginAttemptsCount() < $maxWrongLoginAttempts) {
            $this->_helper->redirector->gotoRoute(array(), 'UserAuthLogin');
        }
    }

    /**
     * Login any role
     *
     * @param Zend_Form $form
     * @param Doctrine_Record $model
     */
    private function _login()
    {
        $this->view->form = $this->_getLoginForm();

        if ($this->getRequest()->isPost()) {
            if ($this->_getLoginForm()->isValid($this->getRequest()->getPost())) {

                $result = FinalView_Auth::getInstance()
                        ->authenticate(new User_Auth_Adapter(
                                        $this->_getLoginForm()->getValues(),
                                        $this->_getLoginAccount(),
                                        $this->storage_params
                                )
                );

                if ($result->getCode() !== Zend_Auth_Result::SUCCESS) {
                    $this->_increaseWrongLoginAttemptsCount();
                    $this->_getLoginForm()->addErrors($result->getMessages());
                } else {
                    // Login successfull
                    
                    $this->_resetWrongLoginAttemptsCount();
                    
                    // remember me for a 2 weeks
                    if ($this->_getLoginForm()->getElement('rememberMe')->isChecked()) {
                        $rememberMeModel = new User_Model_RememberMe();
                        $rememberMeModel->rememberUser($this->_getLoginAccount());
                    }
                }

                return $result->getCode();
            }
        }
    }

    protected function _getLoginAccount()
    {
        return Doctrine::getTable('User')->findOneByParams(array(
            'email' => $this->_getLoginForm()->getValue('email'),
            'role' => Roles::USER_FRONTEND
        ));
    }

    protected function _getLoginForm()
    {
        if (is_null($this->_loginForm)) {
            $this->_loginForm = new User_Form_Login(
                            array('backUrl' => $this->getRequest()->getParam('back_url')));
            $this->_loginForm->setAction($this->view->url(array(), 'UserAuthLogin'));
        }
        return $this->_loginForm;
    }

    /**
     * Logout
     *
     */
    //TODO: If user clicks Logout or logs in to the site from another PC then
    //      system requires to input login info on the first user’s PC
    public function logoutAction()
    {
        FinalView_Auth::getInstance()->clearIdentity();
        
        $rememberMeModel = new User_Model_RememberMe();
        $rememberMeModel->forgetRememberedUser();
        
        $this->_helper->redirector->gotoRoute(array(), 'UserAuthLogin');
    }

    /**
     * Forgot Password
     *
     */
    public function forgotPasswordAction()
    {

        if ($this->getRequest()->isPost()) {
            if ($this->_getForgotPswdForm()->isValid($this->getRequest()->getPost())) {

                $user = $this->_getForgotPswdAccount();
                if (false == $user->getConfirmation('forgot-password')) {
                    $user->createConfirmation('forgot-password');
                }
                $this->_sendForgotPasswordLetter($user);

                $this->_helper->redirector->gotoRoute(array(
                    'hash' => $user->getConfirmation('forgot-password')->hash
                        ), 'UserAuthForgotPasswordMailSent');
            }
        }

        $this->view->forgotPswdForm = $this->_getForgotPswdForm();
    }

    protected function _getForgotPswdAccount()
    {
        return Doctrine::getTable('User')->findOneByParams(array(
            'email' => $this->_getForgotPswdForm()->getValue('email'),
            'role' => Roles::USER
        ));
    }

    protected function _getForgotPswdForm()
    {
        if (is_null($this->_forgotPswdForm)) {
            $this->_forgotPswdForm = new User_Form_ForgotPswd;
        }
        return $this->_forgotPswdForm;
    }

    public function forgotPasswordMailSentAction()
    {
        
    }

    protected function _sendForgotPasswordLetter($user)
    {
        $mail = new FinalView_Mail(new FinalView_Mail_Template_Doctrine('user/forgot-password'), array(
                    'email' => $user->email,
                    'hash' => $user->getConfirmation('forgot-password')->hash,
                ));
        $mail->send($user->email, $user->email);
    }

    protected function _increaseWrongLoginAttemptsCount()
    {
        $this->_getSession()->wrongLoginAttemptsCount++;
    }

    protected function _resetWrongLoginAttemptsCount()
    {
        $this->_getSession()->wrongLoginAttemptsCount = 0;
    }

    protected function _getWrongLoginAttemptsCount()
    {
        return $this->_getSession()->wrongLoginAttemptsCount;
    }

    protected function _getSession()
    {
        if ($this->_session === null) {
            $this->_session = new Zend_Session_Namespace('Module_User');
        }
        return $this->_session;
    }

    protected function _getWrongLoginForm()
    {
        if ($this->_wrongLoginForm === null) {
            $this->_wrongLoginForm = new User_Form_WrongLogin();
        }

        return $this->_wrongLoginForm;
    }

}
