<?php
require_once __DIR__ . '/../app/helpers.php';
$servicoSlug      = 'agronegocio';
$servicoTitulo    = config('servico_agronegocio_titulo', 'Agronegócio');
$servicoIcone     = config('servico_agronegocio_icone', '🌾');
$servicoDesc      = config('servico_agronegocio_desc', 'Produção, transformação e comercialização de produtos agrícolas — contribuindo para a segurança alimentar e o desenvolvimento rural de Angola.');
$servicoGradiente = config('servico_agronegocio_gradiente', 'linear-gradient(135deg,#0a1a00,#1a3d00)');
$servicoIntro     = config('servico_agronegocio_intro', 'O agronegócio é um dos pilares do futuro de Angola. A Atlas Centro Sul posiciona-se como parceiro estratégico para o desenvolvimento da cadeia de valor agrícola.');

$servicosJson     = config('servico_agronegocio_servicos');
$servicoServicos  = $servicosJson ? json_decode($servicosJson, true) : [
    ['icone'=>'🌱','titulo'=>'Produção Agrícola','desc'=>'Exploração de projetos de produção agrícola em larga escala, com foco em culturas prioritárias para Angola.'],
    ['icone'=>'🏭','titulo'=>'Transformação Agroindustrial','desc'=>'Processamento e transformação de matérias-primas agrícolas em produtos de maior valor acrescentado.'],
    ['icone'=>'🚛','titulo'=>'Comercialização','desc'=>'Escoamento e comercialização de produtos agrícolas nos mercados locais, regionais e internacionais.'],
    ['icone'=>'🐄','titulo'=>'Pecuária','desc'=>'Criação e gestão de projetos pecuários integrados na estratégia de agronegócio da empresa.'],
    ['icone'=>'💧','titulo'=>'Irrigação e Tecnologia','desc'=>'Implementação de sistemas de irrigação e tecnologia agrícola para maximizar a produtividade.'],
    ['icone'=>'🌿','titulo'=>'Produtos Orgânicos','desc'=>'Desenvolvimento de linha de produtos orgânicos e naturais com valor premium no mercado.'],
];

$vantagensJson    = config('servico_agronegocio_vantagens');
$servicoVantagens = $vantagensJson ? json_decode($vantagensJson, true) : [
    ['icone'=>'🌍','titulo'=>'Localização Estratégica','desc'=>'Huambo tem um dos solos mais férteis de Angola — posicionamento ideal para o agronegócio.'],
    ['icone'=>'🔗','titulo'=>'Cadeia Completa','desc'=>'Presença em toda a cadeia de valor: produção, transformação e comercialização.'],
    ['icone'=>'📈','titulo'=>'Mercado em Crescimento','desc'=>'O setor agrícola angolano tem enorme potencial de crescimento e diversificação.'],
    ['icone'=>'🤝','titulo'=>'Parcerias Locais','desc'=>'Colaboração com agricultores locais e cooperativas para crescimento partilhado.'],
];
include __DIR__ . '/_template.php';
