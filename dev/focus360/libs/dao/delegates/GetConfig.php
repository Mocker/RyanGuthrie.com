<?php
/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class GetConfig
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
		if( Cache::getInstance()->get( "config" . $config->appID ) != false ){
			//error_log("updating user in cache", 0);
			return Cache::getInstance()->get(  "config" . $config->appID );
		}
		// Instantiate the AmazonSDB class
		$sdb = new AmazonSDB();
		// Store the name of the domain
		$domain = CONFIG_DOMAIN;
		$results = array();
		// Read the domain
		$config_domain = $sdb->domain_metadata( $domain );
		if ($config_domain->isOK())
		{
			//retrieve project from simpledb
			$response = $sdb->get_attributes( $domain, $config->appID );
			if ($response->isOK())
			{
				$response = json_decode( json_encode( $response ) );
			}else{
				return AwsUtils::parseError( $response );
			}
		}else{
			return AwsUtils::parseError( $config_domain );
		}
		if( is_array( $response->{'body'}->{'SelectResult'}->{'Item'} ) ){
			//return error, should onlt be one
			return new CustomAWSError( 1001, "Multiple configs were found with the same appID. Please contact your system administrator with the following appID: " . $config->appID );
		}else{
			if( $response->{'body'}->{'GetAttributesResult'}->{'Attribute'} == null ){
				return new CustomAWSError( 1008, "No config data found for the following appID: " . $config->appID );
			}
			foreach( $response->{'body'}->{'GetAttributesResult'}->{'Attribute'}  as $itemObj ){
				$config->{ $itemObj->{'Name'} } = $itemObj->{'Value'};
			}
		}
		if( Cache::getInstance()->get( "config" . $config->appID ) != false ){
			//error_log("updating user in cache", 0);
			Cache::getInstance()->replace(  "config" . $config->appID, $config, false, Cache::$defaultTimeToLive );
		}else{
			//error_log("adding user in cache", 0);
			Cache::getInstance()->add(  "config" . $config->appID, $config, false, Cache::$defaultTimeToLive);
		}
		return $config;
	}

}
?>