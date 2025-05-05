<?php
namespace libs;
/**
 * Interface IModel
 *
 * Esta interfaz define los métodos básicos que deben implementar las clases
 * que interactúan con modelos de datos en la aplicación. Proporciona una
 * estructura estándar para operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
 * y para inicializar un modelo a partir de un array de datos.
 *
 * @package libs
 */
interface IModel
{
  /**
   * Guarda el modelo actual en la base de datos.
   *
   * @return void
   */
  public function save();

  /**
   * Obtiene todos los registros del modelo desde la base de datos.
   *
   * @return array Lista de registros del modelo.
   */
  public function getAll();

  /**
   * Obtiene un registro específico del modelo desde la base de datos.
   *
   * @param mixed $id Identificador único del registro.
   * @return mixed Registro del modelo correspondiente al ID proporcionado.
   */
  public function get($id);

  /**
   * Elimina un registro específico del modelo de la base de datos.
   *
   * @param mixed $id Identificador único del registro a eliminar.
   * @return void
   */
  public function delete($id);

  /**
   * Actualiza el modelo actual en la base de datos.
   *
   * @return void
   */
  public function update();

  /**
   * Inicializa el modelo a partir de un array de datos.
   *
   * @param array $array Datos para inicializar el modelo.
   * @return void
   */
  public function from($array);
}
