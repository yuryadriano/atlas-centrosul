<?php
/**
 * Atlas Centro Sul — Autenticação e Gestão de Sessões
 */

require_once __DIR__ . '/db.php';

// Iniciar sessão de forma segura
function iniciarSessao(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === 1)) || 
                          (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'),
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        session_start();
    }
}

// Login do utilizador
function login(string $email, string $senha): bool {
    $pdo  = getDB();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND ativo = 1 LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['atlas_user_id']   = $user['id'];
        $_SESSION['atlas_user_nome'] = $user['nome'];
        $_SESSION['atlas_user_email']= $user['email'];
        return true;
    }
    return false;
}

// Verificar se está autenticado
function estaAutenticado(): bool {
    iniciarSessao();
    return isset($_SESSION['atlas_user_id']);
}

// Proteger rota — redireciona para login se não autenticado
function protegerRota(): void {
    iniciarSessao();
    if (!estaAutenticado()) {
        header('Location: /atlas/admin/login.php');
        exit;
    }
}

// Logout
function logout(): void {
    iniciarSessao();
    session_unset();
    session_destroy();
    header('Location: /atlas/admin/login.php');
    exit;
}

// Utilizador autenticado actual
function utilizadorActual(): array {
    iniciarSessao();
    return [
        'id'    => $_SESSION['atlas_user_id']    ?? null,
        'nome'  => $_SESSION['atlas_user_nome']  ?? '',
        'email' => $_SESSION['atlas_user_email'] ?? '',
    ];
}

// Gerar token CSRF
function gerarCSRF(): string {
    iniciarSessao();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validar token CSRF
function validarCSRF(string $token): bool {
    iniciarSessao();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
