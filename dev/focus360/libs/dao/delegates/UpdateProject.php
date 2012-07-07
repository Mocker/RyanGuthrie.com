<?php
/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class UpdateProject
{

	static $dataValidator;

	function __construct()
	{
	}



	/**
	 * 
	 * @param user
	 */
	static function execute( Project $project )
	{
		// Instantiate the AmazonSDB class
		$sdb = new AmazonSDB();
		// Store the name of the domain
		$domain = PROJECTS_DOMAIN;
		// Create the domain
		$project_domain = $sdb->domain_metadata( $domain );
		if ($project_domain->isOK())
		{
			//encode the user and put
			$projectObj = array();
			if( $project->appID == null || $project->appID == ""  ){
				$project->appID = uniqid();
				$projectObj2 = json_decode( $project->projectJSON );
				$projectObj2->{'applicationID'} = $project->appID;
				$project->projectJSON = json_encode( $projectObj2 );
			}else{
				//delete the item entirely from simpledb because the column list related to the JSON structure is dynamic
				//so if the new structure uses fewer columns than the previous one the old columns will corrupt the JSON data
				//since we don't know what the current structure is we can't drop the un-used colums, so we have to drop the record entirely
				$response = $sdb->delete_attributes( $domain, $project->appID );
				if (!$response->isOK())
				{
					return AwsUtils::parseError( $response );
				}
			}
			$projectObj["appID"] = $project->appID;
			$jsonChunk = DataValidator::mb_chunk_split_to_array( $project->projectJSON, 1000, "");
			$count = 1;
			foreach($jsonChunk as $chunk) {
			 	$projectObj[PROJECT_JSON_CHUNK_PREFIX.$count] = $chunk;
			 	$count += 1;
			}
			//$projectObj["projectJSON"] = $project->projectJSON;
			$projectObj["type"] = $project->type;
			$projectObj["client"] = $project->client;
			$projectObj["name"] = $project->name;
			$projectObj["createdOn"] = $project->createdOn;
			$projectObj["modifiedOn"] = $project->modifiedOn;
			$projectObj["description"] = $project->description;
			//check is the project assets directory exists, if not create it
			if( !is_dir( BASE_ASSET_DIRECTORY . $project->appID . "/" ) ){
				try{
					$base = BASE_ASSET_DIRECTORY . $project->appID . "/";
					$SiteMaps =  BASE_ASSET_DIRECTORY . $project->appID . "/".SINGLE_FAMILY_DIRECTORY."/";
					$MultiFamilySiteMaps =  BASE_ASSET_DIRECTORY . $project->appID . "/".MULTI_FAMILY_DIRECTORY."/";
					$FloorPlans =  BASE_ASSET_DIRECTORY . $project->appID . "/".FLOOR_PLAN_DIRECTORY."/";
					$BuildingPlates =  BASE_ASSET_DIRECTORY . $project->appID . "/".BUILDING_PLATE_DIRECTORY."/";
					$Exteriors =  BASE_ASSET_DIRECTORY . $project->appID . "/".EXTERIORS_DIRECTORY."/";
					$Illustrations =  BASE_ASSET_DIRECTORY . $project->appID . "/".ILLUSTRATIONS_DIRECTORY."/";
					$Interiors =  BASE_ASSET_DIRECTORY . $project->appID . "/".INTERIORS_DIRECTORY."/";
					$Videos =  BASE_ASSET_DIRECTORY . $project->appID . "/".VIDEOS_DIRECTORY."/";
					$Photos =  BASE_ASSET_DIRECTORY . $project->appID . "/".PHOTOS_DIRECTORY."/";
					$VacinityMaps =  BASE_ASSET_DIRECTORY . $project->appID . "/".VACINITY_MAPS_DIRECTORY."/";
					if (!mkdir($base, 0777, false)) {
    					return new CustomAWSError( 1040, "Could not create asset directory for the following appID: " . $project->appID );
					}
					if (!mkdir($SiteMaps, 0777, false)) {
    					return new CustomAWSError( 1040, "Could not create site maps directory for the following appID: " . $project->appID );
					}
					if (!mkdir($FloorPlans, 0777, false)) {
    					return new CustomAWSError( 1040, "Could not create floor plans directory for the following appID: " . $project->appID );
					}
					if (!mkdir($BuildingPlates, 0777, false)) {
    					return new CustomAWSError( 1040, "Could not create building plates directory for the following appID: " . $project->appID );
					}
					if (!mkdir($Exteriors, 0777, false)) {
    					return new CustomAWSError( 1040, "Could not create exteriors directory for the following appID: " . $project->appID );
					}
					if (!mkdir($Illustrations, 0777, false)) {
    					return new CustomAWSError( 1040, "Could not create illustrations directory for the following appID: " . $project->appID );
					}
					if (!mkdir($Interiors, 0777, false)) {
    					return new CustomAWSError( 1040, "Could not create interiors directory for the following appID: " . $project->appID );
					}
					if (!mkdir($Videos, 0777, false)) {
    					return new CustomAWSError( 1040, "Could not create videos directory for the following appID: " . $project->appID );
					}
					if (!mkdir($Photos, 0777, false)) {
    					return new CustomAWSError( 1040, "Could not create photos directory for the following appID: " . $project->appID );
					}
					if (!mkdir($VacinityMaps, 0777, false)) {
    					return new CustomAWSError( 1040, "Could not create vacinity maps directory for the following appID: " . $project->appID );
					}
				}catch( Exception $e ){
					return new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
				}
			}
			$response = $sdb->put_attributes( $domain, $project->appID, $projectObj, true );
			if ($response->isOK())
			{
				if( Cache::getInstance()->get($project->appID) != false ){
					//error_log("updating user in cache", 0);
					Cache::getInstance()->replace($project->appID, $project, false, Cache::$defaultTimeToLive);
				}else{
					//error_log("adding user in cache", 0);
					Cache::getInstance()->add($project->appID, $project, false, Cache::$defaultTimeToLive);
				}
				return $project;
			}else{
				return AwsUtils::parseError( $response );
			}
		}else{
			return AwsUtils::parseError( $project_domain );
		}
	}

}
?>