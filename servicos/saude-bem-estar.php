<?php
require_once __DIR__ . '/../app/helpers.php';
$servicoSlug      = 'saude-bem-estar';
$servicoTitulo    = 'Saúde & Bem-Estar';
$servicoIcone     = '🏥';
$servicoDesc      = 'Criação e gestão de infraestruturas de saúde e fornecimento de equipamentos médicos de alta qualidade para Angola.';
$servicoGradiente = 'linear-gradient(135deg,#001a1a,#00404d)';
$servicoIntro     = 'A Atlas Centro Sul investe no bem-estar das populações através de projetos de saúde que combinam infraestrutura, tecnologia e capital humano qualificado.';
$servicoServicos  = [
    ['icone'=>'🏨','titulo'=>'Clínicas e Centros de Saúde','desc'=>'Criação e exploração de clínicas e centros de saúde privados, com serviços de medicina geral e especialidades.'],
    ['icone'=>'🦴','titulo'=>'Fisioterapia e Reabilitação','desc'=>'Centros de fisioterapia com equipamentos modernos para reabilitação física e desportiva.'],
    ['icone'=>'🔬','titulo'=>'Laboratórios de Análises','desc'=>'Laboratórios de diagnóstico clínico com tecnologia avançada para análises rápidas e precisas.'],
    ['icone'=>'🏥','titulo'=>'Hospitais Privados','desc'=>'Desenvolvimento e gestão de unidades hospitalares privadas com padrão internacional.'],
    ['icone'=>'💊','titulo'=>'Equipamentos Médicos','desc'=>'Fornecimento e importação de equipamentos médicos e hospitalares certificados para clínicas e hospitais.'],
    ['icone'=>'🧪','titulo'=>'Equipamentos de Diagnóstico','desc'=>'Comercialização de equipamentos de diagnóstico por imagem, ECG, laboratório e bloco operatório.'],
];
$servicoVantagens = [
    ['icone'=>'🎓','titulo'=>'Profissionais de Saúde Qualificados','desc'=>'Parcerias com médicos, enfermeiros e técnicos de saúde com formação reconhecida.'],
    ['icone'=>'🌍','titulo'=>'Equipamentos Certificados','desc'=>'Fornecimento exclusivo de equipamentos com certificação médica internacional.'],
    ['icone'=>'💰','titulo'=>'Preços Acessíveis','desc'=>'Compromisso com a democratização do acesso a cuidados de saúde de qualidade.'],
    ['icone'=>'📍','titulo'=>'Cobertura Regional','desc'=>'Foco inicial em Huambo, com planos de expansão para outras províncias.'],
];
include __DIR__ . '/_template.php';
