<?php
require_once __DIR__ . '/app/helpers.php';
$pageTitle = 'Galeria';
$metaDesc  = 'Galeria de fotos e vídeos dos projetos e atividades da Atlas Centro Sul.';
$navSolid  = true;
$pdo       = getDB();

$filtroTipo = sanitizar($_GET['tipo'] ?? '');
$filtrocat  = sanitizar($_GET['cat'] ?? '');
$paginaActual = max(1, (int)($_GET['p'] ?? 1));
$porPagina    = 12;

$where  = "WHERE 1=1";
$params = [];
if ($filtroTipo) { $where .= " AND tipo = :tipo"; $params[':tipo'] = $filtroTipo; }
if ($filtrocat)  { $where .= " AND categoria = :cat"; $params[':cat'] = $filtrocat; }

$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM midia $where");
$totalStmt->execute($params);
$total = (int)$totalStmt->fetchColumn();
$pag   = paginar($total, $porPagina, $paginaActual);

$params[':limit']  = $porPagina;
$params[':offset'] = $pag['offset'];
$stmt = $pdo->prepare("SELECT * FROM midia $where ORDER BY destaque DESC, criado_em DESC LIMIT :limit OFFSET :offset");
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$items = $stmt->fetchAll();

$categorias = ['energia-industria','saude-bem-estar','agronegocio','comercio-investimentos','geral'];
?>
<?php include __DIR__ . '/partials/_header.php'; ?>
<div style="padding-top:var(--nav-h);">

<section class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb"><a href="/atlas/">Início</a> <span>/</span> Galeria</div>
        <h1 style="color:var(--white);font-weight:900;">Galeria Multimédia</h1>
        <p style="color:rgba(255,255,255,.7);font-size:16px;margin-top:8px;max-width:500px;">Fotos e vídeos dos nossos projetos, atividades e conquistas.</p>
    </div>
</section>

