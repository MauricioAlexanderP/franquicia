<?php
namespace class;

class SuccessMessages{
  /**
   * Mensaje de error.
   * Nomenclatura: "SUCCESS_CONTROLADOR_METHODO_ACCION"
   */
  const SUCCESS_LOGIN_LOGIN_LOGIN = "59c8a56b3938b1d93d1a097f9f8796ef";
  const SUCCESS_TIENDA_NEWTIENDA_GUARDADDA = "67160a524cbe896f9e4d2320f5d564e9";
  const SUCCESS_TIENDA_DELETE_ELIMINADO = "a2b0f3c4d5e6f7g8h9i0j1k2l3m4n5o6";
  const SUCCESS_TIENDA_UPDATE_GUARDADDA = "a2b0f3c4d5e6f7g8h9i0j1k2l3m4n5o6";

  private $successList = [];

  public function __construct()
  {
    $this->successList = [
      SuccessMessages::SUCCESS_LOGIN_LOGIN_LOGIN => "El inicio de sesión exitoso.",
      SuccessMessages::SUCCESS_TIENDA_NEWTIENDA_GUARDADDA => "La tienda se ha registrado exitosamente",
      SuccessMessages::SUCCESS_TIENDA_DELETE_ELIMINADO => "La tienda se ha eliminado exitosamente",
      SuccessMessages::SUCCESS_TIENDA_UPDATE_GUARDADDA => "La tienda se ha actualizado exitosamente",
    ];
  }

  public function get($hash)
  {
    return $this->successList[$hash];
  }

  public function existsKey($key)
  {
    if(array_key_exists($key, $this->successList)) {
      return true;
    } else {
      return false;
    }
  }
  
}
?>