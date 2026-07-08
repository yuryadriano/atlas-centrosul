<?php
require_once __DIR__ . '/../app/helpers.php';
$servicoSlug      = 'comercio-investimentos';
$servicoTitulo    = 'Comércio & Investimentos';
$servicoIcone     = '🏢';
$servicoDesc      = 'Comércio geral de bens e serviços e gestão estratégica de participações societárias para criar valor no ecossistema empresarial angolano.';
$servicoGradiente = 'linear-gradient(135deg,#0a0020,#200050)';
$servicoIntro     = 'O pilar comercial e de investimentos da Atlas sustenta o ecossistema completo da empresa, criando sinergias entre os diferentes setores de atuação.';
$servicoServicos  = [
    ['icone'=>'🛒','titulo'=>'Comércio Geral','desc'=>'Importação, exportação e distribuição de bens de consumo, industriais e especializados para o mercado angolano.'],
    ['icone'=>'📦','titulo'=>'Distribuição e Logística','desc'=>'Gestão de cadeias de distribuição eficientes para garantir a chegada dos produtos ao cliente final.'],
    ['icone'=>'🤝','titulo'=>'Parcerias Estratégicas','desc'=>'Estabelecimento de parcerias comerciais nacionais e internacionais para ampliar o portfólio de produtos.'],
    ['icone'=>'📊','titulo'=>'Gestão de Participações','desc'=>'Holding empresarial com gestão de participações sociais em sociedades dos setores estratégicos.'],
    ['icone'=>'💼','titulo'=>'Consultoria de Negócios','desc'=>'Apoio a empresas angolanas na estruturação e desenvolvimento dos seus modelos de negócio.'],
    ['icone'=>'🌐','titulo'=>'Representações Comerciais','desc'=>'Representação de marcas e empresas internacionais no mercado angolano.'],
];
$servicoVantagens = [
    ['icone'=>'🏢','titulo'=>'Estrutura Holding','desc'=>'Capacidade de gestão de múltiplas participações com eficiência e governança corporativa.'],
    ['icone'=>'🌍','titulo'=>'Rede de Contactos','desc'=>'Rede estabelecida de parceiros locais e internacionais para negócios transversais.'],
    ['icone'=>'⚡','titulo'=>'Agilidade Comercial','desc'=>'Capacidade de resposta rápida às oportunidades de mercado graças à estrutura multissectorial.'],
    ['icone'=>'📈','titulo'=>'Crescimento Sustentável','desc'=>'Estratégia de investimento orientada para o crescimento a longo prazo com solidez financeira.'],
];
include __DIR__ . '/_template.php';
