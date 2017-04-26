<?php
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/manager/post-manager.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/database.php");

  $addComentCount = 5;        //추가될 코멘트의 수
  $postId = $_POST['postId'];
  $count = $_POST['count'];

  $mysql = new mysqli($config['host'], $config['db_user'], $config['db_password'], $config['db_name']);
  $postManager = PostManager::getInstance($mysql);

  $jsonData = array('isLastComment' => false, 'commentList' => array());
  //댓글이 받아온 이후에도 더 있는지 확인하기 위해 1개 더 받아온다.
  $commentList = $postManager->getCommentListLimit($postId, $count, $addComentCount+1);

  if (count($commentList) <= $addComentCount) {
    $jsonData['isLastComment'] = true;
  }

  for($i=0; $i<count($commentList)-($jsonData['isLastComment']?0:1); $i++) {
    $comment = $commentList[$i];
    array_push($jsonData['commentList'], array(
      'guest_name' => $comment->getGuestName(),
      'message' => $comment->getMessage(),
      'author_ip' => $comment->getAuthorIp(),
    ));
  }

  // echo "포스트 id :".$postId;
  // echo "댓글 개수 :".$count;
  echo json_encode($jsonData);
 ?>
