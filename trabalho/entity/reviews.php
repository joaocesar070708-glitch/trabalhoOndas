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
        $this->id = (int) ($dados['id_review']   ?? 0);
        $this->usuarioId = (int) ($dados['usuario_id']  ?? 0);
        $this->tituloMusica =  $dados['titulo_musica'] ?? '';
        $this->nomeArtista =  $dados['nome_artista']  ?? '';
        $this->nomeAlbum =  $dados['nome_album']    ?? '';
        $this->nota = (int) ($dados['nota']          ?? 0);
        $this->comentario =  $dados['comentario']    ?? '';
        $this->criadoEm=  $dados['criado_em']     ?? '';
    }

  
    public static function novo(
        int $usuarioId,
        string $tituloMusica,
        string $nomeArtista,
        string $nomeAlbum,
        int $nota,
        string $comentario
    ): self {
        $review = new self([
            'usuario_id' => $usuarioId,
            'titulo_musica' => $tituloMusica,
            'nome_artista' => $nomeArtista,
            'nome_album' => $nomeAlbum,
            'nota' => $nota,
            'comentario' => $comentario,
        ]);

        $review->definirTitulo($tituloMusica);
        $review->definirNota($nota);

        return $review;
    }

    public function registrarIdGerado(int $id): void {
        $this->id = $id;
    }

    public function definirTitulo(string $titulo): void {
        $titulo = trim($titulo);
        if ($titulo === '') {
            throw new InvalidArgumentException('O título não pode ser vazio.');
        }
        $this->tituloMusica = $titulo;
    }

    public function definirNota(int $nota): void {
        if ($nota < 1 || $nota > 5) {
            throw new InvalidArgumentException('A nota deve ser entre 1 e 5.');
        }
        $this->nota = $nota;
    }

    public function definirArtista(string $artista): void {
        $this->nomeArtista = trim($artista);
    }

    public function definirAlbum(string $album): void {
        $this->nomeAlbum = trim($album);
    }

    public function definirComentario(string $comentario): void {
        $this->comentario = trim($comentario);
    }

    // Regra de negócio: só o autor da review pode editá-la ou excluí-la.
    public function pertenceAoUsuario(int $usuarioId): bool {
        return $this->usuarioId === $usuarioId;
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