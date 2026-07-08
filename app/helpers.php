<?php
/**
 * Atlas Centro Sul — Funções Utilitárias
 */

// ── Configuração de Erros em Produção ────────────────────────
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isLocal = in_array($host, ['localhost', '127.0.0.1', '[::1]']) || (strpos($host, '192.168.') === 0) || (strpos($host, '10.') === 0);
if (!$isLocal || getenv('APP_ENV') === 'production') {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

require_once __DIR__ . '/db.php';

// ── Texto ────────────────────────────────────────────────────

function criarSlug(string $texto): string {
    $texto = mb_strtolower($texto, 'UTF-8');
    $mapa  = ['á'=>'a','à'=>'a','ã'=>'a','â'=>'a','é'=>'e','ê'=>'e','í'=>'i','ó'=>'o','õ'=>'o','ô'=>'o','ú'=>'u','ü'=>'u','ç'=>'c','ñ'=>'n'];
    $texto = strtr($texto, $mapa);
    $texto = preg_replace('/[^a-z0-9\s-]/', '', $texto);
    $texto = preg_replace('/[\s-]+/', '-', $texto);
    return trim($texto, '-');
}

function sanitizar(string $str): string {
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

function truncar(string $texto, int $limite = 150): string {
    $texto = strip_tags($texto);
    if (mb_strlen($texto) <= $limite) return $texto;
    return mb_substr($texto, 0, $limite) . '…';
}

// ── Data ─────────────────────────────────────────────────────

function formatarData(string $data): string {
    $meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
              'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    $ts = strtotime($data);
    return date('d', $ts) . ' de ' . $meses[(int)date('n', $ts) - 1] . ' de ' . date('Y', $ts);
}

// ── Categorias ───────────────────────────────────────────────

function labelCategoria(string $slug): string {
    $cats = [
        'energia-industria'       => 'Energia & Indústria',
        'saude-bem-estar'         => 'Saúde & Bem-Estar',
        'agronegocio'             => 'Agronegócio',
        'comercio-investimentos'  => 'Comércio & Investimentos',
        'geral'                   => 'Geral',
    ];
    return $cats[$slug] ?? ucfirst($slug);
}

function iconeCategoria(string $slug): string {
    $icons = [
        'energia-industria'       => '⚙️',
        'saude-bem-estar'         => '🏥',
        'agronegocio'             => '🌾',
        'comercio-investimentos'  => '🏢',
        'geral'                   => '📌',
    ];
    return $icons[$slug] ?? '📌';
}

// ── Configurações do Site ────────────────────────────────────

function config(string $chave, string $padrao = ''): string {
    static $cache = [];
    if (!isset($cache[$chave])) {
        try {
            $pdo  = getDB();
            $stmt = $pdo->prepare("SELECT valor FROM configuracoes WHERE chave = :chave LIMIT 1");
            $stmt->execute([':chave' => $chave]);
            $row  = $stmt->fetch();
            $cache[$chave] = $row ? $row['valor'] : $padrao;
        } catch (Exception $e) {
            $cache[$chave] = $padrao;
        }
    }
    return $cache[$chave];
}

// ── Upload de Ficheiros ──────────────────────────────────────

function uploadImagem(array $ficheiro, string $pasta): string|false {
    $extensoesPermitidas = ['jpg','jpeg','png','webp','gif'];
    $tamanhoMaximo       = 5 * 1024 * 1024; // 5MB

    if ($ficheiro['error'] !== UPLOAD_ERR_OK) return false;
    if ($ficheiro['size'] > $tamanhoMaximo) return false;

    $extensao = strtolower(pathinfo($ficheiro['name'], PATHINFO_EXTENSION));
    if (!in_array($extensao, $extensoesPermitidas)) return false;

    $dirUpload = __DIR__ . '/../uploads/' . $pasta . '/';
    if (!is_dir($dirUpload)) mkdir($dirUpload, 0755, true);

    $nomeUnico = uniqid('atlas_', true) . '.' . $extensao;
    $caminho   = $dirUpload . $nomeUnico;

    if (move_uploaded_file($ficheiro['tmp_name'], $caminho)) {
        return '/atlas/uploads/' . $pasta . '/' . $nomeUnico;
    }
    return false;
}

// ── Paginação ────────────────────────────────────────────────

function paginar(int $total, int $porPagina, int $paginaActual): array {
    $totalPaginas = (int)ceil($total / $porPagina);
    $offset       = ($paginaActual - 1) * $porPagina;
    return [
        'total'        => $total,
        'por_pagina'   => $porPagina,
        'pagina_actual'=> $paginaActual,
        'total_paginas'=> $totalPaginas,
        'offset'       => max(0, $offset),
        'tem_anterior' => $paginaActual > 1,
        'tem_proximo'  => $paginaActual < $totalPaginas,
    ];
}

// ── Flash Messages ───────────────────────────────────────────

function flashMensagem(string $tipo, string $mensagem): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensagem' => $mensagem];
}

function obterFlash(): array|null {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
