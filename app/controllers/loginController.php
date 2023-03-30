<?php 

class loginController extends Controller {
  function __construct()
  {
    if (Auth::validate()) {
      Flasher::new('Ya hay una sesión abierta.');
      Redirect::to('home/flash');
    }
  }

  function index()
  {
    $data =
    [
      'title'   => 'Ingresar a tu cuenta',
      'padding' => '0px'
    ];

    View::render('index', $data);
  }

  function post_login()
  {
    try {
      if (!Csrf::validate($_POST['csrf']) || !check_posted_data(['usuario','csrf','password'], $_POST)) {
        throw new Exception('Acceso no autorizado.');
      }

      
    // Data pasada del formulario
    $usuario  = clean($_POST['usuario']);
    $password = clean($_POST['password']);

    //Validar que exista el usuario
    if(!$user=usuarioModel::by_usuario($usuario)){
      throw new Exception('ELas credenciales no son correctas, intenta de nuevo.');
    }

    // Validar que la contraseña sea correcta
    if(!password_verify($password.AUTH_SALT, $user['password'])){
      throw new Exception('Las credenciales no son correctas, intenta de nuevo.');
    }

    // Loggear al usuario
    Auth::login($user['id'], $user);
    Redirect::to('home');
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }
    
}