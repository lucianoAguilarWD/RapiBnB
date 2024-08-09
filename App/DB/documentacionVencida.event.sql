DELIMITER $$

CREATE EVENT eliminar_certificaciones_vencidas
ON SCHEDULE EVERY 1 HOUR
STARTS '2023-10-29 19:46:42'
ON COMPLETION NOT PRESERVE
ENABLE
DO
BEGIN
    UPDATE usuarios
    SET documentacionID = NULL
    WHERE documentacionID IN (SELECT certificacionID FROM certificacion WHERE fechaDeVencimiento <= CURDATE());

    DELETE FROM certificacion
    WHERE fechaDeVencimiento <= CURDATE();
END;

$$
DELIMITER ;