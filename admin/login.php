<?php
/**
 * Atlas Centro Sul — Login do Painel Administrativo
 */
require_once __DIR__ . '/../app/auth.php';
iniciarSessao();

// Se já está autenticado, redirecionar
if (estaAutenticado()) {
    header('Location: /atlas/admin/dashboard.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } elseif (!login($email, $senha)) {
        $erro = 'Email ou palavra-passe incorretos.';
    } else {
        header('Location: /atlas/admin/dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Atlas Centro Sul Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/atlas/assets/css/admin.css">
    <style>
        body { display: block; }
        .login-page { display: flex; }
    </style>
</head>
<body>
<div class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <img src="/atlas/assets/img/logo.png" alt="Atlas Centro Sul">
            <h1>Atlas Centro Sul</h1>
            <p>Painel de Administração</p>
        </div>

        <?php if ($erro): ?>
        <div class="alert alert-danger" role="alert">
            ⚠️ <?= htmlspecialchars($erro) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Endereço de Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="admin@atlascentrosul.ao"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="form-group">
                <label for="senha">Palavra-passe</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    class="form-control"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;">
                🔐 Entrar no Painel
            </button>
        </form>

        <p style="text-align:center;margin-top:24px;font-size:12px;color:#8892A4;">
            Atlas Centro Sul — Comércio e Serviços, Lda<br>
            © <?= date('Y') ?> · Todos os direitos reservados
        </p>
    </div>
</div>
</body>
</html>
