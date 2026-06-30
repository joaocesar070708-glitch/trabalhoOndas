<?php

class Review {

    private int    $id;
    private int    $usuarioId;
    private string $tituloMusica;
    private string $nomeArtista;
    private string $nomeAlbum;
    private int    $nota;
    private string $comentario;
    private string $criadoEm;

    public function __construct(array $dados) {
        $this->id = (int) ($dados['id_review'] ?? 0);
        $this->usuarioId = (int) ($dados['usuario_id'] ?? 0);
        $this->tituloMusica = $dados['titulo_musica'] ?? '';
        $this->nomeArtista = $dados['nome_artista']  ?? '';
        $this->nomeAlbum = $dados['nome_album']    ?? '';
        $this->nota = (int) ($dados['nota']          ?? 0);
        $this->comentario = $dados['comentario']    ?? '';
        $this->criadoEm = $dados['criado_em']     ?? '';
    }

    public static function novo(int $usuarioId, string $tituloMusica, string $nomeArtista, string $nomeAlbum, int $nota, string $comentario): self {
        return new self([
            'usuario_id'    => $usuarioId,
            'titulo_musica' => $tituloMusica,
            'nome_artista'  => $nomeArtista,
            'nome_album'    => $nomeAlbum,
            'nota'          => $nota,
            'comentario'    => $comentario,
        ]);
    }

    public function registrarIdGerado(int $id): void {
        $this->id = $id;
    }

    public function getId():           int    { return $this->id; }
    public function getUsuarioId():    int    { return $this->usuarioId; }
    public function getMusicaTitulo(): string { return $this->tituloMusica; }
    public function getArtistaNome():  string { return $this->nomeArtista; }
    public function getAlbumNome():    string { return $this->nomeAlbum; }
    public function getNota():         int    { return $this->nota; }
    public function getDescricao():    string { return $this->comentario; }
    public function getCriadoEm():     string { return $this->criadoEm; }
}