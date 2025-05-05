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
  public function prepare($sql){
    return $this->db->prepare($sql);
  }

}
