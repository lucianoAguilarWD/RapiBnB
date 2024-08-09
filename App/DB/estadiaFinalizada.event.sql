DELIMITER $$
CREATE EVENT ActualizarEstadoReservas
ON SCHEDULE EVERY 1 DAY STARTS CURRENT_TIMESTAMP
DO
BEGIN
  UPDATE reserva, aplicacion_a_oferta_alquiler
  SET reserva.estado = 'finalizada'
  WHERE reserva.ofertaAlquilerID = aplicacion_a_oferta_alquiler.ofertaAlquilerID
    AND aplicacion_a_oferta_alquiler.fechaFin <= CURDATE()
    AND reserva.estado = 'en curso'
    AND aplicacion_a_oferta_alquiler.estado = 'Aceptado';
END;
$$
DELIMITER ;
