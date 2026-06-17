<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/Artista.php';

class ArtistaRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorId(int $id): ?Artista {
        $stmt = $this->pdo->prepare('SELECT * FROM artista WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch();
        return $dados ? new Artista($dados) : null;
    }

    public function buscarPorUsuarioId(int $usuarioId): ?Artista {
        $stmt = $this->pdo->prepare('SELECT * FROM artista WHERE usuario_id = :usuario_id LIMIT 1');
        $stmt->execute([':usuario_id' => $usuarioId]);
        $dados = $stmt->fetch();
        return $dados ? new Artista($dados) : null;
    }

    /** @return Artista[] */
    public function listarTodos(): array {
        $stmt = $this->pdo->query(
            'SELECT artista.*, usuario.nome FROM artista
             JOIN usuario ON artista.usuario_id = usuario.id
             ORDER BY usuario.nome ASC'
        );
        $lista = [];
        while ($dados = $stmt->fetch()) {
            $lista[] = new Artista($dados);
        }
        return $lista;
    }

    public function salvar(Artista $artista): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO artista (usuario_id, pais, bio) VALUES (:usuario_id, :pais, :bio)'
        );
        $resultado = $stmt->execute([
            ':usuario_id' => $artista->getUsuarioId(),
            ':pais'       => $artista->getPais(),
            ':bio'        => $artista->getBio(),
        ]);

        if ($resultado) {
            $artista->registrarIdGerado((int) $this->pdo->lastInsertId());
        }

        return $resultado;
    }

    public function atualizar(int $id, string $pais, string $bio): bool {
        $artista = $this->buscarPorId($id);

        if ($artista === null) {
            throw new RuntimeException('Artista não encontrado.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE artista SET pais = :pais, bio = :bio WHERE id = :id'
        );
        return $stmt->execute([
            ':pais' => $pais,
            ':bio'  => $bio,
            ':id'   => $id,
        ]);
    }

    public function excluir(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM artista WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}