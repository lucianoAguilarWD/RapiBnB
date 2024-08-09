const updateOfertaForm = document.getElementById("ofertaAlquilerEdit");
updateOfertaForm.addEventListener("submit", (e) => {
  e.preventDefault();
  ofertaUpdate();
});
function ofertaUpdate() {
  const formulario = document.getElementById("ofertaAlquilerEdit");
  const formData = new FormData(formulario);

  fetch(URL_PATH + "/OfertaAlquiler/update/", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        console.log(data.message);
        window.location.replace(URL_PATH + "/OfertaAlquiler/home");
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
