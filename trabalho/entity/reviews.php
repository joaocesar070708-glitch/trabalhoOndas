<?php

class Review {

    private int    $id;
    private int    $nota;
    private string $descricao;
    private int    $usuarioId;
    private int    $musicaId;
    private string $criadoEm;

    public function __construct(array $dados) {
        $this->id        = (int) ($dados['id']         ?? 0);
        $this->nota      = (int) ($dados['nota']        ?? 0);
        $this->descricao =        $dados['descricao']  ?? '';
        $this->usuarioId = (int) ($dados['usuario_id'] ?? 0);
        $this->musicaId  = (int) ($dados['musica_id']  ?? 0);
        $this->criadoEm  =        $dados['criado_em']  ?? '';
    }

    public static function novo(int $nota, string $descricao, int $usuarioId, int $musicaId): self {
        return new self([
            'nota'       => $nota,
            'descricao'  => $descricao,
            'usuario_id' => $usuarioId,
            'musica_id'  => $musicaId,
        ]);
    }

    public function alterarDados(int $nota, string $descricao): void {
        $this->nota      = $nota;
        $this->descricao = $descricao;
    }

    public function registrarIdGerado(int $id): void {
        $this->id = $id;
    }

    public function getMusicaTitulo(): string {
    return $this->dados['titulo_musica'] ?? '';
}

public function getArtistaNome(): string {
    return $this->dados['nome_artista'] ?? '';
}

    public function getId():        int    { return $this->id; }
    public function getNota():      int    { return $this->nota; }
    public function getDescricao(): string { return $this->descricao; }
    public function getUsuarioId(): int    { return $this->usuarioId; }
    public function getMusicaId():  int    { return $this->musicaId; }
    public function getCriadoEm():  string { return $this->criadoEm; }
}