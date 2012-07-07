<?php


/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class CustomAwsResponse
{
	public $requestID;
	public $boxUsage;
	
	function __construct($requestID = null, $boxUsage = null )
	{
		$this->requestID = $requestID;
		$this->boxUsage = $boxUsage;
	}



}
?>