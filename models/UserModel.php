<?php

namespace models;

use libs\Model;
use libs\IModel;

class UserModel extends Model implements IModel
{
  private $usuario_id;
  private $tienda_id;
  private $rol_id;
  private $nombre_usuario;
  private $correo;
  private $password;
  private $telefono;
  private $estado;


  public function __construct()
  {
    parent::__construct();
    $this->usuario_id = null;
    $this->tienda_id = null;
    $this->rol_id = null;
    $this->nombre_usuario = '';
    $this->correo = '';
    $this->password = '';
    $this->telefono = '';
    $this->estado = 1;
  }
  public function save()
  {
    try {
      $query = "INSERT INTO usuario (tienda_id, rol_id, nombre_usuario, correo, contraseña, telefono, estado) 
      VALUES ('$this->tienda_id', '$this->rol_id', '$this->nombre_usuario', '$this->correo', '$this->password', '$this->telefono', '$this->estado')";
      $this->db->consulta($query);

      return true;
    } catch (\Throwable $th) {
      error_log("USERMODEL::save => " . $th->getMessage());
      return false;
    }
  }

  public function getAll()
  {
    $items = [];

    try {
      $query = $this->db->consulta("SELECT * FROM usuario");

      while ($p = $query->fetch_assoc()) {
        $item = new UserModel();
        $item->setId($p['usuario_id']);
        $item->setTiendaId($p['tienda_id']);
        $item->setRolId($p['rol_id']);
        $item->setNombreUsuario($p['nombre_usuario']);
        $item->setCorreo($p['correo']);
        $item->setPassword($p['contraseña']);
        $item->setTelefono($p['telefono']);
        $item->setEstado($p['estado']);

        array_push($items, $item);
      }
    } catch (\Throwable $th) {
      error_log("USERMODEL::getAll => " . $th->getMessage());
      return false;
    }
    return $items;
  }

  public function get($id)
  {
    try {

      $sql = "SELECT * FROM usuario WHERE usuario_id = $id";
      $rs = $this->db->consulta($sql);
      $user = $rs->fetch_assoc();

      //$item = new UserModel();
      $this->setId($user['usuario_id']);
      $this->setTiendaId($user['tienda_id']);
      $this->setRolId($user['rol_id']);
      $this->setNombreUsuario($user['nombre_usuario']);
      $this->setCorreo($user['correo']);
      $this->setPassword($user['contraseña']);
      $this->setTelefono($user['telefono']);
      $this->setEstado($user['estado']);

      return $this;
    } catch (\Throwable $th) {
      error_log("USERMODEL::get => " . $th->getMessage());
      return false;
    }
  }

