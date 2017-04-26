<?php
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/manager/post-manager.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/database.php");
  session_start();

  $mysql = new mysqli($config['host'], $config['db_user'], $config['db_password'], $config['db_name']);
  $postManager = PostManager::getInstance($mysql);

  $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/blog/storage/';
  $uploadfile = $uploaddir.basename($_FILES['upload']['name']);
  $ext = pathinfo($uploadfile, PATHINFO_EXTENSION);

  if (!in_array($ext, array('txt', 'png', 'jpg'))) {
    echo $ext." 파일은 지원하지 않는 확장자 입니다.";
  }
  else {
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploadfile)) {
      $CKEditFuncNum = $_GET['CKEditorFuncNum'];
      $url = "/blog/storage/".$_FILES['upload']['name'];
      $postManager->uploadFile($postId, $name, $ext, $size, $path);
      echo "<script type='text/javascript'>";
      echo "window.parent.CKEDITOR.tools.callFunction('$CKEditFuncNum', '$url', '업로드 성공');";
      echo "</script>";
    } else {
      echo "파일 업로드에 문제가 발생하였습니다.";
    }
  }
 ?>
