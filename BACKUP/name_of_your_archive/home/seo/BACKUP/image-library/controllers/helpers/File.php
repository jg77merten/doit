<?php

/**
 * @deprecated Use Utils::incrementFilename instead
 *
 */
class ImageLibrary_Controller_Helper_File extends Zend_Controller_Action_Helper_Abstract
{

	public function incrementName($directory, $filename)
	{
        $exists = false;

        do {

            if (file_exists($directory . $filename)) {
                $filename = $this->_incrementName($filename);
                $exists = true;
            } else {
                $exists = false;
            }

        } while ($exists);

        return $filename;
	}

    private function _incrementName($filename)
    {
        $original = pathinfo($filename, PATHINFO_FILENAME);

        $incremented = preg_replace('/(\[(\d+)\])+$/e', '"[" . $this->incremenet(\2) . "]"', $original);
        if ($incremented == $original) {
            $incremented = $original . '[1]';
        }

        return $incremented . '.' . pathinfo($filename, PATHINFO_EXTENSION);
    }

    public function incremenet($value)
    {
        return $value + 1;
    }

}