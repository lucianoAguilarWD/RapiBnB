<main class="container">
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="text-center">Editar Oferta de Alquiler</h3>
        </div>
        <div class="card-body g-3 mt-3">
            <form action="" method="post" enctype="multipart/form-data" id="ofertaAlquilerEdit">
                <div class="row">
                    <div class="col-md-6">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" class="form-control" name="titulo" id="titulo" value="<?= $parameters['oferta']['titulo'] ?? '' ?>" placeholder="Título" required>
                    </div>
                    <div class="col-md-6">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <input type text" class="form-control" name="descripcion" id="descripcion" value="<?= $parameters['oferta']['descripcion'] ?? '' ?>" placeholder="Descripción" required>
                    </div>
                    <div class="col-md-6">
                        <label for="ubicacion" class="form-label">Ubicación:</label>
                        <select class="form-select" name="ubicacion" id="ubicacion" required>
                            <option value="" disabled>Selecciona una provincia</option>
                            <option value="Buenos Aires" <?= ($parameters['oferta']['ubicacion'] == 'Buenos Aires') ? 'selected' : '' ?>>Buenos Aires</option>
                            <option value="Catamarca" <?= ($parameters['oferta']['ubicacion'] == 'Catamarca') ? 'selected' : '' ?>>Catamarca</option>
                            <option value="Chaco" <?= ($parameters['oferta']['ubicacion'] == 'Chaco') ? 'selected' : '' ?>>Chaco</option>
                            <option value="Chubut" <?= ($parameters['oferta']['ubicacion'] == 'Chubut') ? 'selected' : '' ?>>Chubut</option>
                            <option value="Córdoba" <?= ($parameters['oferta']['ubicacion'] == 'Córdoba') ? 'selected' : '' ?>>Córdoba</option>
                            <option value="Corrientes" <?= ($parameters['oferta']['ubicacion'] == 'Corrientes') ? 'selected' : '' ?>>Corrientes</option>
                            <option value="Entre Ríos" <?= ($parameters['oferta']['ubicacion'] == 'Entre Ríos') ? 'selected' : '' ?>>Entre Ríos</option>
                            <option value="Formosa" <?= ($parameters['oferta']['ubicacion'] == 'Formosa') ? 'selected' : '' ?>>Formosa</option>
                            <option value="Jujuy" <?= ($parameters['oferta']['ubicacion'] == 'Jujuy') ? 'selected' : '' ?>>Jujuy</option>
                            <option value="La Pampa" <?= ($parameters['oferta']['ubicacion'] == 'La Pampa') ? 'selected' : '' ?>>La Pampa</option>
                            <option value="La Rioja" <?= ($parameters['oferta']['ubicacion'] == 'La Rioja') ? 'selected' : '' ?>>La Rioja</option>
                            <option value="Mendoza" <?= ($parameters['oferta']['ubicacion'] == 'Mendoza') ? 'selected' : '' ?>>Mendoza</option>
                            <option value="Misiones" <?= ($parameters['oferta']['ubicacion'] == 'Misiones') ? 'selected' : '' ?>>Misiones</option>
                            <option value="Neuquén" <?= ($parameters['oferta']['ubicacion'] == 'Neuquén') ? 'selected' : '' ?>>Neuquén</option>
                            <option value="Río Negro" <?= ($parameters['oferta']['ubicacion'] == 'Río Negro') ? 'selected' : '' ?>>Río Negro</option>
                            <option value="Salta" <?= ($parameters['oferta']['ubicacion'] == 'Salta') ? 'selected' : '' ?>>Salta</option>
                            <option value="San Juan" <?= ($parameters['oferta']['ubicacion'] == 'San Juan') ? 'selected' : '' ?>>San Juan</option>
                            <option value="San Luis" <?= ($parameters['oferta']['ubicacion'] == 'San Luis') ? 'selected' : '' ?>>San Luis</option>
                            <option value="Santa Cruz" <?= ($parameters['oferta']['ubicacion'] == 'Santa Cruz') ? 'selected' : '' ?>>Santa Cruz</option>
                            <option value="Santa Fe" <?= ($parameters['oferta']['ubicacion'] == 'Santa Fe') ? 'selected' : '' ?>>Santa Fe</option>
                            <option value="Santiago del Estero" <?= ($parameters['oferta']['ubicacion'] == 'Santiago del Estero') ? 'selected' : '' ?>>Santiago del Estero</option>
                            <option value="Tierra del Fuego" <?= ($parameters['oferta']['ubicacion'] == 'Tierra del Fuego') ? 'selected' : '' ?>>Tierra del Fuego</option>
                            <option value="Tucumán" <?= ($parameters['oferta']['ubicacion'] == 'Tucumán') ? 'selected' : '' ?>>Tucumán</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="servicios[]" class="form-label">Servicios:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="gas" id="Gas" <?= isset($parameters['oferta']['listServicios']) && in_array('gas', explode(', ', $parameters['oferta']['listServicios'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="gas">Gas</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="internet" id="Internet" <?= isset($parameters['oferta']['listServicios']) && in_array('internet', explode(', ', $parameters['oferta']['listServicios'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="internet">Internet</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="electricidad" id="Electricidad" <?= isset($parameters['oferta']['listServicios']) && in_array('electricidad', explode(', ', $parameters['oferta']['listServicios'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="electricidad">Electricidad</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="amoblado" id="Amoblado" <?= isset($parameters['oferta']['listServicios']) && in_array('amoblado', explode(', ', $parameters['oferta']['listServicios'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="amoblado">Amoblado</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="estacionamiento" id="Estacionamiento" <?= isset($parameters['oferta']['listServicios']) && in_array('estacionamiento', explode(', ', $parameters['oferta']['listServicios'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="estacionamiento">Estacionamiento</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="etiqueta" class="form-label">Tipo de propiedad:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="casa" id="Casa" <?= isset($parameters['oferta']['etiquetas']) && $parameters['oferta']['etiquetas'] == 'casa' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="casa">Casa</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="hotel" id="Hotel" <?= isset($parameters['oferta']['etiquetas']) && $parameters['oferta']['etiquetas'] == 'hotel' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="hotel">Hotel</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="departamento" id="Departamento" <?= isset($parameters['oferta']['etiquetas']) && $parameters['oferta']['etiquetas'] == 'departamento' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="departamento">Departamento</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="cabana" id="Cabaña" <?= isset($parameters['oferta']['etiquetas']) && $parameters['oferta']['etiquetas'] == 'cabana' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="cabana">Cabaña</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etiqueta" value="habitaciones" id="Habitaciones" <?= isset($parameters['oferta']['etiquetas']) && $parameters['oferta']['etiquetas'] == 'habitaciones' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="habitaciones">Habitaciones</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="galeriaFotos" class="form-label">Galería de Fotos:</label>
                        <input type="file" class="form-control" id="galeriaFotos" name="galeriaFotos[]" multiple accept="image/*" required>
                    </div>

                    <div class="col-md-6">
                        <label for="costoAlquilerPorDia" class="form-label">Costo de Alquiler por Día:</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="costoAlquilerPorDia" value="<?= $parameters['oferta']['costoAlquilerPorDia'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tiempoMinPermanencia" class="form-label">Tiempo Mínimo de Permanencia:</label>
                        <input type="number" min="0" max="366" class="form-control" name="tiempoMinPermanencia" value="<?= $parameters['oferta']['tiempoMinPermanencia'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tiempoMaxPermanencia" class="form-label">Tiempo Máximo de Permanencia:</label>
                        <input type="number" min="0" max="366" class="form-control" name="tiempoMaxPermanencia" value="<?= $parameters['oferta']['tiempoMaxPermanencia'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="cupo" class="form-label">Cupo:</label>
                        <input type="number" min="0" max="30" class="form-control" name="cupo" value="<?= $parameters['oferta']['cupo'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaInicio">Fecha de inicio de la publicación:</label>
                        <input type="text" class="form-control datepicker" name="fechaInicio" id="fechaInicio" value="<?= $parameters['oferta']['fechaInicio'] ?? '' ?> " readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaFin">Fecha de fin de la publicación:</label>
                        <input type="text" class="form-control datepicker" name="fechaFin" id="fechaFin" value="<?= $parameters['oferta']['fechaFin'] ?? '' ?>" readonly>
                    </div>

                    <div class="col-md-12 d-none">
                        <label for="textID" class="form-label">ID</label>
                        <input type="text" class="form-control" value="<?= $parameters['oferta']['ofertaID'] ?? '' ?>" name="textID" id="textID" placeholder="ID">
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn confirmacion">Modificar Oferta</button>
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

<script src="<?= URL_PATH ?>/Assets/js/publicarOfertaEdit.js"></script>