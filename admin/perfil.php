<?php
/**
 * Atlas Centro Sul — Perfil do Utilizador
 */
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/helpers.php';
protegerRota();

$pdo = getDB();
$flash = obterFlash();
$erros = [];

$userSession = utilizadorActual();
$userId = $userSession['id'];

// Carregar dados frescos do utilizador na BD
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $userId]);
$usuario = $stmt->fetch();

if (!$usuario) {
    die("Utilizador não encontrado no sistema.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitizar($_POST['nome'] ?? '');
    $email = sanitizar($_POST['email'] ?? '');
    $senhaAtual = $_POST['senha_atual'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    // Validar campos básicos
    if (empty($nome)) {
        $erros[] = "O campo nome é obrigatório.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Indique um e-mail válido.";
    }

    // Verificar se o e-mail já está em uso por outro utilizador
    $checkEmail = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id LIMIT 1");
    $checkEmail->execute([':email' => $email, ':id' => $userId]);
    if ($checkEmail->fetch()) {
        $erros[] = "O e-mail indicado já está a ser utilizado por outra conta.";
    }

    // Se quiser alterar a senha
    $alterarSenha = false;
    if (!empty($novaSenha) || !empty($senhaAtual) || !empty($confirmarSenha)) {
        if (empty($senhaAtual)) {
            $erros[] = "Para alterar a senha, deve introduzir a sua senha atual.";
        } elseif (!password_verify($senhaAtual, $usuario['senha'])) {
            $erros[] = "A senha atual introduzida está incorreta.";
        }
        
        if (strlen($novaSenha) < 6) {
            $erros[] = "A nova senha deve conter pelo menos 6 caracteres.";
        }
        if ($novaSenha !== $confirmarSenha) {
            $erros[] = "A confirmação da nova senha não coincide.";
        }
        $alterarSenha = true;
    }

    // Se não houver erros, proceder com a atualização
    if (empty($erros)) {
        if ($alterarSenha) {
            $hashSenha = password_hash($novaSenha, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id");
            $update->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $hashSenha,
                ':id' => $userId
            ]);
        } else {
            $update = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id");
            $update->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':id' => $userId
            ]);
        }

        // Atualizar as informações na sessão
        $_SESSION['atlas_user_nome'] = $nome;
        $_SESSION['atlas_user_email'] = $email;

        flashMensagem('success', 'Perfil atualizado com sucesso!');
        header('Location: /atlas/admin/perfil.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil — Atlas Centro Sul Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/atlas/assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/_sidebar.php'; ?>
<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-title">👤 Configurações do Meu Perfil</div>
    </header>
    <main class="content">
        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['tipo'] ?>"><?= htmlspecialchars($flash['mensagem']) ?></div>
        <?php endif; ?>

        <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <ul style="padding-left: 20px; margin: 0;">
                <?php foreach ($erros as $erro): ?>
                <li><?= htmlspecialchars($erro) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div style="background: #fff; padding: 30px; border-radius: var(--radius); box-shadow: var(--shadow); max-width: 600px;">
            <form method="POST" action="">
                
                <!-- Informações Básicas -->
                <div style="margin-bottom: 24px;">
                    <h3 style="color: var(--navy); margin-bottom: 16px; border-bottom: 1px solid var(--gray-200); padding-bottom: 8px;">Dados do Perfil</h3>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div class="form-group">
                            <label for="nome" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px;">Nome Completo</label>
                            <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>" required style="width: 100%; padding: 10px; border: 1px solid var(--gray-200); border-radius: var(--radius); outline: none;">
                        </div>
                        
                        <div class="form-group">
                            <label for="email" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px;">E-mail do Administrador</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required style="width: 100%; padding: 10px; border: 1px solid var(--gray-200); border-radius: var(--radius); outline: none;">
                        </div>
                    </div>
                </div>

                <!-- Alterar Senha (Opcional) -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: var(--navy); margin-bottom: 16px; border-bottom: 1px solid var(--gray-200); padding-bottom: 8px;">Alterar Senha <span style="font-weight: normal; font-size: 12px; color: var(--gray-500);">(deixe em branco se não quiser alterar)</span></h3>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div class="form-group">
                            <label for="senha_atual" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px;">Senha Atual</label>
                            <input type="password" id="senha_atual" name="senha_atual" class="form-control" placeholder="Introduza a sua senha atual" style="width: 100%; padding: 10px; border: 1px solid var(--gray-200); border-radius: var(--radius); outline: none;">
                        </div>

                        <div class="form-group">
                            <label for="nova_senha" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px;">Nova Senha</label>
                            <input type="password" id="nova_senha" name="nova_senha" class="form-control" placeholder="Mínimo de 6 caracteres" style="width: 100%; padding: 10px; border: 1px solid var(--gray-200); border-radius: var(--radius); outline: none;">
                        </div>

                        <div class="form-group">
                            <label for="confirmar_senha" style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 6px;">Confirmar Nova Senha</label>
                            <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control" placeholder="Confirme a nova senha" style="width: 100%; padding: 10px; border: 1px solid var(--gray-200); border-radius: var(--radius); outline: none;">
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-navy" style="padding: 12px 24px; border-radius: var(--radius); background: var(--navy); color: #white; border: none; font-weight: 600; cursor: pointer;">
                        💾 Guardar Alterações
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>
