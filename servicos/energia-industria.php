<?php
require_once __DIR__ . '/../app/helpers.php';
$servicoSlug      = 'energia-industria';
$servicoTitulo    = 'Energia & Indústria';
$servicoIcone     = '⚙️';
$servicoDesc      = 'Serviços industriais especializados e suporte técnico ao sector petrolífero, onshore e offshore, em Angola.';
$servicoGradiente = 'linear-gradient(135deg,#1a0a00,#3d1a00)';
$servicoIntro     = 'Com equipas técnicas qualificadas, a Atlas oferece soluções industriais completas para os mais exigentes setores da economia angolana.';
$servicoServicos  = [
    ['icone'=>'🔧','titulo'=>'Manutenção Eletromecânica','desc'=>'Manutenção preventiva e corretiva de equipamentos eletromecânicos industriais, garantindo continuidade operacional.'],
    ['icone'=>'🔩','titulo'=>'Soldadura Industrial','desc'=>'Serviços de soldadura MIG, TIG e elétrica para estruturas metálicas, tubagens e equipamentos industriais.'],
    ['icone'=>'🎨','titulo'=>'Pintura Industrial','desc'=>'Tratamento anticorrosivo e pintura de estruturas metálicas, equipamentos e instalações industriais.'],
    ['icone'=>'⚡','titulo'=>'Manutenção Eletrónica','desc'=>'Diagnóstico, reparação e manutenção de sistemas eletrónicos e automatismos industriais.'],
    ['icone'=>'🛢️','titulo'=>'Apoio ao Sector Petrolífero','desc'=>'Logística, manutenção e serviços de apoio técnico a operações onshore e offshore.'],
    ['icone'=>'🚢','titulo'=>'Serviços Offshore','desc'=>'Suporte técnico especializado para plataformas e operações offshore no mar angolano.'],
    ['icone'=>'🏗️','titulo'=>'Engenharia de Construção Civil','desc'=>'Planeamento, projeto e execução de obras civis, infraestruturas e edifícios comerciais ou industriais.'],
    ['icone'=>'📐','titulo'=>'Arquitetura','desc'=>'Desenvolvimento de projetos arquitetónicos inovadores, planeamento urbano e design de interiores/exteriores.'],
    ['icone'=>'💧','titulo'=>'Engenharia Hidráulica','desc'=>'Projetos e soluções para sistemas de abastecimento de água, saneamento, drenagem e gestão de recursos hídricos.'],
    ['icone'=>'💻','titulo'=>'Engenharia Informática','desc'=>'Desenvolvimento de software, gestão de infraestruturas de rede, segurança da informação e suporte tecnológico.'],
];
$servicoVantagens = [
    ['icone'=>'✅','titulo'=>'Equipas Qualificadas','desc'=>'Técnicos com formação especializada e experiência comprovada no setor industrial.'],
    ['icone'=>'⏱️','titulo'=>'Resposta Rápida','desc'=>'Disponibilidade para intervenções urgentes e contratos de manutenção contínua.'],
    ['icone'=>'🔒','titulo'=>'Segurança em Primeiro Lugar','desc'=>'Cumprimento rigoroso de normas de segurança industrial e ocupacional.'],
    ['icone'=>'📋','titulo'=>'Relatórios Detalhados','desc'=>'Documentação completa de cada intervenção para rastreabilidade e conformidade.'],
];
include __DIR__ . '/_template.php';
