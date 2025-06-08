<?php

namespace models;

use libs\Model;
use libs\IModel;

class InventarioModel extends Model implements IModel
{
  private $inventario_id;
  private $tienda_id;
  private $producto_id;
  private $stock;
  private $stock_minimo;
  private $estado;
  private $imagen;
  private $ultima_actualizacion;
  private $nombre;

  public function __construct()
  {
    parent::__construct();
    $this->inventario_id = null;
    $this->tienda_id = null;
    $this->producto_id = null;
    $this->stock = 0;
    $this->stock_minimo = 10;
    $this->estado = 1; // Activo
    $this->ultima_actualizacion = date('Y-m-d');
  }

  public function save()
  {
    try {
      $sql = "INSERT INTO inventario (tienda_id, producto_id, stock, stock_minimo, estado, ultima_actualizacion)
              VALUES ('$this->tienda_id', '$this->producto_id', '$this->stock', '$this->stock_minimo', '$this->estado', '$this->ultima_actualizacion')";
      $this->db->consulta($sql);
      return true;
    } catch (\Throwable $th) {
      error_log("INVENTARIOMODEL::save -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function getAll()
  {
    $items = [];

    try {
      $query = $this->db->consulta("
      SELECT CONCAT( t.ubicacion, '-', t.encargado) as tienda_id, p.nombre, i.stock, i.stock_minimo
        FROM inventario i
        INNER JOIN tienda t on i.tienda_id = t.tienda_id
        INNER JOIN producto p on i.producto_id = p.producto_id
        WHERE i.estado = 1
      ");

      while ($p = $query->fetch_assoc()) {
        $item = new InventarioModel();
        $item->setProductoId($p['producto_id']);
        $item->setTiendaId($p['tienda_id']);
        $item->setStock($p['stock']);
        $item->setStockMinimo($p['stock_minimo']);

        array_push($items, $item);
      }
    } catch (\Throwable $th) {
      error_log("INVENTARIOMODEL::getAll -> Error: " . $th->getMessage());
      return [];
    }
    return $items;
  }
  public function getInventarioByTienda($tienda_id)
  {
    $items = [];

    try {
      $query = $this->db->consulta("
      SELECT i.tienda_id, i.inventario_id, p.producto_id, p.nombre, p.imagen, i.stock, i.stock_minimo
        FROM inventario i
        INNER JOIN tienda t on i.tienda_id = t.tienda_id
        INNER JOIN producto p on i.producto_id = p.producto_id
        WHERE t.tienda_id = $tienda_id AND i.estado = 1
      ");

      while ($p = $query->fetch_assoc()) {
        $item = new InventarioModel();
        $item->setProductoId($p['nombre']);
        $item->setTiendaId($p['tienda_id']);
        $item->setNombre($p['producto_id']);
        $item->setImagen($p['imagen']);
        $item->setStock($p['stock']);
        $item->setStockMinimo($p['stock_minimo']);
        $item->setInventarioId($p['inventario_id']);
        array_push($items, $item);
      }
    } catch (\Throwable $th) {
      error_log("INVENTARIOMODEL::getInventarioByTienda -> Error: " . $th->getMessage());
      return [];
    }
    return $items;
  }

  public function get($id) {}
  public function delete($id)
  {
    try {
      $query = "UPDATE inventario SET estado = 0 WHERE inventario_id = $id";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("INVENTARIOMODEL::delete -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function update()
  {
    try {
      $query = "UPDATE inventario SET estado = 1 WHERE tienda_id = $this->tienda_id AND producto_id = $this->producto_id";
      $rs = $this->db->consulta($query);
      $inventario = $rs->fetch_assoc();

      $this->setUltimaActualizacion(date('Y-m-d'));
      $this->setEstado($inventario['estado']);
      return $this;
    } catch (\Throwable $th) {
      error_log("INVENTARIOMODEL::update -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function from($array)
  {
    $this->inventario_id = $array['inventario_id'];
    $this->tienda_id = $array['tienda_id'];
    $this->producto_id = $array['producto_id'];
    $this->stock = $array['stock'];
    $this->stock_minimo = $array['stock_minimo'];
    $this->estado = $array['estado'];
    $this->ultima_actualizacion = $array['ultima_actualizacion'];
  }

  public function existsInTienda($tiendaId, $productoId)
  {
    $query = "SELECT COUNT(*) as count FROM inventario WHERE tienda_id = $tiendaId AND producto_id = $productoId";
    $stmt = $this->db->consulta($query);
    $result = $stmt->fetch_assoc();

    return $result['count'] > 0; // Retorna true si el producto ya está registrado
  }

  // public function toArray()
  // {
  //   return [
  //     'tienda_id' => $this->tienda_id,
  //     'producto_id' => $this->producto_id,
  //     'stock' => $this->stock,
  //     'stock_minimo' => $this->stock_minimo,
  //     'estado' => $this->estado
  //   ];
  // }
  public function getByTiendaAndProducto($tiendaId, $productoId)
  {
    $query = "SELECT i.inventario_id, i.tienda_id, i.producto_id, i.stock, 
                      i.ultima_actualizacion, i.stock_minimo, i.estado, 
                      p.nombre, p.imagen
              FROM inventario i
              INNER JOIN producto p ON p.producto_id = i.producto_id
              WHERE i.tienda_id = $tiendaId AND i.producto_id = $productoId 
              LIMIT 1";

    $stmt = $this->db->consulta($query);
    if ($row = $stmt->fetch_assoc()) {
      // Cargar datos en el modelo
      $this->inventario_id = $row['inventario_id'];
      $this->tienda_id = $row['tienda_id'];
      $this->producto_id = $row['producto_id'];
      $this->stock = $row['stock'];
      $this->ultima_actualizacion = $row['ultima_actualizacion'];
      $this->stock_minimo = $row['stock_minimo'];
      $this->estado = $row['estado'];
      $this->nombre = $row['nombre'];
      $this->imagen = $row['imagen'];

      return $this;
    }
    return null;
  }

  public function toArray()
  {
    return [
      'inventario_id' => $this->inventario_id,
      'tienda_id' => $this->tienda_id,
      'producto_id' => $this->producto_id,
      'stock' => $this->stock,
      'stock_minimo' => $this->stock_minimo,
      'estado' => $this->estado,
      'ultima_actualizacion' => $this->ultima_actualizacion,
      'nombre' => $this->nombre,
      'imagen' => $this->imagen
    ];
  }

  public function setNombre($nombre)
  {
    $this->nombre = $nombre;
  }
  public function getNombre()
  {
    return $this->nombre;
  }
  public function setInventarioId($inventario_id)
  {
    $this->inventario_id = $inventario_id;
  }
  public function getInventarioId()
  {
    return $this->inventario_id;
  }
  public function setEstado($estado)
  {
    $this->estado = $estado;
  }
  public function getEstado()
  {
    return $this->estado;
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
  public function setImagen($imagen)
  {
    $this->imagen = $imagen;
  }
  public function getImagen()
  {
    return $this->imagen;
  }
  public function setUltimaActualizacion($ultima_actualizacion)
  {
    $this->ultima_actualizacion = $ultima_actualizacion;
  }
  public function getUltimaActualizacion()
  {
    return $this->ultima_actualizacion;
  }
}
