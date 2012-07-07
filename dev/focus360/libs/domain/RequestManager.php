<?php

/**
 * @version 1.0
 * @created 08-Jun-2009 4:16:31 PM
 */
class RequestManager
{

	const FOCUS_KEY = "FC3D001";
	const FOCUS_SECRET_KEY = "/A6xZzrW3my3ErCfIobmz4qkfV0MqwosP4UJQF01";

	public static $clientLookup;

	function __construct()
	{
	}

	/**
	 * Parses a signed_request and validates the signature.
	 *
	 * @param string $signed_request A signed token
	 * @return array The payload inside it or null if the sig is wrong
	 */
	public static function parseSignedRequest( $signed_request ) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2);

		// decode the data
		$sig = base64_decode($encoded_sig);
		$data = json_decode(base64_decode($payload), true);

		if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
			error_log('Unknown algorithm. Expected HMAC-SHA256');
			return null;
		}

		// check sig
		$expected_sig = hash_hmac('sha256', $payload, self::FOCUS_SECRET_KEY, $raw = true);
		if ($sig !== $expected_sig) {
			error_log('Bad Signed JSON signature!');
			return null;
		}

		return $data;
	}

	public static function faultTest( User $usr ){
		$dao = DAOFactory::getDAO();
		return $dao->faultTest( $usr );
	}
	
	public static function getModifiedProjectAsset( Project $project )
	{
		$dao = DAOFactory::getDAO();
		return $dao->getModifiedProjectAsset( $project );
	}
	
	public static function getProjectAssetList( Project $project )
	{
		$dao = DAOFactory::getDAO();
		return $dao->getProjectAssetList( $project );
	}
	
	public static function getConfigs()
	{
		$dao = DAOFactory::getDAO();
		return $dao->getConfigs();
	}
	
	public static function deleteConfig( Config $config )
	{
		$dao = DAOFactory::getDAO();
		return $dao->deleteConfig( $config );
	}
	
	public static function updateConfig( Config $config )
	{
		$dao = DAOFactory::getDAO();
		return $dao->updateConfig( $config );
	}
	
	public static function getConfig( Config $config )
	{
		$dao = DAOFactory::getDAO();
		return $dao->getConfig( $config );
	}
	
	public static function getProject( Project $project )
	{
		$dao = DAOFactory::getDAO();
		return $dao->getProject( $project );
	}
	
	public static function getProjects()
	{
		$dao = DAOFactory::getDAO();
		return $dao->getProjects();
	}
	
	public static function deleteProject( Project $project )
	{
		$dao = DAOFactory::getDAO();
		return $dao->deleteProject( $project );
	}
	
	public static function updateProject( Project $project )
	{
		$dao = DAOFactory::getDAO();
		return $dao->updateProject( $project );
	}

	public static function updateDomain( Domain $domain )
	{
		$dao = DAOFactory::getDAO();
		return $dao->updateDomain( $domain );
	}

	public static function listDomains()
	{
		$dao = DAOFactory::getDAO();
		return $dao->listDomains();
	}

}
?>