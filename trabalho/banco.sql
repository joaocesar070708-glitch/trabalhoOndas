DROP DATABASE IF EXISTS bd_ondas;

CREATE DATABASE bd_ondas
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE bd_ondas;

CREATE TABLE usuario (
    usuario_id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(300) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE review (
    id_review INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    titulo_musica VARCHAR(150) NOT NULL,
    nome_artista VARCHAR(150),
    nome_album VARCHAR(150),
    nota INT NOT NULL CHECK (nota BETWEEN 1 AND 5),
    comentario TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_id_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuario(usuario_id)
        ON DELETE SET NULL
);

INSERT INTO usuario (nome, email, senha) VALUES (
    'Ash Ketchum',
    'admin@email.com',
    '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92'
);