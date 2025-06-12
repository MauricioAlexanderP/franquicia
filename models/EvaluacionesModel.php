<?php

namespace models;

use libs\Model;

class EvaluacionesModel extends Model
{
  protected $evaluacion_id;
  protected $fecha_Evaluacion;
  protected $calificacion;
  protected $comentario;
  protected $tienda_id;
  protected $usuario_id;

  // Parámetros de evaluación
  protected $instalaciones;
  protected $servicio;
  protected $productos;
  protected $limpieza;
  protected $atencion;

  public function __construct()
  {
    parent::__construct();
  }

  public function save()
  {
    // Calcular calificación global como promedio de los parámetros
    $this->calificacion = (
      $this->instalaciones +
      $this->servicio +
      $this->productos +
      $this->limpieza +
      $this->atencion
    ) / 5;

    try {
      $query = "INSERT INTO Evaluaciones (fecha_Evaluacion, calificacion, comentario, tienda_id, usuario_id, instalaciones, servicio, productos, limpieza, atencion)
                VALUES ('$this->fecha_Evaluacion', '$this->calificacion', '$this->comentario', 
                '$this->tienda_id', '$this->usuario_id', '$this->instalaciones', '$this->servicio', 
                '$this->productos', '$this->limpieza', '$this->atencion')";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("EVALUACIONESMODEL::save -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function getByTienda($tienda_id)
  {
    $query = "SELECT e.*, u.nombre_usuario, t.nombre_tienda
                  FROM Evaluaciones e
                  JOIN usuario u ON e.usuario_id = u.usuario_id
                  JOIN tienda t ON e.tienda_id = t.tienda_id
                  WHERE e.tienda_id = $tienda_id
                  ORDER BY e.fecha_Evaluacion DESC";

    return $this->db->consulta($query)->fetch_all(MYSQLI_ASSOC);
  }

  public function getPromedioByTienda($tienda_id)
  {
    $query = "SELECT AVG(calificacion) as promedio
                  FROM Evaluaciones
                  WHERE tienda_id = $tienda_id";

    $result = $this->db->consulta($query)->fetch_assoc();
    return $result['promedio'] ?? 0;
  }

  // Setters para todos los parámetros
  public function setFechaEvaluacion($fecha)
  {
    $this->fecha_Evaluacion = $fecha;
  }

  public function setComentario($comentario)
  {
    $this->comentario = $comentario;
  }

  public function setTiendaId($tienda_id)
  {
    $this->tienda_id = $tienda_id;
  }

  public function setUsuarioId($usuario_id)
  {
    $this->usuario_id = $usuario_id;
  }

  public function setInstalaciones($valor)
  {
    $this->instalaciones = $valor;
  }

  public function setServicio($valor)
  {
    $this->servicio = $valor;
  }

  public function setProductos($valor)
  {
    $this->productos = $valor;
  }

  public function setLimpieza($valor)
  {
    $this->limpieza = $valor;
  }

  public function setAtencion($valor)
  {
    $this->atencion = $valor;
  }

  public function getCalificacionPromedio($tienda_id)
  {
    $query = "SELECT AVG(calificacion) AS promedio 
                  FROM Evaluaciones 
                  WHERE tienda_id = $tienda_id";
    $result = $this->db->consulta($query)->fetch_assoc();
    return round($result['promedio'] ?? 0, 1);
  }

  public function getEvaluacionesRecientes($tienda_id, $limit = 5)
  {
    $query = "SELECT e.calificacion, e.comentario, u.nombre_usuario, e.fecha_Evaluacion
                  FROM Evaluaciones e
                  JOIN usuario u ON e.usuario_id = u.usuario_id
                  WHERE e.tienda_id = $tienda_id
                  ORDER BY e.fecha_Evaluacion DESC
                  LIMIT $limit";
    return $this->db->consulta($query)->fetch_all(MYSQLI_ASSOC);
  }
}
