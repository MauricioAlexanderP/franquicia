<?php

namespace controllers;

use Error;
use libs\View;
use models\TiendaModel;
use models\TipoTiendaModel;
use class\ErrorMessages;
use class\SuccessMessages;
use controllers\SessionController;
/**
 * Class TiendaController
 *
 * Controlador encargado de gestionar la presentación de las tiendas y sus tipos asociados.
 *
 * @package controllers
 */

class TiendaController extends SessionController
{
  protected $view;
  protected $data;
  protected $tipoTienda;
  public function __construct()
  {
    $this->view = new View;
    // $this->data = new TiendaModel();
    $this->tipoTienda = new TipoTiendaModel();
    //error_log("TIENDACONTROLLLER::construct ->" . print_r($this->data->getTiendasWithTipo(), true));
    parent::__construct();
  }

  public function render()
  {
    $dataTiendas =  new TiendaModel();
    $dataTiendas = $dataTiendas->getTiendasWithTipo();
    error_log("TIENDACONTROLLLER::render -> cargar index");
    error_log("TIENDACONTROLLLER::render -> data: " . print_r($dataTiendas, true));
    $this->view->render('tiendas/index', ['data' => $dataTiendas, 'tipoTienda' => $this->getTipoTienda()]);
  }

  /**
   * Método para mostrar la vista de creación de una nueva tienda.
   * Si no se reciben todos los datos requeridos, redirige a la vista de tiendas con un mensaje de error.
   * Guarda los datos de la tienda en la base de datos.
   * 
   * @return view
   */
  public function newTienda()
  {
    error_log("TIENDACONTROLLLER::newTienda");

    if (!$this->existPOST(['tipo_tienda_id', 'ubicacion', 'encargado', 'telefono', 'hora_entrada', 'hora_salida', 'nombre_tienda'])) {
      $this->redirect('tienda', ['error' => ErrorMessages::ERROR_TIENDA_NEWTIENDA_DATOSFALTANTES]);
      return;
    }

    $tienda = new TiendaModel();
    $tienda->setTipoTiendaId($this->getPost('tipo_tienda_id'));
    $tienda->setUbicacion($this->getPost('ubicacion'));
    $tienda->setEncargado($this->getPost('encargado'));
    $tienda->setTelefono($this->getPost('telefono'));
    $tienda->setHoraEntrada($this->getPost('hora_entrada'));
    $tienda->setHoraSalida($this->getPost('hora_salida'));
    $tienda->setNombreTienda($this->getPost('nombre_tienda'));

    $tienda->save();
    $this->redirect('tienda', ['success' => SuccessMessages::SUCCESS_TIENDA_NEWTIENDA_GUARDADDA]);
  }

  /**
   * Método para mostrar la vista de edición de una tienda.
   * Si no se recibe el ID de la tienda, redirige a la vista de tiendas con un mensaje de error.
   * 
   * @return ra
   */
  public function getTiendaById()
  {
    error_log("TIENDACONTROLLLER::getTiendaById");
    if (!$this->existPOST(['tienda_id'])) {
      echo json_encode(['error' => ErrorMessages::ERROR_TIENDA_GETTIENDABYID_DATOSFALTANTES]);
      return;
    }

    $tienda = new TiendaModel();
    $id = $this->getPost('tienda_id');
    $tienda->setId($id);

    $items = $tienda->get($id); // Obtiene el objeto TiendaModel
    if ($items) {
      echo json_encode($items->toArray());
      error_log("TIENDACONTROLLLER::getTiendaById -> array: " . print_r($items->toArray(), true));
    } else {
      echo json_encode(['error' => 'No se encontró la tienda']);
    } 
  }

  public function updateTienda()
  {
    error_log("TIENDACONTROLLLER::updateTienda");
    if (!$this->existPOST(['tienda_id', 'tipo_tienda_id', 'ubicacion', 'encargado', 'telefono', 'hora_entrada', 'hora_salida', 'nombre_tienda'])) {
      echo json_encode(['error' => ErrorMessages::ERROR_TIENDA_UPDATETIENDA_DATOSFALTANTES]);
      return;
    }

    $tienda = new TiendaModel();
    $tienda->setId($this->getPost('tienda_id'));
    $tienda->setTipoTiendaId($this->getPost('tipo_tienda_id'));
    $tienda->setUbicacion($this->getPost('ubicacion'));
    $tienda->setEncargado($this->getPost('encargado'));
    $tienda->setTelefono($this->getPost('telefono'));
    $tienda->setHoraEntrada($this->getPost('hora_entrada'));
    $tienda->setHoraSalida($this->getPost('hora_salida'));
    $tienda->setNombreTienda($this->getPost('nombre_tienda'));

    $tienda->update();
    $this->redirect('tienda', ['success' => SuccessMessages::SUCCESS_TIENDA_UPDATE_GUARDADDA]);
  }

  /**
   * Elimina una tienda específica de la base de datos.
   *
   * Este método se encarga de recibir el identificador de una tienda,
   * realizar las validaciones necesarias y proceder a eliminarla
   * de la base de datos si cumple con los requisitos.
   *
   * @param int $id Identificador único de la tienda a eliminar.
   * @return void
   * @throws Exception Si ocurre un error durante la eliminación.
   */
  public function deleteTienda()
  {
    error_log("TIENDACONTROLLLER::deleteTienda");
    if (!$this->existPOST(['tienda_id'])) {
      $this->redirect('tienda', ['error' => ErrorMessages::ERROR_TIENDA_NEWTIENDA_DATOSFALTANTES]);
      return;
    }

    $tienda = new TiendaModel();
    $id = $this->getPost('tienda_id');
    $tienda->delete($id);
    $this->redirect('tienda', ['success' => SuccessMessages::SUCCESS_TIENDA_DELETE_ELIMINADO]);
  }

  /**
   * Obtiene y formatea todos los tipos de tienda.
   *
   * Recorre los tipos de tienda obtenidos desde el modelo y retorna un arreglo formateado.
   *
   * @return array Arreglo de tipos de tienda con id, tipo y descripción.
   */
  private function getTipoTienda()
  {
    error_log("TIENDACONTROLLLER::getTipoTienda");
    $items = [];
    $tipos = $this->tipoTienda->getAll();

    foreach ($tipos as $tipo) {
      array_push($items, [
        'tipo_tienda_id' => $tipo->getTipoTiendaId(),
        'tipo' => $tipo->getTipo(),
        'descripcion' => $tipo->getDescripcion()
      ]);
    }
    return $items;
  }
}
