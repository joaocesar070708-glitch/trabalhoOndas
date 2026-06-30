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

if ($playlist === null || !$playlist->pertenceAoUsuario($user->getId())) {
    header('Location: playlists.php');
    exit;
}

$reviews   = $repoPlaylist->listarReviews($id);
$tocandoId = !empty($_GET['tocando']) ? (int) $_GET['tocando'] : 0;

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
          <p class="review-musica"><?= htmlspecialchars($review->getMusicaTitulo()) ?></p>
          <p class="review-artista"><?= htmlspecialchars($review->getArtistaNome()) ?></p>
          <?php if ($review->getAlbumNome() !== ''): ?>
            <p class="review-artista"><?= htmlspecialchars($review->getAlbumNome()) ?></p>
          <?php endif; ?>
          <p class="review-estrelas"><?= renderEstrelas($review->getNota()) ?></p>
          <?php if ($review->getDescricao() !== ''): ?>
            <p class="review-descricao"><?= htmlspecialchars($review->getDescricao()) ?></p>
          <?php endif; ?>

          <?php if ($review->podeTocar()): ?>
            <?php if ($tocandoId === $review->getId()): ?>
              <a href="verPlaylist.php?id=<?= $playlist->getId() ?>">Fechar</a>
            <?php else: ?>
              <a href="verPlaylist.php?id=<?= $playlist->getId() ?>&tocando=<?= $review->getId() ?>">Tocar</a>
            <?php endif; ?>
          <?php endif; ?>

          <?php if ($tocandoId === $review->getId() && $review->podeTocar()): ?>
            <div class="player-musica">
              <iframe width="100%" height="200"
                src="https://www.youtube.com/embed/<?= $review->getIdVideoYoutube() ?>"
                frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
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

  .player-musica {
    margin-top: 0.75rem;
  }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>