<?php
/**
 * Atlas Centro Sul — Utilitário de Recuperação de Palavra-passe
 */
require_once __DIR__ . '/../app/db.php';

$senhaPadrao = 'Atlas@2025';
$hash = password_hash($senhaPadrao, PASSWORD_DEFAULT);
$email = 'admin@atlascentrosul.ao';

try {
    $pdo = getDB();
    
    // Verificar se o utilizador existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Atualizar a senha para o padrão
        $update = $pdo->prepare("UPDATE usuarios SET senha = :senha, ativo = 1 WHERE email = :email");
        $update->execute([':senha' => $hash, ':email' => $email]);
        $mensagem = "A palavra-passe do administrador (<strong>$email</strong>) foi redefinida com sucesso para: <strong>$senhaPadrao</strong>";
        $sucesso = true;
    } else {
        // Criar o utilizador administrador se não existir
        $insert = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, ativo) VALUES ('Administrador', :email, :senha, 1)");
        $insert->execute([':email' => $email, ':senha' => $hash]);
        $mensagem = "O utilizador administrador (<strong>$email</strong>) não existia e foi criado com a palavra-passe: <strong>$senhaPadrao</strong>";
        $sucesso = true;
    }
} catch (PDOException $e) {
    $mensagem = "Erro de ligação à base de dados: " . $e->getMessage() . "<br><br><strong>Nota:</strong> Certifique-se de que o MySQL está ativo no painel de controlo do XAMPP.";
    $sucesso = false;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Palavra-passe — Atlas Centro Sul</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 500px;
            text-align: center;
        }
        h2 { color: #1e293b; margin-top: 0; }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            line-height: 1.5;
        }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .btn {
            display: inline-block;
            background: #0f172a;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }
        .btn:hover { background: #1e293b; }
    </style>
</head>
<body>
<div class="container">
    <h2>Recuperação de Acesso</h2>
    <div class="alert <?= $sucesso ? 'alert-success' : 'alert-error' ?>">
        <?= $mensagem ?>
    </div>
    <?php if ($sucesso): ?>
        <a href="/atlas/admin/login.php" class="btn">Ir para o Login</a>
    <?php else: ?>
        <a href="" class="btn">Tentar Novamente</a>
    <?php endif; ?>
</div>
</body>
</html>
