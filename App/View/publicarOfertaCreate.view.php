<main class="container">

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="text-center">Crear Oferta de Alquiler</h3>
        </div>
        <div class="card-body g-3 mt-3">
            <form action="" method="post" enctype="multipart/form-data" id="ofertaAlquilerCreate">
                <div class="row">
                    <div class="col-md-6">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Título" required>
                    </div>
                    <div class="col-md-6">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripción" required>
                    </div>
                    <div class="col-md-6">
                        <label for="ubicacion" class="form-label">Ubicación:</label>
                        <select class="form-select" name="ubicacion" id="ubicacion" required>
                            <option value="" disabled selected>Selecciona una provincia</option>
                            <option value="Buenos Aires, Argentina">Buenos Aires, Argentina</option>
                            <option value="Catamarca, Argentina">Catamarca, Argentina</option>
                            <option value="Chaco, Argentina">Chaco, Argentina</option>
                            <option value="Chubut, Argentina">Chubut, Argentina</option>
                            <option value="Córdoba, Argentina">Córdoba, Argentina</option>
                            <option value="Corrientes, Argentina">Corrientes, Argentina</option>
                            <option value="Entre Ríos, Argentina">Entre Ríos, Argentina</option>
                            <option value="Formosa, Argentina">Formosa, Argentina</option>
                            <option value="Jujuy, Argentina">Jujuy, Argentina</option>
                            <option value="La Pampa, Argentina">La Pampa, Argentina</option>
                            <option value="La Rioja, Argentina">La Rioja, Argentina</option>
                            <option value="Mendoza, Argentina">Mendoza, Argentina</option>
                            <option value="Misiones, Argentina">Misiones, Argentina</option>
                            <option value="Neuquén, Argentina">Neuquén, Argentina</option>
                            <option value="Río Negro, Argentina">Río Negro, Argentina</option>
                            <option value="Salta, Argentina">Salta, Argentina</option>
                            <option value="San Juan, Argentina">San Juan, Argentina</option>
                            <option value="San Luis, Argentina">San Luis, Argentina</option>
                            <option value="Santa Cruz, Argentina">Santa Cruz, Argentina</option>
                            <option value="Santa Fe, Argentina">Santa Fe, Argentina</option>
                            <option value="Santiago del Estero, Argentina">Santiago del Estero, Argentina</option>
                            <option value="Tierra del Fuego, Argentina">Tierra del Fuego, Argentina</option>
                            <option value="Tucumán, Argentina">Tucumán, Argentina</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="servicios" class="form-label">Servicios:</label><br>
                        <select name="servicios[]" id="servicios" multiple required>
                            <option value="gas">Gas</option>
                            <option value="internet">Internet</option>
                            <option value="electricidad">Electricidad</option>
                            <option value="amoblado">Amoblado</option>
                            <option value="estacionamiento">Estacionamiento</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="etiqueta" class="form-label">Tipo de propiedad:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="casa" id="Casa" required>
                            <label class="form-check-label" for="casa">Casa</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="hotel" id="Hotel">
                            <label class="form-check-label" for="hotel">Hotel</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="departamento" id="Departamento">
                            <label class="form-check-label" for="departamento">Departamento</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="cabana" id="Cabaña">
                            <label class="form-check-label" for="cabana">Cabaña</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="habitaciones" id="Habitaciones">
                            <label class="form-check-label" for="habitaciones">Habitaciones</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="galeriaFotos[]" class="form-label">Galería de Fotos:</label>
                        <input type="file" class="form-control" id="galeriaFotos" name="galeriaFotos[]" multiple accept="image/*" required>
                    </div>
                    <div class="col-md-6">
                        <label for="costoAlquilerPorDia" class="form-label">Costo de Alquiler por Día:</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="costoAlquilerPorDia" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tiempoMinPermanencia" class="form-label">Tiempo Mínimo de Permanencia:</label>
                        <input type="number" min="0" max="366" class="form-control" name="tiempoMinPermanencia" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tiempoMaxPermanencia" class="form-label">Tiempo Máximo de Permanencia:</label>
                        <input type="number" min="0" max="366" class="form-control" name="tiempoMaxPermanencia" required>
                    </div>
                    <div class="col-md-6">
                        <label for="cupo" class="form-label">Cupo:</label>
                        <input type="number" min="0" max="30" class="form-control" name="cupo" required>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaInicio">Fecha de inicio de la publicación:</label>
                        <input type="text" class="form-control datepicker" name="fechaInicio" id="fechaInicio" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaFin">Fecha de fin de la publicación:</label>
                        <input type="text" class="form-control datepicker" name="fechaFin" id="fechaFin" readonly>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn confirmacion">Crear Oferta</button>
                    <a name="" id="" class="btn confirmacion" href="<?= URL_PATH . '/OfertaAlquiler/home/'; ?>" role="button">Cancelar</a>
                </div>
            </form>
            <div class="container" id="errores"></div>
        </div>
        <div class="card-footer"></div>
    </div>
</main>

<script>
    $(document).ready(function() {
        var today = new Date();

        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: today, // Establece la fecha mínima como el día actual
            onSelect: function(date, inst) {
                if (inst.id === "fechaInicio") {
                    // Si se selecciona la fecha de inicio, actualiza la fecha mínima de fecha fin
                    var minDate = new Date(date);
                    minDate.setDate(minDate.getDate() + 1); // Asegura que la fecha mínima de fecha fin sea al menos un día después
                    $("#fechaFin").datepicker("option", "minDate", minDate);
                }
            }
        });
    });
</script>


<script src="<?= URL_PATH ?>/Assets/js/publicarOfertaCreate.js"></script>