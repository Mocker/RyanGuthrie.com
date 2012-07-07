<?php
/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class GetModifedProjectAssets
{

	static $dataValidator;
	private static $files = array();
	private static $assetLookup = array();

	function __construct()
	{
	}
	
	/**
	 * 
	 * @param user
	 * This functions returns a list of assets that are not already linked to the project, or have been modified since the last import
	 */
	static function execute( Project $project )
	{
		self::$assetLookup = array();
		//parse project JSON
		try{
			$projectObj = json_decode( $project->projectJSON );
		}catch( Exception $e ){
			return new CustomAWSError( null, $e->getMessage() . "\r\n" . $e->getTraceAsString() );
		}
		//set defaults and lookups
		if( !is_array( $projectObj->{'assets'} ) ){
			$projectObj->{'assets'} = array();
		}else{
			//put assets into an accosiative array
			foreach( $projectObj->{'assets'} as $asseObj ){
				$asset = new ProjectAsset( $asseObj->{'url'}, $asseObj->{'name'}, $asseObj->{'dbKey'}, $asseObj->{'type'}, null, null, $asseObj->{'updatedOn'} );
				self::$assetLookup[ $asseObj->{'url'} ] = $asset;
			}
		}
		
		//get the list of assets that are not already in teh project
		$directory = BASE_ASSET_DIRECTORY . $project->appID;
		self::parseContents( $directory );
		$returnObj = array(); 
		$returnObj["assets"] = self::$files;
		return $returnObj;
	}
	
	private static function parseContents( $path = '.', $level = 0 ){
	    $ignore = array( 'cgi-bin', '.', '..' );
	    $dh = @opendir( $path );
	     // Loop through the directory
	    while( false !== ( $file = readdir( $dh ) ) ){
	        if( !in_array( $file, $ignore ) ){
	            if( is_dir( "$path/$file" ) ){
	                self::parseContents( "$path/$file", ($level+1) );
	            }else{
	            	//see if the asset exists based on location and has not changed
	            	$asset = self::$assetLookup[ BASE_URL . "$path/$file" ];
	            	if( isset( $asset ) && FileUtils::projectAssetsHasChanged( $asset ) ){
	            		//the asset exists but has changed
	            		error_log("asset exists and has changed. Adding to results: " . $asset->url );
	            		array_push( self::$files, $asset );
	            	}
	            }
	        }
	    }
	    return self::$files;
	}

}
?>