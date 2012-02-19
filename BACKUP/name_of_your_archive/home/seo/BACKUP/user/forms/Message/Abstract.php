<?php

abstract class User_Form_Message_Abstract extends Zend_Form
{

    protected $_configPath = null;

    public function __construct($options = null)
    {
        $this->_configPath =
                APPLICATION_PATH .
                DIRECTORY_SEPARATOR .
                'modules' .
                DIRECTORY_SEPARATOR .
                'user' .
                DIRECTORY_SEPARATOR .
                'config_topic.ini';

        parent::__construct($options);
    }

    public function init()
    {
        $maxTopicContentsLength = FinalView_Config::get('user', 'maxTopicContentsLength');

        $contents = new Zend_Form_Element_Textarea('contents');
        $contents
                ->addValidator(new Zend_Validate_StringLength(array(
                            'max' => $maxTopicContentsLength
                        )))
                ->setRequired(FALSE);
        $this->addElement($contents);

        $private = new Zend_Form_Element_Checkbox('private');
        $private->setLabel('Private:')
                ->setAttrib('class', 'privateTopicCheck');
        $this->addElement($private);


        $incognito = new Zend_Form_Element_Checkbox('incognito');
        $incognito->setLabel('Post as incognito');
        $this->addElement($incognito);


        $type = new Zend_Form_Element_Hidden('topic_type');
        $type->setValue($this->getTopicType());
        $this->addElement($type);

// for friends popup
        $popup = new Zend_Form_Element_Multiselect('friends',
                        array('disableLoadDefaultDecorators' => true)
        );
        $popup
                ->setAttrib('class', 'hide')
                ->setRequired(false)
                ->addDecorators(array('viewHelper', 'errors'))
                ->setRegisterInArrayValidator(false)
                ->addValidator(new FinalView_Validate_Db_RecordExists('User', 'ids'));
        $this->addElement($popup);

        //jquery.form error: elements must not have name or id of "submit"
        $submit = new Zend_Form_Element_Submit('topic_submit');
        $submit->setLabel('');
        $this->addElement($submit);
    }
    
    public function getMessages()
    {
        $messages = parent::getMessages();
        
        $mess = array();
        foreach ($messages as $key => $value) {
            $mess = array_merge($mess, array_values($value));
        }
        
        return $mess;
    }

    abstract public function getTopicType();
}