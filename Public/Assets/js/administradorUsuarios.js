async function usuarioList() {
  let reposense = await fetch(URL_PATH + "/Administrador/table");
  let reposenseData = await reposense.json();

  if (reposenseData.success) {
    const tablaUsuarios = document.getElementById("tablaUsuarios");
    tablaUsuarios.innerHTML = "";
    let eliminarHTML = "";

    reposenseData.result.forEach((element) => {
      // beforebegin sirve para colocar al final de cada elemento

      const verificado = element.verificado ? "Verificado" : "No verificado";
      if (verificado === "Verificado") {
        eliminarHTML = `
                    <button onclick="eliminarVerificacion(${element.usuario.usuarioID});" class="btn confirmacion">Quitar</button>
                `;
      } else {
        eliminarHTML = `
                    <button class="btn confirmacion d-none">Eliminar</button>
                `;
      }

      tablaUsuarios.insertAdjacentHTML(
        "beforeend",
        `
                    <tr>
                        <th scope="row">${verificado}</th>
                        <td>${element.usuario.nombreUsuario}</td>
                        <td>${element.usuario.correo}</td>
                        <td>${element.usuario.nombreCompleto}</td>
                        <td><img width="50" height="50" src="${URL_PATH}/Assets/images/fotoPerfil/${
          element.usuario.fotoRostro !== ""
            ? element.usuario.fotoRostro
            : "user.png"
        }" alt="Foto de perfil"></td>
                        <td>${element.usuario.bio}</td>
                        <td>${eliminarHTML}</td>
                    </tr>
            `
      );
    });
  }
}
usuarioList();

function eliminarVerificacion(id) {
  Modal.danger({
    confirm: true,
    title: "¿Desea quitar la verificación del usuario?",
    onAccept: () => {
      const data = new FormData();
      data.append("id", id);

      fetch(URL_PATH + "/Administrador/borrarVerificacion/", {
        method: "POST",
        body: data,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            console.log(data.message);
            usuarioList();
          } else {
            console.log(data.message);
          }
        });
    },
  });
}
