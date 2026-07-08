<?php
require_once __DIR__ . '/app/helpers.php';
$pageTitle = 'Sobre Nós';
$metaDesc  = 'Conheça a história, missão e valores da Atlas Centro Sul — Comércio e Serviços, Lda.';
$navSolid  = true;
?>
<?php include __DIR__ . '/partials/_header.php'; ?>
<div style="padding-top:var(--nav-h);">

<section class="page-hero">
    <div class="container page-hero-content">
        <div class="breadcrumb"><a href="/atlas/">Início</a> <span>/</span> Sobre Nós</div>
        <h1 style="color:var(--white);font-weight:900;">Sobre a Atlas Centro Sul</h1>
        <p style="color:rgba(255,255,255,.7);font-size:16px;margin-top:8px;max-width:500px;">Conheça a empresa, os seus fundadores e a visão que nos move.</p>
    </div>
</section>

<!-- História (Estilo Split Omatapalo) -->
<section class="section">
    <div class="container">
        <div class="about-grid">
            <div>
                <span class="section-eyebrow">A Nossa História</span>
                <h2 class="section-title">Uma empresa angolana com visão multissectorial</h2>
                <p style="font-size:16px;line-height:1.85;margin-bottom:20px;">
                    Fundada em <strong><?= htmlspecialchars(config('ano_fundacao', '2025')) ?></strong> em <strong>Huambo</strong>, a Atlas Centro Sul — Comércio e Serviços, Lda nasceu da visão de dois empreendedores angolanos determinados a criar uma empresa de referência no centro-sul de Angola.
                </p>
                <p style="color:var(--gray-500);margin-bottom:20px;line-height:1.8;">
                    O nome <em>"Atlas"</em> simboliza força e o suporte de múltiplas realidades — tal como a empresa sustenta e desenvolve negócios em setores distintos mas complementares. A nossa sede no Município de Huambo posiciona-nos estrategicamente no coração do país.
                </p>
                <p style="color:var(--gray-700);line-height:1.8;">
                    <?= htmlspecialchars(config('sobre_resumo')) ?>
                </p>
            </div>
            <div style="position:relative;">
                <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));border-radius:var(--radius-lg);height:400px;display:flex;align-items:center;justify-content:center;box-shadow:var(--shadow-md);overflow:hidden;">
                    <img src="/atlas/assets/img/logo.png" alt="Atlas Centro Sul" style="width:60%;height:60%;object-fit:contain;filter:brightness(0) invert(1);opacity:.15;">
                </div>
                <div class="about-badge-float" style="border-radius:var(--radius);">
                    <div class="num"><?= date('Y') - (int)config('ano_fundacao','2025') + 1 ?></div>
                    <div class="lbl">Ano(s) de<br>Atividade</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Missão, Visão, Valores -->
<section class="section section-light">
    <div class="container">
        <div style="text-align:center;margin-bottom:56px;">
            <span class="section-eyebrow">A Nossa Identidade</span>
            <h2 class="section-title">Missão, Visão e Valores</h2>
        </div>
        <div class="card-grid-3">
            <div class="feature-card" style="text-align:center;">
                <div class="feature-icon">🎯</div>
                <h3>Missão</h3>
                <p>Criar valor sustentável para Angola através de soluções multissectoriais inovadoras, com rigor técnico e compromisso com as comunidades onde atuamos.</p>
            </div>
            <div class="feature-card" style="text-align:center;">
                <div class="feature-icon">👁️</div>
                <h3>Visão</h3>
                <p>Ser a empresa de referência no centro-sul de Angola, reconhecida pela excelência operacional, diversidade de serviços e impacto positivo no desenvolvimento nacional.</p>
            </div>
            <div class="feature-card" style="text-align:center;">
                <div class="feature-icon">⚖️</div>
                <h3>Valores</h3>
                <p>Integridade, inovação, responsabilidade social, respeito pelos parceiros e colaboradores, e dedicação à qualidade em tudo o que fazemos.</p>
            </div>
        </div>
    </div>
</section>

