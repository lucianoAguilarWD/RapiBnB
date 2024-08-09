async function ofertasAlquilerRecomendadas(pageNumber) {
  let reposense = await fetch(URL_PATH + "/Page/listarRecomentaciones/", {
    method: "POST",
    body: new URLSearchParams({ pageNumber: pageNumber }),
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
  });

  if (reposense.ok) {
    let reposenseData = await reposense.json();

    if (reposenseData.success) {
      const ofertas = reposenseData.result;
      const divRecomendada = document.getElementById("ofertasRecomendadas");
      const dataPaginacion = document.getElementById("Paginacion");

      const paginas = ofertas.pages;
      const pagina = parseInt(ofertas.page, 10);
      botonesPaginacion(pagina, paginas, dataPaginacion, "ofertasRecomendadas");

      limpiarContenido(divRecomendada); // Limpiar el contenido antes de agregar nuevas tarjetas

      ofertas.data.forEach((element) => {
        const galeriaFotosStr = element.galeriaFotos;
        const galeriaFotosArray = galeriaFotosStr.split(", ");

        let carrouselHTML = "";
        galeriaFotosArray.forEach((foto, index) => {
          const activeClass = index === 0 ? "active" : "";
          carrouselHTML += `
                                    <div class="carousel-item ${activeClass}">
                                        <img src="${URL_PATH}/Assets/images/galeriaFotos/${foto}" style="width: 350px; height: 250px;" alt="Imagen">
                                    </div>
                                `;
        });

        divRecomendada.insertAdjacentHTML(
          "beforeend",
          `
                    <div class="card col-md-3" style="max-width: 400px; max-height: 600px; margin: auto;">
                        <a href="${URL_PATH}/Page/oferta/?ofertaID=${
            element.ofertaID
          }"  class="d-block" style="text-decoration: none;>
                            <div class="card-header">
                                <div id="imageCarousel${
                                  element.ofertaID + 23
                                }" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    ${carrouselHTML}
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel${
                                  element.ofertaID + 23
                                }" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel${
                                  element.ofertaID + 23
                                }" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">${element.titulo}</h6>
                                <p class="card-text"><small class="text-muted">Ubicación: ${
                                  element.ubicacion
                                }</small></p>
                            </div>
                        </a>   
                    </div> 
                `
        );
      });
    } else {
      const divRecomendada = document.getElementById("recomendados");

      divRecomendada.className = "d-none";
    }
  }
}

// Llama a 'ofertasAlquilerCard' con la página inicial
ofertasAlquilerRecomendadas(1);
