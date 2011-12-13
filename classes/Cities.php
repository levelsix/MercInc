<?php

include_once 'ConnectionFactory.php';
include_once 'lvl6MemCache.php';

class Cities {

    private $id;
    private $name;

    function __construct() {
        
    }

    public static function getCity($cityID) {
        $objCity = Lvl6MemCache::getCache('city'.$cityID);
        if ($objCity) {
            return $objCity;
        }
        $objCity = ConnectionFactory::SelectRowAsClass("SELECT * FROM cities where id = :cityID", array("cityID" => $cityID), __CLASS__);
        Lvl6MemCache::addCache('city'.$cityID, $objCity);
        return $objCity;
    }

    public static function getAllCities() {
        $objCities = Lvl6MemCache::getCache('allCities');
        if ($objCities) {
            return $objCities;
        }
        $objCities = ConnectionFactory::SelectRowsAsClasses("SELECT * FROM cities", array(), __CLASS__);
        Lvl6MemCache::addCache('allCities', $objCities);
        return $objCities;
    }

    public function getName() {
        return $this->name;
    }

    public function getID() {
        return $this->id;
    }

}