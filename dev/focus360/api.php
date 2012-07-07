<?php
include_once("root.inc.php");
include_once($ROOT."base.inc.php");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//mb_internal_encoding("UTF-8");
try {
	if(empty($_GET["action"])) {
		throw new Exception("Invalid GET request. Missing action code.");
	}
	$response;
	switch( $_GET["action"] ){
		case "getModifiedProjectAsset":
			if( empty( $_POST["json"] ) || 
			    empty( $_POST["userID"] )  
			) {
				throw new Exception("Invalid POST request. json and userID are required.");
			}
			header("Content-type: application/json; charset=utf-8");
			//get the users account details
			try{
				//extract the required project attributes
				if( get_magic_quotes_gpc() ){
				  $jsonString = stripslashes( $_POST['json'] );
				}else{
				  $jsonString = $_POST['json'];
				}
				$projectObj = json_decode( $jsonString );
				$project = new Project( $projectObj->{"applicationID"}, $projectObj->{"name"} , $jsonString, $projectObj->{"type"},  null, null, time(), $projectObj->{"description"}, $projectObj->{"client"} );
				$response = RequestManager::getModifiedProjectAsset( $project );
			}catch( Exception $e ){
				$response = new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
			}
			break;
		case "getProjectAssetList":
			if( empty( $_POST["json"] ) || 
			    empty( $_POST["userID"] )  
			) {
				throw new Exception("Invalid POST request. json and userID are required.");
			}
			header("Content-type: application/json; charset=utf-8");
			//get the users account details
			try{
				//extract the required project attributes
				if( get_magic_quotes_gpc() ){
				  $jsonString = stripslashes( $_POST['json'] );
				}else{
				  $jsonString = $_POST['json'];
				}
				$projectObj = json_decode( $jsonString );
				$project = new Project( $projectObj->{"applicationID"}, $projectObj->{"name"} , $jsonString, $projectObj->{"type"},  null, null, time(), $projectObj->{"description"}, $projectObj->{"client"} );
				$response = RequestManager::getProjectAssetList( $project );
			}catch( Exception $e ){
				$response = new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
			}
			break;
		case "getConfigList":
			if( empty( $_GET["userID"] ) ) {
				throw new Exception("Invalid GET request. userID is required.");
			}
			header("Content-type: application/json; charset=utf-8");
			//get the users account details
			try{
				$response = RequestManager::getConfigs();
			}catch( Exception $e ){
				$response = new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
			}
			break;
		case "deleteConfig":
			if( empty( $_GET["userID"] ) || 
			    empty( $_GET["applicationID"] ) 
			) {
				throw new Exception("Invalid GET request. userID and applicationID are required.");
			}
			header("Content-type: application/json; charset=utf-8");
			//get the users account details
			try{
				$config = new Config( $_GET["applicationID"] );
				$response = RequestManager::deleteConfig( $config );
			}catch( Exception $e ){
				$response = new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
			}
			break;
		case "setConfig":
			if( empty( $_POST["userID"] ) || 
			    empty( $_POST["json"] ) 
			) {
				throw new Exception("Invalid POST request. userID and json are required.");
			}
			header("Content-type: application/json; charset=utf-8");
			//get the users account details
			try{
				if( get_magic_quotes_gpc() ){
				  $jsonString = stripslashes( $_POST['json'] );
				}else{
				  $jsonString = $_POST['json'];
				}
				$configObj = json_decode( $jsonString );
				$config = new Config( $configObj->{"applicationID"}, $jsonString );
				$response = RequestManager::updateConfig( $config );
			}catch( Exception $e ){
				$response = new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
			}
			break;
		case "getConfig":
			if( empty( $_GET["userID"] ) || 
			    empty( $_GET["applicationID"] ) 
			) {
				throw new Exception("Invalid GET request. userID and appID are required.");
			}
			header("Content-type: application/json; charset=utf-8");
			//get the users account details
			try{
				$config = new Config( $_GET["applicationID"] );
				$config = RequestManager::getConfig( $config );
				//only return the project JSON, decoding verifies it's valid
				if( get_class( $config ) != "CustomAWSError" && get_class( $config ) == "Config" ){
					$response = json_decode( $config->configJSON );
				}else if( get_class( $config ) == "CustomAWSError" ){
					$response = $config;
				}else{
					$response = new CustomAWSError( 1002, "Config JSON stirng is invlaid" );
				}
			}catch( Exception $e ){
				$response = new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
			}
			break;
		case "getProject":
			if( empty( $_GET["userID"] ) || 
			    empty( $_GET["applicationID"] ) 
			) {
				throw new Exception("Invalid POST request. userID and appID are required.");
			}
			header("Content-type: application/json; charset=utf-8");
			//get the users account details
			try{
				$project = new Project( $_GET["applicationID"] );
				$returnedProject = RequestManager::getProject( $project );
				//only return the project JSON, decoding verifies it's valid
				if( get_class( $returnedProject ) != "CustomAWSError" && get_class( $returnedProject ) == "Project" ){
					$response = json_decode( $returnedProject->projectJSON );
				}else if( get_class( $returnedProject ) == "CustomAWSError" ){
					$response = $returnedProject;
				}else{
					$response = new CustomAWSError( 1002, "Project JSON stirng is invlaid" );
				}
			}catch( Exception $e ){
				$response = new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
			}
			break;
		case "getProjectList":
			if( empty( $_GET["userID"] ) ) {
				throw new Exception("Invalid GET request. userID is required.");
			}
			header("Content-type: application/json; charset=utf-8");
			//get the users account details
			try{
				$response = RequestManager::getProjects();
			}catch( Exception $e ){
				$response = new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
			}
			break;
		case "deleteProject":
			if( empty( $_GET["userID"] ) || 
			    empty( $_GET["applicationID"] ) 
			) {
				throw new Exception("Invalid GET request. userID and appID are required.");
			}
			header("Content-type: application/json; charset=utf-8");
			//get the users account details
			try{
				$project = new Project( $_GET["applicationID"] );
				$response = RequestManager::deleteProject( $project );
			}catch( Exception $e ){
				$response = new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
			}
			break;
		case "setProject":
			if( empty( $_POST["json"] ) || 
			    empty( $_POST["userID"] )  
			) {
				throw new Exception("Invalid POST request. json and userID are required.");
			}
			header("Content-type: application/json; charset=utf-8");
			//get the users account details
			try{
				//extract the required project attributes
				if( get_magic_quotes_gpc() ){
				  $jsonString = stripslashes( $_POST["json"] );
				}else{
				  $jsonString = $_POST["json"];
				}
				$projectObj = json_decode( $jsonString );
				$project = new Project( $projectObj->{"applicationID"}, $projectObj->{"name"} , $jsonString, $projectObj->{"type"},  null, null, time(), $projectObj->{"description"}, $projectObj->{"client"} );
				$returnedProject = RequestManager::updateProject( $project );
				if( get_class( $returnedProject ) != "CustomAWSError" && get_class( $returnedProject ) == "Project" ){
					$response = json_decode( $returnedProject->projectJSON );
				}else if( get_class( $returnedProject ) == "CustomAWSError" ){
					$response = $returnedProject;
				}else{
					$response = new CustomAWSError( 1002, "Project JSON stirng is invlaid" );
				}
			}catch( Exception $e ){
				$response = new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
			}
			break;
		case "updateDomain":
			if( empty( $_GET["domain"] ) ) {
				throw new Exception("Invalid GET request. Domain is required.");
			}
			header("Content-type: application/json; charset=utf-8");
			//get the users account details
			try{
				$domain = new Domain( $_GET["domain"] );
				$response = RequestManager::updateDomain( $domain );
			}catch( Exception $e ){
				$response = new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
			}
			break;
		case "listDomains":
			try{
				$response = RequestManager::listDomains();
			}catch( Exception $e ){
				$response = new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
			}
			break;
		default:
			header("HTTP/1.0 501 Not Implemented");
			$response = new CustomAWSError( 1030, "Method not implemented" );
	}
	if( get_class( $response ) != "CustomAWSError" && $response != null ){
		$output = $response;
	}else{
		$output = new stdClass();
		$output->error = $response;
		header("HTTP/1.0 500 Internal Server Error");
	}
	echo (string)str_replace( array( "\r", "\r\n", "\n" ), "", json_encode( $output ) );
	//echo var_dump( UserManager::updateRole( $user, $role ) );
} 
catch (Exception $ex) {
	print_r($ex);
}
?>