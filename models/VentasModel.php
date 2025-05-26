<?php

namespace models;

use libs\Model;
use libs\IModel;


class VentasModel extends Model implements IModel
{
  private $venta_id;
  private $tienda_id;
  private $fecha_venta;
  private $monto_total;

  public function __construct()
  {
    parent::__construct();
    $this->venta_id = null;
    $this->tienda_id = null;
    $this->fecha_venta = date('Y-m-d H:i:s');
    $this->monto_total = 0;
  }
  public function save()
  {
    try {
      $sql = "INSERT INTO venta (tienda_id, fecha_venta, monto_total)
              VALUES ('$this->tienda_id', '$this->fecha_venta', '$this->monto_total')";
      $this->db->consulta($sql);
      return true;

    } catch (\Throwable $th) {
      error_log("VENTASMODEL::save -> Error: " . $th->getMessage());
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
      'venta_id' => $this->venta_id,
      'tienda_id' => $this->tienda_id,
      'fecha_venta' => $this->fecha_venta,
      'monto_total' => $this->monto_total,
    ];
  }

  public function setVentaId($venta_id)
  {
    $this->venta_id = $venta_id;
  }
  public function setTiendaId($tienda_id)
  {
    $this->tienda_id = $tienda_id;
  }
  public function setFechaVenta($fecha_venta)
  {
    $this->fecha_venta = $fecha_venta;
  }
  public function setMontoTotal($monto_total)
  {
    $this->monto_total = $monto_total;
  }

  public function getVentaId()
  {
    return $this->venta_id;
  }
  public function getTiendaId()
  {
    return $this->tienda_id;
  }
  public function getFechaVenta()
  {
    return $this->fecha_venta;
  }
  public function getMontoTotal()
  {
    return $this->monto_total;
  }
}
