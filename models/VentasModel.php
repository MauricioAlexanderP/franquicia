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
    $items = [];
    try {
      $query = $this->db->consulta("SELECT * FROM venta");
      while ($p = $query->fetch_assoc()) {
        $item = new VentasModel();
        $item->setVentaId($p['venta_id']);
        $item->setTiendaId($p['tienda_id']);
        $item->setFechaVenta($p['fecha_venta']);
        $item->setMontoTotal($p['monto_total']);
        array_push($items, $item);
      }
    } catch (\Throwable $th) {
      error_log("VENTASMODEL::getAll -> Error: " . $th->getMessage());
    }
    return $items;
  }

  public function getByTienda($tienda_id)
  {
    $items = [];
    try {
      $query = $this->db->consulta("SELECT * FROM venta WHERE tienda_id = '$tienda_id'");
      while ($p = $query->fetch_assoc()) {
        $item = new VentasModel();
        $item->setVentaId($p['venta_id']);
        $item->setTiendaId($p['tienda_id']);
        $item->setFechaVenta($p['fecha_venta']);
        $item->setMontoTotal($p['monto_total']);
        array_push($items, $item);
      }
    } catch (\Throwable $th) {
      error_log("VENTASMODEL::getByTienda -> Error: " . $th->getMessage());
    }
    return $items;
  }

  public function get($id)
  {
    try {
      $query = "SELECT * FROM venta WHERE venta_id = '$id'";
      $rs = $this->db->consulta($query);
      $venta = $rs->fetch_assoc();
      $this->setVentaId($venta['venta_id']);
      $this->setTiendaId($venta['tienda_id']);
      $this->setFechaVenta($venta['fecha_venta']);
      $this->setMontoTotal($venta['monto_total']);
      return $this;
    } catch (\Throwable $th) {
      error_log("VENTASMODEL::get -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function delete($id)
  {
    try {
      $query = "UPDATE venta SET estado = 0 WHERE venta_id = '$id'";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("VENTASMODEL::delete -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function update()
  {
    try {
      $query = "UPDATE venta SET tienda_id = '$this->tienda_id', 
      fecha_venta = '$this->fecha_venta', monto_total = '$this->monto_total' WHERE venta_id = '$this->venta_id'";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("VENTASMODEL::update -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function from($array)
  {
    $this->venta_id = $array['venta_id'];
    $this->tienda_id = $array['tienda_id'];
    $this->fecha_venta = $array['fecha_venta'];
    $this->monto_total = $array['monto_total'];
    return $this;
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
  //dashboard
  public function getVentasHoy($tienda_id)
  {
    $query = "SELECT SUM(monto_total) AS total 
              FROM venta 
              WHERE tienda_id = $tienda_id 
              AND DATE(fecha_venta) = CURDATE()";
    $result = $this->db->consulta($query)->fetch_assoc();
    return $result['total'] ?? 0;
  }

  public function getVentasSemana($tienda_id)
  {
    $query = "SELECT SUM(monto_total) AS total 
              FROM venta 
              WHERE tienda_id = $tienda_id 
              AND YEARWEEK(fecha_venta, 1) = YEARWEEK(CURDATE(), 1)";
    $result = $this->db->consulta($query)->fetch_assoc();
    return $result['total'] ?? 0;
  }

  public function getVentasMes($tienda_id)
  {
    $query = "SELECT SUM(monto_total) AS total 
              FROM venta 
              WHERE tienda_id = $tienda_id 
              AND MONTH(fecha_venta) = MONTH(CURDATE()) 
              AND YEAR(fecha_venta) = YEAR(CURDATE())";
    $result = $this->db->consulta($query)->fetch_assoc();
    return $result['total'] ?? 0;
  }

  public function getTendenciaVentas($tienda_id)
  {
    $query = "SELECT DATE(fecha_venta) AS fecha, SUM(monto_total) AS total
              FROM venta 
              WHERE tienda_id = $tienda_id 
              AND fecha_venta >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
              GROUP BY DATE(fecha_venta)
              ORDER BY fecha";
    return $this->db->consulta($query)->fetch_all(MYSQLI_ASSOC);
  }

  public function getTotalVentas()
  {
    $query = "SELECT COUNT(*) AS total FROM venta";
    $result = $this->db->consulta($query)->fetch_assoc();
    return $result['total'] ?? 0;
  }

  public function getLastInsertId()
  {
    return $this->db->getLastInsertId();
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
