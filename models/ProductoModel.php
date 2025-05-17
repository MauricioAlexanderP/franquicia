<?php

namespace models;

use libs\Model;
use libs\IModel;

class ProductoModel extends Model implements IModel
{
  private $producto_id;
  private $tipo_producto_id;
  private $nombre;
  private $descripcion;
  private $imagen;
  private $precio;
  private $estado;
  private $stock_minimo;
  private $stock;

  public function __construct()
  {
    parent::__construct();
    $this->producto_id = null;
    $this->tipo_producto_id = null;
    $this->nombre = '';
    $this->descripcion = '';
    $this->imagen = '';
    $this->precio = 0.0;
    $this->estado = 1; // Activo
    $this->stock_minimo = 0;
    $this->stock = 0;
  }


  public function save()
  {
    try {
      $query = "INSERT INTO producto (tipo_producto_id, nombre, descripcion, imagen, precio, stock)
    VALUES ('$this->tipo_producto_id', '$this->nombre', '$this->descripcion', '$this->imagen', 
    '$this->precio', '$this->stock')";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("PRODUCTOMODEL::save -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function getAll()
  {
    $items = [];

    try {
      $query = $this->db->consulta("
        SELECT p.producto_id, tp.catalogo, p.nombre, p.descripcion, p.imagen, p.precio, p.stock, p.stock_minimo
        FROM producto p
        INNER JOIN tipo_producto tp ON p.tipo_producto_id = tp.tipo_producto_id
        WHERE p.estado = 1
      ");

      while ($row = $query->fetch_assoc()) {
        $item = [
          'producto_id' => $row['producto_id'],
          'tipo_producto' => $row['catalogo'],
          'nombre' => $row['nombre'],
          'descripcion' => $row['descripcion'],
          'imagen' => $row['imagen'],
          'precio' => $row['precio'],
          'stock' => $row['stock'],
          'stock_minimo' => $row['stock_minimo']
        ];
        array_push($items, $item);
      }
      return $items;
    } catch (\Throwable $th) {
      error_log("PRODUCTOMODEL::getAll -> Error: " . $th->getMessage());
      return [];
    }
  }

  public function get($id)
  {
    try {
      $query = "SELECT * FROM producto WHERE producto_id = '$id' AND estado = 1";
      $rs = $this->db->consulta($query);
      $tienda = $rs->fetch_assoc();

      $this->setProductoId($tienda['producto_id']);
      $this->setTipoProductoId($tienda['tipo_producto_id']);
      $this->setNombre($tienda['nombre']);
      $this->setDescripcion($tienda['descripcion']);
      $this->setImagen($tienda['imagen']);
      $this->setPrecio($tienda['precio']);
      $this->setStockMinimo($tienda['stock_minimo']);
      $this->setStock($tienda['stock']);
      return $this;

    } catch (\Throwable $th) {
      error_log("PRODUCTOMODEL::get -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function delete($id)
  {
    try {
      $query = "UPDATE producto SET estado = 0 WHERE producto_id = $id";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("PRODUCTOMODEL::delete -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function update()
  {
    try {
      $query = "UPDATE producto SET tipo_producto_id = '$this->tipo_producto_id', 
      nombre = '$this->nombre', descripcion = '$this->descripcion', precio = '$this->precio',
      stock = '$this->stock' 
      WHERE producto_id = '$this->producto_id'";
      $rs = $this->db->consulta($query);
      $producto = $rs->fetch_assoc();
      $this->setProductoId($producto['producto_id']);
      $this->setTipoProductoId($producto['tipo_producto_id']);
      $this->setNombre($producto['nombre']);
      $this->setDescripcion($producto['descripcion']);
      $this->setPrecio($producto['precio']);
      $this->setStock($producto['stock']);
      return $this;

    } catch (\Throwable $th) {
      error_log("PRODUCTOMODEL::update -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function from($array)
  {
    $this->producto_id = $array['producto_id'];
    $this->tipo_producto_id = $array['tipo_producto_id'];
    $this->nombre = $array['nombre'];
    $this->descripcion = $array['descripcion'];
    $this->imagen = $array['imagen'];
    $this->precio = $array['precio'];
    $this->estado = $array['estado'];
    $this->stock_minimo = $array['stock_minimo'];
    $this->stock = $array['stock'];
    return $this;
  }

  public function toArray()
  {
    return [
      'producto_id' => $this->producto_id,
      'tipo_producto_id' => $this->tipo_producto_id,
      'nombre' => $this->nombre,
      'descripcion' => $this->descripcion,
      'imagen' => $this->imagen,
      'precio' => $this->precio,
      'estado' => $this->estado,
      'stock_minimo' => $this->stock_minimo,
      'stock' => $this->stock
    ];
  }

  public function setProductoId($producto_id)
  {
    $this->producto_id = $producto_id;
  }
  public function getProductoId()
  {
    return $this->producto_id;
  }
  public function setTipoProductoId($tipo_producto_id)
  {
    $this->tipo_producto_id = $tipo_producto_id;
  }
  public function getTipoProductoId()
  {
    return $this->tipo_producto_id;
  }
  public function setNombre($nombre)
  {
    $this->nombre = $nombre;
  }
  public function getNombre()
  {
    return $this->nombre;
  }
  public function setDescripcion($descripcion)
  {
    $this->descripcion = $descripcion;
  }
  public function getDescripcion()
  {
    return $this->descripcion;
  }
  public function setImagen($imagen)
  {
    $this->imagen = $imagen;
  }
  public function getImagen()
  {
    return $this->imagen;
  }
  public function setPrecio($precio)
  {
    $this->precio = $precio;
  }
  public function getPrecio()
  {
    return $this->precio;
  }

  public function setStockMinimo($stock_minimo)
  {
    $this->stock_minimo = $stock_minimo;
  }
  public function getStockMinimo()
  {
    return $this->stock_minimo;
  }
  public function setStock($stock)
  {
    $this->stock = $stock;
  }
  public function getStock()
  {
    return $this->stock;
  }

  public function setEstado($estado)
  {
    $this->estado = $estado;
  }
  public function getEstado()
  {
    return $this->estado;
  }
}
