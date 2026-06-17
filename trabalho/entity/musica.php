<?php

class Musica {

    private int    $id;
    private string $titulo;
    private string $duracao;
    private int    $artistaId;
    private ?int   $albumId; // ? significa q pode ser null(musica pode ou nao ser de um album)
    private string $criadoEm;

    public function __construct(array $dados) {
        $this->id        = (int) ($dados['id']         ?? 0);
        $this->titulo    =        $dados['titulo']      ?? '';
        $this->duracao   =        $dados['duracao']     ?? '';
        $this->artistaId = (int) ($dados['artista_id']  ?? 0);
        $this->albumId   = isset($dados['album_id']) && $dados['album_id'] !== null
                            ? (int) $dados['album_id']
                            : null;
        $this->criadoEm  =        $dados['criado_em']   ?? '';
    }

    public static function novo(string $titulo, string $duracao, int $artistaId, ?int $albumId = null): self {
        return new self([
            'titulo'     => $titulo,
            'duracao'    => $duracao,
            'artista_id' => $artistaId,
            'album_id'   => $albumId,
        ]);
    }

    public function alterarDados(string $titulo, string $duracao, ?int $albumId): void {
        $this->titulo  = $titulo;
        $this->duracao = $duracao;
        $this->albumId = $albumId;
    }

    public function registrarIdGerado(int $id): void {
        $this->id = $id;
    }

    public function getId():        int     { return $this->id; }
    public function getTitulo():    string  { return $this->titulo; }
    public function getDuracao():   string  { return $this->duracao; }
    public function getArtistaId(): int     { return $this->artistaId; }
    public function getAlbumId():   ?int    { return $this->albumId; }
    public function getCriadoEm():  string  { return $this->criadoEm; }
}