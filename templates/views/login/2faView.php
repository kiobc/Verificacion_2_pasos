<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="container">
  <div class="py-5 text-center">
    <a href="<?php echo URL; ?>"><img src="<?php echo get_image('bee_logo.png') ?>" alt="Bee framework" class="img-fluid" style="width: 150px;"></a>
    <h2>Autenticacion de 2 factores</h2>
    <p class="lead">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nam, ullam.</p>
  </div>

  <div class="row">
    <!-- formulario -->
    <div class="offset-xl-3 col-xl-6 col-12">
      <div class="card">
       
        <div class="card-body">
          <?php echo Flasher::flash(); ?>
<div id="verificacion_wrapper" class="text-center"></div>
          <form id="verificacion_form" class="text-center" method="post" >
            <?php echo insert_inputs(); ?>
            <input type="hidden" name="hash" id="hash" value="<?php echo $d->hash;?>">

            <div class="mb-3 row">
              <div class="offset-xl-3 col-xl-6">
                <label class="form-label" for="token">Ingresa el codigo de verificacion</label>
                <input type="text" class="form-control form-control-lg text-center" id="token" name="token" maxlength="6" placeholder="289776" required>
                <small class="text-muted"><span class="caducidad_token" data-caducidad="<?php echo $d->caducidad; ?>">123</span><span class="caducidad_texto ms-1">segundos restantes</span></small>
            </div>

            <button class="btn btn-primary btn-lg" type="submit"><i class="fas fa-fingerprint fa-fw"></i>Validar cuenta</button>

          </form>
        </div>
<div class="card-footer">
<small class="text-muted float-end">¿No recibiste el mensaje? Volver a enviar <a class="text-decoration-none reenviar_codigo" href="#">aquí</a>.</small>
</div>     
 </div>
    </div>
  </div>
</div>

<?php require_once INCLUDES.'inc_footer_v2.php'; ?>

