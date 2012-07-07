<?php

/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:48 PM
 */
class Focus360Base
{

	public $id;
	public $createdOn;
	public $modifiedOn;
	public $description;

	function __construct( $id = null, $createdOn = null, $modifiedOn = null, $description = null )
	{
		$this->id = $id;
		$this->createdOn = $createdOn;
		$this->modifiedOn = $modifiedOn;
		$this->description = $description;
	}



}
?>