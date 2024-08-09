<div class="container mt-5">
    <br><br>
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Crear Usuario</h3>
                </div>
                <div class="card-body">
                    <form action="" method="post" id="usuarioSignUp">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" name="correo" id="correo" placeholder="Correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
                        </div>
                        <div class="mb-3">
                            <label for="passwordConfirm" class="form-label">Confirmar contraseña</label>
                            <input type="password" class="form-control" name="passwordConfirm" id="passwordConfirm" placeholder="Confirmar contraseña" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn confirmacion">Crear Usuario</button>
                            <a href="<?= URL_PATH; ?>" class="btn confirmacion">Volver a inicio</a>
                        </div>
                    </form>
                    <div class="container" id="errores">

                    </div>
                </div>
                <div class="card-footer">
                    ¿Ya tienes una cuenta? <a href="<?= URL_PATH ?>/Usuario/login/">Inicia sesión aquí</a>
                </div>

            </div>
        </div>
    </div>
</div>


<script src="<?= URL_PATH ?>/Assets/js/usuarioSignUp.js"></script>