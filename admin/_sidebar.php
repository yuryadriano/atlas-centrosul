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
        <button class="sidebar-close" id="sidebarCloseBtn" aria-label="Fechar Menu">✕</button>
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

<!-- Botão flutuante para abrir o menu em mobile -->
<button class="admin-menu-toggle" id="adminMenuToggleBtn" aria-label="Abrir Menu">☰</button>

<!-- Backdrop overlay para escurecer o fundo em mobile -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('adminMenuToggleBtn');
    const closeBtn = document.getElementById('sidebarCloseBtn');
    const backdrop = document.getElementById('sidebarBackdrop');

    if (openBtn && sidebar && backdrop) {
        openBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.add('open');
            backdrop.classList.add('active');
        });
    }

    if (closeBtn && sidebar && backdrop) {
        closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.remove('open');
            backdrop.classList.remove('active');
        });
    }

    // Fechar ao clicar no backdrop (fundo escuro)
    if (backdrop && sidebar) {
        backdrop.addEventListener('click', function() {
            sidebar.classList.remove('open');
            backdrop.classList.remove('active');
        });
    }

    // Fechar ao clicar fora do sidebar em mobile
    document.addEventListener('click', function(e) {
        if (sidebar && sidebar.classList.contains('open')) {
            if (!sidebar.contains(e.target) && e.target !== openBtn) {
                sidebar.classList.remove('open');
                if (backdrop) backdrop.classList.remove('active');
            }
        }
    });
});
</script>
