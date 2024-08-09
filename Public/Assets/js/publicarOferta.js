async function ofertasAlquilerCard() {
  let reposense = await fetch(URL_PATH + "/OfertaAlquiler/table");
  let reposenseData = await reposense.json();

  if (reposenseData.success) {
    const divCard = document.getElementById("divCard");
    divCard.innerHTML = "";
    const data = reposenseData.result.ofertas;
    if (data != null && data.length > 0) {
      data.forEach((element) => {
        const galeriaFotosStr = element.galeriaFotos;
        const galeriaFotosArray = galeriaFotosStr.split(", ");

        let carrouselHTML = ""; // Variable para almacenar el HTML del carrusel
        galeriaFotosArray.forEach((foto, index) => {
          const activeClass = index === 0 ? "active" : ""; // Establecer la primera imagen como activa
          carrouselHTML += `
                        <div class="carousel-item ${activeClass}">
                            <img  src="${URL_PATH}/Assets/images/galeriaFotos/${foto}" style="width: 500px; height: 300px;" alt="Imagen">
                        </div>
                    `;
        });

        divCard.innerHTML += `
                    <div class="card col-md-4" style="max-width: 500px; max-height: 900px; margin: auto;">
                        <div id="imageCarousel${
                          element.ofertaID
                        }" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                ${carrouselHTML}
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel${
                              element.ofertaID
                            }" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel${
                              element.ofertaID
                            }" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${element.titulo}</h5>
                            <p class="card-text">${element.descripcion}</p>
                            <p class="card-text">Ubicación: ${
                              element.ubicacion
                            }</p>
                            <div class="row border rounded">
                                <div class="col-md-12 border">
                                    <label for="servicios">Servicios:</label>
                                    <p class="card-text " id="servicios"> ${
                                      element.listServicios
                                    }</p>
                                </div>
                                <div class="col-md-6 border">
                                    <label for="costoPorDia">Costo por día:</label>
                                    <p class="card-text " id="costoPorDia">$${
                                      element.costoAlquilerPorDia
                                    }</p>
                                </div>
                                <div class="col-md-6 border">
                                    <label for="cupoPersonas">Cupo de personas:</label>
                                    <p class="card-text" id="cupoPersonas">${
                                      element.cupo
                                    } personas</p>
                                </div>
                                <div class="col-md-6 border">
                                    <label for="tiempoMinPermanencia">Tiempo mínimo de permanencia:</label>
                                    <p class="card-text" id="tiempoMinPermanencia">${
                                      element.tiempoMinPermanencia
                                    } días</p>
                                </div>
                                <div class="col-md-6 border">
                                    <label for="tiempoMaxPermanencia">Tiempo máximo de permanencia:</label>
                                    <p class="card-text " id="tiempoMaxPermanencia">${
                                      element.tiempoMaxPermanencia
                                    } días</p>
                                </div>
                                <div class="col-md-6 border">
                                    <label for="tiempoMaxPermanencia">Estado:</label>
                                    <p class="card-text " id="tiempoMaxPermanencia">${
                                      element.estado
                                    }</p>
                                </div>
                                <div class="col-md-6 border">
                                    <label for="fechaInicio">Fecha en que inicia la publicación:</label>
                                    <p class="card-text" id="fechaInicio">${
                                      element.fechaInicio !== "0000-00-00"
                                        ? element.fechaInicio
                                        : "No especificada"
                                    }</p>
                                </div>
                                <div class="col-md-6 border">
                                    <label for="fechaFin">Fecha en que finaliza la publicación:</label>
                                    <p class="card-text" id="fechaFin">${
                                      element.fechaFin !== "0000-00-00"
                                        ? element.fechaFin
                                        : "No especificada"
                                    }</p>
                                </div>
                            </div>
                            <div class="container mt-1">
                                <div class="row">
                                    <div class="col-md-6">
                                    <a name="" id="" class="btn confirmacion" href="${URL_PATH}/OfertaAlquiler/edit/?id=${
          element.ofertaID
        }" role="button"><i class="fas fa-pencil-alt"></i>Publicación</a>
                                    </div>
                                    <div class="col-md-6">
                                    <button onclick="eliminarOferta(${
                                      element.ofertaID
                                    });"  class="btn confirmacion">Eliminar Publicación</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
      });
    } else {
      divCard.innerHTML = `
                <h1 class="display-4">¡Bienvenido! Aún no has agregado ninguna oferta de alquiler.</h1>
                <p class="lead">Este es un espacio destacado para resaltar tus ofertas de alquiler. Crea una publicación para que otros puedan encontrar y conocer tus propiedades.</p>
                <hr class="my-4">
                <p>Utilizamos clases de utilidad para la tipografía y el espaciado, creando un diseño atractivo para destacar las ofertas disponibles.</p>
            `;
    }
  }
}

ofertasAlquilerCard();

function eliminarOferta(id) {
  Modal.danger({
    title: "¿Desea eliminar oferta?",
    confirm: true,
    onAccept: () => {
      const data = new FormData();
      data.append("id", id);

      fetch(URL_PATH + "/OfertaAlquiler/delete/", {
        method: "POST",
        body: data,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            console.log(data.message);
            ofertasAlquilerCard();
          }
        });
    },
  });
}

function crearOferta(esVerificado, cantOfertas) {
  if (esVerificado === false) {
    if (cantOfertas > 0) {
      Modal.danger({
        title: "No puede agregar más ofertas de alquiler",
        content:
          "Para poder agregar más ofertas de alquiler debe verificar su cuenta primero",
        confirm: false,
      });
    } else {
      window.location.replace(`${URL_PATH}/OfertaAlquiler/crear/`);
    }
  } else {
    window.location.replace(`${URL_PATH}/OfertaAlquiler/crear/`);
  }
}
