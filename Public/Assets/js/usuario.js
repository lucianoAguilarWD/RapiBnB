async function user() {
  let reposense = await fetch(URL_PATH + "/Usuario/table");
  let reposenseData = await reposense.json();

  if (reposenseData.success) {
    const cardUser = document.getElementById("divUser");
    const data = reposenseData.result.usuario;
    const intereses = reposenseData.result.intereses;
    const esVerificado = reposenseData.result.esVerificado;
    const foto = data.fotoRostro != null ? data.fotoRostro : "user.png";
    const nombre =
      data.nombreCompleto != null ? data.nombreCompleto : "Agregue su nombre";
    const bio = data.bio != null ? data.bio : "Agregue su bio";
    const tel = data.telefono != null ? data.telefono : "Agregue su telefono";
    const documentacionData = data.documentacionID;
    let interesesHTML = "";
    let documentacionHTML = "";
    let verificado = "";

    if (intereses && intereses.length > 0) {
      interesesHTML = intereses
        .map((interes) => {
          return `
                <p style="font-weight: 400;">Ubicacion/es: ${interes.ubicacion}</p>
                <p style="font-weight: 400;">Tipo de propiedad: ${interes.etiquetas}</p>
                <p style="font-weight: 400;">Servicios: ${interes.listServicios}</p>
                `;
        })
        .join("");
    } else {
      interesesHTML = "Aún no ha agregado sus intereses";
    }

    if (documentacionData === null && esVerificado === null) {
      documentacionHTML = `
                    <a class="btn confirmacion" href="${URL_PATH}/usuario/verificar/" role="button">Verificar Cuenta</a>
                `;
    } else {
      documentacionHTML = `
                    <a class="btn confirmacion d-none" href="" role="button">no ver</a>
                `;
      if (esVerificado === true) {
        verificado = `
                <h6 style="font-weight: 600; margin-bottom: 10px;">Estado:</h6>
                <p style="font-weight: 400;">Verificado</p>
            `;
      } else {
        verificado = `
                <h6 style="font-weight: 600; margin-bottom: 10px;">Estado:</h6>
                <p style="font-weight: 400;">Procesando solicitud de verificación</p>
            `;
      }
    }

    cardUser.innerHTML = `
                <div style="padding: 3rem !important; display: flex; justify-content: center;">
                    <div style="width: 600px; background-color: #f0f0f0; border-radius: 10px; padding: 20px;">
                        <div style="text-align: center;">
                            <img src="${URL_PATH}/Assets/images/fotoPerfil/${foto}" style="border-radius: 50%; max-width: 100px;" alt="User-Profile-Image">
                            <h6 style="font-weight: 600; margin-top: 10px;">${data.nombreUsuario}</h6>
                            
                        </div>
                        
                        <div>
                            <h6 style="font-weight: 600; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #ccc;">Información</h6>
                            <div>
                                <p style="font-weight: 600; margin-bottom: 10px;">Nombre Completo:</p>
                                <p style="font-weight: 400;">${nombre}</p>
                            </div>
                            <div>
                                <p style="font-weight: 600; margin-bottom: 10px;">Email:</p>
                                <p style="font-weight: 400;">${data.correo}</p>
                            </div>
                            <div>
                                <p style="font-weight: 600; margin-bottom: 10px;">Telefono:</p>
                                <p style="font-weight: 400;">${tel}</p>
                            </div>
                            <div>
                                <p style="font-weight: 600; margin-bottom: 10px;">Bio:</p>
                                <p style="font-weight: 400;">${bio}</p>
                            </div>
                            <div>
                                <h6 style="font-weight: 600; margin-bottom: 10px;">Intereses:</h6>
                                ${interesesHTML}
                                <a class="btn confirmacion text-end" href="${URL_PATH}/usuario/interesesForm/" role="button">Agregar intereses</a>
                            </div>
                            <div>
                                ${verificado}
                                
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                            <button onclick="modalRestriccion(${data.usuarioID},${esVerificado});" class="btn confirmacion"><i class="fas fa-pencil-alt"></i>Perfil</button>
                            ${documentacionHTML}
                        </div>
                    </div>
                </div>
            `;
  } else {
    console.log(data.message);
  }
}

user();

function modalRestriccion(id, esVerificado) {
  if (esVerificado === true) {
    Modal.warning({
      title: "Si edita su perfil, debera verificar su cuenta nuevamente",
      confirm: true,
      onAccept: () => {
        window.location.replace(`${URL_PATH}/usuario/edit/?id=${id}`);
      },
    });
  } else {
    window.location.replace(`${URL_PATH}/usuario/edit/?id=${id}`);
  }
}
