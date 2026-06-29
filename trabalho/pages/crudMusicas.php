<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/UsuarioRepository.php';
require_once __DIR__ . '/../repository/reviewRepository.php';

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
$nota = '';
$review = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['MusTitulo'] ?? '');
    $artistanome  = trim($_POST['MusArtistaNome'] ?? '');
    $album = trim($_POST['MusAlbum'] ?? '');
    $nota = $_POST['Musnota'] ?? '';
    $review = trim($_POST['MusReview'] ?? '');

    if ($titulo === '') {
        $erro = 'Informe o nome da música.';
    } elseif (!ctype_digit((string) $nota) || (int) $nota < 1 || (int) $nota > 5) {
        $erro = 'Escolha uma nota entre 1 e 5.';
    } else {
        $repoReview->criar(
            $user->getId(),
            $titulo,
            $artistanome,
            $album,
            (int) $nota,
            $review
        );

        header('Location: index.php');
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>+ Música</h2>
  <a href="index.php" class="btn btn-ghost">Voltar</a>
</div>

<?php if ($erro !== null): ?>
  <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="form-card">
  <form method="post" action="adicionar-musica.php" novalidate>

    <div class="form-group">
      <label for="titulo">Música</label>
      <input
        type="text"
        id="titulo"
        name="titulo"
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
        name="artistanome"
        placeholder="Nome do artista"
        value="<?= htmlspecialchars($artistanome) ?>"
      >
    </div>

    <div class="form-group">
      <label for="album">Álbum</label>
      <input
        type="text"
        id="album"
        name="album"
        placeholder="Nome do álbum"
        value="<?= htmlspecialchars($album) ?>"
      >
    </div>

    <div class="form-group">
      <label for="nota">Nota</label>
      <select id="nota" name="nota" required>
        <option value="" disabled <?= $nota === '' ? 'selected' : '' ?>>Escolha de 1 a 5</option>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <option value="<?= $i ?>" <?= (string) $nota === (string) $i ? 'selected' : '' ?>>
            <?= str_repeat('★', $i) . str_repeat('☆', 5 - $i) ?>
          </option>
        <?php endfor; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="review">Comentário</label>
      <textarea
        id="review"
        name="review"
        rows="4"
        placeholder="O que te marcou nessa faixa?"
      ><?= htmlspecialchars($review) ?></textarea>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar</button>
      <a href="index.php" class="btn btn-ghost">Cancelar</a>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>