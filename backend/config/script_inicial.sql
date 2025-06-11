
CREATE DATABASE IF NOT EXISTS students_db_3
CHARACTER SET utf8 
COLLATE utf8_unicode_ci;


CREATE USER 'students_user_3'@'localhost' IDENTIFIED BY '12345';


GRANT ALL PRIVILEGES ON students_db_3.* TO 'students_user_3'@'localhost';


FLUSH PRIVILEGES;​
/******************************************************************************/

USE students_db_3;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    age INT NOT NULL
) ENGINE=INNODB;


INSERT INTO students (fullname, email, age) VALUES
('Ana García', 'ana@example.com', 21),
('Lucas Torres', 'lucas@example.com', 24),
('Marina Díaz', 'marina@example.com', 22);


CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=INNODB;


INSERT INTO subjects (name) VALUES 
('Tecnologías A'), 
('Tecnologías B'), 
('Algoritmos y Estructura de Datos I'), 
('Fundamentos de Informática');


CREATE TABLE students_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    approved BOOLEAN DEFAULT FALSE,
    UNIQUE (student_id, subject_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
) ENGINE=INNODB;


INSERT INTO students_subjects (student_id, subject_id, approved) VALUES
(1, 1, 1),
(2, 2, 0);

