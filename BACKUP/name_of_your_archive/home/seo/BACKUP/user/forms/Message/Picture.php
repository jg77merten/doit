<?php

class User_Form_Message_Picture extends User_Form_Message_Abstract
{

    private $_user;

    public function __construct($user = null, $options = null)
    {
        $this->_user = $user;

        parent::__construct($options);
    }

    public function isValid($data)
    {
        $config = FinalView_Config::factory($this->_configPath);

        // 0 - unlimited topics
        if ($config->picture) {
            $input = new Zend_Filter_Input(array(), array('*' => new Zend_Validate_LessThan($config->picture)));

            $input->setData(array($this->_user->getTopicsByType($data['topic_type'])->count()));

            if ($input->isValid() == false) {

                $this->getElement('contents')->addError("You have exceeded the limit for this type of topics.");
                return false;
            }
        }

        return parent::isValid($data);
    }

    public function init()
    {
        $image = new Zend_Form_Element_Hidden('picture_path');
        $image->addFilter(new FinalView_Filter_SrcToPath());
        $image->addValidator(new Zend_Validate_Callback(array(
            'callback'  =>  array($this, 'isValidPictureSrc')
        )));
        $image->setRequired(true);
        
        $this->addElement($image);
        parent::init();
    }

    public function getTopicType()
    {
        return 'picture_topic';
    }
    
    public function isValidPictureSrc($picture_path)
    {
        $fName = basename($picture_path);
        if (($pos = strpos($fName,'temp_')) === false || $pos > 0) {
            return false;
        }
        
        $isImage = new Zend_Validate_File_IsImage();
        $isImage->setMessages(array(
            Zend_Validate_File_IsImage::FALSE_TYPE      =>  'Not an image',
            Zend_Validate_File_IsImage::NOT_DETECTED    =>  'Cannot detect type of file',
            Zend_Validate_File_IsImage::NOT_READABLE    =>  'File not uploaded'
        ) );

        return $isImage->isValid($picture_path);
    }

}