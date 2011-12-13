<?php

include_once 'ConnectionFactory.php';

class DiamondPurchasedHistory {

    private $id;
    private $udid;
    private $amount;
    private $item_id;
    private $recipt_id;
    private $timestamp;

    function __construct() {
        
    }

    public static function addPurchasedDiamond($udid, $amount, $diamonds, $product_id,$transaction_id,$purchase_date, $app_item_id,$quantity,$bid,$bvrs,$valid_request, $level) {

        $diamond_purchased_history = array();
        $diamond_purchased_history['udid'] = $udid;
        $diamond_purchased_history['amount'] = floatval($amount);
        $diamond_purchased_history['diamonds'] = $diamonds;
        $diamond_purchased_history['product_id'] = $product_id;
        $diamond_purchased_history['transaction_id'] = $transaction_id;
        $diamond_purchased_history['purchase_date'] = $purchase_date;
        $diamond_purchased_history['app_item_id'] = $app_item_id;
        $diamond_purchased_history['quantity'] = $quantity;
        $diamond_purchased_history['bid'] = $bid;
        $diamond_purchased_history['bvrs'] = $bvrs;
        $diamond_purchased_history['valid_request'] = $valid_request;
        $diamond_purchased_history['level'] = $level;
        $success = ConnectionFactory::InsertIntoTableBasic("diamond_purchased_history", $diamond_purchased_history);
        return $success;
    }

    public static function deletePurchasedDiamond($id) {
        return ConnectionFactory::DeleteRowFromTable("diamond_purchased_history", array('id' => $id));
    }
    public static function transactionIdExists($transaction_id){
        $conditions = array();
        $conditions['transaction_id'] = $transaction_id;
        $success = ConnectionFactory::SelectValue("transaction_id", "diamond_purchased_history", $conditions);
        return $success;
    }
        
    public function getID() {
        return $this->id;
    }

    public function getUDID() {
        return $this->udid;
    }

    public function getItemID() {
        return $this->item_id;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getReciptID() {
        return $this->recipt_id;
    }

    public function getTimeStamp() {
        return $this->timestamp;
    }

}

?>