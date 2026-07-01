<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/UsuarioRepository.php';
require_once __DIR__ . '/../repository/PlaylistRepository.php';
require_once __DIR__ . '/../repository/reviewRepository.php';
require_once __DIR__ . '/../entity/playlist.php';

$repoUsuario  = new UsuarioRepository();
$repoPlaylist = new PlaylistRepository();
$repoReview   = new ReviewRepository();

$user = $repoUsuario->buscarPorId($_SESSION['usuario_id']);

if ($user === null) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$playlist = $repoPlaylist->buscarPorId($id);


if ($playlist === null || !$playlist->pertenceAoUsuario($user->getId())) {
    header('Location: minhasPlaylists.php');
    exit;
}


if (!empty($_GET['excluir'])) {
    $repoPlaylist->excluir($id);
    header('Location: index.php?playlist_excluida=1');
    exit;
}

if (!empty($_POST['adicionar_review'])) {
    $repoPlaylist->adicionarReview($id, (int) $_POST['adicionar_review']);
    header("Location: editarPlaylist.php?id=$id");
    exit;
}


if (!empty($_GET['remover_review'])) {
    $repoPlaylist->removerReview($id, (int) $_GET['remover_review']);
    header("Location: editarPlaylist.php?id=$id");
    exit;
}

$erros   = [];
$sucesso = false;

// atualiza nome da playlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
    try {
        $playlist->definirNome($_POST['nome']);
        $repoPlaylist->atualizar($id, $playlist->getNome());
        header("Location: verPlaylist.php?id=$id"); 
        exit;
    } catch (InvalidArgumentException $e) {
        $erros[] = $e->getMessage();
        $playlist = $repoPlaylist->buscarPorId($id); // restaura o nome salvo
    }
}

$tocandoId = !empty($_GET['tocando']) ? (int) $_GET['tocando'] : 0;

$reviewsNaPlaylist = $repoPlaylist->listarReviews($id);
$idsNaPlaylist      = array_map(fn($r) => $r->getId(), $reviewsNaPlaylist);

$todasReviews = $repoReview->listarPorUsuario($user->getId());
$reviewsDisponiveis = array_filter(
    $todasReviews,
    fn($r) => !in_array($r->getId(), $idsNaPlaylist)
);


require_once __DIR__ . '/../includes/header.php';
?>

<section class="home-hero">
  <p class="home-eyebrow">Editar playlist</p>
  <h1><?= htmlspecialchars($playlist->getNome()) ?></h1>
</section>

<div class="form-wrapper">

  <?php if ($sucesso): ?>
    <p class="form-sucesso">Playlist atualizada com sucesso!</p>
  <?php endif; ?>

  <?php if (!empty($erros)): ?>
    <ul class="form-erros">
      <?php foreach ($erros as $erro): ?>
        <li><?= htmlspecialchars($erro) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>


  <form method="POST" action="editarPlaylist.php?id=<?= $id ?>">
    <div class="form-group">
      <label for="nome">Nome da playlist</label>
      <input type="text" name="nome" id="nome"
             value="<?= htmlspecialchars($playlist->getNome()) ?>" required>
    </div>
      <button type="submit" class="btn-primario">Salvar</button>
  </form>

  <hr class="secao-divisor">


  <h2 class="secao-titulo">Músicas na playlist</h2>

  <?php if (empty($reviewsNaPlaylist)): ?>
    <p class="feed-empty">Nenhuma música adicionada ainda.</p>
  <?php else: ?>
    <ul class="musicas-lista">
      <?php foreach ($reviewsNaPlaylist as $r): ?>
        <li class="musica-item">
          <span>
            <strong><?= htmlspecialchars($r->getMusicaTitulo()) ?></strong>
            — <?= htmlspecialchars($r->getArtistaNome()) ?>
            <?= $r->renderEstrelas($r->getNota()) ?>
          </span>
          <span class="musica-acoes">
            <?php if ($r->podeTocar()): ?>
              <?php if ($tocandoId === $r->getId()): ?>
                <a href="editarPlaylist.php?id=<?= $id ?>">Fechar</a>
              <?php else: ?>
                <a href="editarPlaylist.php?id=<?= $id ?>&tocando=<?= $r->getId() ?>">Tocar</a>
              <?php endif; ?>
            <?php endif; ?>
            <a href="editarPlaylist.php?id=<?= $id ?>&remover_review=<?= $r->getId() ?>"
               onclick="return confirm('Remover esta música da playlist?')"
               class="btn-remover">Remover</a>
          </span>

          <?php if ($tocandoId === $r->getId() && $r->podeTocar()): ?>
            <div class="player-musica">
              <iframe width="100%" height="200"
                src="https://www.youtube.com/embed/<?= $r->getIdVideoYoutube() ?>"
                frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <hr class="secao-divisor">

  <h2 class="secao-titulo">Adicionar músicas</h2>

  <?php if (empty($reviewsDisponiveis)): ?>
    <p class="feed-empty">Todas as suas músicas já estão nesta playlist.</p>
  <?php else: ?>
    <ul class="musicas-lista">
      <?php foreach ($reviewsDisponiveis as $r): ?>
        <li class="musica-item">
          <span>
            <strong><?= htmlspecialchars($r->getMusicaTitulo()) ?></strong>
            — <?= htmlspecialchars($r->getArtistaNome()) ?>
            <?= $r->renderEstrelas($r->getNota()) ?>
          </span>
          <form method="POST" action="editarPlaylist.php?id=<?= $id ?>">
            <input type="hidden" name="adicionar_review" value="<?= $r->getId() ?>">
            <button type="submit" class="btn-adicionar">+ Adicionar</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <hr class="secao-divisor">

  <div class="zona-perigo">
    <a href="editarPlaylist.php?id=<?= $id ?>&excluir=1"
       onclick="return confirm('Tem certeza que deseja excluir esta playlist?')"
       class="btn-excluir">Excluir playlist</a>
  </div>

</div>

<style>
  .secao-divisor {
    border: none;
    border-top: 1px solid rgba(255,255,255,0.1);
    margin: 2rem 0;
  }

  .secao-titulo {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    opacity: 0.8;
  }

  .musicas-lista {
    list-style: none;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
  }

  .musica-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.75rem 1rem;
    background: rgba(255,255,255,0.05);
    border-radius: 0.5rem;
    flex-wrap: wrap;
  }

  .musica-acoes {
    display: flex;
    gap: 0.75rem;
    align-items: center;
  }

  .player-musica {
    width: 100%;
    margin-top: 0.5rem;
  }

  .btn-remover  { color: #FF6B4A; text-decoration: underline; font-size: 0.85rem; }
  .btn-adicionar { background: none; border: 1px solid #4ADEDE; color: #4ADEDE;
                   padding: 0.3rem 0.75rem; border-radius: 0.4rem; cursor: pointer; font-size: 0.85rem; }
  .zona-perigo  { margin-top: 1rem; }
  .btn-excluir  { color: #FF6B4A; text-decoration: underline; }
  .form-sucesso { color: #4ADEDE; margin-bottom: 1rem; font-weight: 600; }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>