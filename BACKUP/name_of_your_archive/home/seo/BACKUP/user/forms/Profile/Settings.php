<?php

class User_Form_Profile_Settings extends Zend_Form
{

    private $_user;

    function init()
    {
        $timezonesTable = Doctrine::getTable('Timezone');
        $doctrineTimezoneCollection = $timezonesTable->findAll();
        $doctrineTimezoneValues = $doctrineTimezoneCollection->toKeyValueArray('id', 'name');

        $timezone = new Zend_Form_Element_Select('timezone_id');
        $timezone->setMultiOptions($doctrineTimezoneValues)
                ->setLabel('PROFILE_TIMEZONE_TEXT');
        $this->addElement($timezone);

        $doctrineStateValues = array_merge(
                array('0' => '- - - Select State - - -'), Doctrine::getTable('GeoState')->findAll()->toKeyValueArray('code', 'name')
        );
        $state = new Zend_Form_Element_Select('state_code');
        $state->setMultiOptions($doctrineStateValues)
                ->addFilter(new Zend_Filter_Null())
                ->setLabel('PROFILE_STATE_TEXT');
        $this->addElement($state);

        $network = new ZendX_JQuery_Form_Element_AutoComplete(
                        'network_id', array('label' => 'Networks')
        );

        $state_code = '';
        if (isset($this->_user->state_code) && $this->_user->state_code != null) {
            $state_code = $this->_user->state_code;
        }
        $network->setJQueryParams(array('source' =>
            array_values(Doctrine::getTable('Network')->findByStateCode($state_code)->toKeyValueArray('id', 'title'))
        ));
        $network->setRequired(false);
//         $network->addValidator(new FinalView_Validate_Db_RecordExists('Network', 'title'));
        $network->getPluginLoader(Zend_Form_Element::DECORATOR)->addPrefixPath('FinalView_Form_Decorator', 'FinalView/Form/Decorator');
        $this->addElement($network);

        $doctrineAccessTypeValues = Doctrine::getTable('User')->getEnumValues('access_type');
        $availableAccessTypeValues = array();
        foreach ($doctrineAccessTypeValues as $key => $value) {
            $availableAccessTypeValues[$value] = ucfirst($value);
        }

        $accessType = new Zend_Form_Element_Radio('access_type');
        $accessType->setLabel('PROFILE_ACCESS_TYPE_TEXT')
                ->setMultiOptions($availableAccessTypeValues);
        $this->addElement($accessType);

        $iniPath =
                APPLICATION_PATH .
                DIRECTORY_SEPARATOR .
                'modules' .
                DIRECTORY_SEPARATOR .
                'user' . DIRECTORY_SEPARATOR . 'config_topic.ini';

        $config = new Zend_Config_Ini($iniPath);

        $maxAvailableTopicsInBlokkspot = $config->active;
        $maxTopicsInBlokkSpotValues = array();
        for ($i = 1; $i <= $maxAvailableTopicsInBlokkspot; $i++) {
            $maxTopicsInBlokkSpotValues[$i] = $i;
        }

        $maxTopicsInBlokkSpot = new Zend_Form_Element_Select('max_in_blokk_spot');
        $maxTopicsInBlokkSpot->setLabel('PROFILE_MAX_TOPICS_IN_BLOKK_SPOT')
                ->setMultiOptions($maxTopicsInBlokkSpotValues);
        $this->addElement($maxTopicsInBlokkSpot);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('');
        $this->addElement($submit);
    }

    public function isValid($data)
    {
        $validator = new Zend_Validate_GreaterThan(0);

        $validator->setMessages(array(
            Zend_Validate_GreaterThan::NOT_GREATER => 'NETWORK_NOT_FOUND'
        ));

        $this->getElement('network_id')
                ->addValidator($validator)
                ->addFilter(new Zend_Filter_Callback(array(
                            'callback' => array($this, 'getNetworkIdByTitleAndState'),
                            'options' => array('state' => @$data['state_code'])
                        )));

        $isValid = parent::isValid($data);
        return $isValid;
    }

    public function getNetworkIdByTitleAndState($title, $state)
    {
        $network = Doctrine::getTable('Network')->findOneByParams(array(
            'title' => $title,
            'state_code' => $state
                ));

        return $network ? $network->id : ($title === '' ? null : 0);
    }

    public function setDefault($name, $value)
    {
        if ($name === 'network_id' && !empty($value)) {
            $network = Doctrine::getTable('Network')->findOneById($value);
            $value = (bool) $network ? $network->title : '';
        }

        return parent::setDefault($name, $value);
    }

    public function setUser($user)
    {
        $this->_user = $user;
        return $this;
    }

}