  public function delete($id)
  {
    try {
      $query = "UPDATE usuario SET estado = 0 WHERE usuario_id = '$id'";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {

      error_log("USERMODEL::delete => " . $th->getMessage());
      return false;
    }
  }

  public function update()
  {
    try {

      $query = "UPDATE usuario SET 
      tienda_id = '$this->tienda_id', 
      rol_id = '$this->rol_id', nombre_usuario = '$this->nombre_usuario',
      correo = '$this->correo', telefono = '$this->telefono'
      WHERE usuario_id = '$this->usuario_id'";
      $rs = $this->db->consulta($query);

      $user = $rs->fetch_assoc();

      //$item = new UserModel();
      $this->setId($user['usuario_id']);
      $this->setTiendaId($user['tienda_id']);
      $this->setRolId($user['rol_id']);
      $this->setNombreUsuario($user['nombre_usuario']);
      $this->setCorreo($user['correo']);
      $this->setPassword($user['contraseña']);
      $this->setTelefono($user['telefono']);
      $this->setEstado($user['estado']);

      return true;
    } catch (\Throwable $th) {
      error_log("USERMODEL::get => " . $th->getMessage());
      return false;
    }
  }

  public function getUserAndTiendaRol()
  {
    $items = [];
    try {
      $query = $this->db->consulta(
        "SELECT u.usuario_id, t.nombre_tienda as tienda_id , r.nombre_rol as rol_id, u.nombre_usuario, u.correo, u.contraseña, u.telefono, u.estado
        FROM usuario u
        INNER JOIN tienda t on u.tienda_id = t.tienda_id
        INNER JOIN rol r on u.rol_id = r.rol_id
        WHERE u.estado = 1"
      );

      while ($p = $query->fetch_assoc()) {
        $item = new UserModel();
        $item->setId($p['usuario_id']);
        $item->setTiendaId($p['tienda_id']);
        $item->setRolId($p['rol_id']);
        $item->setNombreUsuario($p['nombre_usuario']);
        $item->setCorreo($p['correo']);
        $item->setPassword($p['contraseña']);
        $item->setTelefono($p['telefono']);
        $item->setEstado($p['estado']);

        array_push($items, $item);
      }
    } catch (\Throwable $th) {
      error_log("USERMODEL::getUserAndTiendaRol => " . $th->getMessage());
      return false;
    }
    return $items;
  }

  public function from($array)
  {
    $this->usuario_id = $array['usuario_id'];
    $this->tienda_id = $array['tienda_id'];
    $this->rol_id = $array['rol_id'];
    $this->nombre_usuario = $array['nombre_usuario'];
    $this->correo = $array['correo'];
    $this->password = $array['contraseña'];
    $this->telefono = $array['telefono'];
    $this->estado = $array['estado'];

    return $this;
  }

  public function toArray()
  {
    return [
      'usuario_id' => $this->usuario_id,
      'tienda_id' => $this->tienda_id,
      'rol_id' => $this->rol_id,
      'nombre_usuario' => $this->nombre_usuario,
      'correo' => $this->correo,
      'contraseña' => $this->password,
      'telefono' => $this->telefono,
      'estado' => $this->estado
    ];
  }

  public function exists($username,)
  {
    try {
      $query = "SELECT nombre_usuario FROM usuario WHERE nombre_usuario = '$username'";
      $rs = $this->db->consulta($query);

      $user = $rs->fetch_assoc();
      if ($user != null) {
        return true;
      } else {
        return false;
      }
    } catch (\Throwable $th) {
      error_log("USERMODEL::exists => " . $th->getMessage());
      return false;
    }
  }

  public function comparePassword($password, $id)
  {
    try {
      $user = $this->get($id);

      return password_verify($password, $user['contraseña']);
    } catch (\Throwable $th) {
      error_log("USERMODEL::comparePassword => " . $th->getMessage());
      return false;
    }
  }

  // cambiar la contraseña del usuario
  public function verifyCurrentPassword($userId, $currentPassword)
  {
    try {
      $user = $this->get($userId);
      $decryptedPassword = $this->encriptar_desencriptar("desencriptar", $user->getPassword());
      error_log("USERMODEL::verifyCurrentPassword => Decrypted Password: " . $decryptedPassword);
      return $currentPassword === $decryptedPassword;
    } catch (\Throwable $th) {
      error_log("USERMODEL::verifyCurrentPassword => " . $th->getMessage());
      return false;
    }
  }

  public function updatePassword($userId, $newPassword)
  {
    try {
      $encryptedPassword = $this->encriptar_desencriptar("encriptar", $newPassword);
      $query = "UPDATE usuario SET contraseña = '$encryptedPassword' WHERE usuario_id = $userId";
      $this->db->consulta($query);
      return true;
    } catch (\Throwable $th) {
      error_log("USERMODEL::updatePassword => " . $th->getMessage());
      return false;
    }
  }


  public function setId($usuario_id)
  {
    $this->usuario_id = $usuario_id;
  }
  public function getId()
  {
    return $this->usuario_id;
  }

  public function setTiendaId($tienda_id)
  {
    $this->tienda_id = $tienda_id;
  }
  public function getTiendaId()
  {
    return $this->tienda_id;
  }

  public function setRolId($rol_id)
  {
    $this->rol_id = $rol_id;
  }
  public function getRolId()
  {
    return $this->rol_id;
  }

  public function setNombreUsuario($nombre_usuario)
  {
    $this->nombre_usuario = $nombre_usuario;
  }
  public function getNombreUsuario()
  {
    return $this->nombre_usuario;
  }

  public function setCorreo($correo)
  {
    $this->correo = $correo;
  }
  public function getCorreo()
  {
    return $this->correo;
  }

  public function setPassword($password)
  {
    $this->password = $password;
  }
  public function getPassword()
  {
    return $this->password;
  }

  public function setTelefono($telefono)
  {
    $this->telefono = $telefono;
  }
  public function getTelefono()
  {
    return $this->telefono;
  }

  public function setEstado($estado)
  {
    $this->estado = $estado;
  }
  public function getEstado()
  {
    return $this->estado;
  }

  private function getHash($password)
  {
    return $this->encriptar_desencriptar("encriptar", $password);
  }

  public function encriptar_desencriptar($accion, $texto)
  {
    $salida = "";
    $encriptarmetodo = "AES-256-CBC";
    $palabrasecreta = "35ab83c5e045281f8280a5d9c6b0a0d6";
    $iv = 'C9FBL1EWSD/M8JFTGS';
    $key = hash("sha256", $palabrasecreta);
    $iv = substr(hash("sha256", $iv), 0, 16);
    if ($accion == "encriptar") {
      $salida = openssl_encrypt($texto, $encriptarmetodo, $key, 0, $iv);
    } else if ($accion == "desencriptar") {
      $salida = openssl_decrypt($texto, $encriptarmetodo, $key, 0, $iv);
    }
    return $salida;
  }
}
