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
  }


  public function save()
  {
    // TODO
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
  public function setEstado($estado)
  {
    $this->estado = $estado;
  }
  public function getEstado()
  {
    return $this->estado;
  }
}
