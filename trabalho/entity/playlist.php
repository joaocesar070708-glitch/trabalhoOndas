<?php

class Playlist {

    private int    $id;
    private int    $usuarioId;
    private string $nome;
    private string $criadoEm;

    public function __construct(array $dados) {
        $this->id = (int) ($dados['playlist_id'] ?? 0);
        $this->usuarioId = (int) ($dados['usuario_id']  ?? 0);
        $this->nome = $dados['nome']         ?? '';
        $this->criadoEm  = $dados['criado_em']    ?? '';
    }

    public function getId():        int    { return $this->id; }
    public function getUsuarioId(): int    { return $this->usuarioId; }
    public function getNome():      string { return $this->nome; }
    public function getCriadoEm():  string { return $this->criadoEm; }
}