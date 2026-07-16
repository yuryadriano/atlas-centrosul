<?php
/**
 * Atlas Centro Sul — Home Page
 * Inspirado em: omatapalo.com
 */
require_once __DIR__ . '/app/helpers.php';

$pdo = getDB();
$posts = $pdo->query("SELECT * FROM posts WHERE publicado = 1 ORDER BY criado_em DESC LIMIT 3")->fetchAll();
$midiaDestaque = $pdo->query("SELECT * FROM midia WHERE destaque = 1 ORDER BY criado_em DESC LIMIT 6")->fetchAll();

$pageTitle = null;
$metaDesc  = config('sobre_resumo', 'Atlas Centro Sul — Uma Marca, Múltiplas Soluções. Energia, Saúde, Agronegócio e Comércio em Angola.');
$videoYoutube = config('video_homepage'); // ex: "https://www.youtube.com/embed/XXXXXX?autoplay=1&mute=1&loop=1&playlist=XXXXXX&controls=0"

$flash = null;
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formulario_contacto'])) {
    $nome     = sanitizar($_POST['nome'] ?? '');
    $email    = sanitizar($_POST['email'] ?? '');
    $telefone = sanitizar($_POST['telefone'] ?? '');
    $assunto  = sanitizar($_POST['assunto'] ?? '');
    $mensagem = sanitizar($_POST['mensagem'] ?? '');

    if (empty($nome))     $erros[] = 'O seu nome é obrigatório.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = 'Email inválido.';
    if (empty($mensagem)) $erros[] = 'A mensagem não pode estar em branco.';

    if (empty($erros)) {
        $stmt = $pdo->prepare("INSERT INTO mensagens (nome,email,telefone,assunto,mensagem) VALUES (:nome,:email,:tel,:assunto,:msg)");
        $stmt->execute([':nome'=>$nome,':email'=>$email,':tel'=>$telefone,':assunto'=>$assunto,':msg'=>$mensagem]);
        $flash = ['tipo'=>'success', 'mensagem'=>'✅ Mensagem enviada! Responderemos o mais brevemente possível.'];
    }
}
?>
<?php include __DIR__ . '/partials/_header.php'; ?>

<main>

<!-- ══════════════════════════════════════════════
     HERO — Vídeo/Fundo fullscreen (estilo Omatapalo)
     ══════════════════════════════════════════════ -->
<?php
// Tratar slogan dinâmico para manter estilo de quebra e cor na segunda parte
$slogan1 = config('site_slogan_1');
$slogan2 = config('site_slogan_2');
if (empty($slogan1) && empty($slogan2)) {
    $slogan = config('site_slogan', 'Uma Marca, Múltiplas Soluções.');
    $partes = explode(',', $slogan, 2);
    $slogan1 = trim($partes[0] ?? 'Uma Marca');
    $slogan2 = trim($partes[1] ?? 'Múltiplas Soluções.');
}
?>
<?php if ($videoYoutube): ?>
<section class="hero-video" id="hero">
    <div class="hero-video-bg">
        <iframe
            src="<?= htmlspecialchars($videoYoutube) ?>&controls=0&disablekb=1&playsinline=1&rel=0&showinfo=0&modestbranding=1"
            allow="autoplay; encrypted-media"
            allowfullscreen
            frameborder="0">
        </iframe>
    </div>
    <div class="hero-video-overlay"></div>
    <div class="hero-video-content fade-up">
        <div style="font-size:12px;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:var(--gold);margin-bottom:24px;">
            Huambo · Angola
        </div>
        <h1>
            <?= htmlspecialchars($slogan1) ?><?php if ($slogan2): ?>,<br><span><?= htmlspecialchars($slogan2) ?></span><?php endif; ?>
        </h1>
        <p><?= htmlspecialchars(config('hero_subtitulo', 'A Atlas Centro Sul atua nos setores de energia, saúde, agronegócio e comércio — criando valor sustentável para Angola.')) ?></p>
        <div style="display:flex;gap:16px;flex-wrap:wrap;">
            <a href="/atlas/sobre.php" class="btn btn-white">Conhecer a Atlas</a>
            <a href="/atlas/contacto.php" class="btn btn-outline-white">Contacto</a>
        </div>
    </div>
    <div class="hero-scroll-hint">Scroll</div>
