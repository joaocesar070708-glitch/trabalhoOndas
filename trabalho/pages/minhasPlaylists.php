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

$playlists = $repoPlaylist->listarPorUsuario($user->getId());

require_once __DIR__ . '/../includes/header.php';
?>

<section class="home-hero">
  <p class="home-eyebrow">Suas playlists</p>
  <h1>Olá, <?= htmlspecialchars($user->getNome()) ?></h1>
  <p>Organize suas músicas favoritas em playlists.</p>
</section>

<div class="playlist-actions">
  <a href="criarPlaylist.php" class="btn-primario">+ Nova playlist</a>
</div>

<?php if (empty($playlists)): ?>
  <div class="feed-empty">
    <p>Você ainda não tem nenhuma playlist.</p>
  </div>
<?php else: ?>
  <div class="feed-grid">
    <?php foreach ($playlists as $playlist): ?>
      <article class="review-card">
        <div class="review-body">
          <p class="review-musica"><?= htmlspecialchars($playlist->getNome()) ?></p>
          <p class="review-meta"><?= date('d/m/Y', strtotime($playlist->getCriadoEm())) ?></p>
          <div class="card-actions">
            <a href="verPlaylist.php?id=<?= $playlist->getId() ?>">Ver músicas</a>
            <a href="editarPlaylist.php?id=<?= $playlist->getId() ?>">Editar</a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<style>
  .playlist-actions {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 1.5rem;
  }

  .card-actions {
    display: flex;
    gap: 1rem;
    margin-top: 0.5rem;
  }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>