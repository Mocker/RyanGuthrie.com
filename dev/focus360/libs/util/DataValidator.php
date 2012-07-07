<?php


/**
 * @version 1.0
 * @created 08-Jun-2009 3:59:45 PM
 */
class DataValidator
{

	function __construct()
	{
	}

	static public function validateDomainName( Domain $domain ){
		//the reason I have this function is to not allow domains to be created until they have been agreed upon
		$returnValue = false;
		$validDomains = array();
		$validDomains[ PROJECTS_DOMAIN ] = PROJECTS_DOMAIN;
		$validDomains[ CONFIG_DOMAIN ] = CONFIG_DOMAIN;
		if( $validDomains[ $domain->name ] ){
			$returnValue = true;
		}
		return $returnValue;
	}
	
	static public function mbStringToArray ($str) {
	    if (empty($str)) return false;
	    $len = strlen($str);
	    $array = array();
	    for ($i = 0; $i < $len; $i++) {
	        $array[] = substr($str, $i, 1);
	    }
	    return $array;
	}
	
	static public function mb_chunk_split($str, $len, $glue) {
	    if (empty($str)) return false;
	    $array = self::mbStringToArray($str);
	    $n = 0;
	    $new = '';
	    foreach ($array as $char) {
	        if ($n < $len) $new .= $char;
	        elseif ($n == $len) {
	            $new .= $glue . $char;
	            $n = 0;
	        }
	        $n++;
	    }
	    return $new;
	}
	
	static public function mb_chunk_split_to_array($str, $len, $glue) {
	    if (empty($str)) return false;
	    $array = self::mbStringToArray($str);
	    $returnArray = array();
	    $n = 0;
	    $new = '';
	    foreach ($array as $char) {
	        if ($n < $len) $new .= $char;
	        elseif ($n == $len) {
	        	$new .= $char;
	            array_push( $returnArray, $new );
	            $n = 0;
	            $new = '';
	        }
	        $n++;
	    }
	    //if all chunks were the same size n == 0, if not we need to add the last bits
	    if( $n != 0 ){
	    	array_push( $returnArray, $new );
	    }
	    return $returnArray;
	}

}
?>