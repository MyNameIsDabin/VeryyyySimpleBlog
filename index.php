<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/blog/css/main.css">
  <link rel="stylesheet" href="/blog/css/drop-down.css">
  <link rel="stylesheet" href="/blog/css/article-board.css">
  <link rel="stylesheet" href="/blog/css/article-write.css">
  <link rel="stylesheet" href="/blog/css/article-home.css">
  <link rel="stylesheet" href="/blog/css/article-post.css">
  <link rel="stylesheet" href="/blog/fontello/css/fontello.css">
  <link href="http://fonts.googleapis.com/earlyaccess/jejugothic.css" rel="stylesheet">
  <script type="text/javascript" src="/blog/lib/base-util.js"></script>
  <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
  <title>블로그</title>
</head>
<body>

  <!-- 상단 메뉴  -->
    <div id="top-menu">
<?php
    session_start();
    if (!isset($_SESSION['user_email'])) {
      ?><button type="button" onclick="openPopUpById('login-popup');closePopUpById('common-popup');">로그인</button><?php
    } else {
      ?>
      <form id="top-menu-form" method="post">
        <button type="submit" name="button" onclick="setFormActionWithData('top-menu-form', '/blog/index.php', 'selectMenu', 'write');"><i class="icon-pencil"></i>글쓰기</button>
        <button type="submit" name="button" onclick="setFormActionWithData('top-menu-form', '/blog/index.php', 'selectMenu', 'manage');"><i class="icon-cog"></i>설정</button>
        <button type="submit" name="button" onclick="setFormActionWithData('top-menu-form', '/blog/process/process-logout.php');">로그아웃</button>
      </form>
      <?php
  }
?>
  </div>
  <header>
    <div id="title">
      <h1><a href="/blog" id="title-name">다빈의 블로그</a></h1>
    </div>
  </header>
  <!-- 메뉴  -->
  <aside>
    <div id="drop-down-menu">
      <ul>
        <li><a class="menu-btn" href="javascript:moveUrlWithPost('/blog/index.php','selectMenu','home');">홈</a></li>
        <!-- <li><a class="menu-btn" href="javascript:moveUrlWithPost('/blog/index.php','selectMenu','guestbook');">방명록</a></li> -->
        <li>카테고리
          <ul>
            <li><a href="/blog?category=3">아무말 대잔치</a></li>
            <!-- <li><a href="/blog?category=4">작업물</a></li> -->
          </ul>
        </li>
      </ul>
    </div>
  </aside>
  <!-- 본문  -->
  <article>
    <?php
    if (isset($_GET['category'])){
      include("./view/article-post-board.php");
    } else if (isset($_POST['selectMenu'])){
      include("./view/article-".$_POST["selectMenu"].".php");
    } else {
      include("./view/article-home.php");
    }
    ?>
  </article>
  <!-- 로그인 팝업 -->
  <div id="login-popup">
    <div>
      <h3>로그인</h3><i id="login-popup-close-btn" class="icon-cancel"></i>
        <p><input id="login-form-email" type="email" name="email" placeholder="이메일" required></p>
        <p><input id="login-form-password" type="password" name="password" placeholder="비밀번호" required></p>
        <!-- <button id="btn-register" class="login-popup-btn" type="button">계정 등록</button> -->
        <button id="btn-login" class="login-popup-btn" type="button">확인</button>
    </div>
  </div>
  <!-- 공용팝업 -->
  <div id="common-popup">
    <div>
      <h3 id="common-popup-title">타이틀</h3>
      <div id="common-popup-message">메세지</div>
      <button id="common-popup-btn" class="login-popup-btn" type="button" onclick="closePopUpById('common-popup');">확인</button>
    </div>
  </div>

  <!-- <footer>
    <div>E-mail : dorothy9795@gmail.com</div>
  </footer> -->
  <script type="text/javascript" src="/blog/main.js"></script>
</body>
</html>
