<?php
/**
 * Gera uma página de serviço a partir de um config array
 * Incluído pelas 4 páginas de serviço
 */

$pdo = getDB();

// Posts da categoria
$postsServico = $pdo->prepare("SELECT * FROM posts WHERE categoria = :cat AND publicado = 1 ORDER BY criado_em DESC LIMIT 3");
$postsServico->execute([':cat' => $servicoSlug]);
$postsServico = $postsServico->fetchAll();

// Média da categoria
$midiaServico = $pdo->prepare("SELECT * FROM midia WHERE categoria = :cat ORDER BY criado_em DESC LIMIT 6");
$midiaServico->execute([':cat' => $servicoSlug]);
$midiaServico = $midiaServico->fetchAll();

$pageTitle = $servicoTitulo;
$metaDesc  = $servicoDesc;
$navSolid  = true;
?>
<?php include __DIR__ . '/../partials/_header.php'; ?>
<div style="padding-top:var(--nav-h);">

<!-- Hero do Serviço -->
<section class="page-hero" style="background:var(--navy-dark);min-height:400px;display:flex;align-items:center;">
    <div style="position:absolute;inset:0;background:<?= $servicoGradiente ?? 'linear-gradient(135deg,#0D1B35,#1B2D5B)' ?>;opacity:.55;"></div>
    <div class="container page-hero-content" style="position:relative;z-index:1;width:100%;">
        <div class="breadcrumb">
            <a href="/atlas/">Início</a> <span>/</span>
            <a href="/atlas/#ecossistema">Serviços</a> <span>/</span>
            <span><?= htmlspecialchars($servicoTitulo) ?></span>
        </div>
        <div style="font-size:56px;margin-bottom:16px;line-height:1;"><?= $servicoIcone ?></div>
        <h1 style="color:#fff;font-weight:900;"><?= htmlspecialchars($servicoTitulo) ?></h1>
        <p style="color:rgba(255,255,255,.75);font-size:16px;margin-top:12px;max-width:560px;line-height:1.75;">
            <?= htmlspecialchars($servicoDesc) ?>
        </p>
        <a href="/atlas/contacto.php" class="btn btn-gold" style="margin-top:28px;border-radius:var(--radius);">Solicitar Proposta →</a>
    </div>
</section>

