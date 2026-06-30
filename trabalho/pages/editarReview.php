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

$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$review = $repoReview->buscarPorId($id);

// pertenceAoUsuario() é a entidade decidindo se o dono é esse usuário,
// em vez da página comparar os ids manualmente
if ($review === null || !$review->pertenceAoUsuario($user->getId())) {
    header('Location: index.php');
    exit;
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nota      = isset($_POST['nota'])      ? (int) $_POST['nota']      : 0;
    $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
    $titulo    = isset($_POST['titulo'])    ? trim($_POST['titulo'])    : '';

    try {
        $review->definirTitulo($titulo);
        $review->definirNota($nota);
        $review->definirComentario($descricao);
    } catch (InvalidArgumentException $e) {
        $erros[] = $e->getMessage();
    }

    if (empty($erros)) {
        $repoReview->atualizar(
            $review->getMusicaTitulo(),
            $id,
            $review->getNota(),
            $review->getDescricao()
        );
        header('Location: index.php');
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="home-hero">
  <p class="home-eyebrow">Editar review</p>
  <h1><?= htmlspecialchars($review->getMusicaTitulo()) ?></h1>
  <?php if ($review->getArtistaNome() !== ''): ?>
    <p><?= htmlspecialchars($review->getArtistaNome()) ?></p>
  <?php endif; ?>
</section>

<div class="form-wrapper">

  <?php if (!empty($erros)): ?>
    <ul class="form-erros">
      <?php foreach ($erros as $erro): ?>
        <li><?= htmlspecialchars($erro) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="POST" action="editarReview.php?id=<?= $review->getId() ?>">

    <div class="form-group">
      <label for="titulo">Título da música</label>
      <input type="text" name="titulo" id="titulo"
             value="<?= htmlspecialchars($_POST['titulo'] ?? $review->getMusicaTitulo()) ?>" required>
    </div>

    <div class="form-group">
      <label for="nota">Nota</label>
      <select name="nota" id="nota" required>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <option value="<?= $i ?>" <?= ($review->getNota() === $i ? 'selected' : '') ?>>
            <?= str_repeat('★', $i) . str_repeat('☆', 5 - $i) ?> (<?= $i ?>)
          </option>
        <?php endfor; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="descricao">Descrição</label>
      <textarea name="descricao" id="descricao" rows="4"><?= htmlspecialchars($review->getDescricao()) ?></textarea>
    </div>

    <div class="form-actions">
      <a href="index.php" class="btn-secundario">Cancelar</a>
      <button type="submit" class="btn-primario">Salvar alterações</button>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>