<?php

class Usuario {

    private int     $id;
    private string  $nome;
    private string  $email;
    private string  $senha;
    private string  $criadoEm;
    private ?string $fotoPerfil;

    public function __construct(array $dados) {
        $this->id = (int) ($dados['usuario_id'] ?? $dados['id'] ?? 0);
        $this->nome = $dados['nome'] ?? '';
        $this->email = $dados['email'] ?? '';
        $this->senha = $dados['senha'] ?? '';
        $this->criadoEm = $dados['criado_em'] ?? '';
        $this->fotoPerfil = $dados['foto_perfil'] ?? null;
    }

    public static function novo(string $nome, string $email, string $senha): self {
        $usuario = new self([]);
        $usuario->definirNome($nome);
        $usuario->definirEmail($email);
        $usuario->definirSenha($senha);
        return $usuario;
    }

    public function alterarDados(string $nome, string $email): void {
        $this->definirNome($nome);
        $this->definirEmail($email);
    }

    public function registrarIdGerado(int $id): void {
        $this->id = $id;
    }


    public function definirNome(string $nome): void {
        $nome = trim($nome);
        if ($nome === '') {
            throw new InvalidArgumentException('O nome não pode ser vazio.');
        }
        $this->nome = $nome;
    }


    public function definirEmail(string $email): void {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Informe um e-mail válido.');
        }
        $this->email = $email;
    }

    public function definirSenha(string $senha): void {
        if (strlen($senha) < 6) {
            throw new InvalidArgumentException('A senha deve ter pelo menos 6 caracteres.');
        }
        $this->senha = hash('sha256', $senha);
    }

//compara senha com hash
    public function senhaEstaCorreta(string $senha): bool {
        return hash('sha256', $senha) === $this->senha;
    }

    public function definirFotoPerfil(string $nomeArquivo): void {
        $this->fotoPerfil = $nomeArquivo;
    }

    public function getId(): int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getEmail(): string { return $this->email; }
    public function getSenha(): string { return $this->senha; }
    public function getCriadoEm(): string { return $this->criadoEm; }
    public function getFotoPerfil(): ?string { return $this->fotoPerfil; }
}