<?php

class User_Form_Profile_UploadAvatar extends Zend_Form
{
    const SUBMIT_VALUE = 'profileUploadAvatar';

    function init()
    {
        $avatarConf = FinalView_Config::get('user', 'avatar');

        $avatar = new Zend_Form_Element_File('avatar');
        $avatar
                ->addFilter(new FinalView_Filter_File_SetUniqueName(array(
                    'prefix'        =>  'temp_',
                    'targetDir'     =>  FinalView_Config::get('user', 'upload_avatar_path')
                )))
                ->addValidator(new Zend_Validate_File_IsImage())
                ->addValidator(new Zend_Validate_File_ImageSize(array(
                            'minwidth' => $avatarConf['upload_min_width'],
                            'minheight' => $avatarConf['upload_min_height']))
                )
                ->setLabel('UPLOAD_AVATAR_FIELD_TEXT')
                ->setRequired();
        $this->addElement($avatar);

        $formId = new Zend_Form_Element_Hidden('formId');
        $formId->setValue(self::SUBMIT_VALUE);
        $this->addElement($formId);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('UPLOAD_AVATAR_SUBMIT_BUTTON');
        $this->addElement($submit);
    }

    public function isSubmitted($post)
    {
        return (array_key_exists('formId', $post) && $post['formId'] == self::SUBMIT_VALUE);
    }

}