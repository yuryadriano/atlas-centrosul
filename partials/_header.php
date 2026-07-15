<?php
/**
 * Atlas Centro Sul — Header / Navegação Global
 */
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/auth.php';
$paginaActual = $_SERVER['REQUEST_URI'];
function navAtivo(string $caminho): string {
    global $paginaActual;
    return strpos($paginaActual, $caminho) !== false ? 'ativo' : '';
}

$s1 = config('site_slogan_1');
$s2 = config('site_slogan_2');
if (!empty($s1) || !empty($s2)) {
    $sloganGlobal = $s1 . ($s2 ? ', ' . $s2 : '');
} else {
    $sloganGlobal = config('site_slogan', 'Uma Marca, Múltiplas Soluções.');
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($metaDesc ?? $sloganGlobal . ' — ' . config('empresa_nome')) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? config('site_nome', 'Atlas Centro Sul')) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDesc ?? config('sobre_resumo', '')) ?>">
    <meta property="og:type" content="website">
    <title><?= htmlspecialchars(($pageTitle ?? '') ? $pageTitle . ' — ' . config('site_nome', 'Atlas Centro Sul') : config('site_nome', 'Atlas Centro Sul') . ' — ' . $sloganGlobal) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="/atlas/assets/css/style.css?v=<?= time() ?>">
    <?= $extraHead ?? '' ?>
</head>
<body>

<nav class="navbar <?= $navSolid ?? false ? 'solid' : '' ?>" id="navbar">
    <div class="nav-inner">
        <!-- Logo -->
        <a href="/atlas/index.php" class="nav-logo">
            <img src="/atlas/assets/img/logo.png" alt="Atlas Centro Sul">
            <div class="nav-logo-text">
                <strong>Atlas Centro Sul</strong>
                <span>Comércio & Serviços</span>
            </div>
        </a>

        <!-- Links -->
        <ul class="nav-links" id="navLinks">
            <li><a href="/atlas/index.php" class="<?= navAtivo('index') ?>">Início</a></li>
            <li><a href="/atlas/sobre.php" class="<?= navAtivo('sobre') ?>">Sobre Nós</a></li>
            <li class="nav-dropdown">
                <a href="#" class="<?= navAtivo('servicos') ?>">Serviços ▾</a>
                <div class="dropdown-menu">
                    <a href="/atlas/servicos/energia-industria.php">⚙️ Energia & Indústria</a>
                    <a href="/atlas/servicos/saude-bem-estar.php">🏥 Saúde & Bem-Estar</a>
                    <a href="/atlas/servicos/agronegocio.php">🌾 Agronegócio</a>
                    <a href="/atlas/servicos/comercio-investimentos.php">🏢 Comércio & Investimentos</a>
                </div>
            </li>
            <li><a href="/atlas/blog.php" class="<?= navAtivo('blog') ?>">Blog</a></li>
            <li><a href="/atlas/galeria.php" class="<?= navAtivo('galeria') ?>">Galeria</a></li>
            <li><a href="/atlas/contacto.php" class="nav-cta <?= navAtivo('contacto') ?>">Contacto</a></li>
        </ul>

        <!-- Toggle mobile -->
        <button class="nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>
