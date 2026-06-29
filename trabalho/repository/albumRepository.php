<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/Album.php';

class AlbumRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorId(int $id): ?Album {
        $stmt = $this->pdo->prepare('SELECT * FROM album WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch();
        return $dados ? new Album($dados) : null;
    }

    /** @return Album[] */
    public function listarTodos(): array {
        $stmt = $this->pdo->query(
            'SELECT album.*, usuario.nome AS artista_nome FROM album
             JOIN artista ON album.artista_id = artista.id
             JOIN usuario ON artista.usuario_id = usuario.id
             ORDER BY album.titulo ASC'
        );
        $lista = [];
        while ($dados = $stmt->fetch()) {
            $lista[] = new Album($dados);
        }
        return $lista;
    }

     public function salvar(Review $review): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO review (nota, descricao, usuario_id, musica_id)
             VALUES (:nota, :descricao, :usuario_id, :musica_id)'
        );
        $resultado = $stmt->execute([
            ':nota'       => $review->getNota(),
            ':descricao'  => $review->getDescricao(),
            ':usuario_id' => $review->getUsuarioId(),
            ':musica_id'  => $review->getMusicaId(),
        ]);

        if ($resultado) {
            $review->registrarIdGerado((int) $this->pdo->lastInsertId());
        }

        return $resultado;
    }

    /** @return Album[] */
    public function listarPorArtista(int $artistaId): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM album WHERE artista_id = :artista_id ORDER BY ano DESC'
        );
        $stmt->execute([':artista_id' => $artistaId]);
        $lista = [];
        while ($dados = $stmt->fetch()) {
            $lista[] = new Album($dados);
        }
        return $lista;
    }

    public function salvar(Album $album): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO album (titulo, ano, artista_id) VALUES (:titulo, :ano, :artista_id)'
        );
        $resultado = $stmt->execute([
            ':titulo'     => $album->getTitulo(),
            ':ano'        => $album->getAno(),
            ':artista_id' => $album->getArtistaId(),
        ]);

        if ($resultado) {
            $album->registrarIdGerado((int) $this->pdo->lastInsertId());
        }

        return $resultado;
    }

    public function atualizar(int $id, string $titulo, int $ano): bool {
        $album = $this->buscarPorId($id);

        if ($album === null) {
            throw new RuntimeException('Album não encontrado.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE album SET titulo = :titulo, ano = :ano WHERE id = :id'
        );
        return $stmt->execute([
            ':titulo' => $titulo,
            ':ano'    => $ano,
            ':id'     => $id,
        ]);
    }

    public function excluir(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM album WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}