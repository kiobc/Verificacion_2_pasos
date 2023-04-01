<?php
require_once __DIR__ . '/../../vendor\twilio\autoload.php';



use Twilio\Rest\Client;
class ajaxController extends Controller
{


  /**
   * La petición del servidor
   *
   * @var string
   */
  private $r_type = null;

  /**
   * Hook solicitado para la petición
   *
   * @var string
   */
  private $hook   = null;

  /**
   * Tipo de acción a realizar en ajax
   *
   * @var string
   */
  private $action = null;

  /**
   * Token csrf de la sesión del usuario que solicita la petición
   *
   * @var string
   */
  private $csrf   = null;

  /**
   * Todos los parámetros recibidos de la petición
   *
   * @var array
   */
  private $data   = null;

  /**
   * Parámetros parseados en caso de ser petición put | delete | headers | options
   *
   * @var mixed
   */
  private $parsed = null;

  /**
   * Valor que se deberá proporcionar como hook para
   * aceptar una petición entrante
   *
   * @var string
   */
  private $hook_name        = 'bee_hook'; // Si es modificado, actualizar el valor en la función core insert_inputs()

  /**
   * parámetros que serán requeridos en TODAS las peticiones pasadas a ajaxController
   * si uno de estos no es proporcionado la petición fallará
   *
   * @var array
   */
  private $required_params  = ['hook', 'action'];

  /**
   * Posibles verbos o acciones a pasar para nuestra petición
   *
   * @var array
   */
  private $accepted_actions = ['get', 'post', 'put', 'delete', 'options', 'headers', 'add', 'load'];

  function __construct()
  {
    // Parsing del cuerpo de la petición
    $this->r_type = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null;
    $this->data   = in_array($this->r_type, ['PUT', 'DELETE', 'HEADERS', 'OPTIONS']) ? parse_str(file_get_contents("php://input"), $this->parsed) : ($this->r_type === 'GET' ? $_GET : $_POST);
    $this->data   = $this->parsed !== null ? $this->parsed : $this->data;
    $this->hook   = isset($this->data['hook']) ? $this->data['hook'] : null;
    $this->action = isset($this->data['action']) ? $this->data['action'] : null;
    $this->csrf   = isset($this->data['csrf']) ? $this->data['csrf'] : null;

    // Validar que hook exista y sea válido
    if ($this->hook !== $this->hook_name) {
      http_response_code(403);
      json_output(json_build(403));
    }

    // Validar que se pase un verbo válido y aceptado
    if (!in_array($this->action, $this->accepted_actions)) {
      http_response_code(403);
      json_output(json_build(403));
    }

    // Validación de que todos los parámetros requeridos son proporcionados
    foreach ($this->required_params as $param) {
      if (!isset($this->data[$param])) {
        http_response_code(403);
        json_output(json_build(403));
      }
    }

    // Validar de la petición post / put / delete el token csrf
    if (in_array($this->action, ['post', 'put', 'delete', 'add', 'headers']) && !Csrf::validate($this->csrf)) {
      http_response_code(403);
      json_output(json_build(403));
    }
  }

  function index()
  {
    /**
    200 OK
    201 Created
    300 Multiple Choices
    301 Moved Permanently
    302 Found
    304 Not Modified
    307 Temporary Redirect
    400 Bad Request
    401 Unauthorized
    403 Forbidden
    404 Not Found
    410 Gone
    500 Internal Server Error
    501 Not Implemented
    503 Service Unavailable
    550 Permission denied
     */
    json_output(json_build(403));
  }

