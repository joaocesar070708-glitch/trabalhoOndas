<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/UsuarioRepository.php';
require_once __DIR__ . '/../repository/PlaylistRepository.php';
require_once __DIR__ . '/../entity/playlist.php';

$repoUsuario  = new UsuarioRepository();
$repoPlaylist = new PlaylistRepository();

$user = $repoUsuario->buscarPorId($_SESSION['usuario_id']);

if ($user === null) {
    header('Location: login.php');
    exit;
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';

    try {
        $novaPlaylist = Playlist::novo($user->getId(), $nome);
        $repoPlaylist->criar($novaPlaylist->getUsuarioId(), $novaPlaylist->getNome());

        header('Location: minhasPlaylists.php');
        exit;
    } catch (InvalidArgumentException $e) {
        $erros[] = $e->getMessage();
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