</section>
<?php else: ?>
<!-- Fallback: Hero estático sem vídeo -->
<section class="hero-static" id="hero">
    <div class="hero-video-overlay" style="background:linear-gradient(to bottom,rgba(0,26,53,.2) 0%,rgba(0,26,53,.6) 60%,rgba(0,26,53,.95) 100%);"></div>
    <div class="hero-video-content fade-up" style="position:relative;z-index:2;">
        <div style="font-size:12px;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:var(--gold);margin-bottom:24px;">
            Huambo · Angola
        </div>
        <h1>
            <?= htmlspecialchars($slogan1) ?><?php if ($slogan2): ?>,<br><span style="color:var(--gold);"><?= htmlspecialchars($slogan2) ?></span><?php endif; ?>
        </h1>
        <p><?= htmlspecialchars(config('hero_subtitulo', 'A Atlas Centro Sul atua nos setores de energia & indústria, saúde & bem-estar, agronegócio e comércio — criando valor sustentável para Angola.')) ?></p>
        <div style="display:flex;gap:16px;flex-wrap:wrap;">
            <a href="/atlas/sobre.php" class="btn btn-white" style="border-radius:var(--radius);">Conhecer a Atlas</a>
            <a href="/atlas/contacto.php" class="btn btn-outline-white" style="border-radius:var(--radius);">Contacto</a>
        </div>
    </div>
    <div class="hero-scroll-hint">Scroll</div>
</section>
<?php endif; ?>


<!-- ══════════════════════════════════════════════
     MARQUEE — ticker de setores (estilo Omatapalo)
     ══════════════════════════════════════════════ -->
<div class="marquee-bar" aria-hidden="true">
    <div class="marquee-track">
        <!-- Duplicado para loop contínuo -->
        <?php
        $items = [
            ['Energia & Indústria', '⚙️'],
            ['Saúde & Bem-Estar', '🏥'],
            ['Agronegócio', '🌾'],
            ['Comércio & Investimentos', '🏢'],
            ['Huambo · Angola', '🌍'],
            ['Fazemos Acontecer', '✦'],
        ];
        // Repete 4x para o loop funcionar
        for ($rep = 0; $rep < 4; $rep++):
            foreach ($items as $it): ?>
        <span class="marquee-item">
            <span><?= $it[0] ?></span>
            <span class="marquee-sep"><?= $it[1] ?></span>
        </span>
        <?php endforeach;
        endfor; ?>
    </div>
</div>


<!-- ══════════════════════════════════════════════
     SOBRE — Split 50/50 (estilo Omatapalo)
     ══════════════════════════════════════════════ -->
<div class="split-section" id="sobre">
    <!-- Visual: logo/imagem no lado esquerdo -->
    <div class="split-visual">
        <div class="split-visual-logo">
            <img src="/atlas/assets/img/logo.png" alt="Atlas Centro Sul">
        </div>
    </div>
    <!-- Texto no lado direito -->
    <div class="split-content" style="background:var(--off-white);">
        <span class="section-eyebrow"><?= htmlspecialchars(config('home_sobre_subtitulo', 'Quem Somos')) ?></span>
        <h2 class="section-title" style="margin-bottom:24px;">
            <?= nl2br(htmlspecialchars(config('home_sobre_titulo', "A Atlas\nCentro Sul"))) ?>
        </h2>
        <p style="font-size:16px;line-height:1.85;margin-bottom:20px;white-space:pre-line;"><?= htmlspecialchars(config('home_sobre_texto_1', 'Fundada em 2025 em Huambo, a Atlas Centro Sul — Comércio e Serviços, Lda nasceu da visão de empreendedores angolanos determinados a criar uma empresa de referência no centro-sul de Angola.')) ?></p>
        <p style="font-size:15px;color:var(--gray-500);line-height:1.8;margin-bottom:32px;white-space:pre-line;"><?= htmlspecialchars(config('home_sobre_texto_2', 'Duas palavras dão vida à nossa cultura: Fazemos Acontecer! Assumimos um papel activo na construção de uma Angola mais sustentável e com mais serviços.')) ?></p>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <a href="/atlas/sobre.php" class="btn btn-navy">A Nossa História</a>
            <a href="/atlas/contacto.php" class="btn btn-outline-navy">Contacto</a>
        </div>
    </div>
