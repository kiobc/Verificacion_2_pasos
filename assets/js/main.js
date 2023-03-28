$(document).ready(function() {

  // Toast para notificaciones
  //toastr.warning('My name is Inigo Montoya. You killed my father, prepare to die!');

  // Waitme
  //$('body').waitMe({effect : 'orbit'});
  console.log('////////// Bienvenido a Bee Framework Versión ' + Bee.bee_version + ' //////////');
  console.log('//////////////////// www.joystick.com.mx ////////////////////');
  console.log(Bee);

  /**
   * Prueba de peticiones ajax al backend en versión 1.1.3
   */
  function test_ajax() {
    var body = $('body'),
    hook     = 'bee_hook',
    action   = 'post',
    csrf     = Bee.csrf;

    if ($('#test_ajax').length == 0) return;

    $.ajax({
      url: 'ajax/test',
      type: 'post',
      dataType: 'json',
      data : { hook , action , csrf },
      beforeSend: function() {
        body.waitMe();
      }
    }).done(function(res) {
      toastr.success(res.msg);
      console.log(res);
    }).fail(function(err) {
      toastr.error('Prueba AJAX fallida.', '¡Upss!');
    }).always(function() {
      body.waitMe('hide');
    })
  }
  
  /**
   * Alerta para confirmar una acción establecida en un link o ruta específica
   */
  $('body').on('click', '.confirmar', function(e) {
    e.preventDefault();

    let url = $(this).attr('href'),
    ok      = confirm('¿Estás seguro?');

    // Redirección a la URL del enlace
    if (ok) {
      window.location = url;
      return true;
    }
    
    console.log('Acción cancelada.');
    return true;
  });

  /**
   * Inicializa summernote el editor de texto avanzado para textareas
   */
  function init_summernote() {
    if ($('.summernote').length == 0) return;

    $('.summernote').summernote({
      placeholder: 'Escribe en este campo...',
      tabsize: 2,
      height: 300
    });
  }

  /**
   * Inicializa tooltips en todo el sitio
   */
  function init_tooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
  }
  
  // Inicialización de elementos
  init_summernote();
  init_tooltips();
  test_ajax();
});