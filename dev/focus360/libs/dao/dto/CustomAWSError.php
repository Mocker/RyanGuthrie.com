<?php


/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class CustomAWSError
{
	public $code;
	public $message;
	public $errors;
	
	function __construct($code = null, $message = null )
	{
		$this->code = $code;
		$this->message = $message;
		$this->errors = array();
	}



}
?>