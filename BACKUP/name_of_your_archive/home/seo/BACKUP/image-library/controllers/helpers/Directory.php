<?php

class ImageLibrary_Controller_Helper_Directory
	extends Zend_Controller_Action_Helper_Abstract
{

	const ROOT_DIR_NAME = 'root';
	const ROOT_DIR_URL = '.';

	public function direct($directory, $recursive = false)
	{
		return $this->getFiles($directory, $recursive);
	}

	public function getFiles($directory, $recursive = false)
	{
		return $this->_getFiles($directory, $recursive);
	}

	public function getFilesOnly($directory, $recursive = false)
	{
		$files = array_filter
		(
			$this->_getFiles($directory, $recursive),
			create_function('$file', 'return is_file($file);')
		);

		return $files;
	}

	public function getDirs($directory, $active = self::ROOT_DIR_URL)
	{
		$active = urldecode($active);
		$dirs = array();

		$files = array_filter
		(
			$this->_getFiles($directory),
			create_function('$file', 'return is_dir($file);')
		);
		foreach ($files as $dir) {
			array_push($dirs, array(
				'name'		=> $dir,
				'url'		=> $dir,
				'active'	=> $active == pcgbasename($dir),
			));
		}

		array_unshift($dirs, array(
			'name'		=> self::ROOT_DIR_NAME,
			'url'		=> '.',
			'active'	=> $active == self::ROOT_DIR_URL,
			));

		return $dirs;
	}

	private function _getFiles($directory, $recursive = false)
	{
		$directory = urldecode($directory);
		if (!is_dir($directory)) {
			throw new ImageLibrary_Exception_Directory('Directory does not exist');
		}

		$set = array();

		$this->__getFiles($directory, $set, $recursive);

		sort($set);
		
		return $set;
	}

	private function __getFiles($directory, &$set, $recursive = false)
	{
		$directory = rtrim($directory, '/') . '/';
		$resource = dir($directory);

		while (false !== ($file = $resource->read())) {
			if ('..' == $file || '.' == $file) {
				continue;
			}

			$file = $directory . $file;
			if (is_dir($file) && $recursive) {
				$this->__getFiles($file, $set, $recursive);
			}
			
			array_push($set, $file);
		}

		$resource->close();
	}

}