</div>


<!-- ══════════════════════════════════════════════
     STATS BAR — Números impactantes
     ══════════════════════════════════════════════ -->
<div class="stats-bar">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-icon">⚙️</span>
                <span class="stat-num counter" data-target="4">4</span>
                <span class="stat-label">Pilares Estratégicos</span>
            </div>
            <div class="stat-item">
                <span class="stat-icon">🌍</span>
                <span class="stat-num">Huambo</span>
                <span class="stat-label">Centro-Sul de Angola</span>
            </div>
            <div class="stat-item">
                <span class="stat-icon">🏢</span>
                <span class="stat-num counter" data-target="2">2</span>
                <span class="stat-label">Gerentes Fundadores</span>
            </div>
            <div class="stat-item">
                <span class="stat-icon">📅</span>
                <span class="stat-num"><?= htmlspecialchars(config('ano_fundacao', '2025')) ?></span>
                <span class="stat-label">Ano de Fundação</span>
            </div>
        </div>
    </div>
</div>


<!-- ══════════════════════════════════════════════
     OS 4 PILARES — Grid estilo Omatapalo
     ══════════════════════════════════════════════ -->
<section class="section section-white" id="ecossistema">
    <div class="container">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:56px;gap:24px;flex-wrap:wrap;">
            <div>
                <span class="section-eyebrow"><?= htmlspecialchars(config('home_pilares_subtitulo', 'Ecossistema Atlas')) ?></span>
                <h2 class="section-title"><?= nl2br(htmlspecialchars(config('home_pilares_titulo', "Os 4 Pilares\nEstratégicos"))) ?></h2>
            </div>
            <p class="section-lead" style="max-width:420px;white-space:pre-line;"><?= htmlspecialchars(config('home_pilares_lead', 'Atuamos em setores complementares que se fortalecem mutuamente — da energia ao campo, da saúde ao comércio.')) ?></p>
        </div>
        <div class="pilares-grid">
            <a href="/atlas/servicos/energia-industria.php" class="pilar-card">
                <span class="pilar-num">Pilar 01</span>
                <div class="pilar-icon-wrap"><?= htmlspecialchars(config('home_pilar1_icone', '⚙️')) ?></div>
                <h3 class="pilar-title"><?= nl2br(htmlspecialchars(config('home_pilar1_titulo', "Energia &\nIndústria"))) ?></h3>
                <p class="pilar-desc"><?= htmlspecialchars(config('home_pilar1_desc', 'Manutenção eletromecânica, soldadura, pintura industrial e apoio técnico ao sector petrolífero onshore e offshore.')) ?></p>
                <span class="pilar-link">Saber mais →</span>
            </a>
            <a href="/atlas/servicos/saude-bem-estar.php" class="pilar-card">
                <span class="pilar-num">Pilar 02</span>
                <div class="pilar-icon-wrap"><?= htmlspecialchars(config('home_pilar2_icone', '🏥')) ?></div>
                <h3 class="pilar-title"><?= nl2br(htmlspecialchars(config('home_pilar2_titulo', "Saúde &\nBem-Estar"))) ?></h3>
                <p class="pilar-desc"><?= htmlspecialchars(config('home_pilar2_desc', 'Clínicas, fisioterapia, laboratórios e fornecimento de equipamentos médicos e hospitalares de alta qualidade.')) ?></p>
                <span class="pilar-link">Saber mais →</span>
            </a>
            <a href="/atlas/servicos/agronegocio.php" class="pilar-card">
                <span class="pilar-num">Pilar 03</span>
                <div class="pilar-icon-wrap"><?= htmlspecialchars(config('home_pilar3_icone', '🌾')) ?></div>
                <h3 class="pilar-title"><?= nl2br(htmlspecialchars(config('home_pilar3_titulo', "Agronegócio"))) ?></h3>
                <p class="pilar-desc"><?= htmlspecialchars(config('home_pilar3_desc', 'Produção, transformação e comercialização de produtos agrícolas — do campo ao mercado, contribuindo para a segurança alimentar.')) ?></p>
                <span class="pilar-link">Saber mais →</span>
            </a>
            <a href="/atlas/servicos/comercio-investimentos.php" class="pilar-card">
                <span class="pilar-num">Pilar 04</span>
                <div class="pilar-icon-wrap"><?= htmlspecialchars(config('home_pilar4_icone', '🏢')) ?></div>
                <h3 class="pilar-title"><?= nl2br(htmlspecialchars(config('home_pilar4_titulo', "Comércio &\nInvestimentos"))) ?></h3>
                <p class="pilar-desc"><?= htmlspecialchars(config('home_pilar4_desc', 'Comércio geral de bens e serviços e gestão de participações sociais em empresas parceiras do ecossistema Atlas.')) ?></p>
                <span class="pilar-link">Saber mais →</span>
            </a>
        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════════
     FRASE MANIFESTO (estilo Omatapalo "Fazemos Acontecer")
     ══════════════════════════════════════════════ -->
