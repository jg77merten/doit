<?php

/**
 * User password change form
 * 
 * @copyright FinalView
 * @author gep aka <andrew.semikov@gmail.com>
 *
 */
class User_Form_Profile_ChangePswd extends Zend_Form
{

    protected $_user;

    public function init()
    {
        $element = new Zend_Form_Element_Password('old_password');

        extract(FinalView_Config::get('user', array('min_password_length', 'max_password_length')));
        $element
                ->setLabel('OLD_PASSWORD_FIELD_LABEL')
                ->setRequired(true)
                ->addValidator('StringLength', false, array(
                    $min_password_length, $max_password_length))
                ->setAttrib('renderPassword', true)
        ;
        $this->addElement($element);


        $element = new Zend_Form_Element_Password('new_password');
        $element
                ->setLabel('NEW_PASSWORD_FIELD_LABEL')
                ->setRequired(true)
                ->addValidator('StringLength', false, array(
                    $min_password_length, $max_password_length))
                ->setAttrib('renderPassword', true)
        ;
        $this->addElement($element);

        $element = new Zend_Form_Element_Password('new_password_confirm');
        $element
                ->setLabel('CONFIRM_PASSWORD_FIELD_LABEL')
                ->setRequired()
                ->addValidator(new FinalView_Validate_ContextIdentical('new_password'))
                ->setAttrib('renderPassword', true)
        ;

        $this->addElement($element);

        $element = new Zend_Form_Element_Submit('submit');
        $element
                ->setLabel('')
                ->setIgnore(true)
                ->setOrder(100)
        ;
        $this->addElement($element);
    }

    public function isValid($data)
    {
        if (!($valid = parent::isValid($data))) {
            return $valid;
        }

        if (!is_object($this->getUser())) {
            $this->addErrorMessage('No user given');
            return $valid = false;
        }

        if (!FinalView_Auth_Encrypt::compare($data['old_password'], $this->getUser()->password)) {
            $this->addErrorMessage('Old password is not correct');
            return $valid = false;
        }

        return $valid;
    }

    /**
     * set User for the form
     * @param User $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

    /**
     * Get User
     * @return User
     */
    public function getUser()
    {
        return $this->_user;
    }

}