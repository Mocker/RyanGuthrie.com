<?php

class V1Dao extends DAO
{
	public $mysqli; //see if this can still be used when private as we pass it to our delegates
	public $results = array();
	
	function __construct($host, $usr, $pwd, $db)
	{
		//comment this in if using mysql
		//$this->connect($host, $usr, $pwd, $db);
    }
	
	private function connect($host, $usr, $pwd, $db)
    {
	    /* comment this is if you want to connect to MySql
	   	$this->mysqli = new mysqli($host, $usr, $pwd);
	
		if(mysqli_connect_errno()){
			printf("Connot connect using HOST: %1$s  USR: %2$s PWD: %3$s <br> error: %4$s", $host, $usr, $pwd, $this->mysqli->error);
			die("Could not connect: " . $this->mysqli->error);
		}
	
		$db_selected = $this->mysqli->select_db($db);
		
		if (!$db_selected) {
			die ('Can\'t use ifp : ' . $this->mysqli->error);
		}
		*/
    }
	    
	public function faultTest(User $usr){
	    return ReadFault::faultTest( $usr );
	}
	
	public function getModifiedProjectAsset( Project $project ){
		return GetModifedProjectAssets::execute( $project );
	}
	
	public function getProjectAssetList( Project $project ){
		return GetProjectAssets::execute( $project );
	}
	
	public function getConfigs(){
		return GetConfigs::execute();
	}
	
	public function deleteConfig( Config $config ){
		return DeleteConfig::execute( $config );
	}
	
	public function updateConfig( Config $config ){
		return UpdateConfig::execute( $config );
	}
	
	public function getConfig( Config $config ){
		return GetConfig::execute( $config );
	}
	
	public function getProject( Project $project ){
		return GetProject::execute( $project );
	}
	
	public function getProjects(){
		return GetProjects::execute();
	}
	
	public function updateProject( Project $project ){
		return UpdateProject::execute( $project );
	}
	
	public function deleteProject( Project $project ){
		return DeleteProject::execute( $project );
	}
	
	public function updateDomain( Domain $domain ){
		return UpdateDomain::updateDomain( $domain );
	}
	
	public function listDomains(){
		return ListDomains::listDomains();
	}
    	
}

?>