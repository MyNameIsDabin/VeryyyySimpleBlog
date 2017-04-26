<?php
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/manager/user-manager.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/database.php");

  $mysql = new mysqli($config['host'], $config['db_user'], $config['db_password'], $config['db_name']);
  $userManager = UserManager::getInstance($mysql);

  $userManager->login($_POST['email'], $_POST['password'], new class implements LoginCallBack {
    public function callBackResult($resultData) {

      switch($resultData->getResultCode()) {
        case LoginCallBack::RESULT_NOT_FIND_EMAIL :
        break;
        case LoginCallBack::RESULT_NOT_FIND_PASSWORD :
        break;
        case LoginCallBack::RESULT_ERROR_DATABASE :
        break;
        case LoginCallBack::RESULT_SUCCESS :
        break;
        case LoginCallBack::RESULT_UNREGISTERED :
        break;
        case LoginCallBack::RESULT_NOT_MATCH_PASSWORD :
        break;
        default :
        break;
      }

      echo $resultData->getJsonData();
    }
  });
 ?>
