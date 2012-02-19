<?php

class ImageLibrary_Filter_Search implements Zend_Filter_Interface
{

	private $_token;

	public function __construct($token)
	{
		$this->_token = $token;
	}

	/**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Zend_Filter_Exception If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
	{
		if (0 === strcasecmp($value, $this->_token)) {
			$value = '';
		}

		return $value;
	}

}