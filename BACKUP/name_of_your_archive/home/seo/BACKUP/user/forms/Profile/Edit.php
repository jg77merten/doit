<?php

class User_Form_Profile_Edit extends Zend_Form
{
    const SUBMIT_VALUE = 'profileEdit';

    function init()
    {
        $maxFullnameLength = FinalView_Config::get('user', 'maxFullnameLength');
        $fullname = new Zend_Form_Element_Text('fullname');
        $fullname->setLabel('PROFILE_FULL_NAME_TEXT')
                ->addValidator(new Zend_Validate_StringLength(array('max' => $maxFullnameLength)));
        $this->addElement($fullname);

        $maxUsernameLength = FinalView_Config::get('user', 'maxUsernameLength');
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('PROFILE_USERNAME_TEXT')
                ->addValidator(new Zend_Validate_StringLength(array('max' => $maxUsernameLength)))
                ->setRequired();
        $this->addElement($username);

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('PROFILE_EMAIL_TEXT')
                ->addValidator(new Zend_Validate_EmailAddress())
                ->setRequired();
        $this->addElement($email);


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

        $formId = new Zend_Form_Element_Hidden('formId');
        $formId->setValue(self::SUBMIT_VALUE);
        $this->addElement($formId);

        $submit = new Zend_Form_Element_Button('submit', array('type' => 'submit'));
        $submit->setLabel('');
        $this->addElement($submit);
    }

    public function isSubmitted($post)
    {
        return (array_key_exists('formId', $post) && $post['formId'] == self::SUBMIT_VALUE);
    }

}