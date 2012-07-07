<?php

/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:48 PM
 */
class Config extends Focus360Base
{

	public $appID;
	public $configJSON;

	function __construct( $appID = null, $configJSON = null, $id = null, $createdOn = null, $modifiedOn = null, $description = null  )
	{
		$this->appID = $appID;
		$this->configJSON = $configJSON;
		parent::__construct( $id, $createdOn, $modifiedOn, $description );
	}



}
?>