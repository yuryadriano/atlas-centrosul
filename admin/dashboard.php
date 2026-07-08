<?php
/**
 * Atlas Centro Sul — Dashboard do Painel Administrativo
 */
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/helpers.php';
protegerRota();

$pdo   = getDB();
$user  = utilizadorActual();
$flash = obterFlash();

// Estatísticas
$totalPosts     = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$totalPublicados= $pdo->query("SELECT COUNT(*) FROM posts WHERE publicado = 1")->fetchColumn();
$totalMidia     = $pdo->query("SELECT COUNT(*) FROM midia")->fetchColumn();
$totalMensagens = $pdo->query("SELECT COUNT(*) FROM mensagens WHERE lida = 0")->fetchColumn();

// Últimos posts
$ultimosPosts = $pdo->query("SELECT id, titulo, categoria, publicado, criado_em FROM posts ORDER BY criado_em DESC LIMIT 6")->fetchAll();

// Últimas mensagens
$ultimasMensagens = $pdo->query("SELECT id, nome, email, assunto, lida, criado_em FROM mensagens ORDER BY criado_em DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Atlas Centro Sul Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/atlas/assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/_sidebar.php'; ?>

<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-title">Dashboard</div>
        <div class="topbar-right">
            <a href="/atlas/index.php" target="_blank" class="btn btn-outline btn-sm">🌐 Ver Site</a>
            <div class="topbar-user">
                <div class="topbar-avatar"><?= strtoupper(substr($user['nome'], 0, 1)) ?></div>
                <span><?= htmlspecialchars($user['nome']) ?></span>
            </div>
        </div>
    </header>

    <main class="content">
        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['tipo'] ?>"><?= htmlspecialchars($flash['mensagem']) ?></div>
        <?php endif; ?>

        <!-- Bem-vindo -->
        <div style="margin-bottom:28px;">
            <h2 style="font-size:22px;font-weight:800;color:var(--navy);">
                Bem-vindo, <?= htmlspecialchars(explode(' ', $user['nome'])[0]) ?>! 👋
            </h2>
            <p style="color:var(--gray-500);font-size:14px;margin-top:4px;">
                <?= date('l, d \d\e F \d\e Y') ?> · Atlas Centro Sul — Comércio e Serviços
            </p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card navy">
                <div class="stat-icon">📝</div>
                <div class="stat-info">
                    <h3><?= $totalPosts ?></h3>
                    <p>Total de Posts</p>
                </div>
            </div>
            <div class="stat-card gold">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <h3><?= $totalPublicados ?></h3>
                    <p>Posts Publicados</p>
                </div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon">🖼️</div>
                <div class="stat-info">
                    <h3><?= $totalMidia ?></h3>
                    <p>Ficheiros de Média</p>
                </div>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon">✉️</div>
                <div class="stat-info">
                    <h3><?= $totalMensagens ?></h3>
                    <p>Mensagens Novas</p>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

            <!-- Últimos Posts -->
            <div class="card">
                <div class="card-header">
                    <h2>📝 Últimos Posts</h2>
                    <a href="/atlas/admin/post_form.php" class="btn btn-primary btn-sm">+ Novo</a>
                </div>
                <?php if (empty($ultimosPosts)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3>Sem posts ainda</h3>
                    <p>Comece por criar o primeiro artigo.</p>
                </div>
                <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Categoria</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($ultimosPosts as $post): ?>
                            <tr>
                                <td style="max-width:200px;">
                                    <a href="/atlas/admin/post_form.php?id=<?= $post['id'] ?>" style="color:var(--navy);font-weight:600;">
                                        <?= htmlspecialchars(truncar($post['titulo'], 50)) ?>
                                    </a>
                                    <div style="font-size:11px;color:var(--gray-500);margin-top:2px;">
                                        <?= formatarData($post['criado_em']) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-navy"><?= labelCategoria($post['categoria']) ?></span>
                                </td>
                                <td>
                                    <?php if ($post['publicado']): ?>
                                        <span class="badge badge-success">Publicado</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Rascunho</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div style="padding:14px 20px;border-top:1px solid var(--gray-100);">
                    <a href="/atlas/admin/posts.php" style="font-size:13px;color:var(--navy);font-weight:600;">
                        Ver todos os posts →
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Últimas Mensagens -->
            <div class="card">
                <div class="card-header">
                    <h2>✉️ Mensagens Recentes</h2>
                    <a href="/atlas/admin/mensagens.php" class="btn btn-outline btn-sm">Ver todas</a>
                </div>
                <?php if (empty($ultimasMensagens)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3>Sem mensagens</h3>
                    <p>Nenhuma mensagem recebida ainda.</p>
                </div>
                <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Remetente</th>
                                <th>Assunto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($ultimasMensagens as $msg): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:var(--navy);">
                                        <?= htmlspecialchars($msg['nome']) ?>
                                    </div>
                                    <div style="font-size:11px;color:var(--gray-500);">
                                        <?= htmlspecialchars($msg['email']) ?>
                                    </div>
                                </td>
                                <td style="font-size:13px;"><?= htmlspecialchars(truncar($msg['assunto'] ?? 'Sem assunto', 30)) ?></td>
                                <td>
                                    <?php if (!$msg['lida']): ?>
                                        <span class="badge badge-info">Nova</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Lida</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Ações Rápidas -->
        <div class="card" style="margin-top:24px;">
            <div class="card-header"><h2>⚡ Ações Rápidas</h2></div>
            <div class="card-body" style="display:flex;gap:14px;flex-wrap:wrap;">
                <a href="/atlas/admin/post_form.php" class="btn btn-primary">📝 Novo Artigo</a>
                <a href="/atlas/admin/midia_form.php" class="btn btn-gold">📷 Adicionar Média</a>
                <a href="/atlas/admin/configuracoes.php" class="btn btn-outline">⚙️ Configurações</a>
                <a href="/atlas/index.php" target="_blank" class="btn btn-outline">🌐 Ver Site Público</a>
            </div>
        </div>
    </main>
</div>
</body>
</html>
