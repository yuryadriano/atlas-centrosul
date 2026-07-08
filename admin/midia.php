<?php
/**
 * Atlas Centro Sul — Gestão de Multimédia (Fotos & Vídeos)
 */
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/helpers.php';
protegerRota();

$pdo   = getDB();
$flash = obterFlash();

// Eliminar
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    $item = $pdo->prepare("SELECT url_ou_caminho FROM midia WHERE id = ?")->execute([(int)$_GET['eliminar']]);
    $pdo->prepare("DELETE FROM midia WHERE id = ?")->execute([(int)$_GET['eliminar']]);
    flashMensagem('success', 'Item de multimédia eliminado.');
    header('Location: /atlas/admin/midia.php');
    exit;
}

$filtroTipo = sanitizar($_GET['tipo'] ?? '');
$where  = $filtroTipo ? "WHERE tipo = :tipo" : "WHERE 1=1";
$params = $filtroTipo ? [':tipo' => $filtroTipo] : [];

$total = $pdo->prepare("SELECT COUNT(*) FROM midia $where");
$total->execute($params);
$total = (int)$total->fetchColumn();

$porPagina    = 12;
$paginaActual = max(1, (int)($_GET['p'] ?? 1));
$pag          = paginar($total, $porPagina, $paginaActual);

$params[':limit']  = $porPagina;
$params[':offset'] = $pag['offset'];
$stmt = $pdo->prepare("SELECT * FROM midia $where ORDER BY criado_em DESC LIMIT :limit OFFSET :offset");
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multimédia — Atlas Centro Sul Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/atlas/assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/_sidebar.php'; ?>
<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-title">Multimédia</div>
        <div class="topbar-right">
            <a href="/atlas/admin/midia_form.php" class="btn btn-primary btn-sm">+ Adicionar</a>
        </div>
    </header>

    <main class="content">
        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['tipo'] ?>"><?= htmlspecialchars($flash['mensagem']) ?></div>
        <?php endif; ?>

        <!-- Filtros -->
        <div style="display:flex;gap:12px;margin-bottom:20px;">
            <a href="/atlas/admin/midia.php" class="btn <?= !$filtroTipo ? 'btn-primary' : 'btn-outline' ?>">Todos (<?= $total ?>)</a>
            <a href="/atlas/admin/midia.php?tipo=foto" class="btn <?= $filtroTipo === 'foto' ? 'btn-primary' : 'btn-outline' ?>">📷 Fotos</a>
            <a href="/atlas/admin/midia.php?tipo=video" class="btn <?= $filtroTipo === 'video' ? 'btn-primary' : 'btn-outline' ?>">🎥 Vídeos</a>
        </div>

        <?php if (empty($items)): ?>
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">🖼️</div>
                <h3>Sem multimédia ainda</h3>
                <p>Adicione fotos ou vídeos para enriquecer o site.</p>
                <a href="/atlas/admin/midia_form.php" class="btn btn-primary" style="margin-top:16px;">+ Adicionar Média</a>
            </div>
        </div>
        <?php else: ?>

        <!-- Grid de Multimédia -->
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;">
            <?php foreach ($items as $item): ?>
            <div class="card" style="overflow:hidden;">
                <!-- Preview -->
                <div style="height:160px;background:var(--gray-100);position:relative;overflow:hidden;">
                    <?php if ($item['tipo'] === 'foto'): ?>
                        <img src="<?= htmlspecialchars($item['url_ou_caminho']) ?>" alt="<?= htmlspecialchars($item['titulo']) ?>"
                             style="width:100%;height:100%;object-fit:cover;">
                    <?php else: ?>
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--navy-dark);">
                            <span style="font-size:48px;">▶️</span>
                        </div>
                    <?php endif; ?>
                    <div style="position:absolute;top:8px;right:8px;">
                        <span class="badge <?= $item['tipo'] === 'foto' ? 'badge-info' : 'badge-gold' ?>">
                            <?= $item['tipo'] === 'foto' ? '📷 Foto' : '🎥 Vídeo' ?>
                        </span>
                    </div>
                    <?php if ($item['destaque']): ?>
                    <div style="position:absolute;top:8px;left:8px;">
                        <span class="badge badge-gold">⭐ Destaque</span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Info -->
                <div style="padding:16px;">
                    <h3 style="font-size:14px;font-weight:700;color:var(--navy);margin-bottom:4px;line-height:1.3;">
                        <?= htmlspecialchars(truncar($item['titulo'], 50)) ?>
                    </h3>
                    <p style="font-size:12px;color:var(--gray-500);margin-bottom:4px;">
                        <?= labelCategoria($item['categoria']) ?> · <?= formatarData($item['criado_em']) ?>
                    </p>
                    <?php if ($item['descricao']): ?>
                    <p style="font-size:12px;color:var(--gray-700);margin-bottom:12px;">
                        <?= htmlspecialchars(truncar($item['descricao'], 60)) ?>
                    </p>
                    <?php else: ?>
                    <div style="margin-bottom:12px;"></div>
                    <?php endif; ?>
                    <div style="display:flex;gap:8px;">
                        <a href="/atlas/admin/midia_form.php?id=<?= $item['id'] ?>" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;">✏️ Editar</a>
                        <a href="/atlas/admin/midia.php?eliminar=<?= $item['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Eliminar este item?')">🗑️</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Paginação -->
        <?php if ($pag['total_paginas'] > 1): ?>
        <div class="pagination" style="margin-top:28px;">
            <?php if ($pag['tem_anterior']): ?>
                <a href="?p=<?= $paginaActual - 1 ?>&tipo=<?= $filtroTipo ?>">← Anterior</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pag['total_paginas']; $i++): ?>
                <<?= $i === $paginaActual ? 'span class="ativo"' : "a href=\"?p=$i&tipo=$filtroTipo\"" ?>><?= $i ?></<?= $i === $paginaActual ? 'span' : 'a' ?>>
            <?php endfor; ?>
            <?php if ($pag['tem_proximo']): ?>
                <a href="?p=<?= $paginaActual + 1 ?>&tipo=<?= $filtroTipo ?>">Próximo →</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
