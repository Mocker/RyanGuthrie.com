<?php

	/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class AwsUtils{
	
	function __construct(){
		
	}
	
	static function parseResponse( $cfResponse ){
		//put this into the parser
		$response = json_decode( json_encode( $cfResponse ) );
		$returnValue = new CustomAwsResponse( $response->{'body'}->{'ResponseMetadata'}->{'RequestId'}, $response->{'body'}->{'ResponseMetadata'}->{'BoxUsage'} );
		return $returnValue;
	}
	
	static function parseError( $cfResponse ){
	//put this into the parser
		$response = $cfResponse->body;
		$returnValue = new CustomAWSError();
		if( is_array( $response->Errors ) ){
			foreach( $response->Errors as $error ){
				$srfBetError = new CustomAWSError( $error->Error->Code, $error->Error->Message );
				array_push( $returnValue->errors, $srfBetError );
			}
		}else{
			$srfBetError = new CustomAWSError( strval( $response->Errors->Error->Code ), strval( $response->Errors->Error->Message ) );
			array_push( $returnValue->errors, $srfBetError );
		}
		return $returnValue;
	}
}

?>