<section style="background:var(--navy);padding:120px 0;text-align:center;overflow:hidden;position:relative;">
    <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(0deg,transparent,transparent 60px,rgba(255,255,255,1) 60px,rgba(255,255,255,1) 61px),repeating-linear-gradient(90deg,transparent,transparent 60px,rgba(255,255,255,1) 60px,rgba(255,255,255,1) 61px);"></div>
    <div class="container" style="position:relative;z-index:1;">
        <p style="font-size:12px;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:var(--gold);margin-bottom:24px;">
            <?= htmlspecialchars(config('manifesto_subtitulo', 'A Nossa Cultura')) ?>
        </p>
        <h2 style="font-size:clamp(3rem,7vw,7rem);font-weight:900;color:var(--white);letter-spacing:-.04em;line-height:.95;">
            <?= nl2br(htmlspecialchars(config('manifesto_titulo', "Fazemos\nAcontecer!"))) ?>
        </h2>
        <p style="color:rgba(255,255,255,.5);font-size:16px;margin-top:32px;max-width:580px;margin-left:auto;margin-right:auto;line-height:1.8;">
            <?= htmlspecialchars(config('manifesto_texto', 'Assumimos um papel activo na construção de uma Angola mais sustentável, mais próxima e com mais serviços.')) ?>
        </p>
    </div>
</section>


<!-- ══════════════════════════════════════════════
     GALERIA (se existir)
     ══════════════════════════════════════════════ -->
<?php if (!empty($midiaDestaque)): ?>
<section class="section section-light" id="galeria">
    <div class="container">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:0;gap:24px;flex-wrap:wrap;">
            <div>
                <span class="section-eyebrow">Multimédia</span>
                <h2 class="section-title">Galeria de Projetos</h2>
            </div>
            <a href="/atlas/galeria.php" class="btn btn-navy btn-sm">Ver Galeria Completa →</a>
        </div>
        <div class="gallery-grid">
            <?php foreach (array_slice($midiaDestaque, 0, 6) as $i => $item): ?>
                <?php if ($item['tipo'] === 'video'): ?>
                <div class="gallery-item gallery-video" onclick="abrirVideo('<?= htmlspecialchars($item['url_ou_caminho']) ?>', '<?= htmlspecialchars(addslashes($item['titulo'])) ?>')">
                    <div class="play-btn">▶</div>
                    <p style="color:rgba(255,255,255,.6);font-size:12px;text-align:center;max-width:160px;margin-top:8px;"><?= htmlspecialchars(truncar($item['titulo'], 40)) ?></p>
                </div>
                <?php else: ?>
                <div class="gallery-item">
                    <img src="<?= htmlspecialchars($item['url_ou_caminho']) ?>" alt="<?= htmlspecialchars($item['titulo']) ?>" loading="lazy">
                    <div class="gallery-item-overlay">
                        <span class="gallery-item-label"><?= htmlspecialchars(truncar($item['titulo'], 40)) ?></span>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>


<!-- ══════════════════════════════════════════════
     BLOG / NOTÍCIAS (se existir)
     ══════════════════════════════════════════════ -->
