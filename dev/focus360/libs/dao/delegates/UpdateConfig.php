<?php
/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class UpdateConfig
{

	static $dataValidator;

	function __construct()
	{
	}



	/**
	 * 
	 * @param user
	 */
	static function execute( Config $config )
	{
		// Instantiate the AmazonSDB class
		$sdb = new AmazonSDB();
		// Store the name of the domain
		$domain = CONFIG_DOMAIN;
		// Create the domain
		$config_domain = $sdb->domain_metadata( $domain );
		if ($config_domain->isOK())
		{
			//encode the user and put
			$configObj = array();
			if( $config->appID == null || $config->appID == "" ){
				$config->appID = uniqid();
			}
			$configObj["appID"] = $config->appID;
			$configObj["configJSON"] = $config->configJSON;
			$response = $sdb->put_attributes( $domain, $config->appID, $configObj, true );
			if ($response->isOK())
			{
				if( Cache::getInstance()->get( "config" . $config->appID) != false ){
					//error_log("updating user in cache", 0);
					Cache::getInstance()->replace( "config" . $config->appID, $config, false, Cache::$defaultTimeToLive);
				}else{
					//error_log("adding user in cache", 0);
					Cache::getInstance()->add( "config" . $config->appID, $config, false, Cache::$defaultTimeToLive);
				}
				return AwsUtils::parseResponse( $response );
			}else{
				return AwsUtils::parseError( $response );
			}
		}else{
			return AwsUtils::parseError( $config_domain );
		}
	}

}
?>