<?php
/**
 * Atlas Centro Sul — CRUD de Posts (Blog & Notícias)
 */
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/helpers.php';
protegerRota();

$pdo   = getDB();
$flash = obterFlash();

// Eliminar post
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['eliminar']]);
    flashMensagem('success', 'Post eliminado com sucesso.');
    header('Location: /atlas/admin/posts.php');
    exit;
}

// Alternar publicado/rascunho
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $stmt = $pdo->prepare("UPDATE posts SET publicado = NOT publicado WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['toggle']]);
    header('Location: /atlas/admin/posts.php');
    exit;
}

// Paginação
$porPagina   = 10;
$paginaActual = max(1, (int)($_GET['p'] ?? 1));
$search      = sanitizar($_GET['q'] ?? '');
$categoria   = sanitizar($_GET['cat'] ?? '');

$where  = "WHERE 1=1";
$params = [];

if ($search) {
    $where .= " AND (titulo LIKE :q OR resumo LIKE :q2)";
    $params[':q']  = "%$search%";
    $params[':q2'] = "%$search%";
}
if ($categoria) {
    $where .= " AND categoria = :cat";
    $params[':cat'] = $categoria;
}

$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM posts $where");
$totalStmt->execute($params);
$total = (int)$totalStmt->fetchColumn();
$pag   = paginar($total, $porPagina, $paginaActual);

$params[':limit']  = $porPagina;
$params[':offset'] = $pag['offset'];
$stmt = $pdo->prepare("SELECT * FROM posts $where ORDER BY criado_em DESC LIMIT :limit OFFSET :offset");
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$posts = $stmt->fetchAll();

$categorias = ['energia-industria','saude-bem-estar','agronegocio','comercio-investimentos','geral'];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog & Notícias — Atlas Centro Sul Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/atlas/assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/_sidebar.php'; ?>
<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-title">Blog & Notícias</div>
        <div class="topbar-right">
            <a href="/atlas/admin/post_form.php" class="btn btn-primary btn-sm">+ Novo Post</a>
        </div>
    </header>

    <main class="content">
        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['tipo'] ?>"><?= htmlspecialchars($flash['mensagem']) ?></div>
        <?php endif; ?>

        <!-- Filtros -->
        <form method="GET" action="" style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
            <div class="search-box" style="flex:1;min-width:200px;">
                <span class="search-icon">🔍</span>
                <input type="text" name="q" placeholder="Pesquisar posts..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <select name="cat" class="form-control" style="width:220px;">
                <option value="">Todas as categorias</option>
                <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat ?>" <?= $categoria === $cat ? 'selected' : '' ?>>
                    <?= labelCategoria($cat) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-outline">Filtrar</button>
            <?php if ($search || $categoria): ?>
            <a href="/atlas/admin/posts.php" class="btn btn-outline">Limpar</a>
            <?php endif; ?>
        </form>

        <div class="card">
            <div class="card-header">
                <h2>📝 Posts (<?= $total ?>)</h2>
            </div>

            <?php if (empty($posts)): ?>
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h3>Sem posts</h3>
                <p>Crie o primeiro artigo para aparecer no site.</p>
                <a href="/atlas/admin/post_form.php" class="btn btn-primary" style="margin-top:16px;">+ Criar Post</a>
            </div>
            <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Categoria</th>
                            <th>Estado</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td style="max-width:300px;">
                                <div style="font-weight:600;color:var(--navy);">
                                    <?= htmlspecialchars(truncar($post['titulo'], 60)) ?>
                                </div>
                                <?php if ($post['resumo']): ?>
                                <div style="font-size:12px;color:var(--gray-500);margin-top:2px;">
                                    <?= htmlspecialchars(truncar($post['resumo'], 70)) ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-navy"><?= labelCategoria($post['categoria']) ?></span>
                            </td>
                            <td>
                                <a href="/atlas/admin/posts.php?toggle=<?= $post['id'] ?>" title="Clique para alternar">
                                    <?php if ($post['publicado']): ?>
                                        <span class="badge badge-success">✓ Publicado</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">○ Rascunho</span>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td style="font-size:13px;color:var(--gray-500);white-space:nowrap;">
                                <?= formatarData($post['criado_em']) ?>
                            </td>
                            <td>
                                <div style="display:flex;gap:6px;">
                                    <a href="/atlas/admin/post_form.php?id=<?= $post['id'] ?>" class="btn btn-outline btn-sm">✏️ Editar</a>
                                    <a href="/atlas/admin/posts.php?eliminar=<?= $post['id'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Eliminar este post permanentemente?')">🗑️</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <?php if ($pag['total_paginas'] > 1): ?>
            <div style="padding:16px;border-top:1px solid var(--gray-100);">
                <div class="pagination">
                    <?php if ($pag['tem_anterior']): ?>
                        <a href="?p=<?= $paginaActual - 1 ?>&q=<?= urlencode($search) ?>&cat=<?= $categoria ?>">← Anterior</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $pag['total_paginas']; $i++): ?>
                        <?php if ($i === $paginaActual): ?>
                            <span class="ativo"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?p=<?= $i ?>&q=<?= urlencode($search) ?>&cat=<?= $categoria ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($pag['tem_proximo']): ?>
                        <a href="?p=<?= $paginaActual + 1 ?>&q=<?= urlencode($search) ?>&cat=<?= $categoria ?>">Próximo →</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>
