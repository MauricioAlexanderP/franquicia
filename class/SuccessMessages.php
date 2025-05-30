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
  const SUCCESS_TIPOPRODUCTO_NEWTIPOPRODUCTO = 'a2b0f3c4d5e6f7g8h9i0j1k2l3m5o6pt';
  const SUCCESS_TIPOPRODUCTO_UPDATETIPOPRODUCTO = '5e6fa2b0f3c4d7g8h94n5o6i0j1k2l3m';
  const SUCCESS_TIPOPRODUCTO_DELETE_ELIMINADO  = "d5e6f7g8h9i0j1a2b0f3c4k2l3m5o6pt"; 
  const SUCCESS_PRODUCTO_NEWPRODUCTO = '50a2009a948826c4036ecabe77707800';
  const SUCCESS_PRODUCTO_UPDATEPRODUCTO = '293f8842898813f1897759b19d09ffa2';
  const SUCCESS_PRODUCTO_DELETEPRODUCTO = '9f110508c7c5f36bba3b784eab834914';
  const SUCCESS_ROLES_NEWROL_GUARDADO = 'a548bd32900127ff915d61d669044814';
  const SUCCESS_ROLES_NEWROL_ACTUALIZADO = '890e20634e3bd864354c032ec7732430';
  const SUCCESS_ROLES_NEWROL_ELIMINADO = 'd5d994ab1430ef6871f3d3c2957e235e';
  const SUCCESS_ROLES_NEWROL = '017dcce727b72d97239dd38328de4ed4';
  const SUCCESS_USUARIO_NEWUSUARIO = '046bb43607a1a874e51f77f971a2b90b';
  const SUCCESS_USUARIO_UPDATE_USUARIO = '3c4d5e6f7g8h9i0j1a2b0fk2l3m4n5o6';
  const SUCCESS_USUARIO_DELETE_USUARIO = '5e6f7g9i1a870j1a2b0f39ic4d5e6f7g8h';
  const SUCCESS_INVENTARIO_NEWPRODUCTO_GUARDADDA = 'g8h9i0j1k2la2b0f3c4d5e6f73m4n5o6';
  const SUCCESS_INVENTARIO_PRODUCTO_ELIMINADO = '5e6f7g8h9i0j1k2l230f3c4d4n5o6a2b';
  const PRODUCTOS_AGREGADOS = '4d5e6f7g8ha2b0f3c9i0j1k2l3m4n5o6';
  const PRODUCTO_ELIMINADO = '5e6f7g8h9i0j1k2l3m4n5o6a2b0f3c4d';
  const SUCCESS_VENTA_REGISTRADA = 'd5e6f7g8h9i0ja2b0f3c41k2l3m4n5o6';
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
      SuccessMessages::SUCCESS_TIPOPRODUCTO_NEWTIPOPRODUCTO => "El tipo de producto se ha registrado exitosamente",
      SuccessMessages::SUCCESS_TIPOPRODUCTO_UPDATETIPOPRODUCTO => "El tipo de producto se ha actualizado exitosamente",
      SuccessMessages::SUCCESS_TIPOPRODUCTO_DELETE_ELIMINADO => "El tipo de producto se ha eliminado exitosamente",
      SuccessMessages::SUCCESS_PRODUCTO_NEWPRODUCTO => "El producto se ha registrado exitosamente",
      SuccessMessages::SUCCESS_PRODUCTO_UPDATEPRODUCTO => "El producto se ha actualizado exitosamente",
      SuccessMessages::SUCCESS_PRODUCTO_DELETEPRODUCTO => "El producto se ha eliminado exitosamente",
      SuccessMessages::SUCCESS_ROLES_NEWROL_GUARDADO => "Rol guardado exitosamente.",
      SuccessMessages::SUCCESS_ROLES_NEWROL_ACTUALIZADO => "Rol actualizado exitosamente.",
      SuccessMessages::SUCCESS_ROLES_NEWROL_ELIMINADO => "Rol eliminado exitosamente.",
      SuccessMessages::SUCCESS_ROLES_NEWROL => "Rol registrado exitosamente.",
      SuccessMessages::SUCCESS_USUARIO_NEWUSUARIO => "Usuario registrado exitosamente.",
      SuccessMessages::SUCCESS_USUARIO_UPDATE_USUARIO => "Usuario actualizado exitosamente.",    
      SuccessMessages::SUCCESS_USUARIO_DELETE_USUARIO => "Usuario eliminado exitosamente.",
      SuccessMessages::SUCCESS_INVENTARIO_NEWPRODUCTO_GUARDADDA => "El producto se ha agregado al inventario exitosamente",
      SuccessMessages::SUCCESS_INVENTARIO_PRODUCTO_ELIMINADO => "El producto se ha eliminado del inventario exitosamente",
      SuccessMessages::PRODUCTOS_AGREGADOS => "Los productos se han agregado al carrito exitosamente",
      SuccessMessages::PRODUCTO_ELIMINADO => "El producto se ha eliminado del carrito exitosamente",
      SuccessMessages::SUCCESS_VENTA_REGISTRADA => "La venta se ha registrado exitosamente",
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