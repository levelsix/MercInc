<?php

include_once 'ConnectionFactory.php';
include_once 'lvl6MemCache.php';

class RealEstate {

    private $id;
    private $name;
    private $income;
    private $price;
    private $min_level;
    private $image_url;
    private $quality;

    function __construct() {
        
    }

    public static function getHasRealEstate($userID) {
        $objRE = ConnectionFactory::SelectRowAsClass("SELECT * FROM users_realestates where user_id = :userID AND quantity > 0", array("userID" => $userID), __CLASS__);
        if (empty($objRE)) {
            return false;
        } else {
            return true;
        }
    }

    public static function getRealEstate($realEstateID) {
        $objRE = Lvl6MemCache::getCache('realEstate' . $realEstateID);
        if ($objRE) {
            return $objRE;
        }
        $objRE = ConnectionFactory::SelectRowAsClass("SELECT * FROM realestate where id = :realEstateID", array("realEstateID" => $realEstateID), __CLASS__);
        Lvl6MemCache::addCache('realEstate' . $realEstateID, $objRE);
        return $objRE;
    }

    public static function getRealEstates($reIDs) {
        if (count($reIDs) <= 0) {
            return array();
        }

        $condclauses = array();
        $values = array();
        foreach ($reIDs as $key => $value) {
            array_push($condclauses, "id=?");
            array_push($values, $value);
        }
        $query = "SELECT * from realestate where ";
        $query .= getArrayInString($condclauses, ' OR ');
        $objREs = ConnectionFactory::SelectRowsAsClasses($query, $values, __CLASS__);

        return $objREs;
    }

    public static function getRealEstateIDsToRealEstates($reIDs) {
        $objREs = self::getRealEstates($reIDs);
        $toreturn = array();
        foreach ($objREs as $objRE) {
            $reID = $objRE->getID();
            $toreturn[$reID] = $objRE;
        }
        return $toreturn;
    }

    public static function getRealEstateIDsToRealEstatesVisibleInShop($playerLevel) {
        $toreturn = Lvl6MemCache::getCache('realEstateIDsToRealEstatesVisibleInShop' . $playerLevel);
        if ($toreturn) {
            return $toreturn;
        }
        $query = "SELECT * FROM realestate WHERE min_level <= ? ORDER BY min_level";
        $objREs = ConnectionFactory::SelectRowsAsClasses($query, array($playerLevel), __CLASS__);
        $toreturn = array();
        foreach ($objREs as $objRE) {
            $reID = $objRE->getID();
            $toreturn[$reID] = $objRE;
        }
        Lvl6MemCache::addCache('realEstateIDsToRealEstatesVisibleInShop' . $playerLevel, $toreturn);
        return $toreturn;
    }

    public static function getLockedRealEstate($playerlevel) {
        $toreturn = Lvl6MemCache::getCache('lockedRealEstate' . $playerlevel);
        if ($toreturn) {
            return $toreturn;
        }
        $pLevel = $playerlevel;
        $query = "SELECT * FROM realestate WHERE realestate.min_level =
                (SELECT realestate.min_level FROM realestate WHERE realestate.min_level > ? LIMIT 0,1)";
        $objREs = ConnectionFactory::SelectRowsAsClasses($query, array($pLevel), __CLASS__);
        $toreturn = array();
        foreach ($objREs as $objRE) {
            $reID = $objRE->getID();
            $toreturn[$reID] = $objRE;
        }
        Lvl6MemCache::addCache('lockedRealEstate' . $playerlevel, $toreturn);
        return $toreturn;
    }

    public static function getUserUpkeepRealEstate($userID) {
        $query = "Select realestate.id, realestate.price, users_realestates.quantity, realestate.upkeep From realestate, users_realestates
                where users_realestates.user_id = :userID and users_realestates.realestate_id = realestate.id
                AND realestate.upkeep > 0
                order by realestate.upkeep desc";

        $objItem = ConnectionFactory::SelectRowsAsClasses($query, array("userID" => $userID), __CLASS__);
        return $objItem;
    }

    public function getName() {
        return $this->name;
    }

    public function getID() {
        return $this->id;
    }

    public function getMinLevel() {
        return $this->min_level;
    }

    public function getIncome() {
        return $this->income;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getImage() {
        return $this->image_url;
    }

    public function getKeep() {
        return $this->upkeep;
    }

    public function getQuality() {
        return $this->quality;
    }

}