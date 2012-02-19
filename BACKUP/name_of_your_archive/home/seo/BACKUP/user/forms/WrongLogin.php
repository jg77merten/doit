<?php

class User_Form_WrongLogin extends Zend_Form
{
    
    function init()
    {
        $reCaptchaOptions = FinalView_Config::get('user', 'captcha');
        $recaptcha = new Zend_Form_Element_Captcha('captcha',
                        array('captcha' => 'ReCaptcha',
                            'captchaOptions' => array(
                                'service' => 
                                new Zend_Service_ReCaptcha($reCaptchaOptions['publickey'], $reCaptchaOptions['privatekey'])
                        )));
        $recaptcha->setLabel('Captcha');
        $this->addElement($recaptcha);

        $submit = new Zend_Form_Element_Submit('submitWronglogin');
        $submit->setLabel('WRONG_LOGIN_BUTTON_TEXT');
        $this->addElement($submit);
    }

}