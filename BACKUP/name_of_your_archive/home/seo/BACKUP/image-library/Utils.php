<?php
require_once APPLICATION_PATH . '/Utils.php';

abstract class ImageLibrary_Utils 
{
    
    static public function getDir($sitegift_id, $system = false)
    {
        return Utils::getDir(sprintf('media/sitegift/%d/image-gallery/', $sitegift_id), $system);
    }
    
    static public function removeFile($sitegift_id, $file)
    {
        if ('.' == pathinfo($file, PATHINFO_DIRNAME)) {
            $file = self::getCoverDir($sitegift_id, true) . $file;
        }
        Utils::removeFile($file);
    }

	static public function getFolderImage()
	{
		return 'images/image-library/folder.png';
	}
    
}