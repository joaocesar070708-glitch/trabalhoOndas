<?php

class Playlist {

    private int    $id;
    private string $nome;
    private int    $usuarioId;
    private string $criadoEm;

    public function __construct(array $dados) {
        $this->id        = (int) ($dados['id']         ?? 0);
        $this->nome      =        $dados['nome']       ?? '';
        $this->usuarioId = (int) ($dados['usuario_id'] ?? 0);
        $this->criadoEm  =        $dados['criado_em']  ?? '';
    }

    public static function novo(string $nome, int $usuarioId): self {
        return new self([
            'nome'       => $nome,
            'usuario_id' => $usuarioId,
        ]);
    }

    public function alterarDados(string $nome): void {
        $this->nome = $nome;
    }

    public function registrarIdGerado(int $id): void {
        $this->id = $id;
    }

    public function getId():        int    { return $this->id; }
    public function getNome():      string { return $this->nome; }
    public function getUsuarioId(): int    { return $this->usuarioId; }
    public function getCriadoEm():  string { return $this->criadoEm; }
}