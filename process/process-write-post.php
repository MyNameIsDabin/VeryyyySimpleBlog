<?php
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/manager/post-manager.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/database.php");

  $mysql = new mysqli($config['host'], $config['db_user'], $config['db_password'], $config['db_name']);
  $postManager = PostManager::getInstance($mysql);

  $postManager->writePost($_POST['category'], $_POST['title'], $_POST['content'], new class implements WritePostCallBack {
    public function callBackResult($resultData) {

      switch($resultData->getResultCode()) {
      case WritePostCallBack::RESULT_NOT_FIND_TITLE :
      break;
      case WritePostCallBack::RESULT_NOT_FIND_CONTENT :
      break;
      case WritePostCallBack::RESULT_ERROR_DATABASE :
      break;
      case WritePostCallBack::RESULT_SUCCESS :
      $categoryId = $_POST['category'];
      $postId = $resultData->getValue();
      header("Location:/blog?category=$categoryId&post=$postId");
      break;
      default :
      echo "<script>
              alert('예기치 못한 오류가 발생하였습니다');
              location.href = '/blog/';
            </script>";
      break;
      }
    }
  });
 ?>
