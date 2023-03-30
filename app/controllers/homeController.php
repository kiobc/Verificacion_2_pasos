<?php 

class homeController extends Controller {
  function __construct()
  {
    parent::auth();
  }

  function index()
  {
    $data =
    [
      'title' => 'Bienvenido',
      'user'  => User::profile(),
    ];

    View::render('flash', $data);
  }

  function flash()
  {
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }

    View::render('flash', ['title' => 'Flash', 'user' => User::profile()]);
  }

  function gastos()
  {
    View::render('gastos');
  }

  function yumi()
  {
    View::render('yumi');
  }
}