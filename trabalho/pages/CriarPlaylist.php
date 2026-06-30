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

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';

    if ($nome === '') {
        $erros[] = 'O nome da playlist não pode ser vazio.';
    }

    if (empty($erros)) {
        $repoPlaylist->criar($user->getId(), $nome);
        header('Location: minhasPlaylists.php');
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="home-hero">
  <p class="home-eyebrow">Nova playlist</p>
  <h1>Criar playlist</h1>
</section>

<div class="form-wrapper">

  <?php if (!empty($erros)): ?>
    <ul class="form-erros">
      <?php foreach ($erros as $erro): ?>
        <li><?= htmlspecialchars($erro) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="POST" action="criarPlaylist.php">

    <div class="form-group">
      <label for="nome">Nome da playlist</label>
      <input type="text" name="nome" id="nome" required
             value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
    </div>

    <div class="form-actions">
      <a href="minhasPlaylists.php" class="btn-secundario">Cancelar</a>
      <button type="submit" class="btn-primario">Criar</button>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>