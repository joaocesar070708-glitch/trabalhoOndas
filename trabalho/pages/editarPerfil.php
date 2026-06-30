<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/UsuarioRepository.php';

$repoUsuario = new UsuarioRepository();
$user = $repoUsuario->buscarPorId($_SESSION['usuario_id']);

if ($user === null) {
    header('Location: login.php');
    exit;
}

$erros = [];
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome']  ?? '';
    $email = $_POST['email'] ?? '';

    $senhaAtual = $_POST['senha_atual']    ?? '';
    $senhaNova = $_POST['senha_nova']     ?? '';
    $senhaConfirma = $_POST['senha_confirma'] ?? '';


    try {
        $user->definirNome($nome);
        $user->definirEmail($email);
    } catch (InvalidArgumentException $e) {
        $erros[] = $e->getMessage();
    }

  
    if ($senhaAtual !== '' || $senhaNova !== '') {
        if (!$user->senhaEstaCorreta($senhaAtual)) {
            $erros[] = 'Senha atual incorreta.';
        } elseif ($senhaNova !== $senhaConfirma) {
            $erros[] = 'A confirmação não coincide com a nova senha.';
        } else {
            try {
        
                $user->definirSenha($senhaNova);
                $repoUsuario->atualizarSenha($user->getId(), $senhaNova);
            } catch (InvalidArgumentException $e) {
                $erros[] = $e->getMessage();
            }
        }
    }

   if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto          = $_FILES['foto'];
    $extensao      = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
    $permitidas    = ['jpg', 'jpeg', 'png', 'webp'];
    $tamanhoMaximo = 2 * 1024 * 1024; //limite de tamanho 

    if (!in_array($extensao, $permitidas)) {
        $erros[] = 'Formato de imagem inválido. Use JPG, PNG ou WEBP.';
    } elseif ($foto['size'] > $tamanhoMaximo) {
        $erros[] = 'A imagem deve ter no máximo 2MB.';
    } else {
        $nomeArquivo = uniqid('foto_') . '.' . $extensao;
        $destino     = __DIR__ . '/../../uploads/' . $nomeArquivo;

        if (move_uploaded_file($foto['tmp_name'], $destino)) {
            $user->definirFotoPerfil($nomeArquivo);
            $repoUsuario->atualizarFoto($user->getId(), $nomeArquivo);
        } else {
            $erros[] = 'Erro ao salvar a imagem. Tente novamente.';
        }
    }
}

    if (empty($erros)) {
        $repoUsuario->atualizar($user->getId(), $user->getNome(), $user->getEmail());
        $sucesso = true;
        $user    = $repoUsuario->buscarPorId($_SESSION['usuario_id']);
    } else {
        $user = $repoUsuario->buscarPorId($_SESSION['usuario_id']);
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="home-hero">
  <p class="home-eyebrow">Configurações</p>
  <h1>Editar perfil</h1>
</section>

<div class="form-wrapper">

  <?php if ($sucesso): ?>
    <p class="form-sucesso">Perfil atualizado com sucesso!</p>
  <?php endif; ?>

  <?php if (!empty($erros)): ?>
    <ul class="form-erros">
      <?php foreach ($erros as $erro): ?>
        <li><?= htmlspecialchars($erro) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="POST" action="editarPerfil.php" enctype="multipart/form-data">

    <div class="form-group form-avatar-group">
      <div class="avatar-preview-wrapper">
        <?php if (!empty($user->getFotoPerfil())): ?>
          <img src="/trabalhoOndas/uploads/<?= htmlspecialchars($user->getFotoPerfil()) ?>"
               alt="Foto atual" class="avatar-preview" id="previewImg">
        <?php else: ?>
          <img src="/trabalhoOndas/uploads/307ce493-b254-4b2d-8ba4-d12c080d6651.jpg"
               alt="Foto padrão" class="avatar-preview" id="previewImg">
        <?php endif; ?>
      </div>
      <label for="foto" class="btn-trocar-foto">Trocar foto</label>
      <input type="file" name="foto" id="foto" accept="image/*" style="display:none">
    </div>

    <div class="form-group">
      <label for="nome">Nome</label>
      <input type="text" name="nome" id="nome"
             value="<?= htmlspecialchars($user->getNome()) ?>" required>
    </div>

    <div class="form-group">
      <label for="email">E-mail</label>
      <input type="email" name="email" id="email"
             value="<?= htmlspecialchars($user->getEmail()) ?>" required>
    </div>

    <div class="form-group">
      <label for="senha_atual">Senha atual</label>
      <input type="password" name="senha_atual" id="senha_atual">
    </div>

    <div class="form-group">
      <label for="senha_nova">Nova senha</label>
      <input type="password" name="senha_nova" id="senha_nova">
    </div>

    <div class="form-group">
      <label for="senha_confirma">Confirmar nova senha</label>
      <input type="password" name="senha_confirma" id="senha_confirma">
    </div>

    <div class="form-actions">
      <a href="index.php" class="btn-secundario">Cancelar</a>
      <button type="submit" class="btn-primario">Salvar alterações</button>
    </div>

  </form>
</div>

<style>
  .form-avatar-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
  }

  .avatar-preview-wrapper {
    width: 6rem;
    height: 6rem;
    border-radius: 50%;
    overflow: hidden;
  }

  .avatar-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
  }

  .btn-trocar-foto {
    cursor: pointer;
    font-size: 0.85rem;
    color: #4ADEDE;
    text-decoration: underline;
  }

  .form-sucesso {
    color: #4ADEDE;
    margin-bottom: 1rem;
    font-weight: 600;
  }
</style>

<script>
  document.getElementById('foto').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('previewImg').src = e.target.result;
    };
    reader.readAsDataURL(file);
  });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>