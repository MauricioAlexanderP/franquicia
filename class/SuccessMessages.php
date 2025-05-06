<?php
namespace class;

class SuccessMessages{
  /**
   * Mensaje de error.
   * Nomenclatura: "SUCCESS_CONTROLADOR_METHODO_ACCION"
   */
  const SUCCESS_LOGIN_LOGIN_LOGIN = "59c8a56b3938b1d93d1a097f9f8796ef";
  const SUCCESS_TIENDA_NEWTIENDA_GUARDADDA = "834735e846a2f8ac6bac0403dd92f110";
  const SUCCESS_TIENDA_DELETE_ELIMINADO = "2dec3cd631dd0f566c977ce9827aa465";
  const SUCCESS_TIENDA_UPDATE_GUARDADDA = "a2b0f3c4d5e6f7g8h9i0j1k2l3m4n5o6";
  const SUCCESS_TIPOTIENDA_NEWTIPOTIENDA = "f1d4961ce7478d61b69979ada0cc6b0c";
  const SUCCESS_TIPOTIENDA_DELETE_ELIMINADO = "4f7a75fa74e226450e8b0c25f9af4260";
  const SUCCESS_TIPOTIENDA_UPDATE_GUARDADDA = "3d79c984ba85af08f0335c56b3b0497c";
  const SUCCESS_TIPOTIENDA_EDIT_EDITADO = '298c4a9463bb5cdf35d380b61543693b';

  private $successList = [];

  public function __construct()
  {
    $this->successList = [
      SuccessMessages::SUCCESS_LOGIN_LOGIN_LOGIN => "El inicio de sesión exitoso.",
      SuccessMessages::SUCCESS_TIENDA_NEWTIENDA_GUARDADDA => "La tienda se ha registrado exitosamente",
      SuccessMessages::SUCCESS_TIENDA_DELETE_ELIMINADO => "La tienda se ha eliminado exitosamente",
      SuccessMessages::SUCCESS_TIENDA_UPDATE_GUARDADDA => "La tienda se ha actualizado exitosamente",
      SuccessMessages::SUCCESS_TIPOTIENDA_NEWTIPOTIENDA => "El tipo de tienda se ha registrado exitosamente",
      SuccessMessages::SUCCESS_TIPOTIENDA_DELETE_ELIMINADO => "El tipo de tienda se ha eliminado exitosamente",
      SuccessMessages::SUCCESS_TIPOTIENDA_UPDATE_GUARDADDA => "El tipo de tienda se ha actualizado exitosamente",
      SuccessMessages::SUCCESS_TIPOTIENDA_EDIT_EDITADO => "Tipo de tienda editado con éxito",
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