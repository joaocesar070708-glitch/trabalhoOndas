<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/Review.php';

class ReviewRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorId(int $id): ?Review {
        $stmt = $this->pdo->prepare('SELECT * FROM review WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch();
        return $dados ? new Review($dados) : null;
    }

    public function buscarPorUsuarioEMusica(int $usuarioId, int $musicaId): ?Review {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM review WHERE usuario_id = :usuario_id AND musica_id = :musica_id LIMIT 1'
        );
        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':musica_id'  => $musicaId,
        ]);
        $dados = $stmt->fetch();
        return $dados ? new Review($dados) : null;
    }

    /** @return Review[] */
    public function listarPorMusica(int $musicaId): array {
        $stmt = $this->pdo->prepare(
            'SELECT review.*, usuario.nome AS usuario_nome FROM review
             JOIN usuario ON review.usuario_id = usuario.id
             WHERE review.musica_id = :musica_id
             ORDER BY review.criado_em DESC'
        );
        $stmt->execute([':musica_id' => $musicaId]);
        $lista = [];
        while ($dados = $stmt->fetch()) {
            $lista[] = new Review($dados);
        }
        return $lista;
    }

    /** @return Review[] */
    public function listarPorUsuario(int $usuarioId): array {
        $stmt = $this->pdo->prepare(
            'SELECT review.*, musica.titulo AS musica_titulo FROM review
             JOIN musica ON review.musica_id = musica.id
             WHERE review.usuario_id = :usuario_id
             ORDER BY review.criado_em DESC'
        );
        $stmt->execute([':usuario_id' => $usuarioId]);
        $lista = [];
        while ($dados = $stmt->fetch()) {
            $lista[] = new Review($dados);
        }
        return $lista;
    }

    public function mediaPorMusica(int $musicaId): float {
        $stmt = $this->pdo->prepare(
            'SELECT AVG(nota) AS media FROM review WHERE musica_id = :musica_id'
        );
        $stmt->execute([':musica_id' => $musicaId]);
        $dados = $stmt->fetch();
        return round((float) ($dados['media'] ?? 0), 1);
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

    public function atualizar(int $id, int $nota, string $descricao): bool {
        $review = $this->buscarPorId($id);

        if ($review === null) {
            throw new RuntimeException('Review não encontrada.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE review SET nota = :nota, descricao = :descricao WHERE id = :id'
        );
        return $stmt->execute([
            ':nota'      => $nota,
            ':descricao' => $descricao,
            ':id'        => $id,
        ]);
    }

    public function excluir(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM review WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}