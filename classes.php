<?php

session_start();

	///////////////////////////////// 
	//USER CLASS IMPLEMENTED
	
class User {

	private $id;
	private $firstName;
	private $lastName;
	private $email;
	//private $password;
	private $birthday;
	private $gender;
	private $pictureURL;
	
	function __construct($id, $firstName, $lastName, $email, $birthday, $gender, $pictureURL) {		
		$this->id = $id;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->email = $email;
		$this->birthday = $birthday;
		$this->gender = $gender;
		$this->pictureURL = $pictureURL;
	}
	
	function setId($x) {
		$this->id = $x;
	}
	function setFirstName($x) {
		$this->firstName = $x;
	}
	function setLastName($x) {
		$this->lastName = $x;
	}
	function setBirthday($x) {
		$this->birthday = $x;
	}
	function setEmail($x) {
		$this->email = $x;
	}
	function setGender($x) {
		$this->gender = $x;
	}
	function setPictureURL($x) {
		$this->pictureURL = $x;
	}
	function getId() {
		return $this->id;
	}	
	function getFirstName() {
		return $this->firstName;
	}
	function getLastName() {
		return $this->lastName;
	}
	function getEmail() {
		return $this->email;
	}
	function getBirthday() {
		return $this->birthday;
	}
	function getGender() {
		return $this->gender;
	}
	function getPictureURL() {
		return $this->pictureURL;
	}
	
	function displayOnSearchResult($str) {
		
		echo "<p><img src='" . $this->pictureURL . "' width='60px'>";
		echo $this->firstName . " " . $this->lastName . "<br>";
		echo $this->email . "<br>";
				
		if (strcmp($str, "pending") == 0) {
			
			echo "Friend request is pending";
			
		} else if (strcmp($str, "notFriend") == 0) {
			
			$userId = $_SESSION['userId'];
			
			echo "<select id='relation_type'>
					<option value='School'>School</option>
					<option value='Work'>Work</option>
					<option value='Family'>Family</option>
				  </select>";
				  
			echo "<button id='add_friend' onClick='addFriend($this->id, $userId);'>Add Friend</button>";
			
		} else {
			
			echo $str . " <button id='delete_friend'>Delete Friend</button>";	
			
		}
		
		echo "<br>";
	}
	
}


	///////////////////////////////// 
	//POST CLASS IMPLEMENTED
	
class Post {

	private $postId;
	private $content;
	private $attachmentPath;
	private $userId;
	private $postType;
	
	function getPostId() {
		return $this->postId;
	}	
	function getContent() {
		return $this->content;
	}
	function getAttachmentPath() {
		return $this->attachmentPath;
	}
	function getUserId() {
		return $this->userId;
	}
	function getPostType() {
		return $this->postType;
	}
	function setPostId($x) {
		$this->postId = $x;
	}
	function setContent($x) {
		$this->content = $x;
	}
	function setAttachmentPath($x) {
		$this->attachmentPath = $x;
	}
	function setPostType($x) {
		$this->postType = $x;
	}
	function setUserId($x) {
		$this->userId = $x;
	}
	
}

	
	///////////////////////////////// 
	//COMMENT CLASS IMPLEMENTED

class Comment {

	private $commentId;
	private $userId;
	private $postId;
	private $content;
	
	function getCommentId() {
		return $this->commentId;
	}
	function setCommentId($x) {
		$this->commentId = $x;
	}
	function getUserId() {
		return $this->userId;
	}
	function setUserId($x) {
		$this->userId = $x;
	}
	function getPostId() {
		return $this->postId;
	}
	function setPostId($x) {
		$this->postId = $x;
	}
	function getContent() {
		return $this->content;
	}
	function setContent($x) {
		$this->content = $x;
	}
	
}


	///////////////////////////////// 
	//GROUP CLASS IMPLEMENTED
	
class GroupInfo {

	private $groupId;
	private $name;
	private $adminId;
	
	function getGroupId() {
		return $this->groupId;
	}
	function setContent($x) {
		$this->groupId = $x;
	}
	function getName() {
		return $this->name;
	}
	function setName($x) {
		$this->name = $x;
	}
	function getAdminId() {
		return $this->adminId;
	}
	function setAdminId($x) {
		$this->adminId = $x;
	}

}

?>