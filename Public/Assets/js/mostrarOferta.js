// debe enviar el id de oferta a la function que trae las reservas
async function obtenerYMostrarReservas(pageNumber) {
  const response = await fetch(
    URL_PATH +
      `/Page/mostrarReservasOferta/?id=${idOferta}&pageNumber=${pageNumber}`,
    {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
    }
  );

  if (response.ok) {
    const data = await response.json();

    if (data.success) {
      const { info, page, pages, userLog } = data.result;
      const divReservas = document.getElementById("reservas");
      const dataPaginacion = document.getElementById("btnPaginacion");
      const paginas = pages;
      const pagina = parseInt(page, 10);

      botonesPaginacion(pagina, paginas, dataPaginacion, "reservas");
      limpiarContenido(divReservas);

      info.forEach((element) => {
        let resenaHTML = "&nbsp;";
        let respuestaHTML = "&nbsp;";
        if (element.reserva.textoReserva) {
          resenaHTML = `<div class="col-md-12">Reseña: ${element.reserva.textoReserva}</div>`;
        }
        if (element.reserva.respuesta) {
          respuestaHTML = `<div class="col-md-12 ">Respuesta: ${element.reserva.respuesta}</div>`;
        }
        divReservas.insertAdjacentHTML(
          "beforeend",
          `
                    <div class="col-md-12">
                        <div class="row border rounded" style="margin: auto; padding: 10px;">
                            <div class="col-md-2 align-self-center">
                                <img src="${URL_PATH}/Assets/images/fotoPerfil/${element.usuario.fotoRostro}" style="border-radius: 50%; max-width: 70px;" alt="User-Profile-Image">
                            </div>
                            <div class="col-md-3 align-self-center">
                                <strong>${element.usuario.nombreUsuario}</strong>
                            </div>
                            <div class="col-md-3 align-self-center">
                                <span class="badge bg-primary">Calificación: ${element.reserva.puntaje}/10</span>
                            </div>
                            <div class="col-md-4">
                                ${resenaHTML}
                                ${respuestaHTML}
                            </div>
                        </div>
                    </div>
            
                `
        );
      });

      const ofertar = document.getElementById("contenedorForm");

      if (userLog !== true) {
        ofertar.className = "d-none";
      }
    } else {
      const valoraciones = document.getElementById("valoraciones");
      valoraciones.className = "d-none";
      const oferta = document.getElementById("ofertaDeAlquiler");
      oferta.className = "card col-md-12";

      const ofertar = document.getElementById("contenedorForm");

      if (data.result !== true) {
        ofertar.className = "d-none";
      }
    }
  }
}
obtenerYMostrarReservas(1);

const formOferta = document.getElementById("rentarForm");
formOferta.addEventListener("submit", (e) => {
  e.preventDefault();
  ofertaSubmit();
});

function ofertaSubmit() {
  const formulario = document.getElementById("rentarForm");
  const formData = new FormData(formulario);

  fetch(URL_PATH + "/Rentas/rentar/", { method: "POST", body: formData })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        Modal.confirm({
          title: "Oferta enviada con éxito",
          confirm: false,
          onAccept: () => {
            window.location.replace(URL_PATH + "/Page/home");
          },
        });
      } else {
        const divErr = document.getElementById("errores");
        divErr.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                <strong>${data.message}</strong>
            </div>
            `;
      }
    });
}

const divFechaIniPubli = document.getElementById("fechaInicioOP");
if (fechaInicioOfer === 0) {
  divFechaIniPubli.className = "d-none";
}

const divFechaFinPubli = document.getElementById("fechaFinOP");
if (fechaFinOfer === 0) {
  divFechaFinPubli.className = "d-none";
}
