<?php require_once INCLUDES . 'inc_header.php'; ?>

<div class="container">
  <div class="py-5 text-center">
    <a href="<?php echo URL; ?>"><img src="<?php echo get_image('bee_logo.png') ?>" alt="Bee framework" class="img-fluid" style="width: 150px;"></a>
    <h2>Registrate</h2>
  </div>

  <div class="row">
    <!-- formulario -->
    <div class="offset-xl-3 col-xl-6 col-12">
      <div class="card">
        <div class="card-header">
          <h4>Completa el formulario</h4>
        </div>
        <div class="card-body">
          <?php echo Flasher::flash(); ?>

          <form id="registro_form" method="post">
            <?php echo insert_inputs(); ?>

            <div class="mb3">
              <label for="usuario" class="form-label">Usuario</label>
              <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Walter White" required>
            </div>

            <div class="mb3">
              <label for="email" class="form-label mt-3">Email</label>
              <input type="text" class="form-control" name="email" id="email" placeholder="walter@white.com" required>
            </div>

            <div class="form-group">
  <label for="pais" class="form-label-group mt-3">País</label>
  <select name="pais" id="pais" class="form-control"></select>
</div>



            <div class="mb3">
              <label for="telefono" class="form-label mt-3">Teléfono</label>
              <input type="phone" class="form-control" name="telefono" id="telefono" placeholder="0987897528" required>
            </div>

            <div class="mb3">
              <label for="password" class="form-label mt-3">Contraseña</label>
              <input type="password" class="form-control" name="password" id="password" required>
            </div>

            <div class="mb3">
              <label for="password_conf" class="form-label mt-3"> Confirmar Contraseña</label>
              <input type="password" class="form-control" name="password_conf" id="password_conf" required>
            </div>

            <button class="btn btn-primary btn-block mt-3" type="submit">Registrarse</button>


            <small class="text-muted float-end">¿Ya tienes cuenta? Ingresa!! <a class="text-decoration-none" href="login">aquí</a>.</small>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once INCLUDES . 'inc_footer_v2.php'; ?>