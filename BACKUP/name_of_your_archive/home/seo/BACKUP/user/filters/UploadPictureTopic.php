<?php
class User_Filter_UploadPictureTopic extends Zend_Filter_Input
{
    public function __construct(qqUploadedFile $file)
    {
        $extension = pathinfo($file->getName(), PATHINFO_EXTENSION);

        $isImage = new Zend_Validate_File_IsImage();
        $isImage->setMessages(array(
            Zend_Validate_File_IsImage::FALSE_TYPE      =>  'Not an image',
            Zend_Validate_File_IsImage::NOT_DETECTED    =>  'Cannot detect type of file',
            Zend_Validate_File_IsImage::NOT_READABLE    =>  'File not uploaded'
        ) );
        
        parent::__construct(array(
            'file'  =>  new FinalView_Filter_File_SetUniqueName(array(
                            'prefix'        =>  'temp_',
                            'targetDir'     =>  FinalView_Config::get('user', 'uploadPictureForTopicPath'),
                            'extension'     =>  $extension
            ))), array(
                'file'  =>  array(
                    $isImage
            )), array('file'  =>  $file->getTmpName() ));
    }
    
    public function getMessages()
    {
        $errors = parent::getMessages();
        
        return array_values($errors['file']);
    }
}