<?php if (!empty($posts)): ?>
<section class="section" id="noticias">
    <div class="container">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:48px;gap:24px;flex-wrap:wrap;">
            <div>
                <span class="section-eyebrow">Blog & Notícias</span>
                <h2 class="section-title">Últimas<br>Novidades</h2>
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
                <button class="carousel-nav-btn" onclick="slideNews('prev')" aria-label="Anterior">←</button>
                <button class="carousel-nav-btn" onclick="slideNews('next')" aria-label="Seguinte">→</button>
                <a href="/atlas/blog.php" class="btn btn-outline-navy btn-sm" style="margin-left:8px;">Ver Todos</a>
            </div>
        </div>
        <div class="news-carousel-wrapper">
            <div class="news-carousel" id="newsCarousel">
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
                        <h3 class="news-title"><?= htmlspecialchars($post['titulo']) ?></h3>
                        <p class="news-excerpt"><?= htmlspecialchars(truncar($post['resumo'] ?: strip_tags($post['corpo']), 120)) ?></p>
                        <div class="news-footer">
                            <span class="news-date"><?= formatarData($post['criado_em']) ?></span>
                            <a href="/atlas/post.php?slug=<?= htmlspecialchars($post['slug']) ?>" class="news-link">Ler mais →</a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<script>
function slideNews(direction) {
    const carousel = document.getElementById('newsCarousel');
    const cardWidth = 380;
    const gap = 24;
    const scrollAmount = cardWidth + gap;
    if (direction === 'prev') {
        carousel.scrollLeft -= scrollAmount;
    } else {
        carousel.scrollLeft += scrollAmount;
    }
}
</script>
<?php endif; ?>


<!-- Modal Vídeo -->
<div id="videoModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:9999;align-items:center;justify-content:center;">
    <div style="position:relative;width:90%;max-width:960px;">
        <button onclick="fecharVideo()" style="position:absolute;top:-52px;right:0;background:none;border:none;color:#fff;font-size:32px;cursor:pointer;font-weight:300;">×</button>
        <iframe id="videoFrame" width="100%" height="540" frameborder="0" allow="autoplay;encrypted-media" allowfullscreen style="display:block;"></iframe>
    </div>
</div>

</main>

<!-- ══════════════════════════════════════════════
     ÁREA DE CONTACTO COMPLETA NA HOME PAGE
     ══════════════════════════════════════════════ -->
