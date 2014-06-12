<?php

session_start();

	///////////////////////////////// 
	//USER CLASS IMPLEMENTED
	
class User {

	private $id;
	private $firstName;
	private $lastName;
	private $email;
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
		
		echo "<p><img src='" . $this->pictureURL . "' width='60px' height='60px'>";
		echo $this->firstName . " " . $this->lastName . "<br>";
				
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
		
			$userId = $_SESSION['userId'];
			
			echo $str . " <button id='delete_friend' onClick='deleteFriend($this->id, $userId);'>Delete Friend</button>";	
			
		}
		
		echo "<br>";
	}
	
	function displayPhotoName() {
		echo "<p><img src='" . $this->pictureURL . "' width='30px' height='30px'>";
		echo $this->firstName . " " . $this->lastName . "<br>";
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
	private $likeCount;
	
	function __construct($postId, $content, $attachmentPath, $userId, $postType, $likeCount) {
		$this->postId = $postId;
		$this->content = $content;
		$this->attachmentPath = $attachmentPath;
		$this->userId = $userId;
		$this->postType = $postType;
		$this->likeCount = $likeCount;
	}
	
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
	
	function displayPost($isLiked) {
	
		$userId = $_SESSION['userId'];
		
		if(strcmp($this->postType, "Picture") == 0) {
			echo "<img src = '" . $this->attachmentPath . "'>";
		}
		echo $this->content . "<br>";
		echo $this->likeCount . " ";
		if($isLiked) {
			echo "<button id='unlike_post' onClick='unlikePost($this->postId, $userId);'>Unlike</button>";
		} else {
			echo "<button id='like_post' onClick='likePost($this->postId, $userId);'>Like</button>";
		}
		
	}
	
}

	
	///////////////////////////////// 
	//COMMENT CLASS IMPLEMENTED

class Comment {

	private $commentId;
	private $userId;
	private $postId;
	private $content;
	
	function __construct($userId, $postId) {
		$this->userId = $userId;
		$this->postId = $postId;
	}
	
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
	
	function displayMakeComment() {
		echo "
			<p>
			<input type='text' id='comment_text'>
			<button id='share_comment' onClick='shareComment($this->userId, $this->postId);'>Share</button>
			<p>";
	}
	
	function display($userName) {
		echo "<p>" . $userName;
		echo "<br>" . $this->content;	
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