<?php

include_once 'ConnectionFactory.php';
include_once 'lvl6MemCache.php';

class Item {

    private $id;
    private $name;
    private $type;
    private $atk_boost;
    private $def_boost;
    private $upkeep;
    private $min_level;
    private $price;
    private $chance_of_loss;
    private $image_url;
    private $quality;
    private $quantity;
    private $is_buyable;

    function __construct() {
        
    }

    public static function getItem($itemID) {
        $objItem = Lvl6MemCache::getCache('item' . $itemID);
        if ($objItem) {
            return $objItem;
        }
        $objItem = ConnectionFactory::SelectRowAsClass("SELECT * FROM items where id = :itemID", array("itemID" => $itemID), __CLASS__);
      	Lvl6MemCache::addCache('item' . $itemID, $objItem);
        return $objItem;
    }

    public static function getItems($itemIDs, $statType='defence') {
        $attack_type = 'def_boost';
        if ($statType == 'attack') {
            $attack_type = 'atk_boost';
        }
        if (count($itemIDs) <= 0) {
            return array();
        }

        $condclauses = array();
        $values = array();
        foreach ($itemIDs as $key => $value) {
            array_push($condclauses, "id=?");
            array_push($values, $value);
        }
        $query = "SELECT * from items where ";
        $query .= getArrayInString($condclauses, ' OR ');
        $query .= " ORDER BY $attack_type DESC";
        $objItems = ConnectionFactory::SelectRowsAsClasses($query, $values, __CLASS__);

        return $objItems;
    }

    public static function getItemIDsToItems($itemIDs) {
        $objItems = self::getItems($itemIDs);
        $toreturn = array();
        foreach ($objItems as $objItem) {
            $itemID = $objItem->getID();
            $toreturn[$itemID] = $objItem;
        }
        return $toreturn;
    }

    public static function getUserUpkeepItems($userID) {
        $query = "Select items.id, users_items.quantity, items.price, items.upkeep From items, users_items
                    where users_items.user_id = :userID and users_items.item_id = items.id
                    And users_items.is_looted = 0
                    AND items.upkeep > 0
                    order by items.upkeep desc";
        $objItem = ConnectionFactory::SelectRowsAsClasses($query, array("userID" => $userID), __CLASS__);
        return $objItem;
    }

    public static function getItemIDsToItemsVisibleInShop($playerLevel) {
        $toreturn = Lvl6MemCache::getCache('itemIDsToItemsVisibleInShop' . $playerLevel);
        if ($toreturn) {
            return $toreturn;
        }
        $query = "SELECT * FROM items WHERE min_level <= ? AND is_special = 0 ORDER BY min_level";
        $objItems = ConnectionFactory::SelectRowsAsClasses($query, array($playerLevel), __CLASS__);
        $toreturn = array();
        foreach ($objItems as $objItem) {
            $itemID = $objItem->getID();
            $toreturn[$itemID] = $objItem;
        }
        Lvl6MemCache::addCache('itemIDsToItemsVisibleInShop' . $playerLevel, $toreturn);
        return $toreturn;
    }

    /*
     * gives the user $quantity number of an item
     */

    public function updateUserBlackmarketItems($userID, $bmID, $quantity) {
        $itemParams = array();
        $itemParams['user_id'] = $userID;
        $itemParams['item_id'] = $bmID;
        $itemParams['quantity'] = $quantity + 1;
        // print_r($itemParams);
        //for this to work, need to modify appropriate tables to have unique constraint over two columns
        //http://www.w3schools.com/sql/sql_unique.asp		
        //although i think the two primary keys are doing it
        if (ConnectionFactory::InsertOnDuplicateKeyUpdate("users_items", $itemParams, "quantity", 1))
            return 1;
        return 0;
        //echo $val;
        //die();
    }

    /*
     * create get black market item created by @waseem safder[21/10/2011]
     */

    public static function getBlackMarketItem($is_special, $is_common) {
        $objItems = Lvl6MemCache::getCache('blackMarketItem' . $is_special . $is_common);
        if ($objItems) {
            return $objItems;
        }
        $query = "SELECT  * FROM `items` where bm_type = $bm_type AND bm_item_type = bm_item_type ORDER BY RAND() LIMIT 1";
        $query = "SELECT * FROM items WHERE is_special = $is_special AND  is_common= $is_common ORDER BY  RAND() LIMIT 1";
        $objItems = ConnectionFactory::SelectRowsAsClasses($query, array($userID), __CLASS__);
        Lvl6MemCache::addCache('blackMarketItem' . $is_special . $is_common, $objItems);
        return $objItems;
    }

