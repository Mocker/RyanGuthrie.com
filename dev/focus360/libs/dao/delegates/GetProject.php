<?php
/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class GetProject
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
		if( Cache::getInstance()->get( $project->appID ) != false ){
			//error_log("updating user in cache", 0);
			return Cache::getInstance()->get( $project->appID );
		}
		// Instantiate the AmazonSDB class
		$sdb = new AmazonSDB();
		// Store the name of the domain
		$domain = PROJECTS_DOMAIN;
		$results = array();
		// Read the domain
		$project_domain = $sdb->domain_metadata( $domain );
		if ($project_domain->isOK())
		{
			//retrieve project from simpledb
			$response = $sdb->get_attributes( $domain, $project->appID );
			if ($response->isOK())
			{
				$response = json_decode( json_encode( $response ) );
			}else{
				return AwsUtils::parseError( $response );
			}
		}else{
			return AwsUtils::parseError( $project_domain );
		}
		if( is_array( $response->{'body'}->{'SelectResult'}->{'Item'} ) ){
			//return error, should onlt be one
			return new CustomAWSError( 1001, "Multiple projects were found with the same appID. Please contact your system administrator with the following appID: " . $project->appID );
		}else{
			$returnProject = new Project();
			$returnProject->appID = $project->appID;
			$projectJSONArray = array();
			$projectJSON = '';
			if( $response->{'body'}->{'GetAttributesResult'}->{'Attribute'} == null ){
				return new CustomAWSError( 1012, "No project data found for the following appID: " . $project->appID );
			}
			foreach( $response->{'body'}->{'GetAttributesResult'}->{'Attribute'}  as $itemObj ){
				$columnName = explode( "_", $itemObj->{'Name'} );
				if( $columnName[0] == 'projectJSON' ){
					if( $columnName[1] > 0 ){
						$projectJSONArray[ $columnName[1] ] = $itemObj->{'Value'};
					}
				}else{
					$returnProject->{ $itemObj->{'Name'} } = $itemObj->{'Value'};
				}
			}
			ksort( $projectJSONArray );
			foreach( $projectJSONArray as $jsonChunk ){
				$projectJSON .= $jsonChunk;
			}
			$returnProject->projectJSON = $projectJSON;
			$projectObj = json_decode( $returnProject->projectJSON );
			$projectObj->{'applicationID'} = $returnProject->appID;
			$projectObj->{'ftpLocation'} = BASE_URL . BASE_ASSET_DIRECTORY . $project->appID . "/";
			$returnProject->projectJSON = json_encode( $projectObj );
		}
		if( Cache::getInstance()->get( $returnProject->appID ) != false ){
			//error_log("updating user in cache", 0);
			Cache::getInstance()->replace( $returnProject->appID, $returnProject, false, Cache::$defaultTimeToLive );
		}else{
			//error_log("adding user in cache", 0);
			Cache::getInstance()->add($returnProject->appID, $returnProject, false, Cache::$defaultTimeToLive);
		}
		return $returnProject;
	}

}
?>