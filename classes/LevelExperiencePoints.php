<?php
include_once 'ConnectionFactory.php';
include_once 'lvl6MemCache.php';

class LevelExperiencePoints {

    private $level_id;
    private $required_experience_points;

    public static function getLevelExp($levelID) {
        $objItem = Lvl6MemCache::getCache('levelExp'.$levelID);
        if ($objItem) {
            return $objItem;
        }
        $objItem = ConnectionFactory::SelectRowAsClass("SELECT required_experience_points FROM level_experience_points where level_id = :levelID", array("levelID" => $levelID), __CLASS__);
        Lvl6MemCache::addCache('levelExp'.$levelID, $objItem);
        return $objItem;
    }

    public function getExp() {
        return $this->required_experience_points;
    }

}

?>
