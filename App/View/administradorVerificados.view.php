<main class="container mt-5">
  <br><br><br>
  <div class="card">
    <div class="card-header">
      <h3 class="text-center">Lista de Postulantes a Verificar</h3>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Nombre Usuarios</th>
              <th scope="col">Correo</th>
              <th scope="col">Documentacion</th>
              <th scope="col">Verificar</th>
            </tr>
          </thead>
          <tbody id="tablaPostulantes">
            <!-- Contenido de la tabla -->
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer text-muted"></div>
  </div>
  <div class="container" id="errorContainer"></div>
</main>


<script src="<?= URL_PATH ?>/Assets/js/administradorVerificados.js"></script>