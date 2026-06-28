<?php
 
session_start();

require_once __DIR__ . '/../repository/UsuarioRepository.php';
require_once __DIR__ . '/../entity/Usuario.php';

$erro = '';
$nomeForm = $_POST['nome']  ?? '';
$emailForm = $_POST['email'] ?? '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmarsenha = $_POST['confirmar_senha'] ?? '';

    $repositorio = new UsuarioRepository();
 
    if ($nome === '' || $email === '' || $senha === '') {
        $erro = 'Preencha todos os campos';
    } 
    elseif ($senha != $confirmarsenha){
        $erro = 'As senhas nao batem';
    }  
    elseif ($repositorio->buscarPorEmail($email) !== null) {
        $erro = 'email ja cadastrado';
    }        
    else {
        $novoUsuario = new Usuario([
            'nome'  => $nome,
            'email' => $email,
            'senha' => $senha,
        ]);
 
            $repositorio->salvar($novoUsuario);
 
            $_SESSION['usuario_id']   = $novoUsuario->getId();
            $_SESSION['usuario_nome'] = $novoUsuario->getNome();

            header('Location: index.php');
            exit;
        }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Criar conta — Ondas</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>
<body class="login-body">
 
<div class="login-card">
  <div class="login-logo">Ondas</div>
  <h1 class="login-title">Criar conta</h1>

  <?php if ($erro !== ''): ?>
    <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>
 
  <form method="POST" action="createUser.php">
    <div class="form-group">
      <label for="nome">Nome</label>
      <input
        type="text"
        id="nome"
        name="nome"
        placeholder="Seu nome completo"
        value="<?= htmlspecialchars($nomeForm) ?>"
        required
      />
    </div>
 
    <div class="form-group">
      <label for="email">E-mail</label>
      <input
        type="email"
        id="email"
        name="email"
        placeholder="seu@email.com"
        value="<?= htmlspecialchars($emailForm) ?>"
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
 
    <div class="form-group">
      <label for="confirmar_senha">Confirmar senha</label>
      <input
        type="password"
        id="confirmar_senha"
        name="confirmar_senha"
        placeholder="••••••••"
        required
      />
    </div>
 
    <button type="submit" class="btn btn-primary btn-full">Criar conta</button>
  </form>
 
</div>
 
<p class="login-hint">
  Já tem uma conta? <a href="index.php">Entrar</a>
</p>
 
</body>
</html>