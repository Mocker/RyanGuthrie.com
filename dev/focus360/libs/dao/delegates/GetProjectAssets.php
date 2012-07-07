<?php
/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class GetProjectAssets
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
	            } else {
	            	//see if the asset exists based on location and has not changed
	            	$asset = self::$assetLookup[ BASE_URL . "$path/$file" ];
	            	if( isset( $asset ) && !FileUtils::projectAssetsHasChanged( $asset ) ){
	            		//fon't include assets already in the list
	            		error_log("asset exists and has not changed: " . $asset->url );
	            		continue;
	            	}else if( isset( $asset ) && FileUtils::projectAssetsHasChanged( $asset ) ){
	            		//the asset exists but has changed
	            		error_log("asset exists and has changed. Adding to results: " . $asset->url );
	            		array_push( self::$files, $asset );
	            	}else{
	            		error_log("asset does not exists. Adding to results: " . $file );
	            		$asset = new ProjectAsset( BASE_URL . "$path/$file", $file, uniqid(), null, null, time(), time() );
	            		//figure out what kind of asset it is
		            	$directoryParts = explode("/", trim($path, "/") );
						if( isset( $directoryParts ) ){
							$length = count( $directoryParts ) - 1;
							$directoryName = $directoryParts[ $length ];
						}
						switch( $directoryName ){
							case SINGLE_FAMILY_DIRECTORY:
								$asset->type = ProjectAsset::SINGLE_FAMILY_SITE_MAP;
								break;
							case MULTI_FAMILY_DIRECTORY:
								$asset->type = ProjectAsset::MULTI_FAMILY_SITE_MAP;
								break;
							case FLOOR_PLAN_DIRECTORY:
								$asset->type = ProjectAsset::FLOOR_PLAN;
								break;
							case BUILDING_PLATE_DIRECTORY:
								$asset->type = ProjectAsset::BUILDING_PLATE;
								break;
							case EXTERIORS_DIRECTORY:
								$asset->type = ProjectAsset::ELEVATION;
								break;
							case ILLUSTRATIONS_DIRECTORY:
								$asset->type = ProjectAsset::ILLUSTRATION;
								break;
							case INTERIORS_DIRECTORY:
								$asset->type = ProjectAsset::RENDERING;
								break;
							case VIDEOS_DIRECTORY:
								$asset->type = ProjectAsset::VIDEO;
								break;
							case PHOTOS_DIRECTORY:
								$asset->type = ProjectAsset::PHOTO;
								break;
							case VACINITY_MAPS_DIRECTORY:
								$asset->type = ProjectAsset::VACINITY_MAP;
								break;
							default:
						}
	                	array_push( self::$files, $asset );
	            	}
	            }
	        }
	    }
	    return self::$files;
	}

}
?>