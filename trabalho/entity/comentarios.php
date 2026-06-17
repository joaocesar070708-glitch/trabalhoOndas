<?php

class Comentario {

    private int    $id;
    private string $conteudo;
    private int    $usuarioId;
    private int    $musicaId;
    private string $criadoEm;

    public function __construct(array $dados) {
        $this->id        = (int) ($dados['id']         ?? 0);
        $this->conteudo  =        $dados['conteudo']   ?? '';
        $this->usuarioId = (int) ($dados['usuario_id'] ?? 0);
        $this->musicaId  = (int) ($dados['musica_id']  ?? 0);
        $this->criadoEm  =        $dados['criado_em']  ?? '';
    }

    public static function novo(string $conteudo, int $usuarioId, int $musicaId): self {
        return new self([
            'conteudo'   => $conteudo,
            'usuario_id' => $usuarioId,
            'musica_id'  => $musicaId,
        ]);
    }

    public function alterarDados(string $conteudo): void {
        $this->conteudo = $conteudo;
    }

    public function registrarIdGerado(int $id): void {
        $this->id = $id;
    }

    public function getId():        int    { return $this->id; }
    public function getConteudo():  string { return $this->conteudo; }
    public function getUsuarioId(): int    { return $this->usuarioId; }
    public function getMusicaId():  int    { return $this->musicaId; }
    public function getCriadoEm():  string { return $this->criadoEm; }
}