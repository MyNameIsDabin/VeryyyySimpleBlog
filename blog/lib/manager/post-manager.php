<?php
require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/object/post.php");
require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/object/comment.php");
require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/result-data.php");

class PostManager {

  private $mysql;

  function getCategoryList() {
    $mysql = $this->mysql;
    $sql = "SELECT * FROM `category`";
    $result = $mysql->query($sql);
    $category;
    if ($result) {
      while($row = $result->fetch_assoc()) {
        $category[$row['id']] = $row['name'];
      }
    }

    return $category;
  }

  function writePost($categoryId, $title, $content, WritePostCallBack $result) {
    $mysql = $this->mysql;
    $resultData = new ResultData();

    if (empty($title)) {
      $resultData->setData("타이틀을 입력하세요" , WritePostCallBack::RESULT_NOT_FIND_TITLE);
    } else if (empty($content)) {
      $resultData->setData("내용을 입력하세요" , WritePostCallBack::RESULT_NOT_FIND_CONTENT);
    } else {
      $categoryId = $mysql->real_escape_string($categoryId);
      $title = $mysql->real_escape_string($title);
      $content = $mysql->real_escape_string($content);

      $sql = "INSERT INTO `post` (category_id, title, content) VALUES ($categoryId, '$title', '$content')";
      $sqlResult = $mysql->query($sql);
      if (!$sqlResult) {
        $resultData->setData("서버에 문제가 발생하였습니다" , WritePostCallBack::RESULT_ERROR_DATABASE);
      } else {
        $resultData->setData("성공적으로 글을 작성하였습니다" , WritePostCallBack::RESULT_SUCCESS, $mysql->insert_id);
      }
    }

    $result->callBackResult($resultData);
  }

  function updatePost($category, $postId, $title, $content, WritePostCallBack $result) {
    $mysql = $this->mysql;
    $resultData = new ResultData();

    if (empty($title)) {
      $resultData->setData("타이틀을 입력하세요" , WritePostCallBack::RESULT_NOT_FIND_TITLE);
    } else if (empty($content)) {
      $resultData->setData("내용을 입력하세요" , WritePostCallBack::RESULT_NOT_FIND_CONTENT);
    } else {
      $title = $mysql->real_escape_string($title);
      $content = $mysql->real_escape_string($content);

      $sql = "UPDATE `post` SET category_id=$category, title='$title', content='$content' WHERE id=$postId";
      $sqlResult = $mysql->query($sql);
      if (!$sqlResult) {
        $resultData->setData("서버에 문제가 발생하였습니다" , WritePostCallBack::RESULT_ERROR_DATABASE);
      } else {
        $resultData->setData("성공적으로 글을 수정하였습니다" , WritePostCallBack::RESULT_SUCCESS, $mysql->insert_id);
      }
    }

    $result->callBackResult($resultData);
  }

  function removePost($postId, ErrorCallBack $result) {
    $mysql = $this->mysql;
    $resultData = new ResultData();

    //댓글 삭제
    $sql = "DELETE FROM `comment` WHERE post_id='$postId'";
    $sqlResult = $mysql->query($sql);

    //파일 삭제
    // $sql = "DELETE FROM `file` WHERE post_id='$postId'";
    // $sqlResult = $mysql->query($sql);

    if (!$sqlResult) {
      $resultData->setData("서버에 문제가 발생하였습니다" , ErrorCallBack::RESULT_FAILED);
    } else {
      $sql = "DELETE FROM `post` WHERE id='$postId'";
      $sqlResult = $mysql->query($sql);

      if (!$sqlResult) {
        $resultData->setData("서버에 문제가 발생하였습니다" , ErrorCallBack::RESULT_FAILED);
      }else {
        $resultData->setData("성공적으로 글을 삭제하였습니다" , ErrorCallBack::RESULT_SUCCESS);
      }
    }

    $result->callBackResult($resultData);
  }

