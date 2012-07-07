<?php
/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class DeleteProject
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
			//delete the item entirely from simpledb
			$response = $sdb->delete_attributes( $domain, $project->appID );
			if ($response->isOK())
			{
				if( Cache::getInstance()->get( $project->appID ) != false ){
					//error_log("updating project in cache", 0);
					Cache::getInstance()->delete( $project->appID );
				}
				//invalidate teh project list for future requests
				if( Cache::getInstance()->get( PROJECT_LIST_CACHE_KEY ) != false ){
					//error_log("updating project list in cache", 0);
					Cache::getInstance()->delete( PROJECT_LIST_CACHE_KEY );
				}
				return AwsUtils::parseResponse( $response );
			}else{
				return AwsUtils::parseError( $response );
			}
		}else{
			return AwsUtils::parseError( $project_domain );
		}
	}

}
?>