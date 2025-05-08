<?php

namespace class;

use Error;

class ErrorMessages
{
  /**
   * Mensaje de error para un campo vacío.
   * Nomenclatura: "ERROR_CONTROLADOR_METHODO_ACCION"
   */
  const ERROR_LOGIN_LOGIN_LOGIN = "b6480229f6015da3f9ddfe4806970cc9";
  const ERROR_LOGIN_CAMPO_VACIO = "a2b0f3c4d5e6f7g8h9i0j1k2l3m4n5o6";
  const ERROR_TIENDA_NEWTIENDA_DATOSFALTANTES = '02ff8d598c813d35001edfe2eec61604';
  const ERROR_TIENDA_GETTIENDABYID_DATOSFALTANTES = '357263a8d5dc730dd61b366de11a4a31';
  const ERROR_TIENDA_UPDATETIENDA_DATOSFALTANTES = 'a2df4f3c4d5e6f7g8h9i0j1k2l3m4n5o6';
  const ERROR_TIPOTIENDA_NEWTIPOTIENDA_DATOSFALTANTES = '9orsf3c4d5e6f7g8h9i0j1k2l3m4n5o6';
  const ERROR_TIPOTIENDA_DELETETIPOTIENDA_DATOSFALTANTES = '1rtsartc4d5e6f7g8h9i0j1k2l3m4n5o6';
  const ERROR_TIPOTIENDA_UPDATETIPOTIENDA_DATOSFALTANTES = 'a2b0f3343335e6f7g8h9i0j1k2l3m4n5o6';
  const ERROR_TIPOPRODUCTO_NEWTIPOPRODUCTO_DATOSFALTANTES = 'a2b0f3c4dfd7g8h9i0j1k2l3m4n5o6';
  const ERROR_TIPOPRODUCTO_GETTIPOPRODUCTO_DATOSFALTANTES = '4d5e6f7g8h9i0j1k2l3m4n5a2b0f3co6';
  const ERROR_TIPOPRODUCTO_UPDATETIPOPRODUCTO_DATOSFALTANTES = '7g8h9i0ja2b0f3c4d5e6f1k2l3m4n5o6';
  private $errorList = [];

  public function __construct()
  {
    $this->errorList = [
      ErrorMessages::ERROR_LOGIN_LOGIN_LOGIN => "El usuario o la contraseña no son válidos.",
      ErrorMessages::ERROR_LOGIN_CAMPO_VACIO => "Por favor, completa todos los campos.",
      ErrorMessages::ERROR_TIENDA_NEWTIENDA_DATOSFALTANTES => "Faltan datos requeridos.",
      ErrorMessages::ERROR_TIENDA_GETTIENDABYID_DATOSFALTANTES => "No se proporcionó ID",
      ErrorMessages::ERROR_TIENDA_UPDATETIENDA_DATOSFALTANTES => "Datos faltantes para actualizar la tienda",
      ErrorMessages::ERROR_TIPOTIENDA_NEWTIPOTIENDA_DATOSFALTANTES => "Datos faltantes para crear un nuevo tipo de tienda",
      ErrorMessages::ERROR_TIPOTIENDA_DELETETIPOTIENDA_DATOSFALTANTES => "No se proporcionó ID",
      ErrorMessages::ERROR_TIPOTIENDA_UPDATETIPOTIENDA_DATOSFALTANTES => "Datos faltantes para actualizar el tipo de tienda",
      ErrorMessages::ERROR_TIPOPRODUCTO_NEWTIPOPRODUCTO_DATOSFALTANTES => "Datos faltantes para crear un nuevo tipo de producto",
      ErrorMessages::ERROR_TIPOPRODUCTO_GETTIPOPRODUCTO_DATOSFALTANTES => "No se proporcionó ID",
      ErrorMessages::ERROR_TIPOPRODUCTO_UPDATETIPOPRODUCTO_DATOSFALTANTES => "Datos faltantes para actualizar el tipo de producto",
    ];
  }

  public function get($hash)
  {
    return $this->errorList[$hash];
  }

  public function existsKey($key)
  {
    if(array_key_exists($key, $this->errorList)) {
      return true;
    } else {
      return false;
    }
  }
  
}
