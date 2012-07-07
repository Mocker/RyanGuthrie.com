<?php

/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:48 PM
 */
class ProjectAsset extends Focus360Base
{

	const SINGLE_FAMILY_SITE_MAP = "SFSM";
	const MULTI_FAMILY_SITE_MAP = "MFSM";
	const FLOOR_PLAN = "FLP";
	const BUILDING_PLATE = "BLP";
	const VIDEO = "VID";
	const ILLUSTRATION = "ILL";
	const RENDERING = "RND";
	const ELEVATION = "ELV";
	const ICON = "ICO";
	const VACINITY_MAP = "VMP";
		
	public $url;
	public $name;
	public $dbKey;
	public $type;

	function __construct( $url = null, $name = null, $dbKey = null, $type = null,  $id = null, $createdOn = null, $modifiedOn = null, $description = null  )
	{
		$this->url = $url;
		$this->name = $name;
		$this->dbKey = $dbKey;
		$this->type = $type;
		parent::__construct( $id, $createdOn, $modifiedOn, $description );
	}



}
?>