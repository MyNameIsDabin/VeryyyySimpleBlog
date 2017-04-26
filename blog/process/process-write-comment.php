<?php
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/manager/post-manager.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/database.php");
  session_start();

  $mysql = new mysqli($config['host'], $config['db_user'], $config['db_password'], $config['db_name']);
  $postManager = PostManager::getInstance($mysql);

  if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
  } else {
    $userId = -1;
  }

  $categoryId = $_GET['category'];
  $postId = $_GET['post'];
  $page = $_GET['page'];

  echo $userId;

  $postManager->writeComment($postId, $userId, $_POST['guest_name'], $_POST['message']);

  header("Location:/blog/index.php?category=$categoryId&page=$page&post=$postId");
 ?>
