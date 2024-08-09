<main class="container-fluid mt-3">
  <!-- ofertas de verificados-->
  <section>
    <div class="card">
      <div class="card-header">
        <div class="row">
          <h3 class="col-md-6 text-start">Destacadas</h3>
          <div class="col-md-6 text-end" id="dataPaginacion"></div>
        </div>
      </div>
      <div class="card-body">
        <div class="container row" style="margin: auto;" id="prueba"></div>
      </div>
    </div>
  </section>

  <!-- ofertas restantes -->

  <section class="mt-2">
    <div class="card">
      <div class="card-header">
        <div class="text-end" id="botonesPaginacion"></div>
      </div>
      <div class="card-body">
        <div class="container row" style="margin:auto;" id="ofertas"></div>
      </div>
    </div>
  </section>

  <!-- ofertas restantes -->

  <section class="mt-2">
    <div class="card" id="recomendados">
      <div class="card-header">
        <div class="row">
          <h3 class="col-md-6 text-start">Recomendadas</h3>
          <div class="col-md-6 text-end" id="Paginacion"></div>
        </div>
      </div>
      <div class="card-body">
        <div class="container row" style="margin:auto;" id="ofertasRecomendadas"></div>
      </div>
    </div>
  </section>

</main>

<script src="<?= URL_PATH ?>/Assets/js/pageListarOfertasDestacadas.js"></script>
<script src="<?= URL_PATH ?>/Assets/js/pageListarOfertas.js"></script>
<script src="<?= URL_PATH ?>/Assets/js/pageListarOfertasRecomendadas.js"></script>