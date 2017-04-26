<?php
class Post {
  private $id;
  private $categoryId;
  private $title;
  private $content;
  private $date;

  public function __construct($id, $categoryId, $title, $content, $date) {
    $this->id = $id;
    $this->categoryId = $categoryId;
    $this->title = $title;
    $this->content = $content;
    $this->date = $date;
  }

  public function getId() { return $this->id; }
  public function getCategoryId() { return $this->categoryId; }
  public function getTitle() { return $this->title; }
  public function getContent() { return $this->content; }
  public function getDate() { return $this->date; }
}
?>
