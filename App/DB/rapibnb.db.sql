CREATE TABLE usuarios(
    usuarioID INT AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(255),
    nombreUsuario VARCHAR(255),
    contrasena VARCHAR(255),
    nombreCompleto VARCHAR(255),
    fotoRostro VARCHAR(255),
    telefono VARCHAR(255),
    bio VARCHAR(255),
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE administrador(
    admiID INT AUTO_INCREMENT PRIMARY KEY,
    nombreUsuario VARCHAR(255),
    contrasena VARCHAR(255),
    nombreCompleto VARCHAR(255)
);

CREATE TABLE oferta_de_alquiler(
    ofertaID INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    descripcion VARCHAR(255),
    ubicacion VARCHAR(255),
    etiquetas VARCHAR(255),
    galeriaFotos VARCHAR(255),
    listServicios VARCHAR(255),
    costoAlquilerPorDia DECIMAL(10,2),
    tiempoMinPermanencia INT,
    tiempoMaxPermanencia INT,
    cupo INT,
    fechaInicio DATE,
    fechaFin DATE,
    creadorID INT,
    estado VARCHAR(255),
    userVerificado VARCHAR(255),
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creadorID) REFERENCES usuarios (usuarioID)
);

CREATE TABLE reserva(
    reservaID INT AUTO_INCREMENT PRIMARY KEY,
    textoReserva VARCHAR(255),
    puntaje INT,
    respuesta VARCHAR(255),
    estado ENUM('en curso', 'finalizada'),
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ofertaAlquilerID INT,
    autorID INT,
    FOREIGN KEY (ofertaAlquilerID) REFERENCES oferta_de_alquiler (ofertaID),
    FOREIGN KEY (autorID) REFERENCES usuarios (usuarioID)
);

CREATE TABLE certificacion(
    certificacionID INT AUTO_INCREMENT PRIMARY KEY,
    documentoAdjunto VARCHAR(255),
    usarioAVerfID INT,
    fechaDeVencimiento DATE,
    FOREIGN KEY (usarioAVerfID) REFERENCES usuarios (usuarioID)
);

CREATE TABLE aplicacion_a_oferta_alquiler(
    aplicacionID INT AUTO_INCREMENT PRIMARY KEY,
    fechaAplico TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fechaInicio DATE,
    fechaFin DATE,
    estado ENUM('Aceptado', 'Rechazado','Espera'),
    usuarioAplicoID INT,
    ofertaAlquilerID INT,
    FOREIGN KEY (ofertaAlquilerID) REFERENCES oferta_de_alquiler (ofertaID),
    FOREIGN KEY (usuarioAplicoID) REFERENCES usuarios (usuarioID)
);

CREATE TABLE interes(
    interesID INT AUTO_INCREMENT PRIMARY KEY,
    ubicacion VARCHAR(255),
    etiquetas VARCHAR(255),
    listServicios VARCHAR(255),
    userInteresesID INT,
    FOREIGN KEY (userInteresesID) REFERENCES usuarios (usuarioID)
);


CREATE TABLE verificacion_cuenta(
    verificacionID INT AUTO_INCREMENT PRIMARY KEY,
    fechaVencimiento DATE,
    usuarioPropuestaID INT,
    FOREIGN KEY (usuarioPropuestaID) REFERENCES usuarios (usuarioID)
);

ALTER TABLE usuarios ADD COLUMN documentacionID INT;

ALTER TABLE usuarios
ADD CONSTRAINT fk_usuarios_certificacion
FOREIGN KEY (documentacionID)
REFERENCES certificacion(certificacionID);