<section class="section section-light">
    <div class="container">
        <!-- Filtros (Estilo Omatapalo) -->
        <div style="display:flex;gap:8px;margin-bottom:40px;flex-wrap:wrap;align-items:center;">
            <a href="/atlas/galeria.php" style="display:inline-flex;align-items:center;padding:12px 24px;border:1px solid <?= !$filtroTipo && !$filtrocat ? 'var(--navy)' : 'var(--gray-200)' ?>;background:<?= !$filtroTipo && !$filtrocat ? 'var(--navy)' : 'var(--white)' ?>;color:<?= !$filtroTipo && !$filtrocat ? 'var(--white)' : 'var(--navy)' ?>;border-radius:var(--radius);font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;transition:var(--transition);">Todos</a>
            <a href="/atlas/galeria.php?tipo=foto" style="display:inline-flex;align-items:center;padding:12px 24px;border:1px solid <?= $filtroTipo === 'foto' ? 'var(--navy)' : 'var(--gray-200)' ?>;background:<?= $filtroTipo === 'foto' ? 'var(--navy)' : 'var(--white)' ?>;color:<?= $filtroTipo === 'foto' ? 'var(--white)' : 'var(--navy)' ?>;border-radius:var(--radius);font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;transition:var(--transition);">📷 Fotos</a>
            <a href="/atlas/galeria.php?tipo=video" style="display:inline-flex;align-items:center;padding:12px 24px;border:1px solid <?= $filtroTipo === 'video' ? 'var(--navy)' : 'var(--gray-200)' ?>;background:<?= $filtroTipo === 'video' ? 'var(--navy)' : 'var(--white)' ?>;color:<?= $filtroTipo === 'video' ? 'var(--white)' : 'var(--navy)' ?>;border-radius:var(--radius);font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;transition:var(--transition);">🎥 Vídeos</a>
            <span style="width:1px;background:var(--gray-200);margin:0 8px;align-self:stretch;"></span>
            <?php foreach ($categorias as $cat): ?>
            <a href="/atlas/galeria.php?cat=<?= $cat ?>" style="display:inline-flex;align-items:center;padding:12px 24px;border:1px solid <?= $filtrocat === $cat ? 'var(--gold)' : 'var(--gray-200)' ?>;background:<?= $filtrocat === $cat ? 'var(--gold)' : 'var(--white)' ?>;color:<?= $filtrocat === $cat ? 'var(--navy-dark)' : 'var(--navy)' ?>;border-radius:var(--radius);font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;transition:var(--transition);">
                <?= iconeCategoria($cat) ?> &nbsp; <?= labelCategoria($cat) ?>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($items)): ?>
        <div style="text-align:center;padding:80px 20px;color:var(--gray-500);background:var(--white);border:1px solid var(--gray-100);">
            <div style="font-size:56px;margin-bottom:16px;">🖼️</div>
            <h3 style="color:var(--navy);margin-bottom:8px;font-weight:700;">Nenhum conteúdo encontrado</h3>
            <p>Tente outro filtro ou volte mais tarde.</p>
        </div>
        <?php else: ?>
        <!-- Grid masonry-like -->
        <div class="gallery-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
            <?php foreach ($items as $i => $item): ?>
            <?php $span = ($i === 0 || $i % 7 === 0) ? 'grid-column:span 2;aspect-ratio:16/9;' : 'aspect-ratio:4/3;'; ?>
            <?php if ($item['tipo'] === 'video'): ?>
            <div style="<?= $span ?>border-radius:var(--radius);overflow:hidden;background:var(--navy-dark);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;cursor:pointer;"
                 onclick="abrirVideo('<?= htmlspecialchars($item['url_ou_caminho']) ?>', '<?= htmlspecialchars(addslashes($item['titulo'])) ?>')">
                <div style="width:64px;height:64px;background:rgba(200,169,81,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;transition:.3s;">▶</div>
                <p style="color:rgba(255,255,255,.7);font-size:13px;text-align:center;max-width:200px;padding:0 16px;font-weight:600;"><?= htmlspecialchars(truncar($item['titulo'], 50)) ?></p>
                <?php if ($item['descricao']): ?><p style="color:rgba(255,255,255,.4);font-size:12px;text-align:center;max-width:180px;"><?= htmlspecialchars(truncar($item['descricao'], 60)) ?></p><?php endif; ?>
            </div>
            <?php else: ?>
            <div style="<?= $span ?>border-radius:var(--radius);overflow:hidden;position:relative;background:var(--gray-100);">
                <img src="<?= htmlspecialchars($item['url_ou_caminho']) ?>" alt="<?= htmlspecialchars($item['titulo']) ?>"
                     style="width:100%;height:100%;object-fit:cover;transition:transform .5s ease;" loading="lazy"
                     onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform=''">
                <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(13,27,53,.75),transparent 50%);display:flex;align-items:flex-end;padding:16px;opacity:0;transition:.3s;"
                     onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
                    <div>
                        <p style="color:#white;font-weight:700;font-size:14px;"><?= htmlspecialchars(truncar($item['titulo'],40)) ?></p>
                        <p style="color:rgba(255,255,255,.7);font-size:12px;font-weight:600;"><?= labelCategoria($item['categoria']) ?></p>
                    </div>
                </div>
                <?php if ($item['destaque']): ?>
                <div style="position:absolute;top:10px;left:10px;background:var(--gold);color:var(--navy-dark);padding:4px 12px;border-radius:var(--radius);font-size:11px;font-weight:700;text-transform:uppercase;">⭐ Destaque</div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- Paginação -->
        <?php if ($pag['total_paginas'] > 1): ?>
        <div class="pagination">
            <?php if ($pag['tem_anterior']): ?><a href="?p=<?= $paginaActual-1 ?>&tipo=<?= $filtroTipo ?>&cat=<?= $filtrocat ?>">← Anterior</a><?php endif; ?>
            <?php for ($i = 1; $i <= $pag['total_paginas']; $i++): ?>
                <?php if ($i === $paginaActual): ?><span class="ativo"><?= $i ?></span>
                <?php else: ?><a href="?p=<?= $i ?>&tipo=<?= $filtroTipo ?>&cat=<?= $filtrocat ?>"><?= $i ?></a><?php endif; ?>
            <?php endfor; ?>
            <?php if ($pag['tem_proximo']): ?><a href="?p=<?= $paginaActual+1 ?>&tipo=<?= $filtroTipo ?>&cat=<?= $filtrocat ?>">Próximo →</a><?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
</div>

<!-- Modal Vídeo -->
<div id="videoModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:9999;align-items:center;justify-content:center;">
    <div style="position:relative;width:90%;max-width:900px;">
        <button onclick="fecharVideo()" style="position:absolute;top:-48px;right:0;background:none;border:none;color:#fff;font-size:28px;cursor:pointer;">✕</button>
        <iframe id="videoFrame" width="100%" height="505" frameborder="0" allow="autoplay;encrypted-media" allowfullscreen style="border-radius:var(--radius);"></iframe>
    </div>
</div>

<?php include __DIR__ . '/partials/_footer.php'; ?>
