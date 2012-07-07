<?php
/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:47 PM
 */
class FileUtils
{

	function __construct()
	{
	}
	
	public static function projectAssetsHasChanged( ProjectAsset $projectAsset ){
		error_log("projectAssetsHasChanged called");
		//if there is no modifedOn date return true
		if( !isset( $projectAsset->modifiedOn ) ){
			return true;
		}
		$updatedTime;
		$baseURL = BASE_URL;
		//get the path by extracting the base URL
		$directoryParts = explode($baseURL, $projectAsset->url );
		if( isset( $directoryParts ) ){
			error_log( "FilePath: " . $directoryParts[1] );
			//get the updated time of the asset
			if ( file_exists( $directoryParts[1] ) ){
				$updatedTime = self::getFileTimeStamp( $directoryParts[1] );
				error_log( "updatedTime: " . $updatedTime );
				error_log( "assetModifiedOn: " .$projectAsset->modifiedOn );
				if( $updatedTime > 0 ){
					return $updatedTime > $projectAsset->modifiedOn;
				}
			}
			//look up the asset on the file system and see if it has changed
			//if so add it to the return array
		}
		error_log("end projectAssetsHasChanged");
		return false;
	}
	
	public static function getFileTimeStamp( $filePath )
	{
	    $time = filemtime($filePath);
	    $isDST = (date('I', $time) == 1);
	    $systemDST = (date('I') == 1);
	    $adjustment = 0;
	    if($isDST == false && $systemDST == true){
	        $adjustment = 3600;
	    }else if($isDST == true && $systemDST == false){
	        $adjustment = -3600;
	    }else{
	        $adjustment = 0;
	    }
	    return ($time + $adjustment);
	} 
	
	public static function getAllProjectAssets( $path = '.', $level = 0 ){
		$files = array();
	    $ignore = array( 'cgi-bin', '.', '..' );
	    $dh = @opendir( $path );
	     // Loop through the directory
	    while( false !== ( $file = readdir( $dh ) ) ){
	        if( !in_array( $file, $ignore ) ){
	            if( is_dir( "$path/$file" ) ){
	                self::parseContents( "$path/$file", ($level+1) );
	            } else {
	            	//see if the asset exists based on location
	            	$asset = new ProjectAsset( BASE_URL . "$path/$file", $file, uniqid(), null, null, time() );
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
                	array_push( $files, $asset );
	            }
	        }
	    }
	    return $files;
	}

}
?>