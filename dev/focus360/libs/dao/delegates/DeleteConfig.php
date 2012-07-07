<?php
/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class DeleteConfig
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
			//delete the item entirely from simpledb
			$response = $sdb->delete_attributes( $domain, $config->appID );
			if ($response->isOK())
			{
				if( Cache::getInstance()->get( "config" . $config->appID ) != false ){
					//error_log("updating project in cache", 0);
					Cache::getInstance()->delete( "config" . $config->appID );
				}
				//invalidate the config list for future requests
				if( Cache::getInstance()->get( CONFIG_LIST_CACHE_KEY ) != false ){
					//error_log("updating project list in cache", 0);
					Cache::getInstance()->delete( CONFIG_LIST_CACHE_KEY );
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