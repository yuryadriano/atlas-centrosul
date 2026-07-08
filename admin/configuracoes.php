<?php
/**
 * Atlas Centro Sul — Configurações Gerais do Site
 */
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/helpers.php';
protegerRota();

$pdo   = getDB();
$flash = obterFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campos = ['site_nome','site_slogan','empresa_nome','morada','municipio','provincia','pais',
               'email_geral','email_comercial','email_tecnico','telefone_1','telefone_2','whatsapp',
               'facebook','instagram','linkedin','youtube','sobre_resumo','ano_fundacao','maps_embed'];
    $stmt = $pdo->prepare("UPDATE configuracoes SET valor = :valor WHERE chave = :chave");
    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            $stmt->execute([':valor' => trim($_POST[$campo]), ':chave' => $campo]);
        }
    }
    flashMensagem('success', 'Configurações guardadas com sucesso!');
    header('Location: /atlas/admin/configuracoes.php');
    exit;
}

// Carregar todas as configs
$stmt = $pdo->query("SELECT chave, valor, label FROM configuracoes ORDER BY chave");
$configs = [];
foreach ($stmt->fetchAll() as $row) {
    $configs[$row['chave']] = ['valor' => $row['valor'], 'label' => $row['label']];
}

function cfgVal(array $c, string $key): string {
    return htmlspecialchars($c[$key]['valor'] ?? '');
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações — Atlas Centro Sul Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/atlas/assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/_sidebar.php'; ?>
<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-title">⚙️ Configurações do Site</div>
    </header>
    <main class="content">
        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['tipo'] ?>"><?= htmlspecialchars($flash['mensagem']) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div style="display:flex;flex-direction:column;gap:24px;">

                <!-- Informações Gerais -->
                <div class="card">
                    <div class="card-header"><h2>🏢 Informações Gerais</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nome do Site</label>
                                <input type="text" name="site_nome" class="form-control" value="<?= cfgVal($configs,'site_nome') ?>">
                            </div>
                            <div class="form-group">
                                <label>Slogan</label>
                                <input type="text" name="site_slogan" class="form-control" value="<?= cfgVal($configs,'site_slogan') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nome Legal da Empresa</label>
                            <input type="text" name="empresa_nome" class="form-control" value="<?= cfgVal($configs,'empresa_nome') ?>">
                        </div>
                        <div class="form-group">
                            <label>Resumo — Sobre Nós</label>
                            <textarea name="sobre_resumo" class="form-control" rows="4"><?= cfgVal($configs,'sobre_resumo') ?></textarea>
                            <p class="form-hint">Texto que aparece na secção "Sobre Nós" da Home.</p>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Ano de Fundação</label>
                                <input type="number" name="ano_fundacao" class="form-control" value="<?= cfgVal($configs,'ano_fundacao') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Morada -->
                <div class="card">
                    <div class="card-header"><h2>📍 Localização</h2></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Morada Completa</label>
                            <input type="text" name="morada" class="form-control" value="<?= cfgVal($configs,'morada') ?>">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Município</label>
                                <input type="text" name="municipio" class="form-control" value="<?= cfgVal($configs,'municipio') ?>">
                            </div>
                            <div class="form-group">
                                <label>Província</label>
                                <input type="text" name="provincia" class="form-control" value="<?= cfgVal($configs,'provincia') ?>">
                            </div>
                            <div class="form-group">
                                <label>País</label>
                                <input type="text" name="pais" class="form-control" value="<?= cfgVal($configs,'pais') ?>">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Google Maps Embed URL</label>
                            <input type="url" name="maps_embed" class="form-control" placeholder="https://www.google.com/maps/embed?..." value="<?= cfgVal($configs,'maps_embed') ?>">
                            <p class="form-hint">Obtenha este link em Google Maps → Partilhar → Incorporar um mapa → Copiar HTML.</p>
                        </div>
                    </div>
                </div>

                <!-- Contactos -->
                <div class="card">
                    <div class="card-header"><h2>📞 Contactos</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Email Geral</label>
                                <input type="email" name="email_geral" class="form-control" value="<?= cfgVal($configs,'email_geral') ?>">
                            </div>
                            <div class="form-group">
                                <label>Email Comercial</label>
                                <input type="email" name="email_comercial" class="form-control" value="<?= cfgVal($configs,'email_comercial') ?>">
                            </div>
                            <div class="form-group">
                                <label>Email Técnico</label>
                                <input type="email" name="email_tecnico" class="form-control" value="<?= cfgVal($configs,'email_tecnico') ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Telefone 1</label>
                                <input type="text" name="telefone_1" class="form-control" value="<?= cfgVal($configs,'telefone_1') ?>">
                            </div>
                            <div class="form-group">
                                <label>Telefone 2</label>
                                <input type="text" name="telefone_2" class="form-control" value="<?= cfgVal($configs,'telefone_2') ?>">
                            </div>
                            <div class="form-group">
                                <label>WhatsApp</label>
                                <input type="text" name="whatsapp" class="form-control" value="<?= cfgVal($configs,'whatsapp') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Redes Sociais -->
                <div class="card">
                    <div class="card-header"><h2>📱 Redes Sociais</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Facebook</label>
                                <input type="url" name="facebook" class="form-control" placeholder="https://facebook.com/..." value="<?= cfgVal($configs,'facebook') ?>">
                            </div>
                            <div class="form-group">
                                <label>Instagram</label>
                                <input type="url" name="instagram" class="form-control" placeholder="https://instagram.com/..." value="<?= cfgVal($configs,'instagram') ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>LinkedIn</label>
                                <input type="url" name="linkedin" class="form-control" placeholder="https://linkedin.com/..." value="<?= cfgVal($configs,'linkedin') ?>">
                            </div>
                            <div class="form-group">
                                <label>YouTube</label>
                                <input type="url" name="youtube" class="form-control" placeholder="https://youtube.com/..." value="<?= cfgVal($configs,'youtube') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <button type="submit" class="btn btn-primary" style="padding:12px 32px;">
                        💾 Guardar Todas as Configurações
                    </button>
                </div>
            </div>
        </form>
    </main>
</div>
</body>
</html>
