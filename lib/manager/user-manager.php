<?php
require_once($_SERVER['DOCUMENT_ROOT']."/blog/lib/result-data.php");
session_start();

class UserManager {

  private $mysql;

  function register($email, $password, RegisterCallBack $result) {
    $mysql = $this->mysql;
    $resultData = new ResultData();

    if (empty($email)) {
      $resultData->setData("이메일을 입력하세요" , RegisterCallBack::RESULT_NOT_FIND_EMAIL);
    } else if (empty($password)) {
      $resultData->setData("비밀번호를 입력하세요" , RegisterCallBack::RESULT_NOT_FIND_PASSWORD);
    } else {
      $email = $mysql->real_escape_string($email);
      $password = $mysql->real_escape_string($password);
      $hash = password_hash($password, PASSWORD_BCRYPT);

      $sql = "SELECT * FROM `user` WHERE email='$email'";
      $sql_result = $mysql->query($sql);
      if (!$sql_result) {
        $resultData->setData("서버에 문제가 발생하였습니다" , RegisterCallBack::RESULT_ERROR_DATABASE);
      } else if ($sql_result->num_rows > 0) {
        $resultData->setData("이미 등록된 계정입니다" , RegisterCallBack::RESULT_ALREADY_REGISTERED);
      }

      $sql = 'INSERT INTO `user` (`email`, `password`, `grade`) VALUES ("'.$email.'", "'.$hash.'", 0)';
      if (!$mysql->query($sql)) {
          $resultData->setData("서버에 문제가 발생하였습니다" , RegisterCallBack::RESULT_ERROR_DATABASE);
      } else {
          $resultData->setData("계정을 등록하였습니다" , RegisterCallBack::RESULT_SUCCESS);
      }
    }
    $result->callBackResult($resultData);
  }

  function login($email, $password, LoginCallBack $result) {
    $mysql = $this->mysql;
    $resultData = new ResultData();

    if (empty($email)) {
      $resultData->setData("이메일을 입력하세요" , LoginCallBack::RESULT_NOT_FIND_EMAIL);
    } else if (empty($password)) {
      $resultData->setData("비밀번호를 입력하세요" , LoginCallBack::RESULT_NOT_FIND_PASSWORD);
    } else {
      $email = $mysql->real_escape_string($email);
      $password = $mysql->real_escape_string($password);

      $sql = "SELECT * FROM `user` WHERE email='$email'";
      if ($sql_result = $mysql->query($sql)) {
        $row = $sql_result->fetch_assoc();
        $hash = $row['password'];
        $id = $row['id'];
        if (password_verify($password, $hash)) {
          $_SESSION['user_email'] = $email;
          $_SESSION['user_id'] = $id;
          $resultData->setData("로그인 성공" , LoginCallBack::RESULT_SUCCESS);
        } else {
          if ($sql_result->num_rows == 0) {
            $resultData->setData("등록되지 않은 계정입니다" , LoginCallBack::RESULT_UNREGISTERED);
          } else {
            $resultData->setData("잘못된 비밀번호 입니다" , LoginCallBack::RESULT_NOT_MATCH_PASSWORD);
          }
        }
      } else {
        $result->callBackResult(LoginCallBack::RESULT_ERROR_DATABASE);
      }
    }

    $result->callBackResult($resultData);
  }

  //싱글톤 인스턴스
  protected function __construct($mysql) { $this->mysql = $mysql; }

  protected function __clone() { }

  public static function getInstance($mysql) {
    static $instance = null;
    if ($instance === null) {
      $instance = new static($mysql);
    }
    return $instance;
  }
}
 ?>
