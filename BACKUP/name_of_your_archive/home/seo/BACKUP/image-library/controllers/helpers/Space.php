<?php

/**
 * @deprecated See Customization
 */
class ImageLibrary_Controller_Helper_Space
	extends Zend_Controller_Action_Helper_Abstract
{

	const TOTAL_SPACE = '1074790400'; // ~ 1025MB 

	public function direct($directory)
	{
		return $this->getOccupied($directory);
	}

	public function getOccupied($directory)
	{
		return array
		(
			'used'	=> $this->_getOccupied($directory),
			'total' => self::TOTAL_SPACE,
		);
	}

	private function _getOccupied($dir_name)
	{
		$dir_size =0;

		if (is_dir($dir_name)) {
			if ($dh = opendir($dir_name)) {
				while (($file = readdir($dh)) !== false) {
					if($file != "." && $file != "..") {
						if(is_file($dir_name."/".$file)){
						   $dir_size += filesize($dir_name."/".$file);
						}
						/* check for any new directory inside this directory */
						if(is_dir($dir_name."/".$file)) {
							$dir_size +=  $this->_getOccupied($dir_name."/".$file);
						}
					}
				}
			}

			closedir($dh);
		}

		return $dir_size;
	}

}

