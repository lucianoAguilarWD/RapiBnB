<main class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Agregar Intereses</h3>
        </div>
        <div class="card-body">
            <form action="" method="post" id="usuarioIntereses">
                <div class="row">
                    <!--servicios-->
                    <div class="col-md-4">
                        <label for="servicios[]" class="form-label">Servicios:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="gas" id="Gas">
                            <label class="form-check-label" for="gas">Gas</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="internet" id="Internet">
                            <label class="form-check-label" for="internet">Internet</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="electricidad" id="Electricidad">
                            <label class="form-check-label" for="electricidad">Electricidad</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="amoblado" id="Amoblado">
                            <label class="form-check-label" for="amoblado">Amoblado</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="servicios[]" value="estacionamiento" id="Estacionamiento">
                            <label class="form-check-label" for="estacionamiento">Estacionamiento</label>
                        </div>
                    </div>
                    <!--ubicacion provincia check box-->
                    <div class="col-md-4">
                        <label for="ubicacion" class="form-label">Ubicación:</label>
                        <select class="form-select" name="ubicacion[]" id="ubicacion" multiple>
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

                    <!--tipo de propiedad-->
                    <div class="col-md-4">
                        <label for="etiqueta" class="form-label">Tipo de propiedad:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="etiqueta[]" value="casa" id="Casa">
                            <label class="form-check-label" for="casa">Casa</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="etiqueta[]" value="hotel" id="Hotel">
                            <label class="form-check-label" for="hotel">Hotel</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="etiqueta[]" value="departamento" id="Departamento">
                            <label class="form-check-label" for="departamento">Departamento</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="etiqueta[]" value="cabana" id="Cabaña">
                            <label class="form-check-label" for="cabana">Cabaña</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="etiqueta[]" value="habitaciones" id="Habitaciones">
                            <label class="form-check-label" for="habitaciones">Habitaciones</label>
                        </div>
                    </div>
                    <div class="col-md-12 d-none">
                        <label for="textID" class="form-label">ID</label>
                        <input type="text" class="form-control" value="<?= $parameters['intereses'] ?? '' ?>" name="textID" id="textID" placeholder="ID">
                    </div>
                    <div class="text-center ">
                        <button type="submit" class="btn confirmacion">Agregar</button>
                        <a id="" class="btn confirmacion" href="<?= URL_PATH . '/Usuario/home/'; ?>" role="button">Cancelar</a>
                    </div>
                </div>
            </form>
            <div class="container" id="errores">

            </div>
        </div>
        <div class="card-footer">

        </div>
    </div>

</main>

<script src="<?= URL_PATH ?>/Assets/js/usuarioIntereses.js"></script>