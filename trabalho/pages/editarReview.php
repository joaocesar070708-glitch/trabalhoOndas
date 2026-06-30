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
    $nota = isset($_POST['nota']) ? (int) $_POST['nota'] : 0;
    $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
<<<<<<< HEAD
    $titulo    = isset($_POST['titulo'])    ? trim($_POST['titulo'])    : '';
    $link      = isset($_POST['link'])      ? trim($_POST['link'])      : '';
=======
    $titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
    $album = isset($_POST['album']) ? trim($_POST['album']) : '';
    $artistanome = isset($_POST['artista']) ? trim($_POST['artista']) : '';
>>>>>>> ebe0fe75596b60cd1fa25b466996914f856709fa

    try {
        $review->definirTitulo($titulo);
        $review->definirNota($nota);
        $review->definirComentario($descricao);
<<<<<<< HEAD
        $review->definirLinkYoutube($link);
=======
        $review->definirAlbum($album);
        $review->definirArtista($artistanome);
>>>>>>> ebe0fe75596b60cd1fa25b466996914f856709fa
    } catch (InvalidArgumentException $e) {
        $erros[] = $e->getMessage();
    }

    if (empty($erros)) {
        $repoReview->atualizar(
            $review->getMusicaTitulo(),
            $id,
            $review->getNota(),
            $review->getDescricao(),
<<<<<<< HEAD
            $review->getLinkYoutube()
=======
            $review->getAlbumNome(),
            $review->getArtistaNome()
>>>>>>> ebe0fe75596b60cd1fa25b466996914f856709fa
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
      <label for="titulo">Nome do artista</label>
      <input type="text" name="artista" id="artista"
             value="<?= htmlspecialchars($_POST['artista'] ?? $review->getArtistaNome()) ?>" required>
    </div>

    <div class="form-group">
      <label for="titulo">Título do álbum</label>
      <input type="text" name="album" id="album"
             value="<?= htmlspecialchars($_POST['album'] ?? $review->getAlbumNome()) ?>" required>
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

    <div class="form-group">
      <label for="link">Link do YouTube</label>
      <input type="url" name="link" id="link"
             placeholder="https://www.youtube.com/watch?v=..."
             value="<?= htmlspecialchars($_POST['link'] ?? $review->getLinkYoutube() ?? '') ?>">
    </div>

    <div class="form-actions">
      <a href="index.php" class="btn-secundario">Cancelar</a>
      <button type="submit" class="btn-primario">Salvar alterações</button>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>