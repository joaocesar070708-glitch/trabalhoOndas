<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/Usuario.php';

class UsuarioRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorEmail(string $email): ?Usuario {
   
        $stmt = $this->pdo->prepare('SELECT * FROM usuario WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $dados = $stmt->fetch();
        return $dados ? new Usuario($dados) : null;
    }

    public function buscarPorId(int $id): ?Usuario {
       
        $stmt = $this->pdo->prepare('SELECT * FROM usuario WHERE usuario_id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch();
        return $dados ? new Usuario($dados) : null;
    }


    public function listarTodos(): array {
        $stmt = $this->pdo->query('SELECT * FROM usuario ORDER BY nome ASC');
        $usuarios = [];
        while ($dados = $stmt->fetch()) {
            $usuarios[] = new Usuario($dados);
        }
        return $usuarios;
    }

    public function salvar(Usuario $usuario): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)'
        );
        $resultado = $stmt->execute([
            ':nome'  => $usuario->getNome(),
            ':email' => $usuario->getEmail(),
            ':senha' => hash('sha256', $usuario->getSenha()),
        ]);

        if ($resultado) {
            $usuario->registrarIdGerado((int) $this->pdo->lastInsertId());
        }

        return $resultado;
    }

    public function atualizar(int $id, string $nome, string $email): bool {
        $usuario = $this->buscarPorId($id);

        if ($usuario === null) {
            throw new RuntimeException('Usuário não encontrado.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE usuario SET nome = :nome, email = :email WHERE usuario_id = :id'
        );
        return $stmt->execute([
            ':nome'  => $nome,
            ':email' => $email,
            ':id'    => $id,
        ]);
    }

    public function atualizarFoto(int $id, string $nomeArquivo): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE usuario SET foto_perfil = :foto WHERE usuario_id = :id'
        );
        return $stmt->execute([
            ':foto' => $nomeArquivo,
            ':id'   => $id,
        ]);
    }

    public function excluir(int $id): bool {

        $stmt = $this->pdo->prepare('DELETE FROM usuario WHERE usuario_id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function atualizarSenha(int $id, string $senhaNova): bool
{
    $stmt = $this->pdo->prepare(
        'UPDATE usuario SET senha = :senha WHERE usuario_id = :id'
    );
    return $stmt->execute([
        ':senha' => hash('sha256', $senhaNova),
        ':id'    => $id,
    ]);
}
}