DELIMITER $$
CREATE EVENT cambiar_estado_ofertas
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
ON COMPLETION NOT PRESERVE
ENABLE
DO
BEGIN
    UPDATE oferta_de_alquiler
    SET estado = 'publicado'
    WHERE estado = 'espera' AND DATEDIFF(CURDATE(), fechaRegistro) >= 3;
END;
$$
DELIMITER ;