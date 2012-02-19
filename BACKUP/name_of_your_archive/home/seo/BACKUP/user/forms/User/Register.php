<?php

class User_Form_User_Register extends User_Form_User_Abstract
{
    const NOT_UNIQUE_PASSWORDS = 'NOT_UNIQUE_PASSWORDS';

    /**
     * Initialize form (used by extending classes)
     * 
     * @return void
     */
    public function init()
    {
        parent::init();

        $element = new Zend_Form_Element_Text('username');
        $element
                ->setLabel('USERNAME_FIELD_LABEL')
                ->setRequired()
                ->setAttrib('class','rc')
                ->addFilters(array('StringTrim'))
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 16))
                ->addValidator(new FinalView_Validate_Db_NoRecordExists('User', 'username'))
        ;
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('fullname');
        $element
                ->setLabel('FULLNAME_FIELD_LABEL')
                ->setAttrib('class','rc')
                ->addFilters(array('StringTrim'))
                ->addValidator('StringLength', false, array('min' => 0, 'max' => 25))
        ;
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('email');
        $element
                ->setLabel('EMAIL_FIELD_LABEL')
                ->setRequired()
                ->setAttrib('class','rc')
                ->addFilters(array('StringTrim'))
                ->addValidator('EmailAddress')
        ;
        $element->addValidator(
                new FinalView_Validate_Db_NoRecordExists('User', 'email'));

        $this->addElement($element);

        extract(FinalView_Config::get('user', array('min_password_length', 'max_password_length')));
        $element = new Zend_Form_Element_Password('password');
        $element
                ->setLabel('PASSWORD_FIELD_LABEL')
                ->setRequired()
                ->setAttrib('class','rc')
                ->addValidator('StringLength', false, array(
                    $min_password_length, $max_password_length))
                ->setAttrib('renderPassword', true)
        ;
        $this->addElement($element);

        // password confirm
        $validate = new FinalView_Validate_ContextIdentical('password');

        $element = new Zend_Form_Element_Password('password_confirm');
        $element
                ->setLabel('CONFIRM_PASSWORD_FIELD_LABEL')
                ->setRequired()
                ->setAttrib('class','rc')
                ->addValidator($validate)
                ->setAttrib('renderPassword', true)
        ;
        $this->addElement($element);

        $ReCaptchaOptions = FinalView_Config::get('user', 'captcha');
        $element = new Zend_Form_Element_Captcha('captcha',
                        array('captcha' => 'ReCaptcha',
                            'captchaOptions' => array('theme' => 'white', 'service' => new Zend_Service_ReCaptcha($ReCaptchaOptions['publickey'],
                                        $ReCaptchaOptions['privatekey']))));
        $element->setLabel('Captcha');

        $this->addElement($element);

        $element = new Zend_Form_Element_Submit('submit');
        $element
                ->setLabel('REGISTER_BUTTON_TEXT')
                ->setIgnore(true)
                ->setOrder(100)
        ;
        $this->addElement($element);
    }

}
