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
('sobre_resumo',      'A Atlas Centro Sul é uma empresa angolana multissectorial sediada em Huambo, dedicada a criar valor em múltiplos setores da economia nacional: indústria, energia, saúde, agronegócio e comércio.', 'Resumo Sobre Nós'),
('ano_fundacao',      '2025',                                                       'Ano de Fundação'),
('maps_embed',        '',                                                           'Google Maps Embed URL');

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
