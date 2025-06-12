<?php

namespace controllers;

use libs\View;
use models\EvaluacionesModel;
use models\TiendaModel;
use models\UserModel;
use controllers\SessionController;

class EvaluacionesController extends SessionController
{
  protected $view;
  protected $model;
  protected $tiendaModel;
  protected $data = [];

  public function __construct()
  {
    parent::__construct();
    $this->view = new View();
    $this->model = new EvaluacionesModel();
    $this->tiendaModel = new TiendaModel();
  }

  public function render()
  {
    $this->data = $this->dataUser();
    $tienda_id = $this->data['tienda_id'];

    $evaluaciones = $this->model->getByTienda($tienda_id);
    $promedio = $this->model->getPromedioByTienda($tienda_id);

    $this->view->render('evaluaciones/index', [
      'evaluaciones' => $evaluaciones,
      'promedio' => round($promedio, 1)
    ]);
  }

  public function nuevaEvaluacion()
  {
    $tiendas = $this->tiendaModel->getAll();
    $this->view->render('evaluaciones/form', [
      'tiendas' => $tiendas
    ]);
  }

  public function save()
  {
    // Validar que todos los campos requeridos están presentes
    $requiredFields = [
      'tienda_id',
      'comentario',
      'instalaciones',
      'servicio',
      'productos',
      'limpieza',
      'atencion'
    ];

    if (!$this->existPOST($requiredFields)) {
      $this->redirect('evaluaciones/nuevaEvaluacion', [
        'error' => 'Todos los campos son requeridos'
      ]);
      return;
    }

    // Crear nueva evaluación
    $evaluacion = new EvaluacionesModel();
    $evaluacion->setFechaEvaluacion(date('Y-m-d H:i:s'));
    $evaluacion->setTiendaId($this->getPost('tienda_id'));
    $evaluacion->setUsuarioId($_SESSION['user']);
    $evaluacion->setComentario($this->getPost('comentario'));

    // Parámetros de evaluación
    $evaluacion->setInstalaciones($this->getPost('instalaciones'));
    $evaluacion->setServicio($this->getPost('servicio'));
    $evaluacion->setProductos($this->getPost('productos'));
    $evaluacion->setLimpieza($this->getPost('limpieza'));
    $evaluacion->setAtencion($this->getPost('atencion'));

    if ($evaluacion->save()) {
      $this->redirect('evaluaciones', [
        'success' => 'Evaluación registrada exitosamente'
      ]);
    } else {
      $this->redirect('evaluaciones/nuevaEvaluacion', [
        'error' => 'Error al guardar la evaluación'
      ]);
    }
  }

  private function dataUser()
  {
    $usuario = new UserModel();
    $id = $_SESSION['user'];
    $usuario->get($id);
    return $usuario->toArray();
  }
}
