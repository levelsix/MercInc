<?php
include_once 'ConnectionFactory.php';
class Comments {

	private $id;
	private $sender_id;
	private $receiver_id;
	private $comments;
	private $time_posted;
	private $type;

	function __construct() {

	}
	/**
	 * To post the comment
	 * @param The user who have posted the comment $senderID
	 * @param The user to which comment was posted $receiverID
	 * @param The content of the comment $content
	 * @return boolean
	 */
	public static function postComment ($senderID,$receiverID,$content,$time) {

		$comment = array();
		$comment['sender_id'] = $senderID ;
		$comment['receiver_id'] = $receiverID ;
		$comment['comments'] = $content;
		//$comment['time_posted'] = 'NOW()';
		$success = ConnectionFactory::InsertIntoTableBasic("comments", $comment);

		return $success;
	}
	public static function getUserComments($userID) {
		$query = "Select * FROM comments Where comments.receiver_id = ? AND comments.type=1 ORDER BY comments.id DESC LIMIT 0,10";
		$values = array($userID);
		$comments = ConnectionFactory::SelectRowsAsClasses($query, $values,__CLASS__);
		return $comments;
	}
	/**
	 *  Getter functions
	 */
	public static function deleteComment($id) {
		return ConnectionFactory::DeleteRowFromTable("comments", array('id'=>$id ));
	}
	public function getCommentID () {
		return $this ->id;
	}
	public function getSenderID () {
		return $this ->sender_id;
	}
	public function getReceiverID () {
		return $this ->receiver_id;
	}
	public function getContent () {
		return $this ->comments;
	}
	public function getTimePosted () {
		return $this ->time_posted;
	}
}
?>