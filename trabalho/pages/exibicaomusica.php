<?php

require_once __DIR__ . '../includes/auth.php';
require_once __DIR__ . '../repository/ReviewRepository.php';

$repoReview = new ReviewRepository();
$musica = null;

if (empty($_POST['id'])) {
    header('Location: index.php');
    exit;
}

if (!empty($_POST['id'])) {
    $musica = $repoReview->buscarPorId((int) $_POST['id']);

    if ($musica === null) {
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'></head><body>";
    echo "<script>alert('Música não encontrada.'); window.location.href='index.php';</script>";
    echo "</body></html>";
    exit;
    }
}



require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>Música</h2>
  <a href="index.php" class="btn btn-ghost">Voltar</a>
</div>

<?php if ($musica): ?>
<div class="form-card">

  <div class="form-group">
    <label>Música</label>
    <p><?= htmlspecialchars($musica->getMusicaTitulo()) ?></p>
  </div>

  <div class="form-group">
    <label>Artista</label>
    <p><?= htmlspecialchars($musica->getArtistaNome()) ?></p>
  </div>

  <div class="form-group">
    <label>Álbum</label>
    <p><?= htmlspecialchars($musica->getAlbumNome()) ?></p>
  </div>

  <div class="form-group">
    <label>Nota</label>
    <p><?= htmlspecialchars($musica->getNota()) ?></p>
  </div>

  <div class="form-group">
    <label>Review</label>
    <p><?= htmlspecialchars($musica->getDescricao()) ?></p>
  </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>