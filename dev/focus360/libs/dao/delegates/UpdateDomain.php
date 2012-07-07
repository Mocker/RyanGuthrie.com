<?php

/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class UpdateDomain
{

	static $dataValidator;

	function __construct()
	{
	}



	/**
	 * 
	 * @param role
	 */
	static function updateDomain(Domain $domain)
	{
		// Instantiate the AmazonSDB class
		$sdb = new AmazonSDB();
		//validate the domain name 
		if( !DataValidator::validateDomainName( $domain ) ){
			$srfBetError = new CustomAWSError( "invalidDomainName", "The domain name you have chosen to create does not exist in the global constants defined in the base.inc" );
			return $srfBetError;
		}
		// Create the domain
		$domain_response = $sdb->create_domain( $domain->name );
		if ($domain_response->isOK())
		{
			if( Cache::getInstance()->get("domains") != false ){
				//error_log("updating domain in cache", 0);
				$domains = Cache::getInstance()->get("domains");
				$domains[$domain->name] = $domain;
				Cache::getInstance()->replace("domains", $domains, false, 86400);
			}
			return AwsUtils::parseResponse( $domain_response );
		}else{
			return AwsUtils::parseError( $domain_response );
		}
	}

}
?>