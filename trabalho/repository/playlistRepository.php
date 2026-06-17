<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/Musica.php';

class MusicaRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorId(int $id): ?Musica {
        $stmt = $this->pdo->prepare('SELECT * FROM musica WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch();
        return $dados ? new Musica($dados) : null;
    }

    /** @return Musica[] */
    public function listarTodos(): array {
        $stmt = $this->pdo->query(
            'SELECT musica.*, usuario.nome AS artista_nome, album.titulo AS album_titulo
             FROM musica
             LEFT JOIN artista ON musica.artista_id = artista.id
             LEFT JOIN usuario ON artista.usuario_id = usuario.id
             LEFT JOIN album ON musica.album_id = album.id
             ORDER BY musica.titulo ASC'
        );
        $lista = [];
        while ($dados = $stmt->fetch()) {
            $lista[] = new Musica($dados);
        }
        return $lista;
    }

    /** @return Musica[] */
    public function listarPorArtista(int $artistaId): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM musica WHERE artista_id = :artista_id ORDER BY titulo ASC'
        );
        $stmt->execute([':artista_id' => $artistaId]);
        $lista = [];
        while ($dados = $stmt->fetch()) {
            $lista[] = new Musica($dados);
        }
        return $lista;
    }

    /** @return Musica[] */
    public function listarPorAlbum(int $albumId): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM musica WHERE album_id = :album_id ORDER BY titulo ASC'
        );
        $stmt->execute([':album_id' => $albumId]);
        $lista = [];
        while ($dados = $stmt->fetch()) {
            $lista[] = new Musica($dados);
        }
        return $lista;
    }

    public function salvar(Musica $musica): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO musica (titulo, duracao, artista_id, album_id)
             VALUES (:titulo, :duracao, :artista_id, :album_id)'
        );
        $resultado = $stmt->execute([
            ':titulo'     => $musica->getTitulo(),
            ':duracao'    => $musica->getDuracao(),
            ':artista_id' => $musica->getArtistaId(),
            ':album_id'   => $musica->getAlbumId(),
        ]);

        if ($resultado) {
            $musica->registrarIdGerado((int) $this->pdo->lastInsertId());
        }

        return $resultado;
    }

    public function atualizar(int $id, string $titulo, string $duracao, ?int $albumId): bool {
        $musica = $this->buscarPorId($id);

        if ($musica === null) {
            throw new RuntimeException('Música não encontrada.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE musica SET titulo = :titulo, duracao = :duracao, album_id = :album_id WHERE id = :id'
        );
        return $stmt->execute([
            ':titulo'   => $titulo,
            ':duracao'  => $duracao,
            ':album_id' => $albumId,
            ':id'       => $id,
        ]);
    }

    public function excluir(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM musica WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}