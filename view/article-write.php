<?php
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/manager/post-manager.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/database.php");

  $mysql = new mysqli($config['host'], $config['db_user'], $config['db_password'], $config['db_name']);
  $postManager = PostManager::getInstance($mysql);
  $categoryList = $postManager->getCategoryList();

  //POST 데이터를 전달받았으면, 글 수정에 해당한다.
  if (isset($_POST['postId']) && isset($_POST['category'])) {
    $postId = $_POST['postId'];
    $categoryId = $_POST['category'];
    $post = $postManager->getPostById($categoryId, $postId);
    $title = $post->getTitle();
    $content = $post->getContent();
    $isEditMode = true;
  } else {
    $isEditMode = false;
  }
?>

<div id="article-write">
  <?php
  if (!$isEditMode) {
    echo '<form id="post-form" enctype="multipart/form-data" action="/blog/process/process-write-post.php" method="post">';
  } else {
    echo '<form id="post-form" enctype="multipart/form-data" action="/blog/process/process-edit-post.php" method="post">';
    echo "<input type='hidden' name='postId' value='$postId'>";
  }

  ?>
  <div class="title-container">
  <?php
    echo '<select name="category">';
    foreach ($categoryList as $id => $name) {
      if ($categoryId == $id) {
        echo '<option value="'.$id.'" selected>'.$name.'</option>';
      } else {
        echo '<option value="'.$id.'">'.$name.'</option>';
      }
    }
    echo '</select>';
    if ($isEditMode) {
      echo "<input type='text' name='title' placeholder='제목' value='$title' required>";
    } else {
      echo '<input type="text" name="title" placeholder="제목" required>';
    }
    ?>
  </div>
<?php
if ($isEditMode) {
  echo '<textarea id="post-edit" name="content" rows="20" cols="80" placeholder="내용">'.$content.'</textarea>';
} else {
  echo '<textarea id="post-edit" name="content" rows="20" cols="80" placeholder="내용"></textarea>';
}
 ?>
  <?php
    if ($isEditMode) {
      echo '<button class="red-button" type="submit" name="button">수정완료</button>';
    } else {
      echo '<button class="red-button" type="submit" name="button">작성완료</button>';
    }
  ?>
  </form>
</div>

<script>
  //CKEDITOR (위지윅 에디터)
  CKEDITOR.replace('post-edit', {
      filebrowserUploadUrl: '/blog/process/process-file-upload.php'
  });

  //   CKEDITOR.on('dialogDefinition', function(event) {
  //     var dialogName = event.data.name;
  //     var dialogDefinition = event.data.definition;
  //     var uploadForm = dialogDefinition.contents[2].elements[0];
  //     var dataInput = document.createElement('input');
  //     dataInput.setAttribute('type', 'hidden');
  //     dataInput.setName('postId', getElementById(''));
  //     console.log(uploadForm);
  //   });
</script>
