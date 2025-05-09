<?php

namespace models;

use libs\Model;
use libs\IModel;

/**
 * Class TipoTiendaModel
 *
 * Modelo para gestionar la tabla 'tipo_tienda'.
 * Proporciona métodos para realizar operaciones CRUD en la base de datos.
 */

class TipoTiendaModel extends Model implements IModel
{
  /**
   * @var int|null $tipo_tienda_id Identificador único del tipo de tienda.
   */
  private $tipo_tienda_id;

  /**
   * @var string $tipo Nombre del tipo de tienda.
   */
  private $tipo;

  /**
   * @var string $descripcion Descripción del tipo de tienda.
   */
  private $descripcion;

  private $estado;

  /**
   * Constructor de la clase TipoTiendaModel.
   *
   * Inicializa las propiedades y llama al constructor de la clase padre.
   */
  public function __construct()
  {
    parent::__construct();
    $this->tipo_tienda_id = null;
    $this->tipo = '';
    $this->descripcion = '';
  }

  /**
   * Guarda un nuevo registro en la tabla 'tipo_tienda'.
   *
   * @return bool True en caso de éxito, false si ocurre un error.
   */
  public function save()
  {
    try {
      $query = "INSERT INTO tipo_tienda (tipo, descripcion) 
      VALUES ('$this->tipo', '$this->descripcion')";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("TIPOTIENDAMODEL::save -> Error: " . $th->getMessage());
      return false;
    }
  }

  /**
   * Obtiene todos los registros de la tabla 'tipo_tienda'.
   *
   * @return array|bool Arreglo de instancias de TipoTiendaModel en caso de éxito, false si ocurre un error.
   */
  public function getAll()
  {
    $items = [];

    try{
      $query = $this->db->consulta("SELECT * FROM tipo_tienda WHERE estado = 1");

      while ($p = $query->fetch_assoc()) {
        $item = new TipoTiendaModel();
        $item->setTipoTiendaId($p['tipo_tienda_id']);
        $item->setTipo($p['tipo']);
        $item->setDescripcion($p['descripcion']);
        array_push($items, $item);
      }
    } catch (\Throwable $th) {
      error_log("TIPOTIENDAMODEL::getAll -> Error: " . $th->getMessage());
      return false;
    }
    return $items;
  }

  /**
   * Obtiene un registro específico de la tabla 'tipo_tienda' por su ID.
   *
   * @param int $id Identificador del registro a obtener.
   * @return TipoTiendaModel|bool Instancia con los datos obtenidos o false si ocurre un error.
   */
  public function get($id)
  {
    try {
      $quiery = "SELECT * FROM tipo_tienda WHERE tipo_tienda_id = '$id'";
      $rs = $this->db->consulta($quiery);
      $tipo_tienda = $rs->fetch_assoc();

      $this->setTipoTiendaId($tipo_tienda['tipo_tienda_id']);
      $this->setTipo($tipo_tienda['tipo']);
      $this->setDescripcion($tipo_tienda['descripcion']);
      return $this;
      
    } catch (\Throwable $th) {
      error_log("TIPOTIENDAMODEL::get -> Error: " . $th->getMessage());
      return false;
    }
  }

  /**
   * Elimina un registro de la tabla 'tipo_tienda' por su ID.
   *
   * @param int $id Identificador del registro a eliminar.
   * @return bool True en caso de éxito, false si ocurre un error.
   */
  public function delete($id)
  {
    try {
      $query = "UPDATE tipo_tienda SET estado = 0 WHERE tipo_tienda_id = $id";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("TIPOTIENDAMODEL::delete -> Error: " . $th->getMessage());
      return false;
    }
  }

  /**
   * Actualiza un registro existente en la tabla 'tipo_tienda'.
   *
   * @return bool True en caso de éxito, false si ocurre un error.
   */
  public function update()
  {
    try {
      $query = "UPDATE tipo_tienda SET tipo = '$this->tipo', descripcion = '$this->descripcion', 
      estado = '$this->estado' WHERE tipo_tienda_id = '$this->tipo_tienda_id'";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("TIPOTIENDAMODEL::update -> Error: " . $th->getMessage());
      return false;
    }
  }

  /**
   * Asigna los valores de un arreglo asociativo a las propiedades del modelo.
   *
   * @param array $array Arreglo con los datos del registro.
   * @return TipoTiendaModel Instancia actualizada del modelo.
   */
  public function from($array)
  {
    $this->tipo_tienda_id = $array['tipo_tienda_id'];
    $this->tipo = $array['tipo'];
    $this->descripcion = $array['descripcion'];
    return $this;
  }

  public function toArray(){
    return [
      'tipo_tienda_id' => $this->tipo_tienda_id,
      'tipo' => $this->tipo,
      'descripcion' => $this->descripcion
    ];
  }

  public function getTipoTiendaId(){ return $this->tipo_tienda_id; }
  public function setTipoTiendaId($tipo_tienda_id){ $this->tipo_tienda_id = $tipo_tienda_id; }

  public function getTipo() { return $this->tipo; }
  public function setTipo($tipo){ $this->tipo = $tipo; }

  public function getDescripcion() { return $this->descripcion; }
  public function setDescripcion($descripcion){ $this->descripcion = $descripcion;}

  public function getEstado() { return $this->estado; }
  public function setEstado($estado){ $this->estado = $estado;}
  
}
