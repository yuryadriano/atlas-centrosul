<?php
require_once __DIR__ . '/app/helpers.php';
$pageTitle = 'Contacto';
$metaDesc  = 'Entre em contacto com a Atlas Centro Sul. Estamos em Huambo, Angola.';
$navSolid  = true;
$pdo       = getDB();
$flash     = null;
$erros     = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

$mapsEmbed = config('maps_embed');
?>
<?php include __DIR__ . '/partials/_header.php'; ?>
<div style="padding-top:var(--nav-h);">

<section class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb"><a href="/atlas/">Início</a> <span>/</span> Contacto</div>
        <h1 style="color:var(--white);font-weight:900;">Fale Connosco</h1>
        <p style="color:rgba(255,255,255,.7);font-size:16px;margin-top:8px;">Estamos prontos para responder às suas questões e propor soluções à medida.</p>
    </div>
</section>

<section class="section">
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

                <form method="POST" action="" class="contact-form" id="contactoForm">
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

                <div class="contact-info-card" style="border-radius:var(--radius);">
                    <h3>📍 Sede</h3>
                    <p style="font-size:14px;line-height:1.7;">
                        <?= htmlspecialchars(config('morada')) ?><br>
                        <?= htmlspecialchars(config('municipio')) ?>, <?= htmlspecialchars(config('provincia')) ?><br>
                        <?= htmlspecialchars(config('pais')) ?>
                    </p>
                </div>

                <?php if (config('telefone_1') || config('telefone_2')): ?>
                <div class="contact-info-card" style="border-radius:var(--radius);">
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

                <div class="contact-info-card" style="border-radius:var(--radius);">
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
                        <a href="<?= htmlspecialchars(config('facebook')) ?>" target="_blank" class="btn btn-outline-navy btn-sm" style="border-radius:var(--radius);">Facebook</a>
                        <?php endif; ?>
                        <?php if (config('instagram')): ?>
                        <a href="<?= htmlspecialchars(config('instagram')) ?>" target="_blank" class="btn btn-outline-navy btn-sm" style="border-radius:var(--radius);">Instagram</a>
                        <?php endif; ?>
                        <?php if (config('linkedin')): ?>
                        <a href="<?= htmlspecialchars(config('linkedin')) ?>" target="_blank" class="btn btn-outline-navy btn-sm" style="border-radius:var(--radius);">LinkedIn</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapa -->
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
</div>
<?php include __DIR__ . '/partials/_footer.php'; ?>
