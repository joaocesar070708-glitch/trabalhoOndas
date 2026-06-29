<?php

class Usuario {

    private int    $id;
    private string $nome;
    private string $email;
    private string $senha;
    private string $criadoEm;

    public function __construct(array $dados) {
        $this->id = (int) ($dados['usuario_id'] ?? $dados['id'] ?? 0);
        $this->nome     =        $dados['nome']      ?? '';
        $this->email    =        $dados['email']     ?? '';
        $this->senha    =        $dados['senha']     ?? '';
        $this->criadoEm =        $dados['criado_em'] ?? '';
    }

    public static function novo(string $nome, string $email, string $senha): self {
        return new self([
            'nome'  => $nome,
            'email' => $email,
            'senha' => $senha,
        ]);
    }

    public function alterarDados(string $nome, string $email): void {
        $this->nome  = $nome;
        $this->email = $email;
    }

    public function registrarIdGerado(int $id): void {
        $this->id = $id;
    }

    public function getId():       int    { return $this->id; }
    public function getNome():     string { return $this->nome; }
    public function getEmail():    string { return $this->email; }
    public function getSenha():    string { return $this->senha; }
    public function getCriadoEm(): string { return $this->criadoEm; }
}