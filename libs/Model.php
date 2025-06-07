<?php

namespace libs;
use libs\IModel;
use libs\Conexion;

class Model
{
  protected $db;

  public function __construct()
  {
    $this->db = new Conexion();
  }

  public function consulta($sql){
    return $this->db->consulta($sql);
  }
  public function beginTransaction()
  {
    $this->db->beginTransaction();
  }

  public function commit()
  {
    $this->db->commit();
  }

  public function rollBack()
  {
    $this->db->rollBack();
  }

  public function getLastInsertId()
  {
    return $this->db->getLastInsertId();
  }

}
