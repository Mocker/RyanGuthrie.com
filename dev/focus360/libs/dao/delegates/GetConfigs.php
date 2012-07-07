<?php
/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class GetConfigs
{

	static $dataValidator;

	function __construct()
	{
	}



	/**
	 * 
	 * @param user
	 */
	static function execute()
	{
		if( Cache::getInstance()->get( CONFIG_LIST_CACHE_KEY ) != false ){
			//error_log("updating user in cache", 0);
			return Cache::getInstance()->get( CONFIG_LIST_CACHE_KEY );
		}
		// Instantiate the AmazonSDB class
		$sdb = new AmazonSDB();
		// Store the name of the domain
		$domain = CONFIG_DOMAIN;
		$results = array();
		// Read the domain
		$project_domain = $sdb->domain_metadata( $domain );
		if ($project_domain->isOK())
		{
			//create the query
			$sql = "select * from " . CONFIG_DOMAIN;
			$response = $sdb->select( $sql );
			if ($response->isOK())
			{
				$response = json_decode( json_encode( $response ) );
			}else{
				return AwsUtils::parseError( $response );
			}
		}else{
			return AwsUtils::parseError( $user_domain );
		}
		if( is_array( $response->{'body'}->{'SelectResult'}->{'Item'} ) ){
			foreach( $response->{'body'}->{'SelectResult'}->{'Item'} as $configObj ){
				$config = new Config();
				foreach( $configObj->{'Attribute'} as $itemObj ){
					$config->{ $itemObj->{'Name'} } = $itemObj->{'Value'};
				}
				$results[$config->appID] = $config;
			}
		}else{
			$config = new Config();
			if( $response->{'body'}->{'SelectResult'}->{'Item'}->{'Attribute'} == null ){
				return new CustomAWSError( 1010, "No config records found." );
			}
			foreach( $response->{'body'}->{'SelectResult'}->{'Item'}->{'Attribute'} as $itemObj ){
				$config->{ $itemObj->{'Name'} } = $itemObj->{'Value'};
			}
			$results[$config->appID] = $config;
		}
		if( Cache::getInstance()->get( CONFIG_LIST_CACHE_KEY ) != false ){
			//error_log("updating user in cache", 0);
			Cache::getInstance()->replace( CONFIG_LIST_CACHE_KEY, $results, false, Cache::$defaultTimeToLive );
		}else{
			//error_log("adding user in cache", 0);
			Cache::getInstance()->add(CONFIG_LIST_CACHE_KEY, $results, false, Cache::$defaultTimeToLive);
		}
		return $results;
	}

}
?>