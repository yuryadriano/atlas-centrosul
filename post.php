<?php
require_once __DIR__ . '/app/helpers.php';
$pdo  = getDB();
$slug = sanitizar($_GET['slug'] ?? '');
if (empty($slug)) { header('Location: /atlas/blog.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM posts WHERE slug = :slug AND publicado = 1 LIMIT 1");
$stmt->execute([':slug' => $slug]);
$post = $stmt->fetch();
if (!$post) { header('HTTP/1.0 404 Not Found'); include __DIR__ . '/partials/_header.php'; echo '<div style="text-align:center;padding:200px 24px;"><h1>Artigo não encontrado</h1><a href="/atlas/blog.php" class="btn btn-navy" style="margin-top:20px;">← Voltar ao Blog</a></div>'; include __DIR__ . '/partials/_footer.php'; exit; }

// Posts relacionados
$relacionados = $pdo->prepare("SELECT * FROM posts WHERE categoria = :cat AND id != :id AND publicado = 1 ORDER BY criado_em DESC LIMIT 3");
$relacionados->execute([':cat' => $post['categoria'], ':id' => $post['id']]);
$relacionados = $relacionados->fetchAll();

$pageTitle = $post['titulo'];
$metaDesc  = $post['resumo'] ? $post['resumo'] : truncar(strip_tags($post['corpo']), 160);
?>
<?php include __DIR__ . '/partials/_header.php'; ?>
<div style="padding-top:var(--nav-h);">

<div class="breadcrumb-bar">
    <div class="container">
        <nav class="breadcrumb">
            <a href="/atlas/">Início</a> <span>/</span>
            <a href="/atlas/blog.php">Blog</a> <span>/</span>
            <span><?= htmlspecialchars(truncar($post['titulo'], 50)) ?></span>
        </nav>
    </div>
</div>

<!-- Post Layout style Boa Vida -->
<section style="background:var(--white); padding: 80px 0 40px;">
    <div class="container" style="max-width:760px; text-align: center;">
        <span class="news-category" style="color:var(--gold); font-size: 11px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 24px; display: inline-block;">
            <?= labelCategoria($post['categoria']) ?>
        </span>
        <h1 style="color:var(--navy); font-weight: 600; line-height: 1.3; margin-bottom: 24px; font-size: clamp(1.8rem, 4vw, 2.6rem);">
            <?= htmlspecialchars($post['titulo']) ?>
        </h1>
        <div style="width: 40px; height: 2px; background: var(--gold); margin: 0 auto 24px; border-radius: 2px;"></div>
        <p style="color:var(--gray-500); font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">
            <?= formatarData($post['criado_em']) ?>
        </p>
    </div>
</section>

<?php if ($post['imagem_destaque']): ?>
<div style="background:var(--white); padding-bottom: 20px;">
    <div class="container" style="max-width:900px; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md);">
        <img src="<?= htmlspecialchars($post['imagem_destaque']) ?>" alt="<?= htmlspecialchars($post['titulo']) ?>" style="width: 100%; height: auto; max-height: 520px; object-fit: cover; display: block;">
    </div>
</div>
<?php endif; ?>

<!-- Conteúdo -->
<div style="background:var(--off-white);padding:60px 0;">
    <div class="container" style="max-width:860px;">
        <div style="background:#fff;border-radius:var(--radius-lg);padding:48px;box-shadow:var(--shadow);">
            <div style="font-size:16px;line-height:1.85;color:var(--gray-700);max-width:100%;" class="post-content">
                <?= $post['corpo'] ?>
            </div>

            <!-- Partilhar -->
            <div style="margin-top:48px;padding-top:32px;border-top:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                <div>
                    <p style="font-size:13px;font-weight:700;color:var(--navy);margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;">Partilhar este artigo:</p>
                    <div style="display:flex;gap:8px;">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" target="_blank" class="btn btn-navy btn-sm" style="border-radius:var(--radius);">Facebook</a>
                        <a href="https://wa.me/?text=<?= urlencode($post['titulo'] . ' — http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" target="_blank" class="btn btn-navy btn-sm" style="border-radius:var(--radius);">WhatsApp</a>
                    </div>
                </div>
                <a href="/atlas/blog.php" class="btn btn-navy btn-sm" style="border-radius:var(--radius);">← Voltar ao Blog</a>
            </div>
        </div>
    </div>
</div>

<!-- Relacionados -->
<?php if (!empty($relacionados)): ?>
<section class="section" style="padding-top: 60px; background: var(--white); border-top: 1px solid var(--gray-100);">
    <div class="container" style="max-width:900px;">
        <h2 style="font-size:22px; font-weight: 600; color:var(--navy); margin-bottom:28px; text-align: center;">Artigos Relacionados</h2>
        <div class="news-grid">
            <?php foreach ($relacionados as $r): ?>
            <article class="news-card">
                <div class="news-img-wrapper">
                    <?php if ($r['imagem_destaque']): ?>
                    <img src="<?= htmlspecialchars($r['imagem_destaque']) ?>" alt="<?= htmlspecialchars($r['titulo']) ?>">
                    <?php else: ?>
                    <div class="news-img-placeholder" style="background:var(--navy);color:rgba(255,255,255,.2);font-size:48px;display:flex;align-items:center;justify-content:center;">
                        <?= iconeCategoria($r['categoria']) ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="news-body">
                    <h3 class="news-title" style="font-size:15px;"><?= htmlspecialchars($r['titulo']) ?></h3>
                    <div class="news-footer">
                        <span class="news-date"><?= formatarData($r['criado_em']) ?></span>
                        <a href="/atlas/post.php?slug=<?= htmlspecialchars($r['slug']) ?>" class="news-link">Ler →</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

</div>
<?php include __DIR__ . '/partials/_footer.php'; ?>
<style>
.post-content h2, .post-content h3 { color:var(--navy); margin:36px 0 16px; font-weight:800; }
.post-content p { margin-bottom:20px; }
.post-content img { border-radius:var(--radius); max-width:100%; margin:24px 0; }
.post-content a { color:var(--navy); text-decoration:underline; font-weight:600; }
.post-content ul, .post-content ol { padding-left:24px; margin-bottom:20px; }
.post-content li { margin-bottom:8px; color:var(--gray-700); }
.post-content blockquote { border-left:4px solid var(--gold); padding:16px 24px; margin:24px 0; background:var(--off-white); border-radius:var(--radius); color:var(--navy-dark); font-style:italic; }
</style>
