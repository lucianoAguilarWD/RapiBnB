const usuarioLogin = document.getElementById("usuarioLogin");
usuarioLogin.addEventListener("submit", (e) => {
  e.preventDefault();
  usuarioLogSubmit();
});

async function usuarioLogSubmit() {
  const formulario = document.getElementById("usuarioLogin");
  const formData = new FormData(formulario);

  fetch(URL_PATH + "/Usuario/loginProcess/", { method: "POST", body: formData })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        window.location.replace(URL_PATH);
      } else {
        if (data.message === "El usuario no fue encontrado.") {
          const usuarioLogin = document.getElementById("usuarioLogin");
          usuarioLogin.innerHTML = "";
          usuarioLogin.insertAdjacentHTML(
            "beforeend",
            `
                <div class="mb-3">
                <label for="usuario" class="form-label" >Usuario</label>
                <input type="text" class="form-control" name="usuario" id="usuario" aria-describedby="help">
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="contrasena" id="contrasena" aria-describedby="helpId" placeholder="">
                </div>
                <button type="submit" class="btn confirmacion">Ingresar</button>
                <a href="${URL_PATH}" class="btn confirmacion">Volver a inicio</a>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                        <strong>${data.message}</strong>
                    </div>                            
                `
          );
        }
        if (data.message === "Contraseña incorrecta.") {
          const usuarioLogin = document.getElementById("usuarioLogin");
          const nombreUsuario = document.getElementById("usuario").value;
          usuarioLogin.innerHTML = "";
          usuarioLogin.insertAdjacentHTML(
            "beforeend",
            `
                <div class="mb-3">
                <label for="usuario" class="form-label" >Usuario</label>
                <input type="text" class="form-control" value="${nombreUsuario}" name="usuario" id="usuario" aria-describedby="help">
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="contrasena" id="contrasena" aria-describedby="helpId" placeholder="">
                </div>
                <button type="submit" class="btn confirmacion">Ingresar</button>
                <a href="${URL_PATH}" class="btn confirmacion">Volver a inicio</a>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                        <strong>${data.message}</strong>
                    </div>                           
                `
          );
        }
      }
    });
}
