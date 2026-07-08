<?php
/**
 * Atlas Centro Sul — Sidebar do Painel Administrativo
 */
require_once __DIR__ . '/../app/auth.php';
$user    = utilizadorActual();
$inicial = strtoupper(substr($user['nome'], 0, 1));
$paginaActual = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <img src="/atlas/assets/img/logo.png" alt="Atlas Centro Sul">
        <div class="sidebar-logo-text">
            <strong>Atlas Centro Sul</strong>
            <span>Painel Admin</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Principal</div>
        <a href="/atlas/admin/dashboard.php" class="<?= $paginaActual === 'dashboard.php' ? 'ativo' : '' ?>">
            <span class="nav-icon">📊</span> Dashboard
        </a>

        <div class="nav-section" style="margin-top:12px">Conteúdo</div>
        <a href="/atlas/admin/posts.php" class="<?= in_array($paginaActual, ['posts.php','post_form.php']) ? 'ativo' : '' ?>">
            <span class="nav-icon">📝</span> Blog & Notícias
        </a>
        <a href="/atlas/admin/midia.php" class="<?= in_array($paginaActual, ['midia.php','midia_form.php']) ? 'ativo' : '' ?>">
            <span class="nav-icon">🎥</span> Multimédia
        </a>
        <a href="/atlas/admin/mensagens.php" class="<?= $paginaActual === 'mensagens.php' ? 'ativo' : '' ?>">
            <span class="nav-icon">✉️</span> Mensagens
        </a>

        <div class="nav-section" style="margin-top:12px">Sistema</div>
        <a href="/atlas/admin/perfil.php" class="<?= $paginaActual === 'perfil.php' ? 'ativo' : '' ?>">
            <span class="nav-icon">👤</span> Meu Perfil
        </a>
        <a href="/atlas/admin/configuracoes.php" class="<?= $paginaActual === 'configuracoes.php' ? 'ativo' : '' ?>">
            <span class="nav-icon">⚙️</span> Configurações
        </a>

        <div class="nav-section" style="margin-top:12px">Site</div>
        <a href="/atlas/index.php" target="_blank">
            <span class="nav-icon">🌐</span> Ver Site
        </a>
    </nav>

    <div class="sidebar-bottom">
        <a href="/atlas/admin/logout.php">
            <span>🚪</span> Terminar Sessão
        </a>
    </div>
</aside>
