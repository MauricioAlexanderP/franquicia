<?php

namespace models;

use libs\Model;
use libs\IModel;

class InventarioModel extends Model implements IModel
{
  //private $inventario_id;
  private $tienda_id;
  private $producto_id;
  private $stock;
  private $stock_minimo;
  private $estado;

  public function __construct()
  {
    parent::__construct();
    // $this->inventario_id = null;
    $this->tienda_id = null;
    $this->producto_id = null;
    $this->stock = 0;
    $this->stock_minimo = 10;
    $this->estado = 1; // Activo
  }

  public function save()
  {
    try {
      $sql = "INSERT INTO inventario (tienda_id, producto_id, stock, stock_minimo, estado)
              VALUES ('$this->tienda_id', '$this->producto_id', '$this->stock', '$this->stock_minimo', '$this->estado')";
      $this->db->consulta($sql);
      return true;

    } catch (\Throwable $th) {
      error_log("INVENTARIOMODEL::save -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function getAll()
  {
    // TODO
  }

  public function get($id)
  {
    // TODO
  }

  public function delete($id)
  {
    // TODO
  }

  public function update()
  {
    // TODO
  }

  public function from($array)
  {
    // TODO
  }

  public function toArray()
  {
    return [
      'tienda_id' => $this->tienda_id,
      'producto_id' => $this->producto_id,
      'stock' => $this->stock,
      'stock_minimo' => $this->stock_minimo,
      'estado' => $this->estado
    ];
  }

  public function setTiendaId($tienda_id)
  {
    $this->tienda_id = $tienda_id;
  }
  public function getTiendaId()
  {
    return $this->tienda_id;
  }
  public function setProductoId($producto_id)
  {
    $this->producto_id = $producto_id;
  }
  public function getProductoId()
  {
    return $this->producto_id;
  }
  public function setStock($stock)
  {
    $this->stock = $stock;
  }
  public function getStock()
  {
    return $this->stock;
  }
  public function setStockMinimo($stock_minimo)
  {
    $this->stock_minimo = $stock_minimo;
  }
  public function getStockMinimo()
  {
    return $this->stock_minimo;
  }
}
