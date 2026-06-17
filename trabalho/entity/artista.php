<?php

class Artista {

    private int    $id;
    private int    $usuarioId;
    private string $pais;
    private string $bio;
    private string $criadoEm;

    public function __construct(array $dados) {
        $this->id        = (int) ($dados['id']         ?? 0);
        $this->usuarioId = (int) ($dados['usuario_id'] ?? 0);
        $this->pais      =        $dados['pais']       ?? '';
        $this->bio       =        $dados['bio']        ?? '';
        $this->criadoEm  =        $dados['criado_em']  ?? '';
    }

    public static function novo(int $usuarioId, string $pais, string $bio): self {
        return new self([
            'usuario_id' => $usuarioId,
            'pais'       => $pais,
            'bio'        => $bio,
        ]);
    }

    public function alterarDados(string $pais, string $bio): void {
        $this->pais = $pais;
        $this->bio  = $bio;
    }

    public function registrarIdGerado(int $id): void {
        $this->id = $id;
    }

    public function getId():        int    { return $this->id; }
    public function getUsuarioId(): int    { return $this->usuarioId; }
    public function getPais():      string { return $this->pais; }
    public function getBio():       string { return $this->bio; }
    public function getCriadoEm():  string { return $this->criadoEm; }
}