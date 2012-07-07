<?php

	abstract class DAO
	{
		abstract public function getModifiedProjectAsset( Project $project );
		abstract public function getProjectAssetList( Project $project );
		abstract public function getConfigs();
		abstract public function getConfig( Config $config );
		abstract public function updateConfig( Config $config );
		abstract public function deleteConfig( Config $config );
		abstract public function getProject( Project $project );
		abstract public function getProjects();
		abstract public function updateDomain( Domain $domain );
		abstract public function updateProject( Project $project );
		abstract public function deleteProject( Project $project );
		abstract public function listDomains();
	}

?>