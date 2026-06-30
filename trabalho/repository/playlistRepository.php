<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/playlist.php';

class PlaylistRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    // mostra playlist do usuario
    public function listarPorUsuario(int $usuarioId): array {
        $stmt = $this->pdo->prepare('
            SELECT * FROM playlist
            WHERE usuario_id = ?
            ORDER BY criado_em DESC
        ');
        $stmt->execute([$usuarioId]);
        $playlists = [];
        while ($dados = $stmt->fetch()) {
            $playlists[] = new Playlist($dados);
        }
        return $playlists;
    }

    public function buscarPorId(int $id): ?Playlist {
        $stmt = $this->pdo->prepare('
            SELECT * FROM playlist WHERE playlist_id = ? LIMIT 1
        ');
        $stmt->execute([$id]);
        $dados = $stmt->fetch();
        return $dados ? new Playlist($dados) : null;
    }

    public function criar(int $usuarioId, string $nome): void {
        $stmt = $this->pdo->prepare('
            INSERT INTO playlist (usuario_id, nome) VALUES (?, ?)
        ');
        $stmt->execute([$usuarioId, $nome]);
    }


    public function atualizar(int $id, string $nome): void {
        $stmt = $this->pdo->prepare('
            UPDATE playlist SET nome = ? WHERE playlist_id = ?
        ');
        $stmt->execute([$nome, $id]);
    }
    public function excluir(int $id): void {
        $stmt = $this->pdo->prepare('
            DELETE FROM playlist WHERE playlist_id = ?
        ');
        $stmt->execute([$id]);
    }

    public function listarReviews(int $playlistId): array {
        $stmt = $this->pdo->prepare('
            SELECT r.*
            FROM review r
            INNER JOIN playlist_musica pm ON pm.id_review = r.id_review
            WHERE pm.playlist_id = ?
            ORDER BY r.criado_em DESC
        ');
        $stmt->execute([$playlistId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function adicionarReview(int $playlistId, int $reviewId): void {
        $stmt = $this->pdo->prepare('
            INSERT IGNORE INTO playlist_musica (playlist_id, id_review) VALUES (?, ?)
        ');
        $stmt->execute([$playlistId, $reviewId]);
    }

    public function removerReview(int $playlistId, int $reviewId): void {
        $stmt = $this->pdo->prepare('
            DELETE FROM playlist_musica WHERE playlist_id = ? AND id_review = ?
        ');
        $stmt->execute([$playlistId, $reviewId]);
    }
}