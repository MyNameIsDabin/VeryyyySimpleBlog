<?php
class ResultData {
  private $resultMessage = "";
  private $resultCode;
  private $value;

  public function setData($resultMessage, $resultCode, $value=null) {
    $this->resultMessage = $resultMessage;
    $this->resultCode = $resultCode;
    $this->value = $value;
  }

  public function getResultMessage() {
    return $this->resultMessage;
  }

  public function getResultCode() {
    return $this->resultCode;
  }

  public function getValue() {
    return $this->value;
  }

  public function getJsonData() {
    $arr = array(
      'resultMessage'=>$this->resultMessage,
      'resultCode'=>$this->resultCode
    );

    return json_encode($arr);
  }
}

interface ErrorCallBack {
  const RESULT_SUCCESS = 0;
  const RESULT_FAILED = 1;

  public function callBackResult($resultData);
}

interface RegisterCallBack extends ErrorCallBack {
  const RESULT_NOT_FIND_EMAIL = 2;        //이메일 못찾음
  const RESULT_NOT_FIND_PASSWORD = 3;     //패스워드 못찾음
  const RESULT_ALREADY_REGISTERED  = 4;   //이미 등록된 계정
  const RESULT_ERROR_DATABASE = 5;        //데이터베이스 에러

  public function callBackResult($resultData);
}

interface LoginCallBack extends ErrorCallBack {
  const RESULT_NOT_FIND_EMAIL = 2;        //이메일 못찾음
  const RESULT_NOT_FIND_PASSWORD = 3;     //패스워드 못찾음
  const RESULT_UNREGISTERED = 4;          //등록되지 않은 계정
  const RESULT_NOT_MATCH_PASSWORD = 5;    //비밀번호가 일치하지 않음
  const RESULT_ERROR_DATABASE = 6;        //데이터베이스 에러

  public function callBackResult($resultData);
}

interface WritePostCallBack extends ErrorCallBack {
  const RESULT_NOT_FIND_TITLE = 2;    //타이틀을 기입하지 않음
  const RESULT_NOT_FIND_CONTENT = 3;  //내용을 기입하지 않음
  const RESULT_ERROR_DATABASE = 4;        //데이터베이스 에러

  public function callBackResult($resultData);
}

 ?>