  function getPostListLimit($categoryId, $start, $count) {
    $mysql = $this->mysql;

    $sql = "SELECT * FROM `post` WHERE category_id=$categoryId ORDER BY `id` DESC LIMIT $start, $count";
    $result = $mysql->query($sql);
    $postList = array();

    while($row = $result->fetch_assoc()) {
      $post = new Post($row['id'], $row['category_id'], $row['title'], $row['content'], $row['date']);
      array_push($postList, $post);
    }

    return $postList;
  }

  function getPostList($categoryId) {
    $mysql = $this->mysql;
    $sql = "SELECT * FROM `post` WHERE category_id=$categoryId ORDER BY `id` DESC";
    $result = $mysql->query($sql);
    $postList = array();

    while($row = $result->fetch_assoc()) {
      $post = new Post($row['id'], $row['category_id'], $row['title'], $row['content'], $row['date']);
      array_push($postList, $post);
    }

    return $postList;
  }

  function getRecentPost($categoryId) {
    $mysql = $this->mysql;
    $sql = "SELECT * FROM `post` WHERE category_id=$categoryId ORDER BY `id` DESC LIMIT 1";
    $result = $mysql->query($sql);
    $row = $result->fetch_assoc();
    $post = new Post($row['id'], $row['category_id'], $row['title'], $row['content'], $row['date']);
    return $post;
  }

  function getPostById($categoryId, $postId) {
    $mysql = $this->mysql;
    $sql = "SELECT * FROM `post` WHERE category_id=$categoryId AND id=$postId";
    $result = $mysql->query($sql);
    $row = $result->fetch_assoc();
    $post = new Post($row['id'], $row['category_id'], $row['title'], $row['content'], $row['date']);
    return $post;
  }

  function writeComment($postId, $userId, $guestName, $message) {
    $mysql = $this->mysql;
    $guestName = $mysql->real_escape_string(htmlspecialchars($guestName));
    $message = $mysql->real_escape_string(htmlspecialchars($message));
    $authorIp = $_SERVER['REMOTE_ADDR'];
    $sql = "INSERT INTO `comment` (post_id, user_id, guest_name, message, author_ip) VALUES ($postId, $userId, '$guestName', '$message', '$authorIp')";
    $result = $mysql->query($sql);
  }

  function getCommentList($postId) {
    $mysql = $this->mysql;
    $sql = "SELECT * FROM `comment` WHERE post_id=$postId ORDER BY `id` DESC";
    $result = $mysql->query($sql);
    $commentList = array();

    while($row = $result->fetch_assoc()) {
      $comment = new Comment($row['id'], $row['post_id'], $row['user_id'], $row['guest_name'], $row['message'], $row['author_ip']);
      array_push($commentList, $comment);
    }

    return $commentList;
  }

  function getCommentListLimit($postId, $start, $count) {
    $mysql = $this->mysql;
    $sql = "SELECT * FROM `comment` WHERE post_id=$postId ORDER BY `id` DESC LIMIT $start, $count";
    $result = $mysql->query($sql);
    $commentList = array();

    while($row = $result->fetch_assoc()) {
      $comment = new Comment($row['id'], $row['post_id'], $row['user_id'], $row['guest_name'], $row['message'], $row['author_ip']);
      array_push($commentList, $comment);
    }

    return $commentList;
  }

  function uploadFile($postId, $name, $ext, $size, $path) {
    $mysql = $this->mysql;
    $name = $mysql->real_escape_string($name);
    $ext = $mysql->real_escape_string($ext);
    $path = $mysql->real_escape_string($path);
    $sql = "INSERT INTO `file` WHERE (post_id, name, ext, size, path) VALUES($postId, '$name', '$ext', $size, '$path')";
    $result = $mysql->query($sql);
  }

  //싱글톤 인스턴스
  protected function __construct($mysql) { $this->mysql = $mysql; }

  protected function __clone() { }

  public static function getInstance($mysql) {
    static $instance = null;
    if ($instance === null) {
      $instance = new static($mysql);
    }
    return $instance;
  }
}
 ?>
