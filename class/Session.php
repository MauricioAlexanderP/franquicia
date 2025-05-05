<?php

namespace class;

/**
 * Class Session
 *
 * Esta clase gestiona las sesiones de usuario en la aplicación.
 * Proporciona métodos para iniciar sesión, establecer el usuario actual,
 * obtener el usuario actual, verificar si existe una sesión y cerrar la sesión.
 */
class Session
{
  /**
   * @var string $sessionName
   * Nombre de la clave de sesión utilizada para almacenar el usuario actual.
   */
  private $sessionName = 'user';

  /**
   * Constructor de la clase.
   *
   * Inicia una sesión si no hay ninguna activa.
   */
  public function __construct()
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }
  /**
   * Inicia sesión y establece el usuario actual.
   *
   * @param mixed $user El usuario que se va a establecer como actual.
   */
  public function getCurrentUser()
  {
    return $_SESSION[$this->sessionName];
  }

  /**
   * Establece el usuario actual en la sesión.
   *
   * @param mixed $user El usuario que se desea almacenar en la sesión.
   * @return void
   */
  public function setCurrentUser($user)
  {
    $_SESSION[$this->sessionName] = $user;
  }

  /**
   * Cierra la sesión actual.
   *
   * Elimina todas las variables de sesión y destruye la sesión.
   *
   * @return void
   */
  public function closeSession()
  {
    session_unset();
    session_destroy();
  }

  /**
   * Verifica si existe un usuario en la sesión.
   *
   * @return bool True si existe un usuario en la sesión, false en caso contrario.
   */
  public function exists()
  {
    return isset($_SESSION[$this->sessionName]);
  }
}
