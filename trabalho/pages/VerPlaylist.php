<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/UsuarioRepository.php';
require_once __DIR__ . '/../repository/PlaylistRepository.php';

$repoUsuario  = new UsuarioRepository();
$repoPlaylist = new PlaylistRepository();

$user = $repoUsuario->buscarPorId($_SESSION['usuario_id']);

if ($user === null) {
    header('Location: login.php');
    exit;
}

$id       = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$playlist = $repoPlaylist->buscarPorId($id);

// Garante que a playlist existe e pertence ao usuário logado
if ($playlist === null || $playlist->getUsuarioId() !== $user->getId()) {
    header('Location: playlists.php');
    exit;
}

$reviews = $repoPlaylist->listarReviews($id);

function renderEstrelas(int $nota): string {
    $nota = max(0, min(5, $nota));
    return str_repeat('★', $nota) . str_repeat('☆', 5 - $nota);
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="home-hero">
  <p class="home-eyebrow">Playlist</p>
  <h1><?= htmlspecialchars($playlist->getNome()) ?></h1>
  <p><?= count($reviews) ?> música(s)</p>
</section>

<div class="playlist-actions">
  <a href="editarPlaylist.php?id=<?= $playlist->getId() ?>" class="btn-primario">Editar playlist</a>
  <a href="playlists.php" class="btn-secundario">Voltar</a>
</div>

<?php if (empty($reviews)): ?>
  <div class="feed-empty">
    <p>Essa playlist ainda não tem músicas.</p>
  </div>
<?php else: ?>
  <div class="feed-grid">
    <?php foreach ($reviews as $review): ?>
      <article class="review-card">
        <div class="review-body">
          <p class="review-musica"><?= htmlspecialchars($review['titulo_musica']) ?></p>
          <p class="review-artista"><?= htmlspecialchars($review['nome_artista']) ?></p>
          <?php if (!empty($review['nome_album'])): ?>
            <p class="review-artista"><?= htmlspecialchars($review['nome_album']) ?></p>
          <?php endif; ?>
          <p class="review-estrelas"><?= renderEstrelas((int) $review['nota']) ?></p>
          <?php if (!empty($review['comentario'])): ?>
            <p class="review-descricao"><?= htmlspecialchars($review['comentario']) ?></p>
          <?php endif; ?>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<style>
  .playlist-actions {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>