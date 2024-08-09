<main class="container mt-5">
  <br><br><br>
  <div class="card">
    <div class="card-header">
      <h3 class="text-center">Lista de Usuarios de RapiBnB</h3>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Es</th>
              <th scope="col">Nombre Usuario</th>
              <th scope="col">Correo</th>
              <th scope="col">Nombre Completo</th>
              <th scope="col">Foto</th>
              <th scope="col">Bio</th>
              <th scope="col">Quitar Verificación</th>
            </tr>
          </thead>
          <tbody id="tablaUsuarios">
            <!-- Contenido de la tabla -->
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer text-muted"></div>
  </div>
  <div class="container" id="errorContainer"></div>
</main>


<script src="<?= URL_PATH ?>/Assets/js/administradorUsuarios.js"></script>