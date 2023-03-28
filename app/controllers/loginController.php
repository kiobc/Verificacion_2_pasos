<?php 

class loginController extends Controller {
  function __construct()
  {
    if (Auth::validate()) {
      Flasher::new('Ya hay una sesión abierta.');
      Redirect::to('home');
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
}