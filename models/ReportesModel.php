<?php

namespace models;

use libs\Model;

class ReportesModel extends Model
{
  public function getVentasDetalladas($fechaInicio, $fechaFin, $tiendaId = null)
  {
    $query = "SELECT v.venta_id, t.nombre_tienda, v.fecha_venta, v.monto_total, v.regalias,
                         u.nombre_usuario as cajero
                  FROM venta v
                  JOIN tienda t ON v.tienda_id = t.tienda_id
                  JOIN usuario u ON v.tienda_id = u.tienda_id
                  WHERE v.fecha_venta BETWEEN '$fechaInicio' AND '$fechaFin'";

    if ($tiendaId) {
      $query .= " AND v.tienda_id = $tiendaId";
    }

    $query .= " ORDER BY v.fecha_venta DESC";

    return $this->db->consulta($query)->fetch_all(MYSQLI_ASSOC);
  }

  public function getVentasResumen($fechaInicio, $fechaFin, $tiendaId = null)
  {
    $query = "SELECT DATE(v.fecha_venta) as fecha, 
                         COUNT(v.venta_id) as total_ventas, 
                         SUM(v.monto_total) as total_monto,
                         SUM(v.regalias) as total_regalias
                  FROM venta v
                  WHERE v.fecha_venta BETWEEN '$fechaInicio' AND '$fechaFin'";

    if ($tiendaId) {
      $query .= " AND v.tienda_id = $tiendaId";
    }

    $query .= " GROUP BY DATE(v.fecha_venta)
                   ORDER BY fecha DESC";

    return $this->db->consulta($query)->fetch_all(MYSQLI_ASSOC);
  }

  public function getProductosMasVendidos($fechaInicio, $fechaFin, $tiendaId = null)
  {
    $query = "SELECT p.nombre, 
                         SUM(dv.cantidad) as total_vendido, 
                         SUM(dv.cantidad * dv.precio_unitario) as total_ingresos
                  FROM detalle_venta dv
                  JOIN venta v ON dv.venta_id = v.venta_id
                  JOIN producto p ON dv.producto_id = p.producto_id
                  WHERE v.fecha_venta BETWEEN '$fechaInicio' AND '$fechaFin'";

    if ($tiendaId) {
      $query .= " AND v.tienda_id = $tiendaId";
    }

    $query .= " GROUP BY p.nombre
                  ORDER BY total_vendido DESC
                  LIMIT 20";

    return $this->db->consulta($query)->fetch_all(MYSQLI_ASSOC);
  }

  public function getEvaluaciones($fechaInicio, $fechaFin, $tiendaId = null)
  {
    $query = "SELECT e.calificacion, e.comentario, e.fecha_Evaluacion, 
                        t.nombre_tienda, u.nombre_usuario as evaluador
                  FROM Evaluaciones e
                  JOIN tienda t ON e.tienda_id = t.tienda_id
                  JOIN usuario u ON e.usuario_id = u.usuario_id
                  WHERE e.fecha_Evaluacion BETWEEN '$fechaInicio' AND '$fechaFin'";

    if ($tiendaId) {
      $query .= " AND e.tienda_id = $tiendaId";
    }

    $query .= " ORDER BY e.fecha_Evaluacion DESC";

    return $this->db->consulta($query)->fetch_all(MYSQLI_ASSOC);
  }
}
