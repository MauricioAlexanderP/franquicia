<?php

namespace models;

use libs\Model;
use libs\IModel;
use models\UserModel;

class LoginModel extends Model
{
  function __construct()
  {
    parent::__construct();
  }

  function login($username, $password)
  {
    try {
      $sql = "SELECT * FROM usuario WHERE nombre_usuario = '$username' AND contraseña = '$password'";
      $rs = $this->db->consulta($sql);
      $item = $rs->fetch_assoc();
      if ($item != null) {
        error_log("LOGINMODEL::login-item =>  success");
        $user = new UserModel();
        $user->from($item);
      }else{
        error_log("LOGINMODEL::login =>  failed to execute query");
        return null;
      }
      //password_verify($password, $user->getPassword()
      if($password == $user->getPassword()) {
        error_log("LOGINMODEL::login =>  success");
        return $user;
      } else {
        error_log("LOGINMODEL::login =>  failed");
        return null;
      }
    } catch (\Throwable $th) {
      error_log("LOGINMODEL::login => " . $th->getMessage());
    }
  }
}
