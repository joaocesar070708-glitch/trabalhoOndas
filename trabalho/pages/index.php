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

if (!empty($_GET['excluir'])) {

    $repoReview->excluir((int) $_GET['excluir']);
    header('Location: index.php');
    exit;
}

$reviews = $repoReview->listarPorUsuario($user->getId());


function renderEstrelas(int $nota): string {
    $nota = max(0, min(5, $nota));
    return str_repeat('★', $nota) . str_repeat('☆', 5 - $nota);
}

$gradientes = [
    'linear-gradient(135deg,#FF6B4A,#7A2E1E)',
    'linear-gradient(135deg,#4ADEDE,#163B3B)',
    'linear-gradient(135deg,#FFC857,#5C4317)',
    'linear-gradient(135deg,#8B93A1,#1B2230)',
    'linear-gradient(135deg,#FF6B4A,#4ADEDE)',
    'linear-gradient(135deg,#FFC857,#FF6B4A)',
];

require_once __DIR__ . '/../includes/header.php';
?>



<section class="home-hero">
  <p class="home-eyebrow">Seu diário</p>
  <h1>Olá, <?= htmlspecialchars($user->getNome()) ?></h1>
  <p>Aqui estão as músicas que você já avaliou.</p>
</section>

<?php if (empty($reviews)): ?>
  <div class="feed-empty">
    <p>Você ainda não avaliou nenhuma música.</p>
  </div>
<?php else: ?>
  <div class="feed-grid">
    <?php foreach ($reviews as $review): ?>
      <article class="review-card">
        <div class="review-cover" style="background:<?= $gradientes[$review->getId() % count($gradientes)] ?>"></div>
        <div class="review-body">
          <p class="review-musica"><?= htmlspecialchars($review->getMusicaTitulo()) ?></p>
          <?php if ($review->getArtistaNome() !== ''): ?>
            <p class="review-artista"><?= htmlspecialchars($review->getArtistaNome()) ?></p>
          <?php endif; ?>
          <p class="review-artista"><?= htmlspecialchars($review->getAlbumNome()) ?></p>
          <p class="review-estrelas"><?= renderEstrelas($review->getNota()) ?></p>
          <?php if ($review->getDescricao() !== ''): ?>
            <p class="review-descricao"><?= htmlspecialchars($review->getDescricao()) ?></p>
          <?php endif; ?>
          <p class="review-meta">
            <?= date('d/m/Y', strtotime($review->getCriadoEm())) ?>
          </p>
          <a href="index.php?excluir=<?= $review->getId() ?>" 
            onclick="return confirm('Certeza que deseja excluir esta review?')">Excluir</a>
        </div>
      </article>
    <?php endforeach; ?>


<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

