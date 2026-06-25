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

<style>
  :root{
    --bg-elevated:#161B24;
    --text-muted:#8B93A1;
    --accent-coral:#FF6B4A;
    --accent-cyan:#4ADEDE;
    --accent-gold:#FFC857;
    --border:rgba(244,242,237,0.09);
    --display:'Fraunces', serif;
    --mono:'JetBrains Mono', monospace;
  }

  .home-hero{ padding:10px 0 36px; }
  .home-eyebrow{
    font-family:var(--mono);
    font-size:0.75rem;
    letter-spacing:0.16em;
    text-transform:uppercase;
    color:var(--accent-cyan);
    margin-bottom:10px;
  }
  .home-hero h1{
    font-family:var(--display);
    font-style:italic;
    font-weight:500;
    font-size:clamp(1.6rem, 3vw, 2.2rem);
  }
  .home-hero p{
    margin-top:8px;
    color:var(--text-muted);
    font-size:0.95rem;
  }

  .feed-empty{
    padding:48px 24px;
    text-align:center;
    border:1px solid var(--border);
    border-radius:12px;
    color:var(--text-muted);
  }

  .feed-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));
    gap:16px;
  }

  .review-card{
    display:flex;
    gap:14px;
    padding:14px;
    border:1px solid var(--border);
    border-radius:12px;
    background:var(--bg-elevated);
  }

  .review-cover{
    flex:0 0 64px;
    width:64px;
    height:64px;
    border-radius:8px;
  }

  .review-body{ flex:1; min-width:0; }

  .review-musica{
    font-family:var(--display);
    font-style:italic;
    font-size:1.02rem;
  }

  .review-artista{
    font-size:0.78rem;
    color:var(--text-muted);
    margin-top:1px;
  }

  .review-estrelas{
    color:var(--accent-gold);
    font-size:0.85rem;
    margin-top:6px;
  }

  .review-descricao{
    font-size:0.85rem;
    margin-top:6px;
    color:#D7D4CD;
    overflow:hidden;
    text-overflow:ellipsis;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
  }

  .review-meta{
    font-family:var(--mono);
    font-size:0.72rem;
    color:var(--text-muted);
    margin-top:8px;
  }
</style>

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
          <p class="review-estrelas"><?= renderEstrelas($review->getNota()) ?></p>
          <?php if ($review->getDescricao() !== ''): ?>
            <p class="review-descricao"><?= htmlspecialchars($review->getDescricao()) ?></p>
          <?php endif; ?>
          <p class="review-meta">
            <?= date('d/m/Y', strtotime($review->getCriadoEm())) ?>
          </p>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>