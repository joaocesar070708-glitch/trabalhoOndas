<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ondas</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>
<body>

<header class="site-header">
  <div class="header-inner">
    <a href="../pages/index.php" class="logo">Ondas</a>

    <nav class="nav">
      <a href="../pages/crudMusicas.php">+ Novas Músicas</a>
      <a href="../pages/minhasPlaylists.php">Minhas Playlists</a>
      <form method="post" action="exibicaomusica.php" style="display:flex">
    <input type="text" name="id" placeholder="Digite o ID da música">
    <button type="submit">🔍</button>
</form>
    </nav>

    <div class="header-user">
      <a href="../pages/logout.php" class="btn-logout">Sair</a>
    </div>
  </div>
</header>

<main class="container">
