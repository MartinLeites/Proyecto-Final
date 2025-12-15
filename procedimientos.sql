
/*----------------------------------------------------------
  altaAdmin 
----------------------------------------------------------*/


DROP PROCEDURE IF EXISTS dbSS.esc_altaAdmin;
DELIMITER //
CREATE PROCEDURE dbSS.esc_altaAdmin(
    IN p_Nombre   VARCHAR(255),
    IN p_Mail     VARCHAR(255),
    IN p_Password VARCHAR(255)
)
BEGIN
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        ROLLBACK;
         
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Error al insertar el administrador en dbSS.esc_altaAdmin';
    END;

    START TRANSACTION;

    INSERT INTO dbSS.personas (`Estado`,`Nombre`,`Mail`,`password`)
    VALUES ('Activo', p_Nombre, p_Mail, p_Password);

    INSERT INTO dbSS.administradors (`IDPersona`)
    VALUES (LAST_INSERT_ID());

    COMMIT;

END//
DELIMITER ;

/*----------------------------------------------------------
  altaUsuario 
----------------------------------------------------------*/
DROP PROCEDURE IF EXISTS esc_altaUsuario;
DELIMITER //
CREATE PROCEDURE dbSS.esc_altaUsuario(
    IN p_Nombre   VARCHAR(255),
    IN p_Mail     VARCHAR(255),
    IN p_Password VARCHAR(255)
)
BEGIN
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        ROLLBACK;

		SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Error al insertar el usuario';
    END;

    START TRANSACTION;

    INSERT INTO dbSS.personas (`Estado`,`Nombre`,`Mail`,`password`)
    VALUES ('Activo', p_Nombre, p_Mail, p_Password);

    INSERT INTO dbSS.usuarios (`IDPersona`)
    VALUES (LAST_INSERT_ID());

    COMMIT;

END//
DELIMITER ;
/*----------------------------------------------------------
  activarUsuario 
----------------------------------------------------------*/
DROP PROCEDURE IF EXISTS esc_activarUsuario;
DELIMITER //

CREATE PROCEDURE esc_activarUsuario(
    IN p_Mail VARCHAR(255)
)
BEGIN
    DECLARE huboCambio INT DEFAULT 0;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Ocurrió un error, transacción revertida' AS message;
    END;

    START TRANSACTION;

    UPDATE personas
    SET Estado = 'Activo'
    WHERE TRIM(Mail) = TRIM(p_Mail)
      AND Estado <> 'Activo';

    SET huboCambio = ROW_COUNT();

    IF huboCambio > 0 THEN
        COMMIT;
        SELECT 'Usuario activado exitosamente' AS message;
    ELSE
        ROLLBACK;
        SELECT 'Error: No se encontró ningún usuario con ese email o ya se encuentra activo' AS message;
    END IF;
END//

DELIMITER ;
/*----------------------------------------------------------
  desactivarUsuario
----------------------------------------------------------*/

DROP PROCEDURE IF EXISTS esc_desactivarUsuario;
DELIMITER //

CREATE PROCEDURE esc_desactivarUsuario(
    IN p_Mail VARCHAR(255)
)
BEGIN
    DECLARE huboCambio INT DEFAULT 0;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Ocurrió un error, transacción revertida' AS message;
    END;

    START TRANSACTION;

    UPDATE personas
    SET Estado = 'Inactivo'
    WHERE TRIM(Mail) = TRIM(p_Mail)
      AND Estado <> 'Inactivo';

    SET huboCambio = ROW_COUNT();

    IF huboCambio > 0 THEN
        COMMIT;
        SELECT 'Usuario desactivado exitosamente' AS message;
    ELSE
        ROLLBACK;
        SELECT 'Error: No se encontró ningún usuario con ese email o ya se encuentra desactivado' AS message;
    END IF;
END//

DELIMITER ;
