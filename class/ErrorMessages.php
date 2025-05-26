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
  const ERROR_PRODUCTO_NEWPRODUCTO_DATOSFALTANTES = '5dd56caa0bc9def2f9d1c234dc54f2c1';
  const ERROR_ROLES_NEWROL_DATOSFALTANTES = '4b10a9a9f506147e5ff47975beceedb7';
  const ERROR_USUARIO_USUARIO_DATOSFALTANTES = '7ga2bd5e6f0f3c48h9i0j1k2l3m4n5o6';
  const ERROR_INVENTARIO_NEWPRODUCTO_DATOSFALTANTES = 'aa0bc9def25dd56cf9d1c234dc54f2c1';
  const ERROR_INVENTARIO_PRODUCTO_YA_REGISTRADO = 'b648023f9ddfe4806970cc9';
  const PRODUCTOS_NO_SELECCIONADOS = 'h9i0jc4d5e6f7g81k2l3m4n5f3co6a2b0';
  const PRODUCTOS_NO_VALIDOS = 'a2b0f3c4d5e6f7g8h9i0j1k2o6l3m4n5';
  const PRODUCTO_NO_SELECCIONADO = 'c4d5e6f7a2b0f3g8hk2o6l3m4n59i0j1';
  const PRODUCTO_NO_ENCONTRADO = 'ac4d5e6f7g2b0f38h9i0j1k2l36m4n5o';
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
      ErrorMessages::ERROR_PRODUCTO_NEWPRODUCTO_DATOSFALTANTES => "Datos faltantes para crear un nuevo producto",
      ErrorMessages::ERROR_ROLES_NEWROL_DATOSFALTANTES => "Datos faltantes para crear un nuevo rol",
      ErrorMessages::ERROR_USUARIO_USUARIO_DATOSFALTANTES => "Datos faltantes para crear un nuevo usuario",
      ErrorMessages::ERROR_INVENTARIO_NEWPRODUCTO_DATOSFALTANTES => "Datos faltantes para crear un nuevo producto en el inventario",
      ErrorMessages::ERROR_INVENTARIO_PRODUCTO_YA_REGISTRADO => "El producto ya está registrado en el inventario",
      ErrorMessages::PRODUCTOS_NO_SELECCIONADOS => "No se han seleccionado productos.",
      ErrorMessages::PRODUCTOS_NO_VALIDOS => "Los productos seleccionados no son válidos.",
      ErrorMessages::PRODUCTO_NO_SELECCIONADO => "No se ha seleccionado ningún producto.",
      ErrorMessages::PRODUCTO_NO_ENCONTRADO => "El producto no se ha encontrado en el inventario.",
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
