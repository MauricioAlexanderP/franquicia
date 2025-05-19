<?php

namespace models;

use libs\Model;
use libs\IModel;

class RolesModel extends Model implements IModel
{
  private $role_id;
  private $nombre_rol; 

  public function __construct()
  {
    parent::__construct();
    $this->nombre_rol = '';
    $this->role_id = null;
  }

  
  public function save()
  {
    try {
      $query = "INSERT INTO rol (nombre_rol) VALUES ('$this->nombre_rol')";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("ROLESMODEL::save -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function getAll()
  {
    $items = [];
    try {
      $query = $this->db->consulta("SELECT * FROM rol WHERE estado = 1");
      
      while ($row = $query->fetch_assoc()) {
        $item = new RolesModel();
        $item->setRoleId($row['rol_id']);
        $item->setNombreRol($row['nombre_rol']);

        array_push($items, $item);
      }

    } catch (\Throwable $th) {
      error_log("ROLESMODEL::getAll -> Error: " . $th->getMessage());
      return [];
    }
    return $items;
  }

  public function get($id)
  {
    try{
      $query = "SELECT * FROM rol WHERE rol_id = '$id' AND estado = 1";
      $rs = $this->db->consulta($query);
      $roles = $rs->fetch_assoc();

      $this->setNombreRol($roles['nombre_rol']);
      $this->setRoleId($roles['rol_id']);
      return $this;

    } catch (\Throwable $th) {
      error_log("ROLESMODEL::get -> Error: " . $th->getMessage());
      return null;
    }
  }

  public function delete($id)
  {
    try {
      $query = "UPDATE rol SET estado = 0 WHERE rol_id = '$id'";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("ROLESMODEL::delete -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function update()
  {
    try {
      $query = "UPDATE rol SET nombre_rol = '$this->nombre_rol' WHERE rol_id = '$this->role_id'";
      $rs = $this->db->consulta($query);
      $roles = $rs->fetch_assoc();
      $this->setNombreRol($roles['nombre_rol']);
      
      return $this;
    } catch (\Throwable $th) {
      error_log("ROLESMODEL::update -> Error: " . $th->getMessage());
      return false;
    }
  }

  public function from($array)
  {
    $this->nombre_rol = $array['nombre_rol'];
    $this->role_id = $array['role_id'];
  }

  public function toArray()
  {
    return [
      'role_id' => $this->role_id,
      'nombre_rol' => $this->nombre_rol
    ];
  }
  public function getRoleId()
  {
    return $this->role_id;
  }
  public function setRoleId($role_id)
  {
    $this->role_id = $role_id;
  }
  public function getNombreRol()
  {
    return $this->nombre_rol;
  }
  public function setNombreRol($nombre_rol)
  {
    $this->nombre_rol = $nombre_rol;
  }
}
