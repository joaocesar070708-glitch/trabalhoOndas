<?php

session_start();

if (!empty($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../repository/UsuarioRepository.php';

$erro = '';
$emailFormulario = $_POST['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $erro = 'Preencha todos os campos.';
    } else {
        $repo = new UsuarioRepository();
        $usuario = $repo->buscarPorEmail($email);

        if ($usuario && $usuario->senhaEstaCorreta($senha)) {
            $_SESSION['usuario_id'] = $usuario->getId();
            $_SESSION['usuario_nome'] = $usuario->getNome();

            header('Location: index.php');
            exit;
        } else {
            $erro = 'E-mail ou senha inválidos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — Ondas</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>
<body class="login-body">

<div class="login-card">
  <div class="login-logo">Ondas</div>
  <h1 class="login-title">Entrar no sistema</h1>

  <?php if ($erro !== ''): ?>
    <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form method="POST" action="login.php">
    <div class="form-group">
      <label for="email">E-mail</label>
      <input
        type="email"
        id="email"
        name="email"
        placeholder="seu@email.com"
        value="<?= htmlspecialchars($emailFormulario) ?>"
        required
      />
    </div>

    <div class="form-group">
      <label for="senha">Senha</label>
      <input
        type="password"
        id="senha"
        name="senha"
        placeholder="••••••••"
        required
      />
    </div>

    <button type="submit" class="btn btn-primary btn-full">Entrar</button>
    <p class="login-hint">
        Não tem uma conta? <a href="createUser.php">Criar conta</a>
    </p>
  </form>

</div>

</body>
</html>