<?php
require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/manager/post-manager.php");
require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/database.php");

$list_max = 3; //최대 볼 수 있는 포스트 수
$page_max = 5; //최대 볼 수 있는 페이지 수
$comment_defulat = 10; //처음에 표시될 댓글 수

if (!isset($_GET['page'])) {
  $page = 0;
} else {
  $page = $_GET['page'];
}

$mysql = new mysqli($config['host'], $config['db_user'], $config['db_password'], $config['db_name']);
$postManager = PostManager::getInstance($mysql);

$categoryId = $_GET['category'];

$postList = $postManager->getPostList($categoryId);
$post_count = count($postList);
$page_count = $post_count/$list_max;
$page_front = ((int)($page / $page_max)) * $page_max;
$page_back = min($page_front + $page_max - 1, $page_count - 1);
$postList = $postManager->getPostListLimit($categoryId, $page*$list_max, $list_max);

if ($post_count == 0){
  echo ':( 작성된 게시글이 없습니다';
  exit;
}

if (isset($_GET['post'])) {
  $post = $postManager->getPostById($categoryId, $_GET['post']);
} else {
  $post = $postManager->getRecentPost($categoryId);
}

$post_id = $post->getId();
$commentList = $postManager->getCommentListLimit($post_id, 0, $comment_defulat+1);
$commentCount = count($commentList);
$commentCount -= ($commentCount>$comment_defulat?1:0);
?>

<!-- 게시글 수정 메뉴 -->
<?php
  if (isset($_SESSION['user_email'])) {
    echo "
    <div id='post-edit-menu'>
      <form id='form-post-menu' method='post'>".
        "<button type='submit' class='normal-button' onclick="."setFormActionWithData('form-post-menu','/blog/index.php','selectMenu','write','postId','$post_id','category','$categoryId');"."><i class='icon-edit'></i>수정</button>".
        "<button type='submit' class='normal-button' onclick="."setFormActionWithData('form-post-menu','/blog/process/process-remove-post.php','postId','$post_id','category','$categoryId');"."><i class='icon-cancel'></i>삭제</button>".
      "</form>
    </div>";
}?>

<!-- 게시글 내용  -->
<div class="show-post">
  <h1>" <?php echo htmlspecialchars($post->getTitle());?> "</h1>
  <hr>
  <div>
    <?php echo strip_tags($post->getContent(), '<blockquote><s><em><strong><img><a><h1><h2><h3><h4><h5><ul><ol><li><table><tbody><thead><tr><td><img>');?>
  </div>
</div>

<!-- 댓글 목록 -->
<div class="comment-write-box">
<?php
  for($i=0; $i<$commentCount; $i++) {
    $comment = $commentList[$i];
    $id = $comment->getId();
    $name = $comment->getGuestName();
    $message = $comment->getMessage();
    echo "<p class='comment-name'><i class='icon-comment-1'></i> $name</p><p class='comment-message'>$message</p>";
  }

  $action = "/blog/process/process-write-comment.php?category=$categoryId&post=$post_id&page=$page";
  echo "<form id='form-write-comment' action='$action' method='post'>"; ?>
    <p><input type="text" name="guest_name" placeholder="댓글 작성자" required></p>
    <p><textarea name="message" rows="3" cols="80" placeholder="내용" required></textarea></p>
    <p><button class="red-button" type="submit">작성완료</button></p>
  </form>
</div>
<div id="more-comment-box">
  <?php echo "
  <input type='hidden' name='post-id' value='$post_id'>
  <input type='hidden' name='comment-count' value='$commentCount'>";
  if (count($commentList) > $comment_defulat) {
    echo '<div>댓글 더 보기</div>';
  }
  ?>
</div>
<!-- 게시글 목록  -->
<?php
echo "<div class='table'> <table>";
foreach ($postList as $post) {
  $id = $post->getId();
  $title = $post->getTitle();
  $date = $post->getDate();
  $date = substr($date, 0, 10);

  echo "<tr>";
  if ($id === $post_id) {
    echo "<td class='active'><a href='/blog?category=$categoryId&page=$page&post=$id'>$title</a></td>
      <td class='active'>$date</td>";
  } else {
    echo "<td><a href='/blog?category=$categoryId&page=$page&post=$id'>$title</a></td>
      <td>$date</td>";
  }
  echo "</tr>";
}
echo "</table> </div>";
?>
<!-- 페이지 버튼 -->
<div class="paging-list">
  <ul>
  <?php
    if ($page_max < $page+1) {
      echo '<li><a href="/blog?category='.$categoryId.'&page='.($page_front-1).'">◀</a></li>';
    }
    for ($i=$page_front; $i<$page_back+1; $i++) {
      if ($i==$page){
          echo '<li class="active"><a href="/blog?category='.$categoryId.'&page='.$i.'">'.($i+1).'</a></li>';
      }
      else {
        echo '<li><a href="/blog?category='.$categoryId.'&page='.$i.'">'.($i+1).'</a></li>';
      }
    }
    if ($page_count > $page_max && ($page_count-$page_back) > 1) {
      echo '<li><a href="/blog?category='.$categoryId.'&page='.($page_back+1).'">▶</a></li>';
    }
  ?>
  </ul>
</div>
