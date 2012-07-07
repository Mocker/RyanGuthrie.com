<?php

/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class ListDomains
{

	static $dataValidator;

	function __construct()
	{
	}



	/**
	 * 
	 * @param role
	 */
	static public function listDomains()
	{
		if( Cache::getInstance()->get("domains") != false ){
			//error_log("getting domains from cache", 0);
			return Cache::getInstance()->get("domains");
		}
		// Instantiate the AmazonSDB class
		$sdb = new AmazonSDB();
		// Create the domain
		$domain_response = $sdb->list_domains();
		if ($domain_response->isOK())
		{
			$domains = array();
			foreach( $domain_response->{'body'}->{'ListDomainsResult'}->{'DomainName'} as $domainObj ){
				$domain = new Domain();
				//I can'f figure out how to access the damn value so I just hacked out a solution
				$domainObj = json_decode( json_encode( $domainObj ) );
				$domain->name = $domainObj->{"0"};
				$domains[$domain->name] = $domain;
			}
			Cache::getInstance()->add("domains", $domains, false, 86400);
			return $domains;
		}else{
			return AwsUtils::parseError( $domain_response );
		}
	}

}
?>