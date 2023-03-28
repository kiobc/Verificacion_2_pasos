<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="container">
  <div class="py-5 text-center">
    <a href="<?php echo URL; ?>"><img src="<?php echo get_image('bee_logo.png') ?>" alt="Bee framework" class="img-fluid" style="width: 150px;"></a>
    <h2>Ingresa a tu cuenta</h2>
    <p class="lead">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nam, ullam.</p>
  </div>

  <div class="row">
    <!-- formulario -->
    <div class="offset-xl-3 col-xl-6 col-12">
      <div class="card">
        <div class="card-header">
          <h4>Ingresa a tu cuenta</h4>
        </div>
        <div class="card-body">
          <?php echo Flasher::flash(); ?>

          <form id="login_form" method="post">
            <?php echo insert_inputs(); ?>
            
            <div class="mb-3 row">
              <div class="col-xl-6">
                <label for="usuario">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Walter White" required>
              </div>
              <div class="col-xl-6">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
            </div>

            <button class="btn btn-primary btn-block" type="submit">Ingresar</button>

            <small class="text-muted float-end">¿No tienes cuenta? Regístrate <a class="text-decoration-none" href="registro">aquí</a>.</small>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once INCLUDES.'inc_footer_v2.php'; ?>