<section class="section section-light" id="contacto" style="border-top:1px solid var(--gray-200);">
    <div class="container">
        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['tipo'] ?>"><?= htmlspecialchars($flash['mensagem']) ?></div>
        <?php endif; ?>

        <div class="contact-grid">
            <!-- Formulário -->
            <div>
                <span class="section-eyebrow">Envie uma Mensagem</span>
                <h2 class="section-title" style="margin-bottom:32px;">Estamos aqui para si</h2>

                <?php if (!empty($erros)): ?>
                <div class="alert alert-danger">
                    <ul style="padding-left:16px;margin:0;list-style:square;">
                        <?php foreach ($erros as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="POST" action="#contacto" class="contact-form" id="contactoForm">
                    <input type="hidden" name="formulario_contacto" value="1">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nome">Nome Completo *</label>
                            <input type="text" id="nome" name="nome" class="form-control"
                                   placeholder="O seu nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" class="form-control"
                                   placeholder="email@exemplo.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefone">Telefone / WhatsApp</label>
                            <input type="tel" id="telefone" name="telefone" class="form-control"
                                   placeholder="+244 9XX XXX XXX" value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="assunto">Assunto</label>
                            <input type="text" id="assunto" name="assunto" class="form-control"
                                   placeholder="Motivo do contacto" value="<?= htmlspecialchars($_POST['assunto'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mensagem">Mensagem *</label>
                        <textarea id="mensagem" name="mensagem" class="form-control" rows="6"
                                  placeholder="Descreva como podemos ajudá-lo..." required><?= htmlspecialchars($_POST['mensagem'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-navy" style="width:100%;justify-content:center;padding:16px 20px;font-size:15px;">
                        📨 Enviar Mensagem
                    </button>
                </form>
            </div>

            <!-- Informações de contacto -->
            <div>
                <span class="section-eyebrow">Informações</span>
                <h2 class="section-title" style="margin-bottom:24px;">Onde nos encontrar</h2>

                <div class="contact-info-card" style="border-radius:var(--radius); background:var(--white);">
                    <h3>📍 Sede</h3>
                    <p style="font-size:14px;line-height:1.7;">
                        <?= htmlspecialchars(config('morada')) ?><br>
                        <?= htmlspecialchars(config('municipio')) ?>, <?= htmlspecialchars(config('provincia')) ?><br>
                        <?= htmlspecialchars(config('pais')) ?>
                    </p>
                </div>

                <?php if (config('telefone_1') || config('telefone_2')): ?>
                <div class="contact-info-card" style="border-radius:var(--radius); background:var(--white);">
                    <h3>📞 Telefone</h3>
                    <?php if (config('telefone_1')): ?>
                    <p style="font-size:14px;"><a href="tel:<?= preg_replace('/\s+/', '', config('telefone_1')) ?>"><?= htmlspecialchars(config('telefone_1')) ?></a></p>
                    <?php endif; ?>
                    <?php if (config('telefone_2')): ?>
                    <p style="font-size:14px;"><a href="tel:<?= preg_replace('/\s+/', '', config('telefone_2')) ?>"><?= htmlspecialchars(config('telefone_2')) ?></a></p>
                    <?php endif; ?>
                    <?php if (config('whatsapp')): ?>
                    <p style="margin-top:8px;font-size:14px;"><a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', config('whatsapp')) ?>" target="_blank" style="color:var(--navy);font-weight:700;">💬 WhatsApp disponível</a></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="contact-info-card" style="border-radius:var(--radius); background:var(--white);">
                    <h3>✉️ Email</h3>
                    <?php foreach (['email_geral'=>'Geral','email_comercial'=>'Comercial','email_tecnico'=>'Técnico'] as $key => $label): ?>
                    <?php if (config($key)): ?>
                    <p style="margin-bottom:8px;font-size:14px;line-height:1.6;">
                        <strong style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--gray-500);"><?= $label ?>:</strong><br>
                        <a href="mailto:<?= htmlspecialchars(config($key)) ?>"><?= htmlspecialchars(config($key)) ?></a>
                    </p>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Redes Sociais -->
                <div style="margin-top:24px;">
                    <p style="font-size:12px;font-weight:700;color:var(--navy);margin-bottom:12px;text-transform:uppercase;letter-spacing:1.5px;">Redes Sociais</p>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <?php if (config('facebook')): ?>
                        <a href="<?= htmlspecialchars(config('facebook')) ?>" target="_blank" class="btn btn-outline-navy btn-sm" style="border-radius:var(--radius); background:var(--white);">Facebook</a>
                        <?php endif; ?>
                        <?php if (config('instagram')): ?>
                        <a href="<?= htmlspecialchars(config('instagram')) ?>" target="_blank" class="btn btn-outline-navy btn-sm" style="border-radius:var(--radius); background:var(--white);">Instagram</a>
                        <?php endif; ?>
                        <?php if (config('linkedin')): ?>
                        <a href="<?= htmlspecialchars(config('linkedin')) ?>" target="_blank" class="btn btn-outline-navy btn-sm" style="border-radius:var(--radius); background:var(--white);">LinkedIn</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapa -->
        <?php $mapsEmbed = config('maps_embed'); ?>
        <?php if ($mapsEmbed): ?>
        <div style="margin-top:60px;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-md);">
            <iframe src="<?= htmlspecialchars($mapsEmbed) ?>" width="100%" height="380" style="border:none;" allowfullscreen loading="lazy"></iframe>
        </div>
        <?php else: ?>
        <div style="margin-top:60px;height:240px;background:var(--gray-100);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;color:var(--gray-500);border:1px solid var(--gray-200);">
            <span style="font-size:36px;">🗺️</span>
            <p style="font-size:14px;font-weight:600;">Huambo, Bairro São João — <a href="https://maps.google.com/?q=Huambo+Angola" target="_blank" style="color:var(--navy);font-weight:700;">Ver no Google Maps →</a></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/partials/_footer.php'; ?>
