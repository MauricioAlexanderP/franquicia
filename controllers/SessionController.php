<?php

namespace controllers;

use libs\Controller;
use class\Session;
use models\UserModel;

/**
 * Class SessionController
 *
 * Gestiona la lógica de sesiones y permisos de los usuarios.
 * Se encarga de inicializar la sesión, validar accesos, redirigir según el rol y controlar el cierre de sesión.
 */
class SessionController extends Controller
{
  private $userSession;
  private $userName;

  /**
   * @var Session $session Instancia de la clase Session para gestionar la sesión PHP.
   */ 
  private $session;

  /**
   * @var UserModel $user Instancia del modelo de usuario para acceder al usuario actual.
   */
  private $user;

  /**
   * @var array $sites Configuración de sitios y permisos de acceso.
   */
  private $sites;

  /**
   * @var mixed $defultSite Configuración o ruta por defecto a la que se redirige según el rol.
   */
  private $defultSite;


  /**
   * Constructor de la clase SessionController.
   *
   * Inicializa la sesión, el modelo de usuario y carga la configuración de acceso.
   */
  public function __construct()
  {
    $this->session = new Session();
    $this->userSession = new UserModel();
    $this->init();
  }


  /**
   * Inicializa la configuración de acceso y valida la sesión del usuario.
   *
   * Carga el archivo JSON que contiene la configuración de los sitios y redirige
   * según la validación de la sesión.
   *
   * @return void
   */
  private function init()
  {
    $json = $this->getJSONFileConfig();
    $this->sites = $json['sites'];
    $this->defultSite = $json['default-sites'];
    $this->validateSession();
  }

  /**
   * Obtiene la configuración de acceso desde un archivo JSON.
   *
   * @return array La configuración decodificada del archivo access.json.
   */
  private function getJSONFileConfig()
  {
    $string = file_get_contents('config/access.json');
    $json = json_decode($string, true);
    return $json;
  }

  /**
   * Valida la sesión actual del usuario y redirige según permisos.
   *
   * Si existe sesión, valida el rol del usuario y verifica si la página es pública
   * o está autorizada. En caso de no tener acceso, redirige a la página por defecto del rol.
   *
   * @return void
   */
  public function validateSession()
  {
    error_log("SESSIONCONTROLLLER::validateSession() => ");

    if ($this->existsSession()) {
      $role = $this->getUsersSessionData()->getRolId();
      // Si la página es pública, redirigir al sitio por defecto según el rol
      if ($this->isPublic()) {
        $this->redirectDefaultSiteByRole($role);
      } else {
         // Si la página no es pública, verificar autorización
        if ($this->isAuthorized($role)) {
        } else {
          // Acceso autorizado, continuar sin redirección
          $this->redirectDefaultSiteByRole($role);
        }
      }
    } else {
      // No existe sesión iniciada
      if ($this->isPublic()) {
        // Acceso permitido en páginas públicas
      } else {
        header('location:' . constant('URL') . '');
      }
    }
  }

  /**
   * Verifica si existe una sesión activa para un usuario.
   *
   * @return bool True si existe una sesión válida, false en caso contrario.
   */
  public function existsSession()
  {
    if (!$this->session->exists()) return false;
    if ($this->session->getCurrentUser() == null) return false;

    $userId = $this->session->getCurrentUser();
    if ($userId) return true;

    return false;
  }

  /**
   * Obtiene los datos del usuario actual de la sesión.
   *
   * Recupera el identificador del usuario almacenado en sesión y carga sus datos.
   *
   * @return UserModel Instancia del usuario actual.
   */
  function getUsersSessionData()
  {
    $id = $this->session->getCurrentUser();
    $this->user = new UserModel();
    $this->user->get($id);
    error_log("SESSIONCONTROLLLER::getUsersSessionData() => " . $this->user->getNombreUsuario());
    return $this->user;
  }

  /**
   * Determina si la página actual es de acceso público.
   *
   * Compara la URL actual con la lista de sitios configurados como 'public'.
   *
   * @return bool True si la página es pública, false en caso contrario.
   */
  function isPublic()
  {
    $currectUrl = $this->getCurrentPage();
    $currectUrl = preg_replace("/\?.*/", "", $currectUrl);

    for ($i = 0; $i < sizeof($this->sites); $i++) {
      if ($currectUrl == $this->sites[$i]['site'] && $this->sites[$i]['access'] == 'public') {
        return true;
      }
    }
    return false;
  }

  /**
   * Obtiene la parte actual de la URL que indica la página visitada.
   *
   * Extrae y retorna el segmento correspondiente de la URL.
   *
   * @return string El nombre de la página actual.
   */
  function getCurrentPage()
  {
    $actualLink  = trim($_SERVER['REQUEST_URI']);
    $url = explode('/', $actualLink);
    error_log("SESSIONCONTROLLLER::getCurrentPage() => " . $url[2]);
    return $url[2];
  }

  /**
   * Redirige al sitio por defecto según el rol del usuario.
   *
   * Busca en la configuración el sitio correspondiente al rol y redirige al usuario.
   *
   * @param int $role Rol del usuario.
   * @return void
   */
  private function redirectDefaultSiteByRole($role)
  {
    //error_log("SESSIONCONTROLLLER::redirectDefaultSite() => " . $this->defultSite);
    $url = "";
    for ($i = 0; $i < sizeof($this->sites); $i++) {
      if ($this->sites[$i]['role'] == $role) {
        $url = '/franquicia/' . $this->sites[$i]['site'];
        break;
      }
    }
    header("Location: " . $url);
    exit();
  }

  /**
   * Inicializa la sesión del usuario.
   *
   * Asigna el ID del usuario a la sesión y autoriza el acceso según el rol.
   *
   * @param UserModel $user Objeto con los datos del usuario.
   * @return void
   */
  function initialize($user)
  {
    $this->session->setCurrentUser($user->getId());
    $this->authorizeAccess($user->getRolId());
  }

  /**
   * Determina si el usuario tiene autorización para acceder a la página actual.
   *
   * Compara la URL actual con la configuración asignada al rol del usuario.
   *
   * @param int $role Rol del usuario.
   * @return bool True si el usuario está autorizado, false en caso contrario.
   */
  private function isAuthorized($role)
  {
    //error_log("SESSIONCONTROLLLER::isAuthorized() => " . $this->defultSite);

    $currectUrl = $this->getCurrentPage();
    $currectUrl = preg_replace("/\?.*/", "", $currectUrl);

    for ($i = 0; $i < sizeof($this->sites); $i++) {
      if ($currectUrl == $this->sites[$i]['site'] && $this->sites[$i]['role'] == $role) {
        return true;
      }
    }
    return false;
  }

  /**
   * Autoriza el acceso redirigiendo al sitio por defecto según el rol.
   *
   * Dependiendo del rol del usuario, redirige a la página correspondiente.
   *
   * @param int $role Rol del usuario.
   * @return void
   */
  function authorizeAccess($role)
  {
    switch ($role) {
      case 1:
        $this->redirect($this->defultSite[1], []);
        break;
      case 2:
        $this->redirect($this->defultSite[2], []);
        break;
    }
  }

  /**
   * Cierra la sesión actual del usuario.
   *
   * Llama al método de la clase Session para destruir la sesión.
   *
   * @return void
   */
  function logout()
  {
    $this->session->closeSession();
  }
}
