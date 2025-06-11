
/* 
 * Script: delete_db.sql
 * Propósito: Eliminar completamente la base de datos y el usuario asociado.
 * Uso: Este script debe ejecutarse con privilegios de root en MySQL.
 */

REVOKE ALL PRIVILEGES, GRANT OPTION FROM 'students_user_3'@'localhost';


DROP USER IF EXISTS 'students_user_3'@'localhost';


DROP DATABASE IF EXISTS students_db_3;
