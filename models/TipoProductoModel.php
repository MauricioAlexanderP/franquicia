<?php

namespace models;

use libs\Model;
use libs\IModel;

class TipoProductoModel extends Model implements IModel
{
  private $tipo_producto_id;
  private $catalogo;
  private $descripcion;
  private $estado;

  public function __construct()
  {
    parent::__construct();
    $this->tipo_producto_id = null;
    $this->catalogo = '';
    $this->descripcion = '';
    $this->estado = 1;
  }

  public function save()
  {
    try {
      $query = "INSERT INTO tipo_producto (catalogo, descripcion) 
      VALUES ('$this->catalogo', '$this->descripcion')";
      $this->db->consulta($query);
      return true;

    } catch (\Throwable $th) {
      error_log("TIPOPRODUCTOMODEL::save -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function getAll()
  {
    $items = [];
    try {
      $query = $this->db->consulta("SELECT * FROM tipo_producto WHERE estado = 1");

      while ($p = $query->fetch_assoc()) {
        
        $item = new TipoProductoModel();
        $item->setTipoProductoId($p['tipo_producto_id']);
        $item->setCatalogo($p['catalogo']);
        $item->setDescripcion($p['descripcion']);
        array_push($items, $item);
      }
      return $items;
    } catch (\Throwable $th) {
      error_log("TIPOPRODUCTOMODEL::getAll -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function get($id)
  {
    $query = "SELECT * FROM tipo_producto WHERE tipo_producto_id = $id";
    $rs = $this->db->consulta($query);
    $items = $rs->fetch_assoc();

    $this->setTipoProductoId($items['tipo_producto_id']);
    $this->setCatalogo($items['catalogo']);
    $this->setDescripcion($items['descripcion']);
    return $this;
  }

  public function delete($id)
  {
    try {
      $query = "UPDATE tipo_producto SET estado = 0 WHERE tipo_producto_id = $id";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("TIPOPRODUCTOMODEL::delete -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function update()
  {
    try {
      $query = "UPDATE tipo_producto SET catalogo = '$this->catalogo',
      estado = '$this->estado',
      descripcion = '$this->descripcion' WHERE tipo_producto_id = $this->tipo_producto_id";
      $rs = $this->db->consulta($query);
      $items = $rs->fetch_assoc();

      $this->setTipoProductoId($items['tipo_producto_id']);
      $this->setCatalogo($items['catalogo']);
      $this->setDescripcion($items['descripcion']);
      return $this;
    } catch (\Throwable $th) {
      error_log("TIPOPRODUCTOMODEL::update -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function from($array)
  {
    $this->setTipoProductoId($array['tipo_producto_id']);
    $this->setCatalogo($array['catalogo']);
    $this->setDescripcion($array['descripcion']);
    return $this;
  }

  public function toArray()
  {
    return [
      'tipo_producto_id' => $this->getTipoProductoId(),
      'catalogo' => $this->getCatalogo(),
      'descripcion' => $this->getDescripcion(),
      'estado' => $this->getEstado()
    ];
  }

  public function getTipoProductoId()
  {
    return $this->tipo_producto_id;
  }
  public function setTipoProductoId($tipo_producto_id)
  {
    $this->tipo_producto_id = $tipo_producto_id;
  }
  public function getCatalogo()
  {
    return $this->catalogo;
  }
  public function setCatalogo($catalogo)
  {
    $this->catalogo = $catalogo;
  }
  public function getDescripcion()
  {
    return $this->descripcion;
  }
  public function setDescripcion($descripcion)
  {
    $this->descripcion = $descripcion;
  }
  public function getEstado()
  {
    return $this->estado;
  }
  public function setEstado($estado)
  {
    $this->estado = $estado;
  }
}