<!-- O que oferecemos -->
<section class="section">
    <div class="container">
        <div style="text-align:center;margin-bottom:56px;">
            <span class="section-eyebrow"><?= $servicoTitulo ?></span>
            <h2 class="section-title">O Que Oferecemos</h2>
            <p class="section-lead" style="margin:0 auto;text-align:center;"><?= htmlspecialchars($servicoIntro) ?></p>
        </div>
        <div class="card-grid-3">
            <?php foreach ($servicoServicos as $s): ?>
            <div class="feature-card">
                <div class="feature-icon"><?= $s['icone'] ?></div>
                <h3><?= htmlspecialchars($s['titulo']) ?></h3>
                <p><?= htmlspecialchars($s['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Porque escolher a Atlas (Split) -->
<section class="section section-light">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;">
            <div>
                <span class="section-eyebrow">Vantagens</span>
                <h2 class="section-title">Porque Escolher a Atlas?</h2>
                <div style="display:flex;flex-direction:column;gap:20px;margin-top:28px;">
                    <?php foreach ($servicoVantagens as $v): ?>
                    <div style="display:flex;gap:16px;align-items:flex-start;">
                        <div style="width:44px;height:44px;background:rgba(26,42,94,.06);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;color:var(--navy);"><?= $v['icone'] ?></div>
                        <div>
                            <h4 style="font-size:15px;font-weight:700;color:var(--navy);margin-bottom:4px;"><?= htmlspecialchars($v['titulo']) ?></h4>
                            <p style="font-size:14px;color:var(--gray-500);line-height:1.6;"><?= htmlspecialchars($v['desc']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));border-radius:var(--radius-lg);height:380px;display:flex;align-items:center;justify-content:center;box-shadow:var(--shadow-md);position:relative;overflow:hidden;">
                <div style="position:absolute;inset:0;opacity:.03;background-image:repeating-linear-gradient(0deg,transparent,transparent 30px,rgba(255,255,255,1) 30px,rgba(255,255,255,1) 31px),repeating-linear-gradient(90deg,transparent,transparent 30px,rgba(255,255,255,1) 30px,rgba(255,255,255,1) 31px);"></div>
                <span style="font-size:120px;opacity:.25;z-index:1;"><?= $servicoIcone ?></span>
            </div>
        </div>
    </div>
</section>

<!-- Mídia da categoria -->
<?php if (!empty($midiaServico)): ?>
<section class="section">
    <div class="container">
        <span class="section-eyebrow">Multimédia</span>
        <h2 class="section-title">Galeria — <?= htmlspecialchars($servicoTitulo) ?></h2>
        <div class="gallery-grid" style="margin-top:32px;">
            <?php foreach (array_slice($midiaServico, 0, 6) as $item): ?>
            <?php if ($item['tipo'] === 'video'): ?>
            <div class="gallery-item gallery-video" onclick="abrirVideo('<?= htmlspecialchars($item['url_ou_caminho']) ?>', '<?= htmlspecialchars($item['titulo']) ?>')">
                <div class="play-btn">▶</div>
                <p style="color:rgba(255,255,255,.7);font-size:12px;text-align:center;max-width:150px;margin-top:8px;font-weight:600;"><?= htmlspecialchars(truncar($item['titulo'],40)) ?></p>
            </div>
            <?php else: ?>
            <div class="gallery-item">
                <img src="<?= htmlspecialchars($item['url_ou_caminho']) ?>" alt="<?= htmlspecialchars($item['titulo']) ?>" loading="lazy">
                <div class="gallery-item-overlay"><span class="gallery-item-label"><?= htmlspecialchars(truncar($item['titulo'],40)) ?></span></div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Últimas notícias da categoria -->
<?php if (!empty($postsServico)): ?>
<section class="section section-light">
    <div class="container">
        <span class="section-eyebrow">Blog</span>
        <h2 class="section-title">Últimas Notícias — <?= htmlspecialchars($servicoTitulo) ?></h2>
        <div class="news-grid" style="margin-top:32px;">
            <?php foreach ($postsServico as $post): ?>
            <article class="news-card">
                <div class="news-img-wrapper">
                    <?php if ($post['imagem_destaque']): ?>
                    <img src="<?= htmlspecialchars($post['imagem_destaque']) ?>" alt="">
                    <?php else: ?>
                    <div class="news-img-placeholder" style="background:var(--navy);color:rgba(255,255,255,.2);font-size:48px;display:flex;align-items:center;justify-content:center;"><?= $servicoIcone ?></div>
                    <?php endif; ?>
                </div>
                <div class="news-body">
                    <h3 class="news-title"><?= htmlspecialchars($post['titulo']) ?></h3>
                    <p class="news-excerpt"><?= htmlspecialchars(truncar($post['resumo'] ?: strip_tags($post['corpo']), 100)) ?></p>
                    <div class="news-footer">
                        <span class="news-date"><?= formatarData($post['criado_em']) ?></span>
                        <a href="/atlas/post.php?slug=<?= htmlspecialchars($post['slug']) ?>" class="news-link">Ler →</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA (Estilo Omatapalo) -->
<section class="section section-navy" style="padding:88px 0;text-align:center;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;opacity:.03;background-image:repeating-linear-gradient(0deg,transparent,transparent 40px,rgba(255,255,255,1) 40px,rgba(255,255,255,1) 41px),repeating-linear-gradient(90deg,transparent,transparent 40px,rgba(255,255,255,1) 40px,rgba(255,255,255,1) 41px);"></div>
    <div class="container" style="position:relative;z-index:1;">
        <span style="font-size:48px;display:block;margin-bottom:20px;line-height:1;"><?= $servicoIcone ?></span>
        <h2 style="color:#fff;font-size:2.2rem;margin-bottom:16px;font-weight:900;">Pronto para avançar?</h2>
        <p style="color:rgba(255,255,255,.65);font-size:16px;max-width:480px;margin:0 auto 36px;line-height:1.7;">
            A equipa da Atlas Centro Sul está disponível para apresentar soluções personalizadas na área de <?= strtolower(htmlspecialchars($servicoTitulo)) ?>.
        </p>
        <a href="/atlas/contacto.php" class="btn btn-gold" style="font-size:14px;padding:16px 40px;border-radius:var(--radius);">Contacte-nos Hoje →</a>
    </div>
</section>

<!-- Modal Vídeo -->
<div id="videoModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:9999;align-items:center;justify-content:center;">
    <div style="position:relative;width:90%;max-width:900px;">
        <button onclick="fecharVideo()" style="position:absolute;top:-48px;right:0;background:none;border:none;color:#fff;font-size:28px;cursor:pointer;">✕</button>
        <iframe id="videoFrame" width="100%" height="505" frameborder="0" allow="autoplay;encrypted-media" allowfullscreen style="border-radius:var(--radius);"></iframe>
    </div>
</div>

</div>
<?php include __DIR__ . '/../partials/_footer.php'; ?>
