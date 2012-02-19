<?php

class ImageLibrary_Filter_EmptyFolder implements Zend_Filter_Interface
{

	/**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Zend_Filter_Exception If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
	{
		return '.' == $value ? '' : $value;
	}

}