DELIMITER $$
CREATE EVENT cambiar_estado_rentas
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
ON COMPLETION NOT PRESERVE
ENABLE
DO
BEGIN
    UPDATE aplicacion_a_oferta_alquiler
    SET estado = 'Rechazado'
    WHERE estado = 'Espera' AND DATEDIFF(CURDATE(), fechaRegistro) >= 3;
END;
$$
DELIMITER ;