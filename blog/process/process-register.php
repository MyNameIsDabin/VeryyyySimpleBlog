<?php
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/manager/user-manager.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/database.php");

  $mysql = new mysqli($config['host'], $config['db_user'], $config['db_password'], $config['db_name']);
  $userManager = UserManager::getInstance($mysql);

  $userManager->register($_POST['email'], $_POST['password'], new class implements RegisterCallBack {
    public function callBackResult($resultData) {

      switch($resultData->getResultCode()) {
        case RegisterCallBack::RESULT_NOT_FIND_EMAIL :
        break;
        case RegisterCallBack::RESULT_NOT_FIND_PASSWORD :
        break;
        case RegisterCallBack::RESULT_ERROR_DATABASE :
        break;
        case RegisterCallBack::RESULT_SUCCESS :
        break;
        case RegisterCallBack::RESULT_ALREADY_REGISTERED :
        break;
        default :
        break;
      }

      echo $resultData->getJsonData();
    }
  });
 ?>
