<?php

/**
 * @version 1.0
 * @created 08-Jun-2009 3:16:45 PM
 */
class Cache
{

	private static $instance;
	private static $memcachedInstance;
	private static $memcachedEnabled = false;
	public static $defaultTimeToLive = 1;

	function __construct()
	{
		if( self::$instance != null ){
			throw new Exception("Singleton class has already been instantiated");
		}else{
			//set up mem cache
			self::$instance = $this;
			if(class_exists('Memcache')){
  				self::$memcachedInstance = new Memcache();
				self::$memcachedInstance->connect("localhost",11211);
				self::$memcachedEnabled = true;
			}
		}
	}

	public static function getInstance(){
		if( self::$instance == null ){
			self::$instance = new Cache();
		}
		return self::$instance;
	}

	/**
	 * 
	 * @param keys
	 */
	public function get( $keys )
	{
		if( !self::$memcachedEnabled ){
			return null;
		}
		return self::$memcachedInstance->get( $keys );
	}
	
	/**
	 * 
	 * @param keys
	 * @param var
	 * @param flag
	 * @param expires
	 */
	public function set( $key, $var, $flag = false, $expires = 0 )
	{
		if( !self::$memcachedEnabled ){
			return null;
		}
		return self::$memcachedInstance->set( $key, $var, $flag, $expires );
	}
	
	/**
	 * 
	 * @param keys
	 * @param var
	 * @param flag
	 * @param expires
	 */
	public function add( $key, $var, $flag = false, $expires = 0 )
	{
		if( !self::$memcachedEnabled ){
			return null;
		}
		return self::$memcachedInstance->add( $key, $var, $flag, $expires );
	}
	
	/**
	 * 
	 * @param keys
	 * @param var
	 * @param flag
	 * @param expires
	 */
	public function replace( $key, $var, $flag = false, $expires = 0 )
	{
		if( !self::$memcachedEnabled ){
			return null;
		}
		return self::$memcachedInstance->replace( $key, $var, $flag, $expires );
	}
	
/**
	 * 
	 * @param keys
	 */
	public function delete( $key )
	{
		if( !self::$memcachedEnabled ){
			return null;
		}
		return self::$memcachedInstance->delete( $key );
	}
	
	public function flush()
	{
		if( !self::$memcachedEnabled ){
			return null;
		}
		return self::$memcachedInstance->flush();
	}

}
?>