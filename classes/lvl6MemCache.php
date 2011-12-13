<?php
class Lvl6MemCache {

    private static $factory;
    private static $host = '127.0.0.1';
    private static $port= 11211;
    function __construct() {
        
    }

    public static function getFactory() {   
    	    
        if (!self::$factory){
                self::$factory = new Memcache();
                self::$factory->connect('127.0.0.1', 11211) or die ("Could not connect");                
            }            
       return self::$factory;
    }
    
    public static function addCache($name, $data){
        self::getFactory()->add($name,$data);
    }
    
    public static function getCache($name){
        if(self::getFactory()->get($name)) {                
                return self::$factory->get($name);
        }else{
            return false;
        }   	
    }
    
}

?>
