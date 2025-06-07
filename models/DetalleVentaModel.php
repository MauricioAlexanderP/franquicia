<?php

namespace models;

use libs\Model;
use libs\IModel;

class DetalleVentaModel extends Model implements IModel
{
  private $detalle_venta_id;
  private $venta_id;
  private $producto_id;
  private $cantidad;
  private $precio_unitario;

  public function __construct()
  {
    parent::__construct();
    $this->detalle_venta_id = null;
    $this->venta_id = null;
    $this->producto_id = null;
    $this->cantidad = 0;
    $this->precio_unitario = 0.0;
  }

  public function save()
  {
    try {
      $query = "INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario)
                VALUES ('$this->venta_id', '$this->producto_id', '$this->cantidad', '$this->precio_unitario')";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("DETALLEVENTAMODEL::save -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function getAll()
  {
    $items = [];

    try {
      $query = $this->db->consulta("
        SELECT dv.detalle_venta_id, dv.venta_id, dv.producto_id, 
              p.nombre as producto_nombre, dv.cantidad, dv.precio_unitario
        FROM detalle_venta dv
        INNER JOIN producto p ON dv.producto_id = p.producto_id
      ");

      while ($row = $query->fetch_assoc()) {
        $item = [
          'detalle_venta_id' => $row['detalle_venta_id'],
          'venta_id' => $row['venta_id'],
          'producto_id' => $row['producto_id'],
          'producto_nombre' => $row['producto_nombre'],
          'cantidad' => $row['cantidad'],
          'precio_unitario' => $row['precio_unitario']
        ];
        array_push($items, $item);
      }
      return $items;
    } catch (\Throwable $th) {
      error_log("DETALLEVENTAMODEL::getAll -> Error: " . $th->getMessage());
      return [];
    }
  }

  public function getByVenta($venta_id)
  {
    $items = [];

    try {
      $query = $this->db->consulta("
        SELECT dv.detalle_venta_id, dv.producto_id, 
              p.nombre as producto_nombre, p.imagen as producto_imagen,
              dv.cantidad, dv.precio_unitario
        FROM detalle_venta dv
        INNER JOIN producto p ON dv.producto_id = p.producto_id
        WHERE dv.venta_id = $venta_id
      ");

      while ($row = $query->fetch_assoc()) {
        $item = [
          'detalle_venta_id' => $row['detalle_venta_id'],
          'producto_id' => $row['producto_id'],
          'producto_nombre' => $row['producto_nombre'],
          'producto_imagen' => $row['producto_imagen'],
          'cantidad' => $row['cantidad'],
          'precio_unitario' => $row['precio_unitario'],
          'subtotal' => $row['cantidad'] * $row['precio_unitario']
        ];
        array_push($items, $item);
      }
      return $items;
    } catch (\Throwable $th) {
      error_log("DETALLEVENTAMODEL::getByVenta -> Error: " . $th->getMessage());
      return [];
    }
  }

  public function get($id)
  {
    try {
      $query = "SELECT * FROM detalle_venta WHERE detalle_venta_id = '$id'";
      $rs = $this->db->consulta($query);
      $detalle = $rs->fetch_assoc();

      $this->setDetalleVentaId($detalle['detalle_venta_id']);
      $this->setVentaId($detalle['venta_id']);
      $this->setProductoId($detalle['producto_id']);
      $this->setCantidad($detalle['cantidad']);
      $this->setPrecioUnitario($detalle['precio_unitario']);
      return $this;
    } catch (\Throwable $th) {
      error_log("DETALLEVENTAMODEL::get -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function delete($id)
  {
    try {
      $query = "DELETE FROM detalle_venta WHERE detalle_venta_id = $id";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("DETALLEVENTAMODEL::delete -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function update()
  {
    try {
      $query = "UPDATE detalle_venta SET 
                venta_id = '$this->venta_id', 
                producto_id = '$this->producto_id', 
                cantidad = '$this->cantidad', 
                precio_unitario = '$this->precio_unitario' 
                WHERE detalle_venta_id = '$this->detalle_venta_id'";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("DETALLEVENTAMODEL::update -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function from($array)
  {
    $this->detalle_venta_id = $array['detalle_venta_id'] ?? null;
    $this->venta_id = $array['venta_id'] ?? null;
    $this->producto_id = $array['producto_id'] ?? null;
    $this->cantidad = $array['cantidad'] ?? 0;
    $this->precio_unitario = $array['precio_unitario'] ?? 0.0;
  }

  public function toArray()
  {
    return [
      'detalle_venta_id' => $this->detalle_venta_id,
      'venta_id' => $this->venta_id,
      'producto_id' => $this->producto_id,
      'cantidad' => $this->cantidad,
      'precio_unitario' => $this->precio_unitario
    ];
  }

  // Getters y Setters
  public function setDetalleVentaId($detalle_venta_id)
  {
    $this->detalle_venta_id = $detalle_venta_id;
  }
  public function getDetalleVentaId()
  {
    return $this->detalle_venta_id;
  }
  public function setVentaId($venta_id)
  {
    $this->venta_id = $venta_id;
  }
  public function getVentaId()
  {
    return $this->venta_id;
  }
  public function setProductoId($producto_id)
  {
    $this->producto_id = $producto_id;
  }
  public function getProductoId()
  {
    return $this->producto_id;
  }
  public function setCantidad($cantidad)
  {
    $this->cantidad = $cantidad;
  }
  public function getCantidad()
  {
    return $this->cantidad;
  }
  public function setPrecioUnitario($precio_unitario)
  {
    $this->precio_unitario = $precio_unitario;
  }
  public function getPrecioUnitario()
  {
    return $this->precio_unitario;
  }
}
