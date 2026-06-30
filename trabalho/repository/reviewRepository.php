<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/reviews.php';

class ReviewRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorId(int $id): ?Review {
        $stmt = $this->pdo->prepare('SELECT * FROM review WHERE id_review = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch();
        return $dados ? new Review($dados) : null;
    }

    /** @return Review[] */
    public function listarPorUsuario(int $usuarioId): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM review
             WHERE usuario_id = :usuario_id
             ORDER BY criado_em DESC'
        );
        $stmt->execute([':usuario_id' => $usuarioId]);
        $lista = [];
        while ($dados = $stmt->fetch()) {
            $lista[] = new Review($dados);
        }
        return $lista;
    }

    public function salvar(Review $review): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO review (usuario_id, titulo_musica, nome_artista, nome_album, nota, comentario, link_youtube)
             VALUES (:usuario_id, :titulo_musica, :nome_artista, :nome_album, :nota, :comentario, :link_youtube)'
        );
        $resultado = $stmt->execute([
            ':usuario_id'    => $review->getUsuarioId(),
            ':titulo_musica' => $review->getMusicaTitulo(),
            ':nome_artista'  => $review->getArtistaNome(),
            ':nome_album'    => $review->getAlbumNome(),
            ':nota'          => $review->getNota(),
            ':comentario'    => $review->getDescricao(),
            ':link_youtube'  => $review->getLinkYoutube(),
        ]);

        if ($resultado) {
            $review->registrarIdGerado((int) $this->pdo->lastInsertId());
        }

        return $resultado;
    }

    public function excluir(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM review WHERE id_review = :id');
        return $stmt->execute([':id' => $id]);
    }

<<<<<<< HEAD
    public function atualizar(string $titulo_novo, int $id, int $nota, string $descricao, ?string $linkYoutube): void
    {
        $stmt = $this->pdo->prepare('
            UPDATE review
            SET nota = ?, comentario = ?, titulo_musica = ?, link_youtube = ?
            WHERE id_review = ?
        ');
        $stmt->execute([$nota, $descricao, $titulo_novo, $linkYoutube, $id]);
    }
=======
/**Atualiza tudo de uma review existente.*/

public function atualizar(string $titulo_novo, int $id, int $nota, string $descricao, string $album, string $artista): void
{
    $stmt = $this->pdo->prepare('
        UPDATE review
        SET nota = ?, comentario = ?, titulo_musica = ?, nome_album = ?, nome_artista = ?
        WHERE id_review = ?
    ');
    $stmt->execute([$nota, $descricao, $titulo_novo, $album, $artista, $id]);
}
>>>>>>> ebe0fe75596b60cd1fa25b466996914f856709fa
}