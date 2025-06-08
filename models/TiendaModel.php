<?php

namespace models;

use libs\Model;
use libs\IModel;

class TiendaModel extends Model implements IModel
{
  private $tienda_id;
  private $tipo_tienda_id;
  private $nombre_tienda;
  private $ubicacion;
  private $encargado;
  private $telefono;
  private $hora_entrada;
  private $hora_salida;
  private $estado;

  public function __construct()
  {
    parent::__construct();
    $this->tienda_id = null;
    $this->tipo_tienda_id = null;
    $this->ubicacion = '';
    $this->encargado = '';
    $this->telefono = '';
    $this->hora_entrada = '';
    $this->hora_salida = '';
    $this->estado = 1;
  }
  public function save()
  {
    try {
      $query = "INSERT INTO tienda (tipo_tienda_id, ubicacion, encargado, telefono, hora_entrada, hora_salida, nombre_tienda) 
      VALUES ('$this->tipo_tienda_id', '$this->ubicacion', '$this->encargado', '$this->telefono', '
                $this->hora_entrada', '$this->hora_salida', '$this->nombre_tienda')";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("TIENDAMODEL::save -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function getAll()
  {
    $items = [];

    try {
      $query = $this->db->consulta("SELECT * FROM tienda WHERE estado = 1");

      while ($p = $query->fetch_assoc()) {
        $item = new TiendaModel();
        $item->setId($p['tienda_id']);
        $item->setTiendaId($p['tipo_tienda_id']);
        $item->setNombreTienda($p['nombre_tienda']);
        $item->setUbicacion($p['ubicacion']);
        $item->setEncargado($p['encargado']);
        $item->setTelefono($p['telefono']);
        $item->setHoraEntrada($p['hora_entrada']);
        $item->setHoraSalida($p['hora_salida']);
        array_push($items, $item);
      }
    } catch (\Throwable $th) {
      error_log("TIENDAMODEL::getAll -> Error: " . $th->getMessage());
    }

    return $items;
  }

  public function get($id)
  {
    try {
      $query = "SELECT * FROM tienda WHERE tienda_id = '$id'";
      $rs = $this->db->consulta($query);
      $tienda = $rs->fetch_assoc();

      $this->setId($tienda['tienda_id']);
      $this->setTipoTiendaId($tienda['tipo_tienda_id']);
      $this->setNombreTienda($tienda['nombre_tienda']);
      $this->setUbicacion($tienda['ubicacion']);
      $this->setEncargado($tienda['encargado']);
      $this->setTelefono($tienda['telefono']);
      $this->setHoraEntrada($tienda['hora_entrada']);
      $this->setHoraSalida($tienda['hora_salida']);
      return $this;
    } catch (\Throwable $th) {
      error_log("TIENDAMODEL::get -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function delete($id)
  {
    try {
      $query = "UPDATE tienda SET estado = 0 WHERE tienda_id = $id";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("TIENDAMODEL::delete -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function update()
  {
    try {
      $query = "UPDATE tienda SET tipo_tienda_id = '$this->tipo_tienda_id', 
      ubicacion = '$this->ubicacion', encargado = '$this->encargado', 
      telefono = '$this->telefono', hora_entrada = '$this->hora_entrada',
      estado = '$this->estado', 
      hora_salida = '$this->hora_salida', nombre_tienda = '$this->nombre_tienda' WHERE tienda_id = '$this->tienda_id'";
      $rs = $this->db->consulta($query);
      $tienda = $rs->fetch_assoc();

      $this->setId($tienda['tienda_id']);
      $this->setTipoTiendaId($tienda['tipo_tienda_id']);
      $this->setUbicacion($tienda['ubicacion']);
      $this->setEncargado($tienda['encargado']);
      $this->setTelefono($tienda['telefono']);
      $this->setHoraEntrada($tienda['hora_entrada']);
      $this->setHoraSalida($tienda['hora_salida']);
      $this->setNombreTienda($tienda['nombre_tienda']);
      return $this;
    } catch (\Throwable $th) {
      error_log("TIENDAMODEL::update -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function from($array)
  {
    $this->tienda_id = $array['tienda_id'];
    $this->tipo_tienda_id = $array['tipo_tienda_id'];
    $this->ubicacion = $array['ubicacion'];
    $this->encargado = $array['encargado'];
    $this->telefono = $array['telefono'];
    $this->hora_entrada = $array['hora_entrada'];
    $this->hora_salida = $array['hora_salida'];
    $this->nombre_tienda = $array['nombre_tienda'];
    return $this;
  }

  public function toArray()
  {
    return [
      'tienda_id' => $this->tienda_id,
      'tipo_tienda_id' => $this->tipo_tienda_id,
      'ubicacion' => $this->ubicacion,
      'encargado' => $this->encargado,
      'telefono' => $this->telefono,
      'hora_entrada' => $this->hora_entrada,
      'hora_salida' => $this->hora_salida,
      'nombre_tienda' => $this->nombre_tienda,
    ];
  }

  public function getTiendasWithTipo()
  {
    $items = [];

    try {
      // Consulta con JOIN para obtener datos de la tienda y el tipo de tienda
      $query = $this->db->consulta(
        "
            SELECT t.tienda_id, t.nombre_tienda , tt.tipo AS tipo_tienda_id, tt.tipo_tienda_id as tipo_id, t.ubicacion,t.encargado, t.telefono, t.hora_entrada, t.hora_salida
            FROM tienda t
            INNER JOIN tipo_tienda tt ON t.tipo_tienda_id = tt.tipo_tienda_id
            WHERE t.estado = 1
        "
      );

      while ($row = $query->fetch_assoc()) {
        $item = [
          'tienda_id' => $row['tienda_id'],
          'tipo_tienda_id' => $row['tipo_tienda_id'],
          'tipo_id' => $row['tipo_id'],
          'ubicacion' => $row['ubicacion'],
          'encargado' => $row['encargado'],
          'telefono' => $row['telefono'],
          'hora_entrada' => $row['hora_entrada'],
          'hora_salida' => $row['hora_salida'],
          'nombre_tienda' => $row['nombre_tienda'],
        ];
        array_push($items, $item);
      }
    } catch (\Throwable $th) {
      error_log("TIENDAMODEL::getTiendasWithTipo -> Error: " . $th->getMessage());
    }

    return $items;
  }

  //dashboard
  public function getTotalTiendas()
  {
    $query = "SELECT COUNT(*) AS total FROM tienda";
    $result = $this->db->consulta($query)->fetch_assoc();
    return $result['total'] ?? 0;
  }

  //GETTERS Y SETTERS
  public function getTiendaId()
  {
    return $this->tienda_id;
  }
  public function setTiendaId($tienda_id)
  {
    $this->tienda_id = $tienda_id;
  }

  public function getTipoTiendaId()
  {
    return $this->tipo_tienda_id;
  }
  public function setTipoTiendaId($tipo_tienda_id)
  {
    $this->tipo_tienda_id = $tipo_tienda_id;
  }

  public function getNombreTienda()
  {
    return $this->nombre_tienda;
  }
  public function setNombreTienda($nombre_tienda)
  {
    $this->nombre_tienda = $nombre_tienda;
  }

  public function getUbicacion()
  {
    return $this->ubicacion;
  }
  public function setUbicacion($ubicacion)
  {
    $this->ubicacion = $ubicacion;
  }

  public function getEncargado()
  {
    return $this->encargado;
  }
  public function setEncargado($encargado)
  {
    $this->encargado = $encargado;
  }

  public function getTelefono()
  {
    return $this->telefono;
  }
  public function setTelefono($telefono)
  {
    $this->telefono = $telefono;
  }

  public function getHoraEntrada()
  {
    return $this->hora_entrada;
  }
  public function setHoraEntrada($hora_entrada)
  {
    $this->hora_entrada = $hora_entrada;
  }

  public function getHoraSalida()
  {
    return $this->hora_salida;
  }
  public function setHoraSalida($hora_salida)
  {
    $this->hora_salida = $hora_salida;
  }

  public function getId()
  {
    return $this->tienda_id;
  }
  public function setId($tienda_id)
  {
    $this->tienda_id = $tienda_id;
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
