<?php
/**
 * Atlas Centro Sul — Configurações Gerais do Site
 */
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/helpers.php';
protegerRota();

$pdo   = getDB();
$flash = obterFlash();

// Garantir que todas as chaves de configuração novas existem no banco de dados
$novasConfigs = [
    'sobre_missao' => [
        'valor' => 'Criar valor sustentável para Angola através de soluções multissectoriais inovadoras, com rigor técnico e compromisso com as comunidades onde atuamos.',
        'label' => 'Missão da Empresa'
    ],
    'sobre_visao' => [
        'valor' => 'Ser a empresa de referência no centro-sul de Angola, reconhecida pela excelência operacional, diversidade de serviços e impacto positivo no desenvolvimento nacional.',
        'label' => 'Visão da Empresa'
    ],
    'sobre_valores' => [
        'valor' => 'Integridade, inovação, responsabilidade social, respeito pelos parceiros e colaboradores, e dedicação à qualidade em tudo o que fazemos.',
        'label' => 'Valores da Empresa'
    ],
    'sobre_texto' => [
        'valor' => 'A Atlas Centro Sul é uma empresa angolana multissectorial sediada em Huambo, dedicada a criar valor em múltiplos setores da economy nacional: indústria, energia, saúde, agronegócio e comércio.',
        'label' => 'Texto Detalhado Sobre Nós'
    ],
    'gerente_1_nome' => [
        'valor' => 'Quintino Lino Machado Adriano',
        'label' => 'Gerente 1 - Nome'
    ],
    'gerente_1_cargo' => [
        'valor' => 'Gerente',
        'label' => 'Gerente 1 - Cargo'
    ],
    'gerente_1_bio' => [
        'valor' => 'Natural de Huambo, co-fundador e gerente da Atlas Centro Sul, detém a quota maioritária da empresa.',
        'label' => 'Gerente 1 - Biografia'
    ],
    'gerente_2_nome' => [
        'valor' => 'António Francisco Bumba',
        'label' => 'Gerente 2 - Nome'
    ],
    'gerente_2_cargo' => [
        'valor' => 'Gerente',
        'label' => 'Gerente 2 - Cargo'
    ],
    'gerente_2_bio' => [
        'valor' => 'Natural de Huambo, Bairro São João, co-fundador e gerente da Atlas Centro Sul.',
        'label' => 'Gerente 2 - Biografia'
    ],
    'hero_subtitulo' => [
        'valor' => 'A Atlas Centro Sul atua nos setores de energia & indústria, saúde & bem-estar, agronegócio e comércio — criando valor sustentável para Angola.',
        'label' => 'Subtítulo do Banner Principal'
    ],
    'manifesto_subtitulo' => [
        'valor' => 'A Nossa Cultura',
        'label' => 'Subtítulo do Manifesto'
    ],
    'manifesto_titulo' => [
        'valor' => "Fazemos\nAcontecer!",
        'label' => 'Título do Manifesto'
    ],
    'manifesto_texto' => [
        'valor' => 'Assumimos um papel activo na construção de uma Angola mais sustentável, mais próxima e com mais serviços.',
        'label' => 'Texto do Manifesto'
    ],
    'video_homepage' => [
        'valor' => '',
        'label' => 'Link de Vídeo Homepage'
    ],
    'empresa_nif' => [
        'valor' => 'NIF da Empresa',
        'label' => 'NIF'
    ]
];

$stmtVerificar = $pdo->prepare("SELECT COUNT(*) FROM configuracoes WHERE chave = :chave");
$stmtInserir = $pdo->prepare("INSERT INTO configuracoes (chave, valor, label) VALUES (:chave, :valor, :label)");

