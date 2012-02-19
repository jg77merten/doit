<?php
class User_Form_Message_Statement extends User_Form_Message_Abstract {

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
        if ($config->statement) {
            $input = new Zend_Filter_Input(array(), array('*' => new Zend_Validate_LessThan($config->statement)));

            $input->setData(array($this->_user->getTopicsByType($data['topic_type'])->count()));

            if ($input->isValid() == false) {

                $this->getElement('contents')->addError("You have exceeded the limit for this type of topics.");
                return false;
            }
        }

        return parent::isValid($data);
    }
    
    public function getTopicType()
    {
        return 'statement';
    }
}