    /**
     * this function will display the next two locked items
     * TODO: make it configurable bu assigning constant instead of two
     * @return the locked items as classes
     */
    public static function get_locked_items_visible_in_shop($playerLevel, $item_type) {
        $toreturn = Lvl6MemCache::getCache('locked_items_visible_in_shop' . $playerLevel . $item_type);
        if ($toreturn) {
            return $toreturn;
        }
        $query = "SELECT * FROM items WHERE items.min_level = (SELECT items.min_level as next_level FROM items WHERE items.min_level > ? and items.type = ? LIMIT 0,1)";
        //$query = "SELECT * FROM items WHERE min_level > ? AND type = ? ORDER BY min_level ASC LIMIT 0,1";
        $objItems = ConnectionFactory::SelectRowsAsClasses($query, array($playerLevel, $item_type), __CLASS__);
        $toreturn = array();
        foreach ($objItems as $objItem) {
            $itemID = $objItem->getID();
            $toreturn[$itemID] = $objItem;
        }
        Lvl6MemCache::addCache('locked_items_visible_in_shop' . $playerLevel . $item_type, $toreturn);
        return $toreturn;
    }

    public static function getHasItem($userID) {
        $objRE = ConnectionFactory::SelectRowAsClass("SELECT * FROM users_items where user_id = :userID ", array("userID" => $userID), __CLASS__);
        if (empty($objRE)) {
            return false;
        } else {
            return true;
        }
    }

    public static function ItemAtkCmp($item1, $item2) {
        return $item2->getAtkBoost() - $item1->getAtkBoost();
    }

    public static function ItemDefCmp($item1, $item2) {
        return $item2->getDefBoost() - $item1->getDefBoost();
    }

    public static function getItemUpkeep($itemId) {
        $upkeepAmount = Lvl6MemCache::getCache('itemUpkeep' . $itemId);
        if ($upkeepAmount) {
            return $upkeepAmount;
        }
        $upkeepAmount = ConnectionFactory::SelectValue("upkeep", "items", array("id" => $itemId));
        Lvl6MemCache::addCache('itemUpkeep' . $itemId, $upkeepAmount);
        return $upkeepAmount;
    }

    public static function getNextUnlockWeaponLevel($playerLevel) {
        $objItem = Lvl6MemCache::getCache('nextUnlockWeaponLeve' . $playerLevel);
        if ($objItem) {
            return $objItem;
        }
        $query = "SELECT * FROM items WHERE min_level > ? ORDER BY min_level";
        $objItem = ConnectionFactory::SelectRowAsClass($query, array($playerLevel), __CLASS__);
        Lvl6MemCache::addCache('nextUnlockWeaponLeve' . $playerLevel, $objItem);
        return $objItem;
    }

    public function getQuanitybyUserId($userID) {
        $conditions = array();
        $conditions['item_id'] = $this->id;
        $conditions['user_id'] = $userID;
        $quantity = ConnectionFactory::SelectValue('quantity', 'users_items', $conditions);
        //echo $quantity;
        //die;
        if ($quantity)
            return $quantity;
        return 0;
    }

    /*
      should not be used because item objects do not encapsulate quantity by themselves
      public static function getItemsForUser($userID) {
      $query = "SELECT * FROM users_items WHERE user_id = ?";
      $objItems = ConnectionFactory::SelectRowsAsClasses($query, array($userID), __CLASS__);
      return $objItems;
      } */

    public function getName() {
        return $this->name;
    }

    public function getID() {
        return $this->id;
    }

    public function getChanceOfLoss() {
        return $this->chance_of_loss;
    }

    public function getMinLevel() {
        return $this->min_level;
    }

    public function getType() {
        return $this->type;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getAtkBoost() {
        return $this->atk_boost;
    }

    public function getDefBoost() {
        return $this->def_boost;
    }

    public function getUpkeep() {
        return $this->upkeep;
    }

    public function getImage() {
        return $this->image_url;
    }

    public function getitemurl() {
        return $this->image_url;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getQuality() {
        return $this->quality;
    }

    public function getIsBuyable() {
        return $this->is_buyable;
    }

}