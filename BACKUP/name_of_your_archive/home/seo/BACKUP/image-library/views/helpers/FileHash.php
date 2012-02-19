<?php

class ImageLibrary_View_Helper_FileHash extends Zend_View_Helper_Abstract
{

    public function fileHash($filename)
    {
        return md5_file($filename);
    }

}