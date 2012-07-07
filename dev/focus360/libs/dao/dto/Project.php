<?php

/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:48 PM
 */
class Project extends Focus360Base
{

	public $appID;
	public $name;
	public $projectJSON;
	public $type;
	public $client;
	public $ftpLocation;

	function __construct( $appID = null, $name = null, $projectJSON = null, $type = null,  $id = null, $createdOn = null, $modifiedOn = null, $description = null, $client = null, $ftpLocation = null  )
	{
		$this->appID = $appID;
		$this->name = $name;
		$this->projectJSON = $projectJSON;
		$this->type = $type;
		$this->client = $client;
		$this->ftpLocation = $ftpLocation;
		parent::__construct( $id, $createdOn, $modifiedOn, $description );
	}



}
?>