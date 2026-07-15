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
('home_pilar4_desc',   'Comércio geral de bens e serviços e gestão de participações sociais em empresas parceiras do ecossistema Atlas.', 'Homepage - Pilar 4 - Descrição');

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
