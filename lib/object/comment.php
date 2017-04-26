<?php
class Comment {
  private $id;
  private $postId;
  private $userId;
  private $guestName;
  private $message;
  private $authorIp;

  public function __construct($id, $postId, $userId, $guestName, $message, $authorIp) {
    $this->id = $id;
    $this->postId = $postId;
    $this->userId = $userId;
    $this->guestName = $guestName;
    $this->message = $message;
    $this->authorIp = $authorIp;
  }

  public function getId() { return $this->id; }
  public function getPostId() { return $this->postId; }
  public function getUserId() { return $this->userId; }
  public function getGuestName() { return $this->guestName; }
  public function getMessage() { return $this->message; }
  public function getAuthorIp() { return $this->authorIp; }
}
?>