<!-- Objeto Social -->
<section class="section">
    <div class="container">
        <div style="text-align:center;margin-bottom:56px;">
            <span class="section-eyebrow">Objeto Social</span>
            <h2 class="section-title">O Que Fazemos</h2>
            <p class="section-lead" style="margin:0 auto;text-align:center;">Conforme os estatutos da empresa, a Atlas Centro Sul tem por objeto o exercício das seguintes atividades:</p>
        </div>
        <div class="card-grid-3">
            <div class="feature-card">
                <div class="feature-icon">⚙️</div>
                <h3>Serviços Industriais</h3>
                <p>Prestação de serviços industriais incluindo soldadura, pintura, manutenção eletromecânica e eletrónica. Suporte técnico especializado para unidades industriais.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛢️</div>
                <h3>Sector Petrolífero</h3>
                <p>Atuação no setor petrolífero, onshore e offshore, incluindo manutenção, logística e serviços de apoio técnico a operadoras e contratantes.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏥</div>
                <h3>Saúde</h3>
                <p>Criação e exploração de projetos no ramo da saúde: clínicas, hospitais, fisioterapia, laboratórios e fornecimento de equipamentos médicos e hospitalares.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌾</div>
                <h3>Agronegócio</h3>
                <p>Agronomia e agronegócio, incluindo produção, transformação e comercialização de produtos agrícolas — contribuindo para a segurança alimentar nacional.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛒</div>
                <h3>Comércio Geral</h3>
                <p>Comércio geral de bens e serviços, respondendo às diversas necessidades do mercado angolano com qualidade e competitividade.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏢</div>
                <h3>Holding & Investimentos</h3>
                <p>Gestão de participações sociais noutras sociedades (holding), contribuindo para o desenvolvimento do ecossistema empresarial angolano.</p>
            </div>
        </div>
    </div>
</section>

<!-- Gerência -->
<section class="section section-dark">
    <div class="container">
        <div style="text-align:center;margin-bottom:56px;">
            <span class="section-eyebrow">Liderança</span>
            <h2 class="section-title white">A Nossa Gerência</h2>
            <p class="section-lead white" style="margin:0 auto;text-align:center;">Designados nos termos dos estatutos da empresa, os gerentes fundadores lideram a Atlas Centro Sul com determinação e visão estratégica.</p>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;max-width:800px;margin:0 auto;">
            <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:var(--radius-lg);padding:40px;text-align:center;transition:var(--transition);" onmouseover="this.style.background='rgba(255,255,255,.05)'" onmouseout="this.style.background='rgba(255,255,255,.03)'">
                <div style="width:80px;height:80px;background:linear-gradient(135deg,var(--gold),var(--gold-light));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:900;color:var(--navy-dark);margin:0 auto 20px;">Q</div>
                <h3 style="color:#fff;font-size:18px;margin-bottom:6px;">Quintino Lino Machado Adriano</h3>
                <p style="color:var(--gold);font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:12px;">Gerente</p>
                <p style="color:rgba(255,255,255,.6);font-size:14px;line-height:1.7;">Natural de Huambo, co-fundador e gerente da Atlas Centro Sul, detém a quota maioritária da empresa.</p>
            </div>
            <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:var(--radius-lg);padding:40px;text-align:center;transition:var(--transition);" onmouseover="this.style.background='rgba(255,255,255,.05)'" onmouseout="this.style.background='rgba(255,255,255,.03)'">
                <div style="width:80px;height:80px;background:linear-gradient(135deg,var(--steel-dark),var(--steel));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:900;color:#fff;margin:0 auto 20px;">A</div>
                <h3 style="color:#fff;font-size:18px;margin-bottom:6px;">António Francisco Bumba</h3>
                <p style="color:var(--gold);font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:12px;">Gerente</p>
                <p style="color:rgba(255,255,255,.6);font-size:14px;line-height:1.7;">Natural de Huambo, Bairro São João, co-fundador e gerente da Atlas Centro Sul.</p>
            </div>
        </div>
        <div style="margin-top:56px;text-align:center;">
            <p style="color:rgba(255,255,255,.3);font-size:13px;">Empresa registada sob os termos do Código das Sociedades Comerciais de Angola · <?= htmlspecialchars(config('ano_fundacao', '2025')) ?></p>
        </div>
    </div>
</section>

</div>
<?php include __DIR__ . '/partials/_footer.php'; ?>
