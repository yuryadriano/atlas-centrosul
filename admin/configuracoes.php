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
$sloganAtual = '';
try {
    $stmtSlogan = $pdo->query("SELECT valor FROM configuracoes WHERE chave = 'site_slogan' LIMIT 1");
    $sloganAtual = $stmtSlogan->fetchColumn() ?: '';
} catch (Exception $e) {}

$partesSlogan = explode(',', $sloganAtual, 2);
$defaultS1 = trim($partesSlogan[0] ?? 'Uma Marca');
$defaultS2 = trim($partesSlogan[1] ?? 'Múltiplas Soluções.');

$novasConfigs = [
    'site_slogan_1' => [
        'valor' => $defaultS1 ?: 'Uma Marca',
        'label' => 'Slogan - Linha 1'
    ],
    'site_slogan_2' => [
        'valor' => $defaultS2 ?: 'Múltiplas Soluções.',
        'label' => 'Slogan - Linha 2'
    ],
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
    ],
    'home_sobre_subtitulo' => [
        'valor' => 'Quem Somos',
        'label' => 'Homepage - Sobre Nós - Subtítulo'
    ],
    'home_sobre_titulo' => [
        'valor' => "A Atlas\nCentro Sul",
        'label' => 'Homepage - Sobre Nós - Título'
    ],
    'home_sobre_texto_1' => [
        'valor' => 'Fundada em 2025 em Huambo, a Atlas Centro Sul — Comércio e Serviços, Lda nasceu da visão de empreendedores angolanos determinados a criar uma empresa de referência no centro-sul de Angola.',
        'label' => 'Homepage - Sobre Nós - Parágrafo 1'
    ],
    'home_sobre_texto_2' => [
        'valor' => 'Duas palavras dão vida à nossa cultura: Fazemos Acontecer! Assumimos um papel activo na construção de uma Angola mais sustentável e com mais serviços.',
        'label' => 'Homepage - Sobre Nós - Parágrafo 2'
    ],
    'home_pilares_subtitulo' => [
        'valor' => 'Ecossistema Atlas',
        'label' => 'Homepage - Pilares - Subtítulo'
    ],
    'home_pilares_titulo' => [
        'valor' => "Os 4 Pilares\nEstratégicos",
        'label' => 'Homepage - Pilares - Título'
    ],
    'home_pilares_lead' => [
        'valor' => 'Atuamos em setores complementares que se fortalecem mutuamente — da energia ao campo, da saúde ao comércio.',
        'label' => 'Homepage - Pilares - Introdução'
    ],
    'home_pilar1_icone' => [
        'valor' => '⚙️',
        'label' => 'Homepage - Pilar 1 - Ícone'
    ],
    'home_pilar1_titulo' => [
        'valor' => "Energia &\nIndústria",
        'label' => 'Homepage - Pilar 1 - Título'
    ],
    'home_pilar1_desc' => [
        'valor' => 'Manutenção eletromecânica, soldadura, pintura industrial e apoio técnico ao sector petrolífero onshore e offshore.',
        'label' => 'Homepage - Pilar 1 - Descrição'
    ],
    'home_pilar2_icone' => [
        'valor' => '🏥',
        'label' => 'Homepage - Pilar 2 - Ícone'
    ],
    'home_pilar2_titulo' => [
        'valor' => "Saúde &\nBem-Estar",
        'label' => 'Homepage - Pilar 2 - Título'
    ],
    'home_pilar2_desc' => [
        'valor' => 'Clínicas, fisioterapia, laboratórios e fornecimento de equipamentos médicos e hospitalares de alta qualidade.',
        'label' => 'Homepage - Pilar 2 - Descrição'
    ],
    'home_pilar3_icone' => [
        'valor' => '🌾',
        'label' => 'Homepage - Pilar 3 - Ícone'
    ],
    'home_pilar3_titulo' => [
        'valor' => 'Agronegócio',
        'label' => 'Homepage - Pilar 3 - Título'
    ],
    'home_pilar3_desc' => [
        'valor' => 'Produção, transformação e comercialização de produtos agrícolas — do campo ao mercado, contribuindo para a segurança alimentar.',
        'label' => 'Homepage - Pilar 3 - Descrição'
    ],
    'home_pilar4_icone' => [
        'valor' => '🏢',
        'label' => 'Homepage - Pilar 4 - Ícone'
    ],
    'home_pilar4_titulo' => [
        'valor' => "Comércio &\nInvestimentos",
        'label' => 'Homepage - Pilar 4 - Título'
    ],
    'home_pilar4_desc' => [
        'valor' => 'Comércio geral de bens e serviços e gestão de participações sociais em empresas parceiras do ecossistema Atlas.',
        'label' => 'Homepage - Pilar 4 - Descrição'
    ],
    'sobre_objeto_social' => [
        'valor' => '[{"icone":"⚙️","titulo":"Serviços Industriais","desc":"Prestação de serviços industriais incluindo soldadura, pintura, manutenção eletromecânica e eletrónica. Suporte técnico especializado para unidades industriais."},{"icone":"🛢️","titulo":"Sector Petrolífero","desc":"Atuação no setor petrolífero, onshore e offshore, incluindo manutenção, logística e serviços de apoio técnico a operadoras e contratantes."},{"icone":"🏥","titulo":"Saúde","desc":"Criação e exploração de projetos no ramo da saúde: clínicas, hospitais, fisioterapia, laboratórios e fornecimento de equipamentos médicos e hospitalares."},{"icone":"🌾","titulo":"Agronegócio","desc":"Agronomia e agronegócio, incluindo produção, transformação e comercialização de produtos agrícolas — contribuindo para a segurança alimentar nacional."},{"icone":"🛒","titulo":"Comércio Geral","desc":"Comércio geral de bens e serviços, respondendo às diversas necessidades do mercado angolano com qualidade e competitividade."},{"icone":"🏢","titulo":"Holding & Investimentos","desc":"Gestão de participações sociais noutras sociedades (holding), contribuindo para o desenvolvimento do ecossistema empresarial angolano."}]',
        'label' => 'Sobre Nós - Objeto Social'
    ],
    // ENERGIA & INDÚSTRIA
    'servico_energia_industria_titulo' => [
        'valor' => 'Energia & Indústria',
        'label' => 'Serviço - Energia - Título'
    ],
    'servico_energia_industria_icone' => [
        'valor' => '⚙️',
        'label' => 'Serviço - Energia - Ícone'
    ],
    'servico_energia_industria_desc' => [
        'valor' => 'Serviços industriais especializados e suporte técnico ao sector petrolífero, onshore e offshore, em Angola.',
        'label' => 'Serviço - Energia - Descrição'
    ],
    'servico_energia_industria_intro' => [
        'valor' => 'Com equipas técnicas qualificadas, a Atlas oferece soluções industriais completas para os mais exigentes setores da economia angolana.',
        'label' => 'Serviço - Energia - Introdução'
    ],
    'servico_energia_industria_gradiente' => [
        'valor' => 'linear-gradient(135deg,#1a0a00,#3d1a00)',
        'label' => 'Serviço - Energia - Gradiente CSS'
    ],
    'servico_energia_industria_servicos' => [
        'valor' => '[{"icone":"🔧","titulo":"Manutenção Eletromecânica","desc":"Manutenção preventiva e corretiva de equipamentos eletromecânicos industriais, garantindo continuidade operacional."},{"icone":"🔩","titulo":"Soldadura Industrial","desc":"Serviços de soldadura MIG, TIG e elétrica para estruturas metálicas, tubagens e equipamentos industriais."},{"icone":"🎨","titulo":"Pintura Industrial","desc":"Tratamento anticorrosivo e pintura de estruturas metálicas, equipamentos e instalações industriais."},{"icone":"⚡","titulo":"Manutenção Eletrónica","desc":"Diagnóstico, reparação e manutenção de sistemas eletrónicos e automatismos industriais."},{"icone":"🛢️","titulo":"Apoio ao Sector Petrolífero","desc":"Logística, manutenção e serviços de apoio técnico a operações onshore e offshore."},{"icone":"🚢","titulo":"Serviços Offshore","desc":"Suporte técnico especializado para plataformas e operações offshore no mar angolano."},{"icone":"🏗️","titulo":"Engenharia de Construção Civil","desc":"Planeamento, projeto e execução de obras civis, infraestruturas e edifícios comerciais ou industriais."},{"icone":"📐","titulo":"Arquitetura","desc":"Desenvolvimento de projetos arquitetónicos inovadores, planeamento urbano e design de interiores/exteriores."},{"icone":"💧","titulo":"Engenharia Hidráulica","desc":"Projetos e soluções para sistemas de abastecimento de água, saneamento, drenagem e gestão de recursos hídricos."},{"icone":"💻","titulo":"Engenharia Informática","desc":"Desenvolvimento de software, gestão de infraestruturas de rede, segurança da informação e suporte tecnológico."}]',
        'label' => 'Serviço - Energia - Lista de Serviços'
    ],
    'servico_energia_industria_vantagens' => [
        'valor' => '[{"icone":"✅","titulo":"Equipas Qualificadas","desc":"Técnicos com formação especializada e experiência comprovada no setor industrial."},{"icone":"⏱️","titulo":"Resposta Rápida","desc":"Disponibilidade para intervenções urgentes e contratos de manutenção contínua."},{"icone":"🔒","titulo":"Segurança em Primeiro Lugar","desc":"Cumprimento rigoroso de normas de segurança industrial e ocupacional."},{"icone":"📋","titulo":"Relatórios Detalhados","desc":"Documentação completa de cada intervenção para rastreabilidade e conformidade."}]',
        'label' => 'Serviço - Energia - Lista de Vantagens'
    ],
    // SAÚDE & BEM-ESTAR
    'servico_saude_bem_estar_titulo' => [
        'valor' => 'Saúde & Bem-Estar',
        'label' => 'Serviço - Saúde - Título'
    ],
    'servico_saude_bem_estar_icone' => [
        'valor' => '🏥',
        'label' => 'Serviço - Saúde - Ícone'
    ],
    'servico_saude_bem_estar_desc' => [
        'valor' => 'Criação e gestão de infraestruturas de saúde e fornecimento de equipamentos médicos de alta qualidade para Angola.',
        'label' => 'Serviço - Saúde - Descrição'
    ],
    'servico_saude_bem_estar_intro' => [
        'valor' => 'A Atlas Centro Sul investe no bem-estar das populações através de projetos de saúde que combinam infraestrutura, tecnologia e capital humano qualificado.',
        'label' => 'Serviço - Saúde - Introdução'
    ],
    'servico_saude_bem_estar_gradiente' => [
        'valor' => 'linear-gradient(135deg,#001a1a,#00404d)',
        'label' => 'Serviço - Saúde - Gradiente CSS'
    ],
    'servico_saude_bem_estar_servicos' => [
        'valor' => '[{"icone":"🏨","titulo":"Clínicas e Centros de Saúde","desc":"Criação e exploração de clínicas e centros de saúde privados, com serviços de medicina geral e especialidades."},{"icone":"🦴","titulo":"Fisioterapia e Reabilitação","desc":"Centros de fisioterapia com equipamentos modernos para reabilitação física e desportiva."},{"icone":"🔬","titulo":"Laboratórios de Análises","desc":"Laboratórios de diagnóstico clínico com tecnologia avançada para análises rápidas e precisas."},{"icone":"🏥","titulo":"Hospitais Privados","desc":"Desenvolvimento e gestão de unidades hospitalares privadas com padrão internacional."},{"icone":"💊","titulo":"Equipamentos Médicos","desc":"Fornecimento e importação de equipamentos médicos e hospitalares certificados para clínicas e hospitais."},{"icone":"🧪","titulo":"Equipamentos de Diagnóstico","desc":"Comercialização de equipamentos de diagnóstico por imagem, ECG, laboratório e bloco operatório."}]',
        'label' => 'Serviço - Saúde - Lista de Serviços'
    ],
    'servico_saude_bem_estar_vantagens' => [
        'valor' => '[{"icone":"🎓","titulo":"Profissionais de Saúde Qualificados","desc":"Parcerias com médicos, enfermeiros e técnicos de saúde com formação reconhecida."},{"icone":"🌍","titulo":"Equipamentos Certificados","desc":"Fornecimento exclusivo de equipamentos com certificação médica internacional."},{"icone":"💰","titulo":"Preços Acessíveis","desc":"Compromisso com a democratização do acesso a cuidados de saúde de qualidade."},{"icone":"📍","titulo":"Cobertura Regional","desc":"Foco inicial em Huambo, com planos de expansão para outras províncias."}]',
        'label' => 'Serviço - Saúde - Lista de Vantagens'
    ],
    // AGRONEGÓCIO
    'servico_agronegocio_titulo' => [
        'valor' => 'Agronegócio',
        'label' => 'Serviço - Agronegócio - Título'
    ],
    'servico_agronegocio_icone' => [
        'valor' => '🌾',
        'label' => 'Serviço - Agronegócio - Ícone'
    ],
    'servico_agronegocio_desc' => [
        'valor' => 'Produção, transformação e comercialização de produtos agrícolas — contribuindo para a segurança alimentar e o desenvolvimento rural de Angola.',
        'label' => 'Serviço - Agronegócio - Descrição'
    ],
    'servico_agronegocio_intro' => [
        'valor' => 'O agronegócio é um dos pilares do futuro de Angola. A Atlas Centro Sul posiciona-se como parceiro estratégico para o desenvolvimento da cadeia de valor agrícola.',
        'label' => 'Serviço - Agronegócio - Introdução'
    ],
    'servico_agronegocio_gradiente' => [
        'valor' => 'linear-gradient(135deg,#0a1a00,#1a3d00)',
        'label' => 'Serviço - Agronegócio - Gradiente CSS'
    ],
    'servico_agronegocio_servicos' => [
        'valor' => '[{"icone":"🌱","titulo":"Produção Agrícola","desc":"Exploração de projetos de produção agrícola em larga escala, com foco em culturas prioritárias para Angola."},{"icone":"🏭","titulo":"Transformação Agroindustrial","desc":"Processamento e transformação de matérias-primas agrícolas em produtos de maior valor acrescentado."},{"icone":"🚛","titulo":"Comercialização","desc":"Escoamento e comercialização de produtos agrícolas nos mercados locais, regionais e internacionais."},{"icone":"🐄","titulo":"Pecuária","desc":"Criação e gestão de projetos pecuários integrados na estratégia de agronegócio da empresa."},{"icone":"💧","titulo":"Irrigação e Tecnologia","desc":"Implementação de sistemas de irrigação e tecnologia agrícola para maximizar a produtividade."},{"icone":"🌿","titulo":"Produtos Orgânicos","desc":"Desenvolvimento de linha de produtos orgânicos e naturais com valor premium no mercado."}]',
        'label' => 'Serviço - Agronegócio - Lista de Serviços'
    ],
    'servico_agronegocio_vantagens' => [
        'valor' => '[{"icone":"🌍","titulo":"Localização Estratégica","desc":"Huambo tem um dos solos mais férteis de Angola — posicionamento ideal para o agronegócio."},{"icone":"🔗","titulo":"Cadeia Completa","desc":"Presença em toda a cadeia de valor: produção, transformação e comercialização."},{"icone":"📈","titulo":"Mercado em Crescimento","desc":"O setor agrícola angolano tem enorme potencial de crescimento e diversificação."},{"icone":"🤝","titulo":"Parcerias Locais","desc":"Colaboração com agricultores locais e cooperativas para crescimento partilhado."}]',
        'label' => 'Serviço - Agronegócio - Lista de Vantagens'
    ],
    // COMÉRCIO & INVESTIMENTOS
    'servico_comercio_investimentos_titulo' => [
        'valor' => 'Comércio & Investimentos',
        'label' => 'Serviço - Comércio - Título'
    ],
    'servico_comercio_investimentos_icone' => [
        'valor' => '🏢',
        'label' => 'Serviço - Comércio - Ícone'
    ],
    'servico_comercio_investimentos_desc' => [
        'valor' => 'Comércio geral de bens e serviços e gestão estratégica de participações societárias para criar valor no ecossistema empresarial angolano.',
        'label' => 'Serviço - Comércio - Descrição'
    ],
    'servico_comercio_investimentos_intro' => [
        'valor' => 'O pilar comercial e de investimentos da Atlas sustenta o ecossistema completo da empresa, criando sinergias entre os diferentes setores de atuação.',
        'label' => 'Serviço - Comércio - Introdução'
    ],
    'servico_comercio_investimentos_gradiente' => [
        'valor' => 'linear-gradient(135deg,#0a0020,#200050)',
        'label' => 'Serviço - Comércio - Gradiente CSS'
    ],
    'servico_comercio_investimentos_servicos' => [
        'valor' => '[{"icone":"🛒","titulo":"Comércio Geral","desc":"Importação, exportação e distribuição de bens de consumo, industriais e especializados para o mercado angolano."},{"icone":"📦","titulo":"Distribuição e Logística","desc":"Gestão de cadeias de distribuição eficientes para garantir a chegada dos produtos ao cliente final."},{"icone":"🤝","titulo":"Parcerias Estratégicas","desc":"Estabelecimento de parcerias comerciais nacionais e internacionais para ampliar o portfólio de produtos."},{"icone":"📊","titulo":"Gestão de Participações","desc":"Holding empresarial com gestão de participações sociais em sociedades dos setores estratégicos."},{"icone":"💼","titulo":"Consultoria de Negócios","desc":"Apoio a empresas angolanas na estruturação e desenvolvimento dos seus modelos de negócio."},{"icone":"🌐","titulo":"Representações Comerciais","desc":"Representação de marcas e empresas internacionais no mercado angolano."}]',
        'label' => 'Serviço - Comércio - Lista de Serviços'
    ],
    'servico_comercio_investimentos_vantagens' => [
        'valor' => '[{"icone":"🏢","titulo":"Estrutura Holding","desc":"Capacidade de gestão de múltiplas participações com eficiência e governança corporativa."},{"icone":"🌍","titulo":"Rede de Contactos","desc":"Rede estabelecida de parceiros locais e internacionais para negócios transversais."},{"icone":"⚡","titulo":"Agilidade Comercial","desc":"Capacidade de resposta rápida às oportunidades de mercado graças à estrutura multissectorial."},{"icone":"📈","titulo":"Crescimento Sustentável","desc":"Estratégia de investimento orientada para o crescimento a longo prazo com solidez financeira."}]',
        'label' => 'Serviço - Comércio - Lista de Vantagens'
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
        'site_nome','site_slogan_1','site_slogan_2','empresa_nome','empresa_nif','morada','municipio','provincia','pais',
        'email_geral','email_comercial','email_tecnico','telefone_1','telefone_2','whatsapp',
        'facebook','instagram','linkedin','youtube','sobre_resumo','ano_fundacao','maps_embed',
        'sobre_missao','sobre_visao','sobre_valores','sobre_texto',
        'gerente_1_nome','gerente_1_cargo','gerente_1_bio',
        'gerente_2_nome','gerente_2_cargo','gerente_2_bio',
        'hero_subtitulo','manifesto_subtitulo','manifesto_titulo','manifesto_texto','video_homepage',
        'home_sobre_subtitulo','home_sobre_titulo','home_sobre_texto_1','home_sobre_texto_2',
        'home_pilares_subtitulo','home_pilares_titulo','home_pilares_lead',
        'home_pilar1_icone','home_pilar1_titulo','home_pilar1_desc',
        'home_pilar2_icone','home_pilar2_titulo','home_pilar2_desc',
        'home_pilar3_icone','home_pilar3_titulo','home_pilar3_desc',
        'home_pilar4_icone','home_pilar4_titulo','home_pilar4_desc',
        'sobre_objeto_social',
        'servico_energia_industria_titulo', 'servico_energia_industria_icone', 'servico_energia_industria_desc', 'servico_energia_industria_intro', 'servico_energia_industria_gradiente', 'servico_energia_industria_servicos', 'servico_energia_industria_vantagens',
        'servico_saude_bem_estar_titulo', 'servico_saude_bem_estar_icone', 'servico_saude_bem_estar_desc', 'servico_saude_bem_estar_intro', 'servico_saude_bem_estar_gradiente', 'servico_saude_bem_estar_servicos', 'servico_saude_bem_estar_vantagens',
        'servico_agronegocio_titulo', 'servico_agronegocio_icone', 'servico_agronegocio_desc', 'servico_agronegocio_intro', 'servico_agronegocio_gradiente', 'servico_agronegocio_servicos', 'servico_agronegocio_vantagens',
        'servico_comercio_investimentos_titulo', 'servico_comercio_investimentos_icone', 'servico_comercio_investimentos_desc', 'servico_comercio_investimentos_intro', 'servico_comercio_investimentos_gradiente', 'servico_comercio_investimentos_servicos', 'servico_comercio_investimentos_vantagens'
    ];
    $stmt = $pdo->prepare("UPDATE configuracoes SET valor = :valor WHERE chave = :chave");
    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            $stmt->execute([':valor' => trim($_POST[$campo]), ':chave' => $campo]);
        }
    }
    
    // Atualizar site_slogan para retrocompatibilidade
    $s1 = trim($_POST['site_slogan_1'] ?? '');
    $s2 = trim($_POST['site_slogan_2'] ?? '');
    $sloganConcatenado = $s1 . ($s2 ? ', ' . $s2 : '');
    $stmt->execute([':valor' => $sloganConcatenado, ':chave' => 'site_slogan']);
    
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
        /* Sub-Abas dos Serviços */
        .subtabs-nav {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--gray-200);
            padding-bottom: 8px;
            flex-wrap: wrap;
        }
        .subtab-btn {
            padding: 8px 16px;
            background: #f1f5f9;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            cursor: pointer;
            font-family: var(--font);
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-600);
            transition: all var(--transition);
        }
        .subtab-btn:hover {
            background: #e2e8f0;
            color: var(--navy);
        }
        .subtab-btn.active {
            background: var(--navy);
            color: #fff;
            border-color: var(--navy);
        }
        .subtab-content {
            display: none;
        }
        .subtab-content.active {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        .dynamic-list-row {
            transition: all var(--transition);
        }
        .dynamic-list-row:hover {
            border-color: var(--navy-light) !important;
            box-shadow: var(--shadow-sm);
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
            <button type="button" class="tab-btn" onclick="changeTab('tab-pilares')">🏛️ Os 4 Pilares</button>
            <button type="button" class="tab-btn" onclick="changeTab('tab-servicos-detalhe')">🛠️ Páginas de Serviços</button>
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
                                <label>Slogan - Linha 1 (Topo)</label>
                                <input type="text" name="site_slogan_1" class="form-control" value="<?= cfgVal($configs,'site_slogan_1') ?>">
                                <p class="form-hint">Ex: Uma Marca</p>
                            </div>
                            <div class="form-group">
                                <label>Slogan - Linha 2 (Destaque)</label>
                                <input type="text" name="site_slogan_2" class="form-control" value="<?= cfgVal($configs,'site_slogan_2') ?>">
                                <p class="form-hint">Ex: Múltiplas Soluções.</p>
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

                <div class="card" style="margin-top: 24px;">
                    <div class="card-header"><h2>🏡 Secção "Quem Somos" da Homepage</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Subtítulo da Secção</label>
                                <input type="text" name="home_sobre_subtitulo" class="form-control" value="<?= cfgVal($configs,'home_sobre_subtitulo') ?>">
                                <p class="form-hint">Ex: Quem Somos</p>
                            </div>
                            <div class="form-group">
                                <label>Título Principal</label>
                                <textarea name="home_sobre_titulo" class="form-control" rows="2"><?= cfgVal($configs,'home_sobre_titulo') ?></textarea>
                                <p class="form-hint">Ex: A Atlas\nCentro Sul (pode quebrar linha)</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Parágrafo 1 (Destaque)</label>
                            <textarea name="home_sobre_texto_1" class="form-control" rows="3"><?= cfgVal($configs,'home_sobre_texto_1') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Parágrafo 2 (Secundário / Cultura)</label>
                            <textarea name="home_sobre_texto_2" class="form-control" rows="3"><?= cfgVal($configs,'home_sobre_texto_2') ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="card" style="margin-top: 24px;">
                    <div class="card-header"><h2>💼 Objeto Social (Página "Sobre Nós")</h2></div>
                    <div class="card-body">
                        <p class="form-hint" style="margin-bottom: 16px;">Gerencie as áreas de atuação (Objeto Social) exibidas na página "Sobre Nós" (O Que Fazemos).</p>
                        <div id="dynamic-list-objeto-social"></div>
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

            <!-- ABA 7: OS 4 PILARES -->
            <div id="tab-pilares" class="tab-content">
                <div class="card">
                    <div class="card-header"><h2>🏛️ Configurações da Secção dos Pilares (Homepage)</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Subtítulo da Secção</label>
                                <input type="text" name="home_pilares_subtitulo" class="form-control" value="<?= cfgVal($configs,'home_pilares_subtitulo') ?>">
                                <p class="form-hint">Ex: Ecossistema Atlas</p>
                            </div>
                            <div class="form-group">
                                <label>Título Principal</label>
                                <textarea name="home_pilares_titulo" class="form-control" rows="2"><?= cfgVal($configs,'home_pilares_titulo') ?></textarea>
                                <p class="form-hint">Ex: Os 4 Pilares\nEstratégicos</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Texto de Introdução (Lead)</label>
                            <textarea name="home_pilares_lead" class="form-control" rows="2"><?= cfgVal($configs,'home_pilares_lead') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h2>⚙️ Pilar 01 - Energia & Indústria</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group" style="flex:0 0 120px;">
                                <label>Ícone (Emoji)</label>
                                <input type="text" name="home_pilar1_icone" class="form-control" value="<?= cfgVal($configs,'home_pilar1_icone') ?>">
                            </div>
                            <div class="form-group">
                                <label>Título do Pilar</label>
                                <input type="text" name="home_pilar1_titulo" class="form-control" value="<?= cfgVal($configs,'home_pilar1_titulo') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Descrição Curta</label>
                            <textarea name="home_pilar1_desc" class="form-control" rows="2"><?= cfgVal($configs,'home_pilar1_desc') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h2>🏥 Pilar 02 - Saúde & Bem-Estar</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group" style="flex:0 0 120px;">
                                <label>Ícone (Emoji)</label>
                                <input type="text" name="home_pilar2_icone" class="form-control" value="<?= cfgVal($configs,'home_pilar2_icone') ?>">
                            </div>
                            <div class="form-group">
                                <label>Título do Pilar</label>
                                <input type="text" name="home_pilar2_titulo" class="form-control" value="<?= cfgVal($configs,'home_pilar2_titulo') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Descrição Curta</label>
                            <textarea name="home_pilar2_desc" class="form-control" rows="2"><?= cfgVal($configs,'home_pilar2_desc') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h2>🌾 Pilar 03 - Agronegócio</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group" style="flex:0 0 120px;">
                                <label>Ícone (Emoji)</label>
                                <input type="text" name="home_pilar3_icone" class="form-control" value="<?= cfgVal($configs,'home_pilar3_icone') ?>">
                            </div>
                            <div class="form-group">
                                <label>Título do Pilar</label>
                                <input type="text" name="home_pilar3_titulo" class="form-control" value="<?= cfgVal($configs,'home_pilar3_titulo') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Descrição Curta</label>
                            <textarea name="home_pilar3_desc" class="form-control" rows="2"><?= cfgVal($configs,'home_pilar3_desc') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h2>🏢 Pilar 04 - Comércio & Investimentos</h2></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group" style="flex:0 0 120px;">
                                <label>Ícone (Emoji)</label>
                                <input type="text" name="home_pilar4_icone" class="form-control" value="<?= cfgVal($configs,'home_pilar4_icone') ?>">
                            </div>
                            <div class="form-group">
                                <label>Título do Pilar</label>
                                <input type="text" name="home_pilar4_titulo" class="form-control" value="<?= cfgVal($configs,'home_pilar4_titulo') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Descrição Curta</label>
                            <textarea name="home_pilar4_desc" class="form-control" rows="2"><?= cfgVal($configs,'home_pilar4_desc') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ABA 8: PÁGINAS DE SERVIÇOS DETALHADOS -->
            <div id="tab-servicos-detalhe" class="tab-content">
                <div class="subtabs-nav">
                    <button type="button" class="subtab-btn active" onclick="changeSubTab('subtab-energia')">⚙️ Energia & Indústria</button>
                    <button type="button" class="subtab-btn" onclick="changeSubTab('subtab-saude')">🏥 Saúde & Bem-Estar</button>
                    <button type="button" class="subtab-btn" onclick="changeSubTab('subtab-agronegocio')">🌾 Agronegócio</button>
                    <button type="button" class="subtab-btn" onclick="changeSubTab('subtab-comercio')">🏢 Comércio & Investimentos</button>
                </div>

                <!-- SUB-ABA 1: ENERGIA & INDÚSTRIA -->
                <div id="subtab-energia" class="subtab-content active">
                    <div class="card">
                        <div class="card-header"><h2>⚙️ Configurações Gerais — Energia & Indústria</h2></div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group" style="flex: 0 0 120px;">
                                    <label>Ícone (Emoji)</label>
                                    <input type="text" name="servico_energia_industria_icone" class="form-control" value="<?= cfgVal($configs,'servico_energia_industria_icone') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Título da Página</label>
                                    <input type="text" name="servico_energia_industria_titulo" class="form-control" value="<?= cfgVal($configs,'servico_energia_industria_titulo') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Gradiente de Fundo (CSS)</label>
                                    <input type="text" name="servico_energia_industria_gradiente" class="form-control" value="<?= cfgVal($configs,'servico_energia_industria_gradiente') ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Descrição Curta (SEO / Hero)</label>
                                <textarea name="servico_energia_industria_desc" class="form-control" rows="2"><?= cfgVal($configs,'servico_energia_industria_desc') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Texto de Introdução (Secção "O que oferecemos")</label>
                                <textarea name="servico_energia_industria_intro" class="form-control" rows="2"><?= cfgVal($configs,'servico_energia_industria_intro') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h2>🔧 Serviços Oferecidos</h2></div>
                        <div class="card-body">
                            <div id="dynamic-list-servicos-energia"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h2>📋 Vantagens (Porque escolher a Atlas)</h2></div>
                        <div class="card-body">
                            <div id="dynamic-list-vantagens-energia"></div>
                        </div>
                    </div>
                </div>

                <!-- SUB-ABA 2: SAÚDE & BEM-ESTAR -->
                <div id="subtab-saude" class="subtab-content">
                    <div class="card">
                        <div class="card-header"><h2>🏥 Configurações Gerais — Saúde & Bem-Estar</h2></div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group" style="flex: 0 0 120px;">
                                    <label>Ícone (Emoji)</label>
                                    <input type="text" name="servico_saude_bem_estar_icone" class="form-control" value="<?= cfgVal($configs,'servico_saude_bem_estar_icone') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Título da Página</label>
                                    <input type="text" name="servico_saude_bem_estar_titulo" class="form-control" value="<?= cfgVal($configs,'servico_saude_bem_estar_titulo') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Gradiente de Fundo (CSS)</label>
                                    <input type="text" name="servico_saude_bem_estar_gradiente" class="form-control" value="<?= cfgVal($configs,'servico_saude_bem_estar_gradiente') ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Descrição Curta (SEO / Hero)</label>
                                <textarea name="servico_saude_bem_estar_desc" class="form-control" rows="2"><?= cfgVal($configs,'servico_saude_bem_estar_desc') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Texto de Introdução (Secção "O que oferecemos")</label>
                                <textarea name="servico_saude_bem_estar_intro" class="form-control" rows="2"><?= cfgVal($configs,'servico_saude_bem_estar_intro') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h2>🩺 Serviços Oferecidos</h2></div>
                        <div class="card-body">
                            <div id="dynamic-list-servicos-saude"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h2>📋 Vantagens (Porque escolher a Atlas)</h2></div>
                        <div class="card-body">
                            <div id="dynamic-list-vantagens-saude"></div>
                        </div>
                    </div>
                </div>

                <!-- SUB-ABA 3: AGRONEGÓCIO -->
                <div id="subtab-agronegocio" class="subtab-content">
                    <div class="card">
                        <div class="card-header"><h2>🌾 Configurações Gerais — Agronegócio</h2></div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group" style="flex: 0 0 120px;">
                                    <label>Ícone (Emoji)</label>
                                    <input type="text" name="servico_agronegocio_icone" class="form-control" value="<?= cfgVal($configs,'servico_agronegocio_icone') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Título da Página</label>
                                    <input type="text" name="servico_agronegocio_titulo" class="form-control" value="<?= cfgVal($configs,'servico_agronegocio_titulo') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Gradiente de Fundo (CSS)</label>
                                    <input type="text" name="servico_agronegocio_gradiente" class="form-control" value="<?= cfgVal($configs,'servico_agronegocio_gradiente') ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Descrição Curta (SEO / Hero)</label>
                                <textarea name="servico_agronegocio_desc" class="form-control" rows="2"><?= cfgVal($configs,'servico_agronegocio_desc') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Texto de Introdução (Secção "O que oferecemos")</label>
                                <textarea name="servico_agronegocio_intro" class="form-control" rows="2"><?= cfgVal($configs,'servico_agronegocio_intro') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h2>🚜 Serviços Oferecidos</h2></div>
                        <div class="card-body">
                            <div id="dynamic-list-servicos-agronegocio"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h2>📋 Vantagens (Porque escolher a Atlas)</h2></div>
                        <div class="card-body">
                            <div id="dynamic-list-vantagens-agronegocio"></div>
                        </div>
                    </div>
                </div>

                <!-- SUB-ABA 4: COMÉRCIO & INVESTIMENTOS -->
                <div id="subtab-comercio" class="subtab-content">
                    <div class="card">
                        <div class="card-header"><h2>🏢 Configurações Gerais — Comércio & Investimentos</h2></div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group" style="flex: 0 0 120px;">
                                    <label>Ícone (Emoji)</label>
                                    <input type="text" name="servico_comercio_investimentos_icone" class="form-control" value="<?= cfgVal($configs,'servico_comercio_investimentos_icone') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Título da Página</label>
                                    <input type="text" name="servico_comercio_investimentos_titulo" class="form-control" value="<?= cfgVal($configs,'servico_comercio_investimentos_titulo') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Gradiente de Fundo (CSS)</label>
                                    <input type="text" name="servico_comercio_investimentos_gradiente" class="form-control" value="<?= cfgVal($configs,'servico_comercio_investimentos_gradiente') ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Descrição Curta (SEO / Hero)</label>
                                <textarea name="servico_comercio_investimentos_desc" class="form-control" rows="2"><?= cfgVal($configs,'servico_comercio_investimentos_desc') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Texto de Introdução (Secção "O que oferecemos")</label>
                                <textarea name="servico_comercio_investimentos_intro" class="form-control" rows="2"><?= cfgVal($configs,'servico_comercio_investimentos_intro') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h2>🛍️ Serviços Oferecidos</h2></div>
                        <div class="card-body">
                            <div id="dynamic-list-servicos-comercio"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h2>📋 Vantagens (Porque escolher a Atlas)</h2></div>
                        <div class="card-body">
                            <div id="dynamic-list-vantagens-comercio"></div>
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

function changeSubTab(subTabId) {
    // Esconder todas as sub-abas
    document.querySelectorAll('.subtab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.subtab-btn').forEach(el => el.classList.remove('active'));
    
    // Mostrar a sub-aba selecionada
    document.getElementById(subTabId).classList.add('active');
    
    // Encontrar o botão clicado e marcá-lo como ativo
    const btn = document.querySelector(`.subtab-btn[onclick*="${subTabId}"]`);
    if (btn) btn.classList.add('active');
    
    // Gravar sub-aba ativa no localStorage
    localStorage.setItem('active_config_subtab', subTabId);
}

function initDynamicList(containerId, inputName, currentValue) {
    const container = document.getElementById(containerId);
    if (!container) return;

    // Criar o input hidden para enviar no form POST
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = inputName;
    hiddenInput.value = currentValue || '[]';
    container.parentNode.insertBefore(hiddenInput, container);

    let items = [];
    try {
        items = JSON.parse(hiddenInput.value || '[]');
    } catch(e) {
        items = [];
    }

    function render() {
        container.innerHTML = '';
        
        items.forEach((item, index) => {
            const row = document.createElement('div');
            row.className = 'dynamic-list-row';
            row.style = 'display: flex; gap: 12px; margin-bottom: 12px; align-items: stretch; background: var(--off-white); padding: 12px; border-radius: var(--radius); border: 1px solid var(--gray-200);';
            row.innerHTML = `
                <div style="flex: 0 0 80px;">
                    <label style="font-size: 11px; font-weight: 700; display: block; margin-bottom: 4px; color: var(--gray-500);">Ícone</label>
                    <input type="text" placeholder="Emoji" value="${item.icone || ''}" class="form-control list-icon" style="text-align: center; font-size: 18px; height: 38px;">
                </div>
                <div style="flex: 1;">
                    <label style="font-size: 11px; font-weight: 700; display: block; margin-bottom: 4px; color: var(--gray-500);">Título</label>
                    <input type="text" placeholder="Título" value="${item.titulo || ''}" class="form-control list-title" style="height: 38px;">
                </div>
                <div style="flex: 2;">
                    <label style="font-size: 11px; font-weight: 700; display: block; margin-bottom: 4px; color: var(--gray-500);">Descrição</label>
                    <textarea placeholder="Descrição..." class="form-control list-desc" rows="1" style="resize: vertical; min-height: 38px; padding: 8px 12px;">${item.desc || ''}</textarea>
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <button type="button" class="btn btn-danger remove-btn" style="height: 38px; padding: 0 16px; font-size: 14px; font-weight: bold; border-radius: var(--radius);">❌</button>
                </div>
            `;
            
            // Eventos
            row.querySelector('.list-icon').addEventListener('input', (e) => { items[index].icone = e.target.value; update(); });
            row.querySelector('.list-title').addEventListener('input', (e) => { items[index].titulo = e.target.value; update(); });
            row.querySelector('.list-desc').addEventListener('input', (e) => { items[index].desc = e.target.value; update(); });
            row.querySelector('.remove-btn').addEventListener('click', () => { items.splice(index, 1); render(); update(); });
            
            container.appendChild(row);
        });

        // Botão de Adicionar
        const addBtn = document.createElement('button');
        addBtn.type = 'button';
        addBtn.className = 'btn btn-navy btn-sm';
        addBtn.style = 'margin-top: 8px; border-radius: var(--radius); padding: 8px 16px;';
        addBtn.innerHTML = '➕ Adicionar Novo Item';
        addBtn.addEventListener('click', () => {
            items.push({ icone: '📌', titulo: '', desc: '' });
            render();
            update();
        });
        container.appendChild(addBtn);
    }

    function update() {
        hiddenInput.value = JSON.stringify(items);
    }

    render();
}

// Restaurar abas e sub-abas, e iniciar as listas dinâmicas
document.addEventListener('DOMContentLoaded', () => {
    // Restaurar aba ativa
    const activeTab = localStorage.getItem('active_config_tab') || 'tab-geral';
    const btn = document.querySelector(`.tab-btn[onclick*="${activeTab}"]`);
    if (btn) {
        btn.click();
    } else {
        const firstBtn = document.querySelector('.tab-btn');
        if (firstBtn) firstBtn.click();
    }

    // Restaurar sub-aba ativa
    const activeSubTab = localStorage.getItem('active_config_subtab') || 'subtab-energia';
    const subBtn = document.querySelector(`.subtab-btn[onclick*="${activeSubTab}"]`);
    if (subBtn) {
        subBtn.click();
    } else {
        const firstSubBtn = document.querySelector('.subtab-btn');
        if (firstSubBtn) firstSubBtn.click();
    }

    // Inicializar listas dinâmicas
    initDynamicList('dynamic-list-objeto-social', 'sobre_objeto_social', <?= json_encode(config('sobre_objeto_social'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
    
    initDynamicList('dynamic-list-servicos-energia', 'servico_energia_industria_servicos', <?= json_encode(config('servico_energia_industria_servicos'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
    initDynamicList('dynamic-list-vantagens-energia', 'servico_energia_industria_vantagens', <?= json_encode(config('servico_energia_industria_vantagens'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);

    initDynamicList('dynamic-list-servicos-saude', 'servico_saude_bem_estar_servicos', <?= json_encode(config('servico_saude_bem_estar_servicos'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
    initDynamicList('dynamic-list-vantagens-saude', 'servico_saude_bem_estar_vantagens', <?= json_encode(config('servico_saude_bem_estar_vantagens'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);

    initDynamicList('dynamic-list-servicos-agronegocio', 'servico_agronegocio_servicos', <?= json_encode(config('servico_agronegocio_servicos'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
    initDynamicList('dynamic-list-vantagens-agronegocio', 'servico_agronegocio_vantagens', <?= json_encode(config('servico_agronegocio_vantagens'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);

    initDynamicList('dynamic-list-servicos-comercio', 'servico_comercio_investimentos_servicos', <?= json_encode(config('servico_comercio_investimentos_servicos'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
    initDynamicList('dynamic-list-vantagens-comercio', 'servico_comercio_investimentos_vantagens', <?= json_encode(config('servico_comercio_investimentos_vantagens'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
});
</script>
</body>
</html>
