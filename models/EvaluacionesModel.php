<?php

namespace models;

use libs\Model;

class EvaluacionesModel extends Model
{
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
