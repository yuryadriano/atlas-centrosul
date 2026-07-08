<?php
require_once __DIR__ . '/app/helpers.php';
$pageTitle = 'Blog & Notícias';
$metaDesc  = 'Últimas notícias, artigos e novidades da Atlas Centro Sul.';
$pdo = getDB();

$porPagina    = 9;
$paginaActual = max(1, (int)($_GET['p'] ?? 1));
$cat          = sanitizar($_GET['cat'] ?? '');
$q            = sanitizar($_GET['q'] ?? '');

$where  = "WHERE publicado = 1";
$params = [];
if ($cat) { $where .= " AND categoria = :cat"; $params[':cat'] = $cat; }
if ($q)   { $where .= " AND (titulo LIKE :q OR resumo LIKE :q2)"; $params[':q'] = "%$q%"; $params[':q2'] = "%$q%"; }

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
<?php include __DIR__ . '/partials/_header.php'; ?>
<div style="padding-top:var(--nav-h)">
<section class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb"><a href="/atlas/">Início</a> <span>/</span> Blog & Notícias</div>
        <h1 style="color:var(--white);font-weight:900;">Blog & Notícias</h1>
        <p style="color:rgba(255,255,255,.7);font-size:16px;margin-top:8px;max-width:500px;">Artigos, análises e novidades dos nossos setores de atuação.</p>
    </div>
</section>

<section class="section section-light">
    <div class="container">
        <!-- Filtros (Estilo Omatapalo) -->
        <form method="GET" style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:48px;align-items:stretch;">
            <div style="position:relative;flex:1;min-width:280px;">
                <input type="text" name="q" placeholder="Pesquisar artigos..." value="<?= htmlspecialchars($q) ?>"
                       style="width:100%;height:100%;padding:14px 20px;border:1px solid var(--gray-200);border-radius:var(--radius);font-size:14px;outline:none;font-family:var(--font);color:var(--navy-dark);background:var(--white);">
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="/atlas/blog.php" style="display:inline-flex;align-items:center;padding:12px 24px;border:1px solid <?= !$cat ? 'var(--navy)' : 'var(--gray-200)' ?>;background:<?= !$cat ? 'var(--navy)' : 'var(--white)' ?>;color:<?= !$cat ? 'var(--white)' : 'var(--navy)' ?>;border-radius:var(--radius);font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;transition:var(--transition);">Todos</a>
                <?php foreach ($categorias as $c): ?>
                <a href="/atlas/blog.php?cat=<?= $c ?>" style="display:inline-flex;align-items:center;padding:12px 24px;border:1px solid <?= $cat === $c ? 'var(--navy)' : 'var(--gray-200)' ?>;background:<?= $cat === $c ? 'var(--navy)' : 'var(--white)' ?>;color:<?= $cat === $c ? 'var(--white)' : 'var(--navy)' ?>;border-radius:var(--radius);font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;transition:var(--transition);">
                    <?= iconeCategoria($c) ?> &nbsp; <?= labelCategoria($c) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </form>

        <?php if (empty($posts)): ?>
        <div style="text-align:center;padding:80px 20px;color:var(--gray-500);background:var(--white);border:1px solid var(--gray-100);">
            <div style="font-size:48px;margin-bottom:16px;">📭</div>
            <h3 style="color:var(--navy);margin-bottom:8px;font-weight:700;">Nenhum artigo encontrado</h3>
            <p>Tente outra pesquisa ou categoria.</p>
        </div>
        <?php else: ?>
        <div class="news-grid">
            <?php foreach ($posts as $post): ?>
            <article class="news-card">
                <div class="news-img-wrapper">
                    <?php if ($post['imagem_destaque']): ?>
                    <img src="<?= htmlspecialchars($post['imagem_destaque']) ?>" alt="<?= htmlspecialchars($post['titulo']) ?>">
                    <?php else: ?>
                    <div class="news-img-placeholder" style="background:var(--navy);color:rgba(255,255,255,.2);font-size:56px;display:flex;align-items:center;justify-content:center;">
                        <?= iconeCategoria($post['categoria']) ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="news-body">
                    <span class="news-category"><?= labelCategoria($post['categoria']) ?></span>
                    <h2 class="news-title" style="font-size:18px;font-weight:800;"><?= htmlspecialchars($post['titulo']) ?></h2>
                    <p class="news-excerpt"><?= htmlspecialchars($post['resumo'] ? $post['resumo'] : truncar(strip_tags($post['corpo']), 120)) ?></p>
                    <div class="news-footer">
                        <span class="news-date"><?= formatarData($post['criado_em']) ?></span>
                        <a href="/atlas/post.php?slug=<?= htmlspecialchars($post['slug']) ?>" class="news-link">Ler mais →</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        
        <?php if ($pag['total_paginas'] > 1): ?>
        <div class="pagination">
            <?php if ($pag['tem_anterior']): ?><a href="?p=<?= $paginaActual-1 ?>&cat=<?= $cat ?>&q=<?= urlencode($q) ?>">← Anterior</a><?php endif; ?>
            <?php for ($i = 1; $i <= $pag['total_paginas']; $i++): ?>
                <?php if ($i === $paginaActual): ?><span class="ativo"><?= $i ?></span>
                <?php else: ?><a href="?p=<?= $i ?>&cat=<?= $cat ?>&q=<?= urlencode($q) ?>"><?= $i ?></a><?php endif; ?>
            <?php endfor; ?>
            <?php if ($pag['tem_proximo']): ?><a href="?p=<?= $paginaActual+1 ?>&cat=<?= $cat ?>&q=<?= urlencode($q) ?>">Próximo →</a><?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
</div>
<?php include __DIR__ . '/partials/_footer.php'; ?>
