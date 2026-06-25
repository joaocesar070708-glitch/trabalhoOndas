-- BANCO DE DADOS ONDAS

CREATE DATABASE IF NOT EXISTS bd_ondas
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE bd_ondas;


-- usuario

CREATE TABLE IF NOT EXISTS usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(300) NOT NULL,
    criado_em  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- artista (pega usuario 1:1)

CREATE TABLE IF NOT EXISTS artista (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL UNIQUE,
    bio TEXT,
    criado_em  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_artista_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuario(id)
        ON DELETE CASCADE
);


-- album (1:n com musica)

CREATE TABLE IF NOT EXISTS album (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(150) NOT NULL,
    artista_id INT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_album_artista
        FOREIGN KEY (artista_id) REFERENCES artista(id)
        ON DELETE SET NULL
);


-- musica

CREATE TABLE IF NOT EXISTS musica (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(150) NOT NULL,
    duracao TIME,
    artista_id INT,
    album_id INT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_musica_artista
        FOREIGN KEY (artista_id) REFERENCES artista(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_musica_album
        FOREIGN KEY (album_id) REFERENCES album(id)
        ON DELETE SET NULL
);

-- playlist

CREATE TABLE IF NOT EXISTS playlist (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    usuario_id INT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_playlist_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuario(id)
        ON DELETE CASCADE
);


-- playlist_musica (n:n relacao playlist e musica)

CREATE TABLE IF NOT EXISTS playlist_musica (
    playlist_id INT NOT NULL,
    musica_id INT NOT NULL,
    adicionado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (playlist_id, musica_id),
    CONSTRAINT fk_pm_playlist
        FOREIGN KEY (playlist_id) REFERENCES playlist(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_pm_musica
        FOREIGN KEY (musica_id) REFERENCES musica(id)
        ON DELETE CASCADE
);


-- comentario (usuario comenta em musica)

CREATE TABLE IF NOT EXISTS comentario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    descricao TEXT NOT NULL,
    usuario_id INT NOT NULL,
    musica_id INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comentario_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuario(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_comentario_musica
        FOREIGN KEY (musica_id) REFERENCES musica(id)
        ON DELETE CASCADE
);


-- review (usuario avalia musica com nota)

CREATE TABLE IF NOT EXISTS review (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nota INT NOT NULL CHECK (nota BETWEEN 1 AND 5),
    descricao TEXT,
    usuario_id INT NOT NULL,
    musica_id INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_review (usuario_id, musica_id),
    CONSTRAINT fk_review_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuario(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_review_musica
        FOREIGN KEY (musica_id) REFERENCES musica(id)
        ON DELETE CASCADE
);



-- ------------------------------------------------------------
--  Usuário de teste
--  Email: admin@email.com
--  Senha: 123456  (SHA256 = 8d969eef6ecad3c29a3a629280e686cf...)
-- ------------------------------------------------------------
INSERT INTO usuario (nome, email, senha) VALUES
(
    'Ash Ketchum',
    'admin@email.com',
    '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92'
);

