<?php

class User_Form_Login extends Zend_Form 
{
    public $backUrl;
    
    public function setBackUrl($val) 
    {
        $this->backUrl = $val;
    }
    
    public function getBackUrl($val) 
    {
        
        return $this->backUrl;
    }
    
    /**
     * Set form action
     *
     * @param  string $action
     * @return Zend_Form
     */
    public function setAction($action) 
    {
        if ($this->backUrl) {
            $addToUrlHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('AddToUrl');
            $action = $addToUrlHelper->addToUrl(array('back_url' => $this->backUrl), $action);
        }
        
        parent::setAction($action);
    }
        
    /**
     * Initialize form (used by extending classes)
     * 
     * @return void
     */
    public function init()
    {
        parent::init();

        // Email
        $element = new Zend_Form_Element_Text('email');
        $element
            ->setLabel('EMAIL_FIELD_LABEL')
            ->setRequired()
            ->setAttrib('class','rc')
            ->addFilters(array('StringTrim'))
            ->addValidator('EmailAddress')
            ;
        $this->addElement($element);
        
        // Password
        $min_password_length = null; 
        $max_password_length = null;
        extract(FinalView_Config::get('user', 
            array('min_password_length', 'max_password_length')));
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
        
        // Remember me
        $rememberMe = new Zend_Form_Element_Checkbox('rememberMe');
        $rememberMe->setLabel('REMEMBER_ME_TEXT');
        $this->addElement($rememberMe);
        
        // Submit
        $element = new Zend_Form_Element_Submit('submit');
        $element
            ->setLabel('Log in')
            ->setIgnore(true)
            ;
        $this->addElement($element);
        
        // Decorators
        $this->addPrefixPath('FinalView_Form_Decorator', 'FinalView/Form/Decorator', Zend_Form::DECORATOR);
        $this->loadDefaultDecorators();
        $this->addDecorator('FvformErrors');
        $this->getDecorator('FvformErrors')->setOption('placement', Zend_Form_Decorator_Abstract::PREPEND);      
    }
    
}
