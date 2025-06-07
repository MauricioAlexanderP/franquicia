<?php

namespace libs;

require_once 'config/config.php';
class Conexion
{
  private $con = null;

  public function __construct()
  {
    $this->con = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  public function consulta($sql)
  {
    return $this->con->query($sql);
  }
  public function beginTransaction()
  {
    $this->con->begin_transaction();
  }

  public function commit()
  {
    $this->con->commit();
  }

  public function rollBack()
  {
    $this->con->rollback();
  }

  public function getLastInsertId()
  {
    return $this->con->insert_id;
  }

  // public function prepare($sql)
  // {
  //   return $this->con->prepare($sql);
  // }


  public function secureSQL($strVar)
  {
    $banned = array("select", "drop", "|", "'", ";", "--", "insert", "delete", "xp_");
    $vowels = $banned;
    $no = str_replace($vowels, "", $strVar);
    $final = str_replace("'", "", $no);
    return $final;
  }
}
