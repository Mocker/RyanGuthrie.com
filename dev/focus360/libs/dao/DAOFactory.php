<?php
	class DAOFactory
	{
				
		const host = "localhost";
		const usr = "root";
		const pwd = "pwd";	
		const db = "somedb";		

		function __construct()
		{
			/*
				Load config here
			*/
    	}
				
		public static function getDAO()
		{
			$returnDAO = new V1Dao( self::host, self::usr, self::pwd, self::db );
			return $returnDAO;
		}
	}
?>