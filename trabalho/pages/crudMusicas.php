<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/UsuarioRepository.php';
require_once __DIR__ . '/../repository/reviewRepository.php';
require_once __DIR__ . '/../entity/reviews.php';

$repoUsuario = new UsuarioRepository();
$user = $repoUsuario->buscarPorId($_SESSION['usuario_id']);

if ($user === null) {
    header('Location: login.php');
    exit;
}

$repoReview = new ReviewRepository();

$erro = null;

$titulo = '';
$artistanome = '';
$album = '';
$nota  = '';
$comentario  = '';
$link  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = trim($_POST['MusTitulo'] ?? '');
    $artistanome = trim($_POST['MusArtistaNome'] ?? '');
    $album       = trim($_POST['MusAlbum'] ?? '');
    $nota        = $_POST['Musnota'] ?? '';
    $comentario  = trim($_POST['MusReview'] ?? '');
    $link        = trim($_POST['MusLink'] ?? '');

    try {
        $novaReview = Review::novo(
            $user->getId(),
            $titulo,
            $artistanome,
            $album,
            (int) $nota,
            $comentario,
            $link
        );
        $repoReview->salvar($novaReview);

        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException $e) {
        $erro = $e->getMessage();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>+ Música</h2>
</div>

<?php if ($erro !== null): ?>
  <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="form-card">
  <form method="post" action="" novalidate>

    <div class="form-group">
      <label for="titulo">Música</label>
      <input
        type="text"
        id="titulo"
        name="MusTitulo"
        placeholder="Nome da faixa"
        value="<?= htmlspecialchars($titulo) ?>"
        required
      >
    </div>

    <div class="form-group">
      <label for="artistanome">Artista</label>
      <input
        type="text"
        id="artistanome"
        name="MusArtistaNome"
        placeholder="Nome do artista"
        value="<?= htmlspecialchars($artistanome) ?>"
      >
    </div>

    <div class="form-group">
      <label for="album">Álbum</label>
      <input
        type="text"
        id="album"
        name="MusAlbum"
        placeholder="Nome do álbum"
        value="<?= htmlspecialchars($album) ?>"
      >
    </div>

    <div class="form-group">
      <label for="nota">Nota</label>
      <select id="nota" name="Musnota" required>
        <option value="" disabled <?= $nota === '' ? 'selected' : '' ?>>Escolha de 1 a 5</option>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <option value="<?= $i ?>" <?= (string) $nota === (string) $i ? 'selected' : '' ?>>
            <?= str_repeat('★', $i) . str_repeat('☆', 5 - $i) ?>
          </option>
        <?php endfor; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="comentario">Comentário</label>
      <textarea
        id="comentario"
        name="MusReview"
        rows="4"
        placeholder="O que te marcou nessa faixa?"
      ><?= htmlspecialchars($comentario) ?></textarea>
    </div>

    <div class="form-group">
      <label for="link">Link do YouTube</label>
      <input
        type="url"
        id="link"
        name="MusLink"
        placeholder="https://www.youtube.com/watch?v=..."
        value="<?= htmlspecialchars($link) ?>"
      >
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar</button>
      <a href="index.php" class="btn btn-ghost">Cancelar</a>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>