foreach ($novasConfigs as $chave => $info) {
    $stmtVerificar->execute([':chave' => $chave]);
    if ($stmtVerificar->fetchColumn() == 0) {
        $stmtInserir->execute([
            ':chave' => $chave,
            ':valor' => $info['valor'],
            ':label' => $info['label']
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campos = [
        'site_nome','site_slogan','empresa_nome','empresa_nif','morada','municipio','provincia','pais',
        'email_geral','email_comercial','email_tecnico','telefone_1','telefone_2','whatsapp',
        'facebook','instagram','linkedin','youtube','sobre_resumo','ano_fundacao','maps_embed',
        'sobre_missao','sobre_visao','sobre_valores','sobre_texto',
        'gerente_1_nome','gerente_1_cargo','gerente_1_bio',
        'gerente_2_nome','gerente_2_cargo','gerente_2_bio',
        'hero_subtitulo','manifesto_subtitulo','manifesto_titulo','manifesto_texto','video_homepage'
    ];
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
    <style>
        /* Estilo das Abas Premium */
        .tabs-nav {
            display: flex;
            gap: 4px;
            margin-bottom: 24px;
            border-bottom: 2px solid var(--gray-200);
            padding-bottom: 0;
            flex-wrap: wrap;
        }
        .tab-btn {
            padding: 12px 20px;
            background: #e2e8f0;
            border: 1px solid var(--gray-200);
            border-bottom: none;
            border-radius: var(--radius) var(--radius) 0 0;
            cursor: pointer;
            font-family: var(--font);
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            transition: all var(--transition);
            margin-bottom: -2px;
        }
        .tab-btn:hover {
            color: var(--navy);
            background: #cbd5e1;
        }
        .tab-btn.active {
            color: #fff;
            background: var(--navy);
            border-color: var(--navy);
            box-shadow: 0 -2px 8px rgba(0,26,53,0.1);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/_sidebar.php'; ?>
<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-title">⚙️ Configurações Gerais do Site</div>
    </header>
    <main class="content">
        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['tipo'] ?>"><?= htmlspecialchars($flash['mensagem']) ?></div>
        <?php endif; ?>

        <!-- Navegação de Abas -->
        <div class="tabs-nav">
            <button type="button" class="tab-btn active" onclick="changeTab('tab-geral')">🏢 Geral & Banner</button>
            <button type="button" class="tab-btn" onclick="changeTab('tab-identidade')">🎯 Quem Somos / Identidade</button>
            <button type="button" class="tab-btn" onclick="changeTab('tab-gerencia')">👥 Gerência & Sócios</button>
            <button type="button" class="tab-btn" onclick="changeTab('tab-manifesto')">🏡 Cultura & Manifesto</button>
            <button type="button" class="tab-btn" onclick="changeTab('tab-localizacao')">📍 Localização & Contactos</button>
            <button type="button" class="tab-btn" onclick="changeTab('tab-redes')">📱 Redes Sociais</button>
        </div>

        <form method="POST" action="">
            <!-- ABAS DE CONTEÚDO -->

            <!-- ABA 1: GERAL E BANNER -->
            <div id="tab-geral" class="tab-content active">
                <div class="card">
                    <div class="card-header"><h2>🏢 Configurações Gerais</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nome do Site</label>
                                <input type="text" name="site_nome" class="form-control" value="<?= cfgVal($configs,'site_nome') ?>">
                            </div>
                            <div class="form-group">
                                <label>Slogan da Empresa</label>
                                <input type="text" name="site_slogan" class="form-control" value="<?= cfgVal($configs,'site_slogan') ?>">
                                <p class="form-hint">Dica: Use vírgula para quebrar a linha no banner (Ex: Uma Marca, Múltiplas Soluções).</p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nome Legal / Razão Social</label>
                                <input type="text" name="empresa_nome" class="form-control" value="<?= cfgVal($configs,'empresa_nome') ?>">
                            </div>
                            <div class="form-group">
                                <label>NIF da Empresa</label>
                                <input type="text" name="empresa_nif" class="form-control" value="<?= cfgVal($configs,'empresa_nif') ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Ano de Fundação</label>
                                <input type="number" name="ano_fundacao" class="form-control" value="<?= cfgVal($configs,'ano_fundacao') ?>">
                            </div>
                            <div class="form-group">
                                <label>Vídeo Background do Banner (URL YouTube)</label>
                                <input type="url" name="video_homepage" class="form-control" placeholder="https://www.youtube.com/embed/..." value="<?= cfgVal($configs,'video_homepage') ?>">
                                <p class="form-hint">URL do iframe para rodar no fundo. Deixe em branco para usar uma imagem estática padrão.</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Subtítulo do Banner Principal</label>
                            <textarea name="hero_subtitulo" class="form-control" rows="2"><?= cfgVal($configs,'hero_subtitulo') ?></textarea>
                            <p class="form-hint">Exibido na página inicial abaixo do slogan principal.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ABA 2: QUEM SOMOS & IDENTIDADE -->
            <div id="tab-identidade" class="tab-content">
                <div class="card">
                    <div class="card-header"><h2>🎯 Identidade & Textos de Apresentação</h2></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Resumo Curto (Footer / SEO) <span style="color:var(--danger)">*</span></label>
                            <textarea name="sobre_resumo" class="form-control" rows="2" maxlength="200" required><?= cfgVal($configs,'sobre_resumo') ?></textarea>
                            <p class="form-hint">Escreva um resumo curto (máx. 200 caracteres). Este texto vai para o rodapé e meta tags de redes sociais.</p>
                        </div>
                        <div class="form-group">
                            <label>História Completa (Página "Sobre Nós")</label>
                            <textarea name="sobre_texto" class="form-control" rows="6"><?= cfgVal($configs,'sobre_texto') ?></textarea>
                            <p class="form-hint">Este texto aceita quebras de linha/parágrafos e será exibido em destaque na secção de história.</p>
                        </div>
                        <div class="form-group">
                            <label>Missão</label>
                            <textarea name="sobre_missao" class="form-control" rows="3"><?= cfgVal($configs,'sobre_missao') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Visão</label>
                            <textarea name="sobre_visao" class="form-control" rows="3"><?= cfgVal($configs,'sobre_visao') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Valores</label>
                            <textarea name="sobre_valores" class="form-control" rows="3"><?= cfgVal($configs,'sobre_valores') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ABA 3: GERÊNCIA & LIDERANÇA -->
            <div id="tab-gerencia" class="tab-content">
                <div class="card">
                    <div class="card-header"><h2>👤 Gerente Co-Fundador 1</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nome do Gerente 1</label>
                                <input type="text" name="gerente_1_nome" class="form-control" value="<?= cfgVal($configs,'gerente_1_nome') ?>">
                            </div>
                            <div class="form-group">
                                <label>Cargo / Função 1</label>
                                <input type="text" name="gerente_1_cargo" class="form-control" value="<?= cfgVal($configs,'gerente_1_cargo') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Biografia / Descrição Curta 1</label>
                            <textarea name="gerente_1_bio" class="form-control" rows="3"><?= cfgVal($configs,'gerente_1_bio') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h2>👤 Gerente Co-Fundador 2</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nome do Gerente 2</label>
                                <input type="text" name="gerente_2_nome" class="form-control" value="<?= cfgVal($configs,'gerente_2_nome') ?>">
                            </div>
                            <div class="form-group">
                                <label>Cargo / Função 2</label>
                                <input type="text" name="gerente_2_cargo" class="form-control" value="<?= cfgVal($configs,'gerente_2_cargo') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Biografia / Descrição Curta 2</label>
                            <textarea name="gerente_2_bio" class="form-control" rows="3"><?= cfgVal($configs,'gerente_2_bio') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ABA 4: CULTURA & MANIFESTO -->
            <div id="tab-manifesto" class="tab-content">
                <div class="card">
                    <div class="card-header"><h2>🏡 Manifesto da Homepage</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Subtítulo do Manifesto</label>
                                <input type="text" name="manifesto_subtitulo" class="form-control" value="<?= cfgVal($configs,'manifesto_subtitulo') ?>">
                                <p class="form-hint">Ex: "A Nossa Cultura"</p>
                            </div>
                            <div class="form-group">
                                <label>Título do Manifesto</label>
                                <textarea name="manifesto_titulo" class="form-control" rows="2"><?= cfgVal($configs,'manifesto_titulo') ?></textarea>
                                <p class="form-hint">Ex: "Fazemos Acontecer!" (Pode quebrar linhas se desejar).</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Texto Descritivo do Manifesto</label>
                            <textarea name="manifesto_texto" class="form-control" rows="3"><?= cfgVal($configs,'manifesto_texto') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ABA 5: LOCALIZAÇÃO & CONTACTOS -->
            <div id="tab-localizacao" class="tab-content">
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
                        <div class="form-group">
                            <label>Google Maps Embed URL</label>
                            <input type="url" name="maps_embed" class="form-control" placeholder="https://www.google.com/maps/embed?..." value="<?= cfgVal($configs,'maps_embed') ?>">
                            <p class="form-hint">Obtenha em Google Maps → Partilhar → Incorporar um mapa → Copiar HTML (copie apenas o link do atributo 'src').</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h2>📞 Contactos e Canais de Comunicação</h2></div>
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
                                <label>WhatsApp (com código do país)</label>
                                <input type="text" name="whatsapp" class="form-control" placeholder="+244XXXXXXXXX" value="<?= cfgVal($configs,'whatsapp') ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ABA 6: REDES SOCIAIS -->
            <div id="tab-redes" class="tab-content">
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
                                <input type="url" name="linkedin" class="form-control" placeholder="https://linkedin.com/company/..." value="<?= cfgVal($configs,'linkedin') ?>">
                            </div>
                            <div class="form-group">
                                <label>YouTube</label>
                                <input type="url" name="youtube" class="form-control" placeholder="https://youtube.com/c/..." value="<?= cfgVal($configs,'youtube') ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTÃO FIXO DE SUBMISSÃO -->
            <div style="display:flex;justify-content:flex-end;margin-top:24px;">
                <button type="submit" class="btn btn-primary" style="padding:14px 40px; font-size:15px; font-weight:700;">
                    💾 Guardar Todas as Alterações
                </button>
            </div>
        </form>
    </main>
</div>

<script>
function changeTab(tabId) {
    // Esconder todas as abas
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    
    // Mostrar a aba selecionada
    document.getElementById(tabId).classList.add('active');
    
    // Encontrar o botão clicado e marcá-lo como ativo
    const btn = document.querySelector(`.tab-btn[onclick*="${tabId}"]`);
    if (btn) btn.classList.add('active');
    
    // Gravar aba ativa no localStorage
    localStorage.setItem('active_config_tab', tabId);
}

// Restaurar a aba ativa após refresh/postback
document.addEventListener('DOMContentLoaded', () => {
    const activeTab = localStorage.getItem('active_config_tab') || 'tab-geral';
    const btn = document.querySelector(`.tab-btn[onclick*="${activeTab}"]`);
    if (btn) {
        btn.click();
    } else {
        const firstBtn = document.querySelector('.tab-btn');
        if (firstBtn) firstBtn.click();
    }
});
</script>
</body>
</html>
