-- ============================================================
-- Atlas Centro Sul — Comércio e Serviços, Lda
-- Schema da Base de Dados
-- Criado: 2025
-- ============================================================

CREATE DATABASE IF NOT EXISTS atlas_centrosul CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE atlas_centrosul;

-- ------------------------------------------------------------
-- Tabela: usuarios (Administradores do painel)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    senha       VARCHAR(255) NOT NULL,
    ativo       TINYINT(1) DEFAULT 1,
    criado_em   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabela: posts (Blog e Notícias)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS posts (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    titulo           VARCHAR(250) NOT NULL,
    slug             VARCHAR(250) NOT NULL UNIQUE,
    resumo           TEXT,
    corpo            LONGTEXT NOT NULL,
    categoria        ENUM('energia-industria','saude-bem-estar','agronegocio','comercio-investimentos','geral') DEFAULT 'geral',
    imagem_destaque  VARCHAR(300),
    publicado        TINYINT(1) DEFAULT 1,
    criado_em        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabela: midia (Fotos e Vídeos)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS midia (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    titulo         VARCHAR(250) NOT NULL,
    descricao      TEXT,
    tipo           ENUM('foto','video') NOT NULL DEFAULT 'foto',
    url_ou_caminho VARCHAR(500) NOT NULL,
    miniatura      VARCHAR(300),
    categoria      ENUM('energia-industria','saude-bem-estar','agronegocio','comercio-investimentos','geral') DEFAULT 'geral',
    destaque       TINYINT(1) DEFAULT 0,
    criado_em      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabela: mensagens (Formulário de Contacto)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS mensagens (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(150) NOT NULL,
    email       VARCHAR(150) NOT NULL,
    telefone    VARCHAR(30),
    assunto     VARCHAR(200),
    mensagem    TEXT NOT NULL,
    lida        TINYINT(1) DEFAULT 0,
    criado_em   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabela: configuracoes (Dados globais do site)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS configuracoes (
    chave   VARCHAR(100) PRIMARY KEY,
    valor   TEXT,
    label   VARCHAR(150)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Dados Iniciais — Configurações
-- ------------------------------------------------------------
INSERT INTO configuracoes (chave, valor, label) VALUES
('site_nome',         'Atlas Centro Sul',                                          'Nome do Site'),
('site_slogan',       'Uma Marca, Múltiplas Soluções.',                            'Slogan'),
('site_slogan_1',     'Uma Marca',                                                 'Slogan - Linha 1'),
('site_slogan_2',     'Múltiplas Soluções.',                                       'Slogan - Linha 2'),
('empresa_nome',      'Atlas Centro Sul — Comércio e Serviços, Lda',              'Nome Legal da Empresa'),
('empresa_nif',       'NIF da Empresa',                                            'NIF'),
('morada',            'Bairro São Jão, Rua Principal, casa n.º Junto A Escola 103, Huambo', 'Morada'),
('municipio',         'Município de Huambo',                                       'Município'),
('provincia',         'Província de Huambo',                                       'Província'),
('pais',              'Angola',                                                     'País'),
('email_geral',       'geral@atlascentrosul.ao',                                   'Email Geral'),
('email_comercial',   'comercial@atlascentrosul.ao',                               'Email Comercial'),
('email_tecnico',     'tecnico@atlascentrosul.ao',                                 'Email Técnico'),
('telefone_1',        '+244 9XX XXX XXX',                                          'Telefone 1'),
('telefone_2',        '+244 9XX XXX XXX',                                          'Telefone 2'),
('whatsapp',          '+244 9XX XXX XXX',                                          'WhatsApp'),
('facebook',          'https://facebook.com/atlascentrosul',                       'Facebook'),
('instagram',         'https://instagram.com/atlascentrosul',                      'Instagram'),
('linkedin',          'https://linkedin.com/company/atlascentrosul',               'LinkedIn'),
('youtube',           '',                                                           'YouTube'),
('sobre_resumo',      'A Atlas Centro Sul é uma empresa angolana multissectorial sediada em Huambo, dedicada a criar valor em múltiplos setores da economia nacional.', 'Resumo Sobre Nós'),
('sobre_texto',       'A Atlas Centro Sul é uma empresa angolana multissectorial sediada em Huambo, dedicada a criar valor em múltiplos setores da economia nacional: indústria, energia, saúde, agronegócio e comércio.', 'Texto Detalhado Sobre Nós'),
('sobre_missao',      'Criar valor sustentável para Angola através de soluções multissectoriais inovadoras, com rigor técnico e compromisso com as comunidades onde atuamos.', 'Missão da Empresa'),
('sobre_visao',       'Ser a empresa de referência no centro-sul de Angola, reconhecida pela excelência operacional, diversidade de serviços e impacto positivo no desenvolvimento nacional.', 'Visão da Empresa'),
('sobre_valores',     'Integridade, inovação, responsabilidade social, respeito pelos parceiros e colaboradores, e dedicação à qualidade em tudo o que fazemos.', 'Valores da Empresa'),
('gerente_1_nome',    'Quintino Lino Machado Adriano',                              'Gerente 1 - Nome'),
('gerente_1_cargo',   'Gerente',                                                    'Gerente 1 - Cargo'),
('gerente_1_bio',     'Natural de Huambo, co-fundador e gerente da Atlas Centro Sul, detém a quota maioritária da empresa.', 'Gerente 1 - Biografia'),
('gerente_2_nome',    'António Francisco Bumba',                                    'Gerente 2 - Nome'),
('gerente_2_cargo',   'Gerente',                                                    'Gerente 2 - Cargo'),
('gerente_2_bio',     'Natural de Huambo, Bairro São João, co-fundador e gerente da Atlas Centro Sul.', 'Gerente 2 - Biografia'),
('hero_subtitulo',    'A Atlas Centro Sul atua nos setores de energia, saúde, agronegócio e comércio — criando valor sustentável para Angola.', 'Subtítulo do Banner Principal'),
('manifesto_subtitulo','A Nossa Cultura',                                           'Subtítulo do Manifesto'),
('manifesto_titulo',   'Fazemos\nAcontecer!',                                       'Título do Manifesto'),
('manifesto_texto',    'Assumimos um papel activo na construção de uma Angola mais sustentável, mais próxima e com mais serviços.', 'Texto do Manifesto'),
('ano_fundacao',      '2025',                                                       'Ano de Fundação'),
('maps_embed',        '',                                                           'Google Maps Embed URL'),
('home_sobre_subtitulo','Quem Somos',                                                'Homepage - Sobre Nós - Subtítulo'),
('home_sobre_titulo',  'A Atlas\nCentro Sul',                                       'Homepage - Sobre Nós - Título'),
('home_sobre_texto_1', 'Fundada em 2025 em Huambo, a Atlas Centro Sul — Comércio e Serviços, Lda nasceu da visão de empreendedores angolanos determinados a criar uma empresa de referência no centro-sul de Angola.', 'Homepage - Sobre Nós - Parágrafo 1'),
('home_sobre_texto_2', 'Duas palavras dão vida à nossa cultura: Fazemos Acontecer! Assumimos um papel activo na construção de uma Angola mais sustentável e com mais serviços.', 'Homepage - Sobre Nós - Parágrafo 2'),
('home_pilares_subtitulo','Ecossistema Atlas',                                        'Homepage - Pilares - Subtítulo'),
('home_pilares_titulo','Os 4 Pilares\nEstratégicos',                                  'Homepage - Pilares - Título'),
('home_pilares_lead',  'Atuamos em setores complementares que se fortalecem mutuamente — da energia ao campo, da saúde ao comércio.', 'Homepage - Pilares - Introdução'),
('home_pilar1_icone',  '⚙️',                                                         'Homepage - Pilar 1 - Ícone'),
('home_pilar1_titulo', 'Energia &\nIndústria',                                       'Homepage - Pilar 1 - Título'),
('home_pilar1_desc',   'Manutenção eletromecânica, soldadura, pintura industrial e apoio técnico ao sector petrolífero onshore e offshore.', 'Homepage - Pilar 1 - Descrição'),
('home_pilar2_icone',  '🏥',                                                         'Homepage - Pilar 2 - Ícone'),
('home_pilar2_titulo', 'Saúde &\nBem-Estar',                                         'Homepage - Pilar 2 - Título'),
('home_pilar2_desc',   'Clínicas, fisioterapia, laboratórios e fornecimento de equipamentos médicos e hospitalares de alta qualidade.', 'Homepage - Pilar 2 - Descrição'),
('home_pilar3_icone',  '🌾',                                                         'Homepage - Pilar 3 - Ícone'),
('home_pilar3_titulo', 'Agronegócio',                                               'Homepage - Pilar 3 - Título'),
('home_pilar3_desc',   'Produção, transformação e comercialização de produtos agrícolas — do campo ao mercado, contribuindo para a segurança alimentar.', 'Homepage - Pilar 3 - Descrição'),
('home_pilar4_icone',  '🏢',                                                         'Homepage - Pilar 4 - Ícone'),
('home_pilar4_titulo', 'Comércio &\nInvestimentos',                                 'Homepage - Pilar 4 - Título'),
('home_pilar4_desc',   'Comércio geral de bens e serviços e gestão de participações sociais em empresas parceiras do ecossistema Atlas.', 'Homepage - Pilar 4 - Descrição'),
('sobre_objeto_social', '[{"icone":"⚙️","titulo":"Serviços Industriais","desc":"Prestação de serviços industriais incluindo soldadura, pintura, manutenção eletromecânica e eletrónica. Suporte técnico especializado para unidades industriais."},{"icone":"🛢️","titulo":"Sector Petrolífero","desc":"Atuação no setor petrolífero, onshore e offshore, incluindo manutenção, logística e serviços de apoio técnico a operadoras e contratantes."},{"icone":"🏥","titulo":"Saúde","desc":"Criação e exploração de projetos no ramo da saúde: clínicas, hospitais, fisioterapia, laboratórios e fornecimento de equipamentos médicos e hospitalares."},{"icone":"🌾","titulo":"Agronegócio","desc":"Agronomia e agronegócio, incluindo produção, transformação e comercialização de produtos agrícolas — contribuindo para a segurança alimentar nacional."},{"icone":"🛒","titulo":"Comércio Geral","desc":"Comércio geral de bens e serviços, respondendo às diversas necessidades do mercado angolano com qualidade e competitividade."},{"icone":"🏢","titulo":"Holding & Investimentos","desc":"Gestão de participações sociais noutras sociedades (holding), contribuindo para o desenvolvimento do ecossistema empresarial angolano."}]', 'Sobre Nós - Objeto Social'),
('servico_energia_industria_titulo', 'Energia & Indústria', 'Serviço - Energia - Título'),
('servico_energia_industria_icone', '⚙️', 'Serviço - Energia - Ícone'),
('servico_energia_industria_desc', 'Serviços industriais especializados e suporte técnico ao sector petrolífero, onshore e offshore, em Angola.', 'Serviço - Energia - Descrição'),
('servico_energia_industria_intro', 'Com equipas técnicas qualificadas, a Atlas oferece soluções industriais completas para os mais exigentes setores da economia angolana.', 'Serviço - Energia - Introdução'),
('servico_energia_industria_gradiente', 'linear-gradient(135deg,#1a0a00,#3d1a00)', 'Serviço - Energia - Gradiente CSS'),
('servico_energia_industria_servicos', '[{"icone":"🔧","titulo":"Manutenção Eletromecânica","desc":"Manutenção preventiva e corretiva de equipamentos eletromecânicos industriais, garantindo continuidade operacional."},{"icone":"🔩","titulo":"Soldadura Industrial","desc":"Serviços de soldadura MIG, TIG e elétrica para estruturas metálicas, tubagens e equipamentos industriais."},{"icone":"🎨","titulo":"Pintura Industrial","desc":"Tratamento anticorrosivo e pintura de estruturas metálicas, equipamentos e instalações industriais."},{"icone":"⚡","titulo":"Manutenção Eletrónica","desc":"Diagnóstico, reparação e manutenção de sistemas eletrónicos e automatismos industriais."},{"icone":"🛢️","titulo":"Apoio ao Sector Petrolífero","desc":"Logística, manutenção e serviços de apoio técnico a operações onshore e offshore."},{"icone":"🚢","titulo":"Serviços Offshore","desc":"Suporte técnico especializado para plataformas e operações offshore no mar angolano."},{"icone":"🏗️","titulo":"Engenharia de Construção Civil","desc":"Planeamento, projeto e execução de obras civis, infraestruturas e edifícios comerciais ou industriais."},{"icone":"📐","titulo":"Arquitetura","desc":"Desenvolvimento de projetos arquitetónicos inovadores, planeamento urbano e design de interiores/exteriores."},{"icone":"💧","titulo":"Engenharia Hidráulica","desc":"Projetos e soluções para sistemas de abastecimento de água, saneamento, drenagem e gestão de recursos hídricos."},{"icone":"💻","titulo":"Engenharia Informática","desc":"Desenvolvimento de software, gestão de infraestruturas de rede, segurança da informação e suporte tecnológico."}]', 'Serviço - Energia - Lista de Serviços'),
('servico_energia_industria_vantagens', '[{"icone":"✅","titulo":"Equipas Qualificadas","desc":"Técnicos com formação especializada e experiência comprovada no setor industrial."},{"icone":"⏱️","titulo":"Resposta Rápida","desc":"Disponibilidade para intervenções urgentes e contratos de manutenção contínua."},{"icone":"🔒","titulo":"Segurança em Primeiro Lugar","desc":"Cumprimento rigoroso de normas de segurança industrial e ocupacional."},{"icone":"📋","titulo":"Relatórios Detalhados","desc":"Documentação completa de cada intervenção para rastreabilidade e conformidade."}]', 'Serviço - Energia - Lista de Vantagens'),
('servico_saude_bem_estar_titulo', 'Saúde & Bem-Estar', 'Serviço - Saúde - Título'),
('servico_saude_bem_estar_icone', '🏥', 'Serviço - Saúde - Ícone'),
('servico_saude_bem_estar_desc', 'Criação e gestão de infraestruturas de saúde e fornecimento de equipamentos médicos de alta qualidade para Angola.', 'Serviço - Saúde - Descrição'),
('servico_saude_bem_estar_intro', 'A Atlas Centro Sul investe no bem-estar das populações através de projetos de saúde que combinam infraestrutura, tecnologia e capital humano qualificado.', 'Serviço - Saúde - Introdução'),
('servico_saude_bem_estar_gradiente', 'linear-gradient(135deg,#001a1a,#00404d)', 'Serviço - Saúde - Gradiente CSS'),
('servico_saude_bem_estar_servicos', '[{"icone":"🏨","titulo":"Clínicas e Centros de Saúde","desc":"Criação e exploração de clínicas e centros de saúde privados, com serviços de medicina geral e especialidades."},{"icone":"🦴","titulo":"Fisioterapia e Reabilitação","desc":"Centros de fisioterapia com equipamentos modernos para reabilitação física e desportiva."},{"icone":"🔬","titulo":"Laboratórios de Análises","desc":"Laboratórios de diagnóstico clínico com tecnologia avançada para análises rápidas e precisas."},{"icone":"🏥","titulo":"Hospitais Privados","desc":"Desenvolvimento e gestão de unidades hospitalares privadas com padrão internacional."},{"icone":"💊","titulo":"Equipamentos Médicos","desc":"Fornecimento e importação de equipamentos médicos e hospitalares certificados para clínicas e hospitais."},{"icone":"🧪","titulo":"Equipamentos de Diagnóstico","desc":"Comercialização de equipamentos de diagnóstico por imagem, ECG, laboratório e bloco operatório."}]', 'Serviço - Saúde - Lista de Serviços'),
('servico_saude_bem_estar_vantagens', '[{"icone":"🎓","titulo":"Profissionais de Saúde Qualificados","desc":"Parcerias com médicos, enfermeiros e técnicos de saúde com formação reconhecida."},{"icone":"🌍","titulo":"Equipamentos Certificados","desc":"Fornecimento exclusivo de equipamentos com certificação médica internacional."},{"icone":"💰","titulo":"Preços Acessíveis","desc":"Compromisso com a democratização do acesso a cuidados de saúde de qualidade."},{"icone":"📍","titulo":"Cobertura Regional","desc":"Foco inicial em Huambo, com locais de expansão para outras províncias."}]', 'Serviço - Saúde - Lista de Vantagens'),
('servico_agronegocio_titulo', 'Agronegócio', 'Serviço - Agronegócio - Título'),
('servico_agronegocio_icone', '🌾', 'Serviço - Agronegócio - Ícone'),
('servico_agronegocio_desc', 'Produção, transformação e comercialização de produtos agrícolas — contribuindo para a segurança alimentar e o desenvolvimento rural de Angola.', 'Serviço - Agronegócio - Descrição'),
('servico_agronegocio_intro', 'O agronegócio é um dos pilares do futuro de Angola. A Atlas Centro Sul posiciona-se como parceiro estratégico para o desenvolvimento da cadeia de valor agrícola.', 'Serviço - Agronegócio - Introdução'),
('servico_agronegocio_gradiente', 'linear-gradient(135deg,#0a1a00,#1a3d00)', 'Serviço - Agronegócio - Gradiente CSS'),
('servico_agronegocio_servicos', '[{"icone":"🌱","titulo":"Produção Agrícola","desc":"Exploração de projetos de produção agrícola em larga escala, com foco em culturas prioritárias para Angola."},{"icone":"🏭","titulo":"Transformação Agroindustrial","desc":"Processamento e transformação de matérias-primas agrícolas em produtos de maior valor acrescentado."},{"icone":"🚛","titulo":"Comercialização","desc":"Escoamento e comercialização de produtos agrícolas nos mercados locais, regionais e internacionais."},{"icone":"🐄","titulo":"Pecuária","desc":"Criação e gestão de projetos pecuários integrados na estratégia de agronegócio da empresa."},{"icone":"💧","titulo":"Irrigação e Tecnologia","desc":"Implementação de sistemas de irrigação e tecnologia agrícola para maximizar a produtividade."},{"icone":"🌿","titulo":"Produtos Orgânicos","desc":"Desenvolvimento de linha de produtos orgânicos e naturais com valor premium no mercado."}]', 'Serviço - Agronegócio - Lista de Serviços'),
('servico_agronegocio_vantagens', '[{"icone":"🌍","titulo":"Localização Estratégica","desc":"Huambo tem um dos solos mais férteis de Angola — posicionamento ideal para o agronegócio."},{"icone":"🔗","titulo":"Cadeia Completa","desc":"Presença em toda a cadeia de valor: produção, transformação e comercialização."},{"icone":"📈","titulo":"Mercado em Crescimento","desc":"O setor agrícola angolano tem enorme potencial de crescimento e diversificação."},{"icone":"🤝","titulo":"Parcerias Locais","desc":"Colaboração com agricultores locais e cooperativas para crescimento partilhado."}]', 'Serviço - Agronegócio - Lista de Vantagens'),
('servico_comercio_investimentos_titulo', 'Comércio & Investimentos', 'Serviço - Comércio - Título'),
('servico_comercio_investimentos_icone', '🏢', 'Serviço - Comércio - Ícone'),
('servico_comercio_investimentos_desc', 'Comércio geral de bens e serviços e gestão estratégica de participações societárias para criar valor no ecossistema empresarial angolano.', 'Serviço - Comércio - Descrição'),
('servico_comercio_investimentos_intro', 'O pilar comercial e de investimentos da Atlas sustenta o ecossistema completo da empresa, criando sinergias entre os diferentes setores de atuação.', 'Serviço - Comércio - Introdução'),
('servico_comercio_investimentos_gradiente', 'linear-gradient(135deg,#0a0020,#200050)', 'Serviço - Comércio - Gradiente CSS'),
('servico_comercio_investimentos_servicos', '[{"icone":"🛒","titulo":"Comércio Geral","desc":"Importação, exportação e distribuição de bens de consumo, industriais e especializados para o mercado angolano."},{"icone":"📦","titulo":"Distribuição e Logística","desc":"Gestão de cadeias de distribuição eficientes para garantir a chegada dos produtos ao cliente final."},{"icone":"🤝","titulo":"Parcerias Estratégicas","desc":"Estabelecimento de parcerias comerciais nacionais e internacionais para ampliar o portfólio de produtos."},{"icone":"📊","titulo":"Gestão de Participações","desc":"Holding empresarial com gestão de participações sociais em sociedades dos setores estratégicos."},{"icone":"💼","titulo":"Consultoria de Negócios","desc":"Apoio a empresas angolanas na estruturação e desenvolvimento dos seus modelos de negócio."},{"icone":"🌐","titulo":"Representações Comerciais","desc":"Representação de marcas e empresas internacionais no mercado angolano."}]', 'Serviço - Comércio - Lista de Serviços'),
('servico_comercio_investimentos_vantagens', '[{"icone":"🏢","titulo":"Estrutura Holding","desc":"Capacidade de gestão de múltiplas participações com eficiência e governança corporativa."},{"icone":"🌍","titulo":"Rede de Contactos","desc":"Rede estabelecida de parceiros locais e internacionais para negócios transversais."},{"icone":"⚡","titulo":"Agilidade Comercial","desc":"Capacidade de resposta rápida às oportunidades de mercado graças à estrutura multissectorial."},{"icone":"📈","titulo":"Crescimento Sustentável","desc":"Estratégia de investimento orientada para o crescimento a longo prazo com solidez financeira."}]', 'Serviço - Comércio - Lista de Vantagens');

-- ------------------------------------------------------------
-- Utilizador Administrador Inicial
-- Senha: Atlas@2025 (alterar após primeiro login!)
-- Hash gerado com password_hash('Atlas@2025', PASSWORD_DEFAULT)
-- ------------------------------------------------------------
-- Senha: Atlas@2025
-- Para gerar novo hash: php -r "echo password_hash('Atlas@2025', PASSWORD_DEFAULT);"
-- Ou aceda a: /atlas/admin/debug.php após importar o schema
INSERT INTO usuarios (nome, email, senha) VALUES
('Administrador', 'admin@atlascentrosul.ao', '$2y$10$TJ6fW.Gm01T0M35mdCEIReape9WK/Lgz9eij3t9peLD/Sm2N6Nwoi');

-- ------------------------------------------------------------
-- Posts de Exemplo
-- ------------------------------------------------------------
INSERT INTO posts (titulo, slug, resumo, corpo, categoria, publicado) VALUES
(
    'Atlas Centro Sul: Uma marca, múltiplas soluções',
    'atlas-centro-sul-uma-marca-multiplas-solucoes',
    'Conheça a visão da Atlas Centro Sul e como estamos a transformar múltiplos setores em Angola.',
    '<p>A <strong>Atlas Centro Sul — Comércio e Serviços, Lda</strong> nasceu com uma missão clara: criar valor sustentável para Angola através de uma atuação multissectorial robusta e comprometida com a excelência.</p><p>Com sede em Huambo, a empresa opera nas áreas de indústria, energia, saúde, agronegócio e comércio, posicionando-se como um parceiro estratégico de referência no centro-sul do país.</p>',
    'geral',
    1
),
(
    'Expansão no Setor de Energia e Indústria',
    'expansao-setor-energia-industria',
    'A Atlas Centro Sul reforça a sua presença no setor industrial e energético em Angola.',
    '<p>O mercado angolano de serviços industriais apresenta oportunidades significativas de crescimento. A Atlas Centro Sul está a posicionar-se estrategicamente para responder à crescente procura por serviços de manutenção eletromecânica, soldadura e apoio técnico no sector petrolífero.</p>',
    'energia-industria',
    1
);

-- ------------------------------------------------------------
-- Mídia de Exemplo
-- ------------------------------------------------------------
INSERT INTO midia (titulo, descricao, tipo, url_ou_caminho, categoria, destaque) VALUES
('Apresentação Institucional Atlas Centro Sul', 'Vídeo de apresentação da empresa e suas áreas de atuação.', 'video', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'geral', 1);