  function test()
  {
    try {
      json_output(json_build(200, null, 'Prueba de AJAX realizada con éxito.'));
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }
  function get_codigos_paises()
  {
    $data = json_decode(file_get_contents(ROOT . 'assets' . DS . 'js' . DS . 'paises.json'));
    json_output(json_build(200, $data));
  }
  function do_registrar_usuario()
  {
    try {
      if (!check_posted_data(['usuario', 'email', 'telefono', 'pais', 'password', 'password_conf'], $_POST)) {
        throw new Exception('Faltan datos por enviar');
      }
      $usuario = clean($_POST["usuario"]);
      $email = clean($_POST["email"]);
      $pais = clean(str_replace(['+', ' ', '_', '-'], '', $_POST["pais"]));
      $telefono = clean(str_replace(['+', ' ', '_', '-'], '', $_POST["telefono"]));
      $password = clean($_POST["password"]);
      $password2 = clean($_POST["password_conf"]);
      //Validacion de datos
      if (strlen($usuario) <= 5) {
        throw new Exception('El nombre de usuario es demasiado corto, debe ser mayor a 5 caracteres');
      }
      if (usuarioModel::by_usuario($usuario)) {
        throw new Exception(sprintf('El nombre de usuario %s ya existe', $usuario));
      }
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El email no es válido');
      }
      if (strlen($telefono) < 8) {
        throw new Exception('El número de teléfono es demasiado corto, debe ser mayor a 8 caracteres');
      }
      $telefono = sprintf('%s%s', $pais, $telefono);
      if ($password !== $password2) {
        throw new Exception('Las contraseñas no coinciden');
      }
      $data = [
        'usuario' => $usuario,
        'email' => $email,
        'telefono' => $telefono,
        'password' => password_hash($password . AUTH_SALT, PASSWORD_BCRYPT),
        'hash' => generate_token(),
        'creado' => now()
      ];
      if (!$id = usuarioModel::add(usuarioModel::$t1, $data)) {
        throw new Exception('No se pudo registrar el usuario');
      }
      $usuario = usuarioModel::by_id($id);
      json_output(json_build(201, $usuario, 'Usuario registrado con éxito'));
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function do_login_usuario_v1()
  {
    try {
      if (!check_posted_data(['usuario', 'password'], $_POST)) {
        throw new Exception('Complete el formulario para continuar');
      }


      // Data pasada del formulario
      $usuario  = clean($_POST['usuario']);
      $password = clean($_POST['password']);

      //Validar que exista el usuario
      if (!$user = usuarioModel::by_usuario($usuario)) {
        throw new Exception('Las credenciales no son correctas, intenta de nuevo.');
      }

      // Validar que la contraseña sea correcta
      if (!password_verify($password.AUTH_SALT, $user['password'])) {
        throw new Exception('Las credenciales no son correctas, intenta de nuevo.');
      }

      // Loggear al usuario
      Auth::login($user['id'], $user);
      json_output(json_build(200, ['url' => URL.'home'], sprintf('Bienvenido de nuevo %s ', $user['usuario'])));
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function do_login_usuario_v2()
  {
    try {
      if (!check_posted_data(['usuario', 'password'], $_POST)) {
        throw new Exception('Complete el formulario para continuar');
      }


      // Data pasada del formulario
      $ip = get_user_ip();
      $usuario  = clean($_POST['usuario']);
      $password = clean($_POST['password']);
      $token = null;
      $caducidad= null;

      //Validar que exista el usuario
      if (!$user = usuarioModel::by_usuario($usuario)) {
        throw new Exception('Las credenciales no son correctas, intenta de nuevo.');
      }

      // Validar que la contraseña sea correcta
      if (!password_verify($password.AUTH_SALT, $user['password'])) {
        throw new Exception('Las credenciales no son correctas, intenta de nuevo.');
      }

      //Verificar si existe un registro de 2da verificacion valido
      if(!$token=postModel::autorizado($user['id'])){
        //se generara un nuevo token y sms al usuario a verificar
        //borrar todos los tokens anteriores
        postModel::remove(postModel::$t1, ['id_usuario' => $user['id'],'tipo' => '2fa_token']);
        //generar nuevo token
        $token= random_password(6, 'numeric');
        $caducidad= strtotime('+2 minutes');
        $data = [
          'id_usuario' => $user['id'],
          'tipo' => '2fa_token',
          'titulo'=> 'Token de verificación',
          'contenido' => password_hash($token.AUTH_SALT, PASSWORD_BCRYPT),
          'permalink' => $caducidad,
          'ip' => $ip,
          'creado' => now()
        ];
        //Agregar el nuevo token a la base de datos
        if (!$id_post = postModel::add(postModel::$t1, $data)) {
          throw new Exception('Hubo un error al generar el token de verificación');
        }
      }
//Enviar sms al usuario

$sid    = "AC5ea54765058ff0d8805d4d839cef2393"; 
$auth_token = "262f8eb283467ef32aa66d790e01c557"; 
$twilio = new Twilio\Rest\Client($sid, $auth_token); 
 
$message = $twilio->messages 
                  ->create("+593987897528", // to 
                           array(  
                               "messagingServiceSid" => "MG17bbdc41b7efe5607c507078ca5a0d73",      
                               "body" => sprintf('Tu token de verificación es: %s', $token)
                           ) 
                  ); 
                  
$response=print($message->sid);
if(!isset($response->id)){
  throw new Exception('Hubo un error al enviar el sms');}
  logger(sprintf('nuevo token creado: %s', $token));

json_output(json_build(200,['url'=>buildURL(URL.'login/verificar', ['hash' => $user['hash']],false, false)],sprintf('Verifica tu cuenta %s', $user['usuario'])));

      // Loggear al usuario
      Auth::login($user['id'], $user);
      json_output(json_build(200, ['url' => URL.'home'], sprintf('Bienvenido de nuevo %s ', $user['usuario'])));
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }
}
