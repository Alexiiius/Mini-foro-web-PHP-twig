create database ProyectoServidor character set utf8mb4 collate utf8mb4_unicode_ci;

use ProyectoServidor;

CREATE TABLE Usuarios (
    idUsuario INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(30) UNIQUE NOT NULL,
    DNI VARCHAR(9) UNIQUE,
	nombre VARCHAR(20) NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    apellido VARCHAR(20),
    telefono VARCHAR(9)
);

CREATE TABLE Articulos (
    idArticulo INT PRIMARY KEY AUTO_INCREMENT,
    id_autor INT,
    FOREIGN KEY (id_autor) REFERENCES Usuarios(idUsuario),
    titulo VARCHAR(255),
    contenido VARCHAR(255),
    fecha_publicacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Ratings (
                idRatings INT AUTO_INCREMENT PRIMARY KEY,
                id_usuario INT NOT NULL,
                id_articulo INT NOT NULL,
                puntuaje DECIMAL(3,1) NOT NULL,
                FOREIGN KEY (id_usuario) REFERENCES Usuarios(idUsuario),
                FOREIGN KEY (id_articulo) REFERENCES Articulos(idArticulo),
                UNIQUE KEY (id_usuario, id_articulo)
);

#Insertar usuarios de prueba
INSERT INTO Usuarios (email, nombre, contraseña)
VALUES ('alexius1996@gmail.com', 'Alex', '$argon2id$v=19$m=65536,t=4,p=1$L1hVUDI4WENUbGkvZDZ1Qw$EoMvInukuO2MkKUNMbFV3nL4vd91zUUl7BR7weE59gg'),
('prueba@gmail.com', 'prueba', '$argon2id$v=19$m=65536,t=4,p=1$eWxMUWkzUDZqTjJ1dUp5cQ$pKvjV0A0pbI3PpnG6ZBK9WXEwUX5GwY7k5s0jqTF/9c'),
('profesor@gmail.com', 'profesor', '$argon2id$v=19$m=65536,t=4,p=1$V3p2dS8vbDlPMjlRdk9kMg$FNF+nNPR3vHcbhjA/L52vbnrLLEnDPGnge6blIjzqS8');

#SELECT * FROM Usuarios WHERE email = 'usuario@example.com';

#INSERT INTO Articulos (id_autor, titulo, contenido) VALUES (2, 'Título del Artículo', 'Contenido del Artículo');
#INSERT INTO Ratings (id_usuario, id_articulo, puntuaje) VALUES (1, 2, 8.5);


#select * from Usuarios;
#select * from Articulos order by fecha_publicacion desc;
#select* from Ratings;


