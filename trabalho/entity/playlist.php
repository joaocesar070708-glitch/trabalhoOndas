<?php

class Playlist {

    private int    $id;
    private int    $usuarioId;
    private string $nome;
    private string $criadoEm;

    public function __construct(array $dados) {
        $this->id        = (int) ($dados['playlist_id'] ?? 0);
        $this->usuarioId = (int) ($dados['usuario_id']  ?? 0);
        $this->nome       =        $dados['nome']         ?? '';
        $this->criadoEm   =        $dados['criado_em']    ?? '';
    }

    public static function novo(int $usuarioId, string $nome): self {
        $playlist = new self([
            'usuario_id' => $usuarioId,
        ]);
        $playlist->definirNome($nome);
        return $playlist;
    }

    public function registrarIdGerado(int $id): void {
        $this->id = $id;
    }

    public function definirNome(string $nome): void {
        $nome = trim($nome);
        if ($nome === '') {
            throw new InvalidArgumentException('O nome da playlist não pode ser vazio.');
        }
        $this->nome = $nome;
    }

    public function pertenceAoUsuario(int $usuarioId): bool {
        return $this->usuarioId === $usuarioId;
    }

    public function getId():        int    { return $this->id; }
    public function getUsuarioId(): int    { return $this->usuarioId; }
    public function getNome():      string { return $this->nome; }
    public function getCriadoEm():  string { return $this->criadoEm; }
}