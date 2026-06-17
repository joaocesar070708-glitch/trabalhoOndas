<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/Comentario.php';

class ComentarioRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorId(int $id): ?Comentario {
        $stmt = $this->pdo->prepare('SELECT * FROM comentario WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch();
        return $dados ? new Comentario($dados) : null;
    }

    /** @return Comentario[] */
    public function listarPorMusica(int $musicaId): array {
        $stmt = $this->pdo->prepare(
            'SELECT comentario.*, usuario.nome AS usuario_nome FROM comentario
             JOIN usuario ON comentario.usuario_id = usuario.id
             WHERE comentario.musica_id = :musica_id
             ORDER BY comentario.criado_em DESC'
        );
        $stmt->execute([':musica_id' => $musicaId]);
        $lista = [];
        while ($dados = $stmt->fetch()) {
            $lista[] = new Comentario($dados);
        }
        return $lista;
    }

    /** @return Comentario[] */
    public function listarPorUsuario(int $usuarioId): array {
        $stmt = $this->pdo->prepare(
            'SELECT comentario.*, musica.titulo AS musica_titulo FROM comentario
             JOIN musica ON comentario.musica_id = musica.id
             WHERE comentario.usuario_id = :usuario_id
             ORDER BY comentario.criado_em DESC'
        );
        $stmt->execute([':usuario_id' => $usuarioId]);
        $lista = [];
        while ($dados = $stmt->fetch()) {
            $lista[] = new Comentario($dados);
        }
        return $lista;
    }

    public function salvar(Comentario $comentario): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO comentario (conteudo, usuario_id, musica_id)
             VALUES (:conteudo, :usuario_id, :musica_id)'
        );
        $resultado = $stmt->execute([
            ':conteudo'   => $comentario->getConteudo(),
            ':usuario_id' => $comentario->getUsuarioId(),
            ':musica_id'  => $comentario->getMusicaId(),
        ]);

        if ($resultado) {
            $comentario->registrarIdGerado((int) $this->pdo->lastInsertId());
        }

        return $resultado;
    }

    public function atualizar(int $id, string $conteudo): bool {
        $comentario = $this->buscarPorId($id);

        if ($comentario === null) {
            throw new RuntimeException('Comentário não encontrado.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE comentario SET conteudo = :conteudo WHERE id = :id'
        );
        return $stmt->execute([
            ':conteudo' => $conteudo,
            ':id'       => $id,
        ]);
    }

    public function excluir(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM comentario WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}