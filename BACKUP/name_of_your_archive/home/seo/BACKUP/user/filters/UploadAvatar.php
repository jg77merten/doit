<?php
class User_Filter_UploadAvatar extends Zend_Filter_Input
{
    public function __construct(qqUploadedFile $file)
    {
        $avatarConf = FinalView_Config::get('user', 'avatar');
        $extension = pathinfo($file->getName(), PATHINFO_EXTENSION);

        $isImage = new Zend_Validate_File_IsImage();
        $isImage->setMessages(array(
            Zend_Validate_File_IsImage::FALSE_TYPE      =>  'Not an image',
            Zend_Validate_File_IsImage::NOT_DETECTED    =>  'Cannot detect type of file',
            Zend_Validate_File_IsImage::NOT_READABLE    =>  'File not uploaded'
        ) );
        
        $isImageSize = new Zend_Validate_File_ImageSize(array(
                            'minwidth' => $avatarConf['upload_min_width'],
                            'minheight' => $avatarConf['upload_min_height']));
        $isImageSize->setMessages(array(
            Zend_Validate_File_ImageSize::WIDTH_TOO_BIG    => "Not correct width",
            Zend_Validate_File_ImageSize::WIDTH_TOO_SMALL  => "Not correct width",
            Zend_Validate_File_ImageSize::HEIGHT_TOO_BIG   => "Not correct height",
            Zend_Validate_File_ImageSize::HEIGHT_TOO_SMALL => "Not correct height",
            Zend_Validate_File_ImageSize::NOT_DETECTED     => "The size could not be detected",
            Zend_Validate_File_ImageSize::NOT_READABLE     => "File not uploaded or not readable",
        ));
        
        parent::__construct(array(
            'file'  =>  new FinalView_Filter_File_SetUniqueName(array(
                            'prefix'        =>  'temp_',
                            'targetDir'     =>  FinalView_Config::get('user', 'upload_avatar_path'),
                            'extension'     =>  $extension
            ))), array(
                'file'  =>  array(
                    $isImage,
                    $isImageSize
            )), array('file'  =>  $file->getTmpName() ));
    }
    
    public function getMessages()
    {
        $errors = parent::getMessages();
        
        return array_values($errors['file']);
    }
}