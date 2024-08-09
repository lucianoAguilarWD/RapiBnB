DELIMITER $$
CREATE EVENT eliminar_verificaciones_vencidas
ON SCHEDULE
    EVERY 1 HOUR 
DO
BEGIN
    DELETE FROM verificacion_cuenta
    WHERE fechaVencimiento <= CURDATE();
END;
$$
DELIMITER ;