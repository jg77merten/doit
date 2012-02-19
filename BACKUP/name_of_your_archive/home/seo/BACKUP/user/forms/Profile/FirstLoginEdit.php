<?php

class User_Form_Profile_FirstLoginEdit extends Zend_Form
{
    const SUBMIT_VALUE = 'profileEdit';

    private $_user;

    public function __construct($user = null, $options = null)
    {
        $this->_user = $user;
        parent::__construct($options);
    }

    function init()
    {
        $doctrineSexValues = Doctrine::getTable('User')->getEnumValues('sex');

        $availableSexValues = array();
        foreach ($doctrineSexValues as $key => $value) {
            $availableSexValues[$value] = ucfirst($value);
        }

        $sex = new Zend_Form_Element_Select('sex');
        $sex->setLabel('PROFILE_SEX_TEXT')
                ->addMultiOptions($availableSexValues);
        $this->addElement($sex);

        $doctrineRaceValues = Doctrine::getTable('User')->getEnumValues('race');

        $availableRaceValues = array();
        foreach ($doctrineRaceValues as $key => $value) {
            $availableRaceValues[$value] = ucfirst($value);
        }

        $race = new Zend_Form_Element_Select('race');
        $race->setLabel('PROFILE_RACE_TEXT')
                ->addMultiOptions($availableRaceValues);
        $this->addElement($race);

        $birthDate = new FinalView_Form_Element_Date('birthdate', array('start_year' => 1970));
        $birthDate->setLabel('PROFILE_BIRTH_DATE_TEXT');
        $this->addElement($birthDate);

        $timezonesTable = Doctrine::getTable('Timezone');
        $doctrineTimezoneCollection = $timezonesTable->findAll();
        $doctrineTimezoneValues = $doctrineTimezoneCollection->toKeyValueArray('id', 'name');
        $utcId = $timezonesTable->findOneByTzLabel('UTC')->id;

        $doctrineTimezoneValues = array_merge(
                array('0' => '- - - Select Time Zone - - -'), $doctrineTimezoneValues
        );

        $timezone = new Zend_Form_Element_Select('timezone_id');
        $timezone->setLabel('PROFILE_TIMEZONE_TEXT')
                // more elegant? UTC
                ->addFilter(new Zend_Filter_PregReplace(array('match' => '/0/',
                            'replace' => $utcId)))
                ->setMultiOptions($doctrineTimezoneValues);
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
        $network->getPluginLoader(Zend_Form_Element::DECORATOR)->addPrefixPath('FinalView_Form_Decorator', 'FinalView/Form/Decorator');
        $this->addElement($network);

        $formId = new Zend_Form_Element_Hidden('formId');
        $formId->setValue(self::SUBMIT_VALUE);
        $this->addElement($formId);


        $submit = new Zend_Form_Element_Button('submit', array('type' => 'submit'));
        $submit->setValue(1111)
                ->setLabel('PROFILE_SUBMIT_TEXT');
        $this->addElement($submit);

        $this->setDefaults(array('submit' => '111'));
    }

    public function isSubmitted($post)
    {
        return (array_key_exists('formId', $post) && $post['formId'] == self::SUBMIT_VALUE);
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

}