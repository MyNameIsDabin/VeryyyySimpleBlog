<?php
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/manager/post-manager.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/database.php");

  $mysql = new mysqli($config['host'], $config['db_user'], $config['db_password'], $config['db_name']);
  $postManager = PostManager::getInstance($mysql);

  $postId = $_POST['postId'];
  $postManager->removePost($postId, new class implements ErrorCallBack {
    public function callBackResult($resultData) {

      switch($resultData->getResultCode()) {
      case ErrorCallBack::RESULT_SUCCESS :
      $categoryId = $_POST['category'];
      header("Location:/blog?category=$categoryId");
      break;
      case ErrorCallBack::RESULT_FAILED :
      break;
      default :
      break;
      }

    }
  });
 ?>
