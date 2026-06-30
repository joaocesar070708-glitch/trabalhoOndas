CREATE TABLE IF NOT EXISTS usuario (
    usuario_id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(300) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    foto_perfil VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS review (
    id_review INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    titulo_musica VARCHAR(150) NOT NULL,
    nome_artista VARCHAR(150) NOT NULL,
    nome_album VARCHAR(150),
    nota INT NOT NULL CHECK (nota BETWEEN 1 AND 5),
    comentario TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    link_youtube VARCHAR(255) DEFAULT NULL,
    CONSTRAINT fk_id_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuario(usuario_id)
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS playlist (
    playlist_id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_playlist_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuario(usuario_id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS playlist_musica (
    playlist_id INT NOT NULL,
    id_review   INT NOT NULL,
    PRIMARY KEY (playlist_id, id_review),
    CONSTRAINT fk_pm_playlist
        FOREIGN KEY (playlist_id) REFERENCES playlist(playlist_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_pm_review
        FOREIGN KEY (id_review) REFERENCES review(id_review)
        ON DELETE CASCADE
);

INSERT INTO usuario (nome, email, senha) VALUES (
    'Joaonuele',
    'admin@email.com',
    '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92'
);